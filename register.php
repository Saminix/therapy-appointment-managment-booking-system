<?php
// Include config file
session_start();

require_once "includes/connection.php";
include_once 'includes/connection.php';

// Define variables and initialize with empty values
$User_name = $Pass_word = $confirm_Pass_word = "";
$User_name_err = $Pass_word_err = $confirm_Pass_word_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["User_name"]))) {
        $User_name_err = "Please enter a User_name.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["User_name"]))) {
        $User_name_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT Client_ID FROM client INNER JOIN user on client.User_ID = user.User_ID WHERE Username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_User_name);

            // Set parameters
            $param_User_name = trim($_POST["User_name"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $User_name_err = "This username is already taken.";
                } else {
                    $User_name = trim($_POST["User_name"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["Pass_word"]))) {
        $Pass_word_err = "Please Enter A Password.";
    } elseif (strlen(trim($_POST["Pass_word"])) < 6) {
        $Pass_word_err = "Password must have at least 6 characters.";
    } else {
        $Pass_word = trim($_POST["Pass_word"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_Pass_word"]))) {
        $confirm_Pass_word_err = "Please Confirm your Password.";
    } else {
        $confirm_Pass_word = trim($_POST["confirm_Pass_word"]);
        if (empty($Pass_word_err) && ($Pass_word != $confirm_Pass_word)) {
            $confirm_Pass_word_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($User_name_err) && empty($Pass_word_err) && empty($confirm_Pass_word_err)) {

        // Escape user inputs for security
        $First_Name = mysqli_real_escape_string($conn, $_REQUEST['FirstName']);
        $Last_Name = mysqli_real_escape_string($conn, $_REQUEST['LastName']);
        $Email = mysqli_real_escape_string($conn, $_REQUEST['Email']);
        $Address = mysqli_real_escape_string($conn, $_REQUEST['Address']);
        $Tel = mysqli_real_escape_string($conn, $_REQUEST['Tel']);
        $Post_Code = mysqli_real_escape_string($conn, $_REQUEST['Post_Code']);
        $Role = "client";

        // Prepare an insert statement
        $sql = "INSERT INTO user (Username,Password,Role) 
        VALUES (?,?,'$Role')";
        $sql2 = "INSERT INTO client (User_ID,FirstName,LastName,Email, Address, Tel, Post_Code) VALUES (?,'$First_Name','$Last_Name','$Email','$Address','$Tel','$Post_Code')";

        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_User_name, $param_Pass_word);

            // Set parameters
            $param_User_name = $User_name;
            $param_Pass_word = Password_hash($Pass_word, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {

                if($stmt2 = mysqli_prepare($conn,$sql2)){
                    mysqli_stmt_bind_param($stmt2,"i", $param_User_ID);
                    $param_User_ID = mysqli_insert_id($conn);
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt2)) {
                        // Redirect to login page
                        header("location: login.php");
                    }
                    else
                    {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
                else
                {
                        // Close statement
                    mysqli_stmt_close($stmt);
                }
                
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="with=device-width, inital-scale-1.0">
    <link href="index" rel="stylesheet" type="text/css" />
    <title> Register with us </title>
    <link rel="stylesheet" href="register.css">

</head>

<body>
    <div class="header">
        <?php include("includes/navigation.php") ?>
    </div>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="FirstName" required class="form-control">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="LastName" required class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="Email" required class="form-control">
            </div>
            <div class="form-group">
                <label>Telephone</label>
                <input type="tel" name="Tel" maxlength="11"required class="form-control">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="Address"required class="form-control">
            </div>
            <div class="form-group">
                <label>Post Code</label>
                <input type="text" name="Post_Code" maxlength="7" required  class="form-control">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" required name="User_name"
                class="form-control <?php echo (!empty($User_name_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $User_name; ?>">
                <span class="invalid-feedback">
                    <?php echo $User_name_err; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="Pass_word"
                class="form-control <?php echo (!empty($Pass_word_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $Pass_word; ?>">
                <span class="invalid-feedback">
                    <?php echo $Pass_word_err; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_Pass_word"
                class="form-control <?php echo (!empty($confirm_Pass_word_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $confirm_Pass_word; ?>">
                <span class="invalid-feedback">
                    <?php echo $confirm_Pass_word_err; ?>
                </span>
            </div>
            <div class="form-group-button">
                <input type="submit" class="btn-primary" value="Submit">
                <input type="reset" class="btn-secondary" value="Reset">
            </div>
            <div class="register_bottom">
                <p>Already have an account? <a href="login.php" Class='bbutton'>Login here</a>.</p>
            </div>
        </form>
    </div>

    </html>