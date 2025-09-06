<?php
// One time include to connect to database.
include 'includes/connection.php';

// Start a session to store variable across web pages
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: ../login.php");
  exit;
}
$Client_ID=$_SESSION["Client_ID"];
$User_ID=$_SESSION["User_ID"];

if(isset($_POST['submit'])) {
  // Get values
  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $tel = mysqli_real_escape_string($conn, $_POST['tel']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $post_code = mysqli_real_escape_string($conn, $_POST['post_code']);
  $username = $_POST['username'];

  $query = "UPDATE user SET Username='$username' WHERE User_ID='$User_ID'";
  // this sql statement is for Updating the client's details in the database
  $query2 = "UPDATE client SET Firstname='$first_name', Lastname='$last_name', Email='$email', Tel='$tel', Address='$address', Post_Code='$post_code' WHERE Client_ID = '$Client_ID'";

  // if(mysqli_query($conn, $query)) {
  //   $Success = "Client details updated successfully.";
  // } else {
  //   $Success = "Error updating client details: " . mysqli_error($conn);
  // }

  try
  {
    $conn->begin_transaction();
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt = $conn->prepare($query2);
    $stmt->execute();
    $conn->commit();
    $Success = "Client details updated successfully.";
  }
  catch(\Throwable $e)
  {
    $Success = "Error updating client details: " . mysqli_error($conn);
    $conn->rollback();
    throw $e;
  }
}

if(isset($_SESSION["Client_ID"]))
{
  $sql="SELECT Client_ID, Firstname,Lastname,Email, Address,Post_Code,Tel,Username FROM CLIENT  INNER JOIN user on client.User_ID = user.User_ID WHERE Client_ID=$Client_ID";
  $result = $conn->query($sql); 
  if ($result->num_rows > 0) {        

    while ($row = $result->fetch_assoc()) {

      $Client_ID = $row['Client_ID'];
      $FirstName = $row['Firstname'];
      $LastName = $row['Lastname'];
      $Email = $row['Email'];
      $Address = $row['Address'];
      $PostCode = $row['Post_Code'];
      $Tel = $row['Tel'];
      $UserName = $row['Username'];
      $Profile = $row['Profile'];
    }
  }
  else 
  {
    echo "0 results found";
  }

}
?>





<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="stylesheets/profile.css">

 <title>Client Profile</title>
</head>
<body>
  <?php include("includes/navigation.php")?>

  <div class='user-info'>
    <form method='post'>
      <p class='title'>Click On the Boxes To Edit Your Profile </p>
      <P style="color: greenyellow;"><?php echo $Success ?></P>
      <p class='user-data'>First Name: <input type='text' name='first_name' value="<?php echo $FirstName?>" class="text" required></p>
      <p class='user-data'>Last Name: <input type='text' name='last_name' value="<?php echo $LastName ?>"class='text' required></p>
      <p class='user-data'>Email: <input type='text' name='email' value="<?php echo $Email ?>"class='text' required></p>
      <p class='user-data'>Address: <input type='text' name='address' value="<?php echo $Address ?>"class='text' required></p>
      <p class='user-data'>Post Code: <input type='text' name='post_code' value="<?php echo $PostCode?>"class='text' required></p>
      <p class='user-data'>Tel: <input type='text' name='tel' value="<?php echo $Tel?>"class='text' maxlength="10" required></p>
      <p class='user-data'>Username: <input type='text' name='username' value="<?php echo $UserName?>"class='text' required></p>
      <input type='submit' class='button' name='submit' value='Update'>
    </form>
  </div>

  
    
  </form>
</form>
</body>
</html>






