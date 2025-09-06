<?php
// Start a session to store variable across web pages
session_start();
// One time include to connect to database.
include_once 'includes/connection.php';

// Booking functionality when user clicks the submit button
if(isset($_POST['submit'])){

  // Assign session Variables to PHP variables
  $Client_ID = $_SESSION["Client_ID"];
  // Retreives these Details from form
  $arr = explode('#',mysqli_real_escape_string($conn,$_POST['Service']));
  $Service_ID = $arr[0];
  $Service_name = $arr[1];
  $Service_Duration = $arr[2];

  $Therapist_ID = mysqli_real_escape_string($conn,$_POST['Therapist']);
  $Date_of_Appointment = mysqli_real_escape_string($conn,$_POST['Date_of_Appointment']);
  $Time_of_Appointment = mysqli_real_escape_string($conn,$_POST['Time_of_Appointment']);

  // Calculate the end of an appointment based on the duration of the service page selected with the time of appointment as the start time
  $time = strtotime($Time_of_Appointment) + strtotime($Service_Duration) - strtotime('00:00:00');
  $End_of_Appointment = date('H:i:s', $time);
  $Reason_of_Cancellation="not cancelled yet";
  $Cancelled = 1;

  $sqlcheck = "SELECT * FROM appointment WHERE Date_of_Appointment = '$Date_of_Appointment' AND Time_of_Appointment <= '$Time_of_Appointment' AND End_of_Appointment >= '$End_of_Appointment' AND Therapist_ID ='$Therapist_ID'";
  $check2 = mysqli_query($conn, $sqlcheck);

#double booking check
  $Bookingcheck = mysqli_num_rows($check2);
  // echo $Bookingcheck ."  --- ";
  // echo "-= Date_of_Appointment is: ".$Date_of_Appointment." -= Time_of_Appointment is: ".$Time_of_Appointment."-= End_of_Appointment is:" .$End_of_Appointment;

  if($Bookingcheck !=0){ 
    $Bookingcheck = "Unfortunately This Booking Is Taken. 
    Please Try Another Date Or Time.";

    // echo "<script>alert(' Unfortunately, This Booking Is Taken'); window.location='booking.php';</script>";
  }
  else{

   $sql = "INSERT INTO appointment(Client_ID,Therapist_ID,Service_ID,Service_name,Date_of_Appointment,Time_of_Appointment,End_of_Appointment,Cancelled,Reason_of_Cancellation) values('$Client_ID','$Therapist_ID','$Service_ID','$Service_name','$Date_of_Appointment','$Time_of_Appointment','$End_of_Appointment','$Cancelled','$Reason_of_Cancellation')";
   $paymentQuery = "INSERT INTO payment(Appointment_ID,Paid) values (?,?)";

   $stmt = $conn->prepare($sql);
   $stmt->execute();
   $insert_App_id = mysqli_insert_id($conn);
   $stmt->close();

   $stmt = $conn->prepare($paymentQuery);
   $stmt->bind_param("ii",$insert_App_id,$param_Paid);
   $param_Paid = 0;
   if($stmt->execute()){
    echo "<script>alert('Booking Complete'); window.location='welcome.php';</script>";
    $success = true;
  }
  stmt->close();
}
}

?>

<?php
session_start();
?>

<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="with=device-width, inital-scale-1.0">
  <link href="index" rel="stylesheet" type="text/css" />
  <title> Booking Page </title>
  <link rel="stylesheet" href="booking.css">
  <link rel="stylesheet" href="stylesheets/navigation.css">
  <body>
   <?php include("includes/navigation.php"); ?>

   <div class="header">
    <div class="navigationbar">
      <nav>
        <div class="navbar-links">
          <a href="welcome.php" class="backb"> BACK </a>
        </div>
      </nav>
    </div>
  </head>
</body>

<!-- (B) booking form  -->
<div class="wrapper">
  <h2>Book An Appointment</h2>
  <p>Please fill in your booking Information.</p>
  <form id="resForm" method="post" target="_self">
   <?php
   if($Bookingcheck !=0){
    echo '<div class="alertDanger">' . $Bookingcheck . '</div>';
  }
  ?>



  <label> Choose Your Service Type</label>
  <select required name="Service" value="Choose Service Type" class=textbox>
    <br>
    <?php while ($service = mysqli_fetch_array($all_services,MYSQLI_ASSOC)):;?>
      <option value ="<?php echo $service["Service_ID"] ."#". $service["Service_Name"]."#".$service["Session_Duration"];?>">
        <?php echo $service["Service_Name"] . ". " . $service["Session_Duration"] . " Mins £" . $service["Price"]; endwhile;?> 

      </option>
    </select>
    <label> Choose Your Therapist </label>
    <select required name="Therapist" value="Select the Therapist you wish to consult with" class="textbox">
      <br>
      <?php while ($therapist= mysqli_fetch_array($all_therapist,MYSQLI_ASSOC)):;?>
        <option value="<?php echo $therapist["Therapist_ID"];?>">
          <?php echo $therapist["Firstname"] . " " . $therapist['Lastname'];
        endwhile;?>
      </option>
    </select>


    <label>Choose Your Appointment Date</label>

    <input type="date" value= "05/11/2022" required name="Date_of_Appointment" min="<?=date("Y-m-d")?>" class=textbox>


    <label> Choose Your Appointment Time</label>
    <select id="box"type=time value = "Service Time" required name="Time_of_Appointment" class=textbox min="10:00" max="18:00" required>
      <option value="10:00:00">10.00 AM</option>
      <option value="10:30:00">10.30 AM</option>
      <option value="11:00:00">11.00 AM</option>
      <option value="11:30:00">11.30 AM</option>
      <option value="13:00:00">13.00 PM</option>
      <option value="13:30:00">13.30 PM</option>
      <option value="14:00:00">14.00 PM</option>
      <option value="14:30:00">14.30 PM</option>
      <option value="15:00:00">15.00 PM</option>
      <option value="15:30:00">15.30 PM</option>
      <option value="16:00:00">16.00 PM</option>
      <option value="16:30:00">16.30 PM</option>
      <option value="17:00:00">17.00 PM</option>
      <option value="17:30:00">17.30 PM</option>
    </select>

    <label> Choose Your Payment Type</label>
    <select id="box" value="Enter a Payment Type"name="method" class=textbox>
      <option value="Chip and Pin reader" >Card/Pin reader</option>
      <option value="Cash">Cash</option>
      <option value="Cheque">Cheque</option>
      
    </select>

    <input type="submit" value="Submit" class="submit" name="submit">

    <?php
    if($success)
    {
      ?> 
      <?php
    }

    ?>

    <!-- <button type="button" href="welcome.php" onclick="closePopup()">OK</button> -->

  </form>

</div>
<!-- <p style="text-align: center;" > Name: <?php echo $Client_Name;;?> </p>  -->



</body>

</html>
