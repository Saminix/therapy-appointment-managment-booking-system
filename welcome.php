<?php
include 'includes/connection.php';
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: login.php");
	exit;
}
$Roles = $_SESSION['Role'];
  //Get details for logged in user with the role of Client
$sql ="SELECT Client_ID,Firstname,Lastname FROM client INNER JOIN user ON user.User_ID = client.User_ID WHERE client.User_ID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$param_User_ID);
$param_User_ID = $_SESSION['User_ID'];
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($Client_ID,$FirstName,$LastName);
if($stmt->num_rows == 1)
{
  $stmt->fetch();
  $_SESSION['Client_ID'] = $Client_ID;
  $_SESSION['FirstName'] = $FirstName;
  $_SESSION['LastName'] = $LastName;
}

   //Get details for logged in user with the role of therapist
$sql ="SELECT Therapist_ID,Firstname,Lastname FROM therapist INNER JOIN user ON user.User_ID = therapist.User_ID WHERE therapist.User_ID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$param_User_ID);
$param_User_ID = $_SESSION['User_ID'];
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($Therapist_ID,$FirstName,$LastName);
if($stmt->num_rows == 1)
{
  $stmt->fetch();
  $_SESSION['Therapist_ID'] = $Therapist_ID;
  $_SESSION['FirstName'] = $FirstName;
  $_SESSION['LastName'] = $LastName;
}

//echo "Welcome " . $_SESSION['FirstName'] . $_SESSION['Client_ID'];


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name ="viewport" content="with=device-width, inital-scale-1.0">
  <link href="html" rel="stylesheet" type="text/css" />
  <title> Dashboard </title>
  <link rel="stylesheet" href="welcome.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script type="text/javascript">
    function drawChart() {

      var jsonData = $.ajax({
        url: "includes/popularAppointment.php",
        dataType: "json",
        async: false
      }).responseText;

         // Create the data table.
      var data = new google.visualization.DataTable(jsonData);

        // Set chart options
      var options = {
        title: 'Popular Services',
        // legend: { position: 'right' },
        bar: {groupWidth: '50'},
        hAxis: {
         format:'0',
         gridlines: {count:1},
         title:'Number of reviews completed for each service',
       },
       vAxis: {
        format:'0',
        gridlines: {count:5},
        title:'Star Ratings',
        viewWindow:{
          max:5.5,
          min:0.5,
        }
      },

      width:1500,
      height: 400,

    }

    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

    chart.draw(data, options);

  }
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
</script>

</head>
<body>

  <?php include("includes/navigation.php")?>

  <div class="content">
    <h1 class="UserTitle">

      <?php echo $deleteMsg??''; 
      if($_SESSION['Role'] == "client"){
       echo "Welcome " . $_SESSION['FirstName'] . " " . $_SESSION['LastName'];

     }
     else if($_SESSION['Role'] == "therapist")
     {
       echo "Welcome " . $_SESSION['FirstName'] . " " . $_SESSION['LastName'];

     }
     ?>
   </h1>

   <div class="content2">
    <h1 class= "text">
     <?php 
     echo "Appointments";
     ?>
   </h1>
 </div>


 <div class="search">
  <form action="welcome.php" method="post">
    <div class="search_date">
      <label for="start_date">Date:</label>
      <input type="date" class="date" id="start_date" name="start_date">
      <label for="end_date">To</label>
      <input type="date" class="date" id="end_date" name="end_date">
    </div>
    <div class="search_box">
      <input type="text" name="key" class="search_input" placeholder="Search Appointments by ID, Client or Therapist...">
      <input type="submit" value="Search" name="submit" class="btnsub">
    </div>
  </form>
</div>

