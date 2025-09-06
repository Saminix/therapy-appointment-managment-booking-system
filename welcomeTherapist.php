<!-- <?php
include 'includes/connection.php';
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: login.php");
	exit;
}

//echo "Welcome " . $_SESSION['FirstName'] . $_SESSION['Client_ID'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name ="viewport" content="with=device-width, inital-scale-1.0">
  <link href="html" rel="stylesheet" type="text/css" />
  <title> Therapist Dashboard </title>
  <link rel="stylesheet" href="welcome.css">

</head>
<body>

  <?php include("includes/navigation.php")?>

  <div class="content">
    <h1 class="UserTitle">

    <?php echo $deleteMsg??''; 
    echo "Welcome " . $_SESSION['FirstName'] . $_SESSION['Client_ID'];
    ?>
  </h1>
    
    <div class="content2">
      <h1 class= "text">
         <?php 
      echo "Your Appointments";
      ?>
    </h1>
    </div>

    <table class="table">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Service Type</th>
        <th>Service Duration</th>
        <th>Appointment Date</th>
        <th>Appointment Time</th>
        <th>Price</th>
        <th>Therapist Name</th>
        <th>Cancelled</th>
        <th style="display : hidden"></th>
        <th style="display : hidden"></th>
        <th style="display : hidden"></th>
      </tr>
      <?php
      $Client_ID=$_SESSION["Client_ID"];

      $sql = "SELECT 
      Appointment_ID,
      CONCAT(client.FirstName,' ',client.LastName) AS 'Name_of_Client',
      service.Service_Name, 
      service.Session_Duration,
      Date_of_Appointment,
      Time_of_Appointment,
      service.Price,
      CONCAT(therapist.FirstName, ' ',therapist.LastName) AS 'Name_of_Therapist',
      Cancelled
      FROM appointment
      INNER JOIN client ON appointment.Client_ID = client.Client_ID
      INNER JOIN service ON appointment.Service_ID = service.Service_ID
      INNER JOIN therapist ON appointment.Therapist_ID = therapist.Therapist_ID
      WHERE client.Client_ID = '$Client_ID'";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
          echo 
          "<tr>"
          . "<td>" . $row["Appointment_ID"] . "</td>"
          . "<td>" . $row["Name_of_Client"] . "</td>"
          . "<td>" . $row["Service_Name"] . "</td>"
          . "<td>" . $row["Session_Duration"] . "</td>"
          . "<td>" . $row["Date_of_Appointment"] . "</td>"
          . "<td>" . $row["Time_of_Appointment"] . "</td>"
          . "<td>" ."£". $row["Price"] . "</td>"
          . "<td>" . $row["Name_of_Therapist"] . "</td>"
          . "<td>" . (boolval($row["Cancelled"]) ? 'No' : 'Yes') . "</td>"
          . "<td>" . "<a href=includes/updateAppointment.php?update_id=".$row['Appointment_ID']."><button class=update-button>Update</button></a></td>"
          . "<td>" . "<a href=includes/deleteAppointment.php?delete_id=".$row['Appointment_ID']."><button class=delete-button>Delete</button></a></td>"
          . "<td>" . "<a href=includes/cancelAppointment.php?cancel_id=".$row['Appointment_ID']."><button class=cancel-button>Cancel</button></a></td>"
          ."</tr>";
        }

        echo "</table>";
      } else {
        echo "0 results";
      }
      ?>    

    </table>
    </div>
  </body>
  </html>

 -->