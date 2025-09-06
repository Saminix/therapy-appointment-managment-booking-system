<?php
// One time include to connect to database.
include 'connection.php';
    // Start a session to store variable across web pages
session_start();


// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../stylesheets/navigation.css">
    <link rel="stylesheet" href="../stylesheets/resetPassword.css">
</head>
<body>
    <?php include("navigation.php")?>
    <div class="content">

      <input type="Password" name="oldPassword">  </input>
  </div>
</body>
</html>