<table class="table">
  <tr style="width:auto;">
    <th>ID</th>
    <th>Name</th>
    <th>Service Type</th>
    <th>Service Duration</th>
    <th>Appointment Date</th>
    <th>Appointment Time</th>
    <th>Price</th>
    <th>Therapist Name</th>
    <th>Cancelled</th>
    <th class="btn-th" colspan="100%"></th>
  </tr>

  <?php
  $sql = "SELECT 
  appointment.Appointment_ID,
  CONCAT(client.Firstname,' ',client.Lastname) AS 'Name_of_Client',
  service.Service_Name, 
  service.Session_Duration,
  Date_of_Appointment,
  Time_of_Appointment,
  service.Price,
  CONCAT(therapist.Firstname, ' ',therapist.Lastname) AS 'Name_of_Therapist',
  Paid,
  Cancelled
  FROM appointment
  INNER JOIN client ON appointment.Client_ID = client.Client_ID
  INNER JOIN service ON appointment.Service_ID = service.Service_ID
  INNER JOIN therapist ON appointment.Therapist_ID = therapist.Therapist_ID
  INNER JOIN user ON user.User_ID = client.User_ID
  INNER JOIN payment on appointment.Appointment_ID = payment.Appointment_ID";

  if($_SESSION['Role'] == "client"){
    $Client_ID=$_SESSION["Client_ID"];
    $sql .= " WHERE client.Client_ID = '$Client_ID'";
  }
  elseif($_SESSION['Role'] == "therapist")
  {
    $sql .= " WHERE therapist.Therapist_ID = '$Therapist_ID'";
  }

  if(isset($_POST['submit']))
  {
    $searchKey = $_POST['key'];
    $Start_date = $_POST['start_date'];
    $End_date = $_POST['end_date'];
    $sql .= " AND (appointment.Appointment_ID LIKE '%$searchKey%' OR client.FirstName like '%$searchKey%' OR client.LastName Like '%$searchKey%' OR therapist.Firstname Like '%$searchKey%' OR therapist.LastName Like '%$searchKey%') ";
    if(!empty($_POST['start_date'] or !empty($_POST['end_date'])))
    {
      $sql .= " AND (DATE(Date_of_Appointment) BETWEEN '$Start_date' AND '$End_date') ORDER BY Appointment_ID";
    }
    else
    {
      $sql .= " ORDER BY Appointment_ID";
    }
  }

  if($_SESSION["Role"] == "admin" | $_SESSION['Role'] == "therapist")
    echo '<link rel="stylesheet" href="admin.css">';
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {

    // output data of each row
    while($row = $result->fetch_assoc()) {
      $Appointment_ID = $row["Appointment_ID"];
      $Name_of_Client = $row["Name_of_Client"];
      $Service_Name = $row["Service_Name"];
      $Session_Duration = $row["Session_Duration"];
      $Date_of_Appointment = $row["Date_of_Appointment"];
      $Time_of_Appointment = $row["Time_of_Appointment"];
      $Price = $row["Price"];
      $Name_of_Therapist = $row["Name_of_Therapist"];
      $Cancelled = (boolval($row["Cancelled"]) ? 'No' : 'Yes');
      $Paid = $row['Paid'];
      echo 
      "<tr colspan='100%'";
      if($Cancelled =='Yes'){
          echo "style='background-color:red;'>";}
      elseif ($Paid == 1) {
        echo " style='background-color:#8bbca5;color:#757575;'>";
      }else
      {
        echo ">";
      }
      echo "<td>" . $Appointment_ID . "</td>"
      . "<td>" . $Name_of_Client . "</td>"
      . "<td>" . $Service_Name . "</td>"
      . "<td>" . $Session_Duration . "</td>"
      . "<td>" . $Date_of_Appointment . "</td>"
      . "<td>" . $Time_of_Appointment . "</td>"
      . "<td>" ."£". $Price . "</td>"
      . "<td>" . $Name_of_Therapist . "</td>"
      . "<td>" . $Cancelled . "</td>";
      if($Paid != 1 ){
         if($Cancelled != 'Yes'){
        echo "<td>" . "<a href=includes/updateAppointment.php?update_id=".$row['Appointment_ID']."><button class=update-button>Update</button></a></td>";
        echo "<td >" . "<a href=includes/cancelAppointment.php?cancel_id=".$row['Appointment_ID']."><button class=cancel-button>Cancel</button></a></td><td colspan='100%'>";
      }
        if(($_SESSION['Role'] == "admin" | $_SESSION['Role'] == "therapist")) {
          if($Cancelled != 'Yes'){
            echo "<a href=includes/confirmPayment.php?confirm_id=".$row['Appointment_ID']."><button class=confirm-button>Confirm Payment and Booking</button></a></td>";
          }
          echo "<td>" . "<a href=includes/deleteAppointment.php?delete_id=".$row['Appointment_ID']."><button class=delete-button>Delete</button></a></td>";
        }
      }
      else
      {
        if ($_SESSION['Role'] == "client"){
          echo "<td colspan='3' style='color: black; font-family: Arial; font-weight: bold; background-color: #8bbca5;'>Payment and Booking Completed</td>";
          echo "<td><a href=includes/writeReview.php?review_id=".$row['Appointment_ID']."><button class=review-button>Review Appointment</button></a></td>";
          echo "<td><form class=form-print method=post action=includes/generatePDF.php?print_id=".$row['Appointment_ID']."><button type=submit id=pdf name=generatePDF class=print-button >Print</button></form></td>";
        }
        else
        {
          echo "<td colspan=100%' style='color: black; font-family: Arial; font-weight: bold; background-color: #8bbca5;'>Payment and Booking Completed</td>";
        }
      }
      echo "</tr>";
    }
  }
  else
  {
    echo "<td colspan='100%' rowspan='100%' style='color:Red;font-family: Arial; font-weight: bold;'>No Booking Found</td>";
  }


  ?>
</table>
<?php 
if($_SESSION["Role"] == "admin"){
  ?>
  <div id="chart_div"></div>
  <?php
}
?>
<!-- Java script code to restrict user from entering a single start or end date input value. Both must be entered if a value has been entered into either field. -->
<script type="text/javascript">
  const start_date_input = document.getElementById('start_date');
  const end_date_input = document.getElementById('end_date');

  start_date_input.addEventListener("input", setRequired);
  end_date_input.addEventListener("input", setRequired);
   // Function to set the required attribute on both input fields
  function setRequired() {
  // Check if both start and end dates have a value
    if (start_date_input.value || end_date_input.value) {
      start_date_input.setAttribute("required", "");
      end_date_input.setAttribute("required", "");
    } else {
      start_date_input.removeAttribute("required");
      end_date_input.removeAttribute("required");
    }
  }
</script>
</div>
</div>
</body>
</html>

