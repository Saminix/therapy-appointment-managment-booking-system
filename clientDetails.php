<?php 
session_start();
include_once 'includes/connection.php';

$query = "SELECT * FROM client WHERE CONCAT(Firstname, Lastname, Email, Tel, Address, Post_Code)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$query_run = mysqli_stmt_get_result($stmt);

if(isset($_GET['search'])) {
  $filtervalues = $_GET['search'];
  $query = "SELECT * FROM client WHERE CONCAT(Firstname, Lastname, Email, Tel, Address, Post_Code) LIKE ?";
  $stmt = mysqli_prepare($conn, $query);
  $filtervalues = "%{$filtervalues}%";
  mysqli_stmt_bind_param($stmt, "s", $filtervalues);
  mysqli_stmt_execute($stmt);
  $query_run = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="stylesheets/details.css">

 <title>Client Details</title>
</head>
<body>
  <?php include("includes/navigation.php")?>
  <div class="title">
    <h2> View Client Details</h2>
  </div>

  
  <div class="container">
    <div class="field">
     <form action="" method="GET">
       <div class="searching">
        <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="bar" placeholder="Search data">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>
<table class="table" style="border-collapse: collapse">
  <thead class="thead">
    <tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Address</th>
      <th>Post Code</th>
    </tr>
    <?php
    if(mysqli_num_rows($query_run) > 0) {
      $answer = "";
      while($items = mysqli_fetch_assoc($query_run)) {  
        $client_id = $items['Client_ID'];
        $firstname = $items['Firstname'];
        $lastname = $items['Lastname'];
        $email = $items['Email'];
        $tel= $items['Tel'];
        $address = $items['Address'];
        $post_code = $items['Post_Code'];

      // concatenate each row to $answer
        $answer .= "<tr>
        <td>$client_id</td>
        <td>$firstname</td>
        <td>$lastname</td>
        <td>$email</td>
        <td>$tel</td>
        <td>$address</td>
        <td>$post_code</td>
        </tr>";
      }
    }
    else {
      $answer = "<tr><td colspan='7'style=font-weight:bolder;>No Record Found</td></tr>";
    }
    ?>
  </thead>
  <tbody>
    <?php 
    // Output the values of Firstname and Lname
    echo $answer;
    ?>
  </tbody>
</table>
