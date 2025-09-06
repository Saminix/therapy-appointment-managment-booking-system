<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
//if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//  header("location: welcome.php");
//exit;
// }

// Include config file
include_once 'includes/connection.php';

// Define variables and initialize with empty values
$User_name = $Pass_word = "";
$User_name_err = $Pass_word_err = $login_err = "";
$tryagain = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["User_name"]))) {
        $User_name_err = "Please enter your Username.";
    } else {
        $User_name = trim($_POST["User_name"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["Pass_word"]))) {
        $Pass_word_err = "Please enter your Password.";
    } else {
        $Pass_word = trim($_POST["Pass_word"]);
    }

    // Validate credentials
    if (($User_name_err == "") and ($Pass_word_err == "")) {
        // Prepare a select statement
        $sql = "SELECT User_ID,Username,Password,Role FROM user WHERE Username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$User_name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($User_ID,$Username,$Password,$Role);

        if($stmt->num_rows == 1)
        {
            $stmt->fetch();
            if(password_verify($Pass_word, $Password)){
                $_SESSION['loggedin'] = true;
                $_SESSION['User_ID'] = $User_ID;
                $_SESSION['User_name'] = $Username;
                $_SESSION['Role'] = $Role;
                header("location: welcome.php");
                exit();
            }
            else
            {
                $login_err = "Invalid password.";
                $_SESSION = [];
                session_destroy();
            }
        }
        else {

            $login_err = "Invalid Username or password.";

        }
    } else {
        $tryagain = "Oops! Something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <title> About us </title>
    <link rel="stylesheet" href="loginform.css">
    <link rel="stylesheet" href="login.css">

    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="Bootstrap.css"> -->
</head>

<body>
    <?php
    include("includes/navigation.php");
    ?>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php
            if (!empty($login_err)) {
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }

            if (!empty($tryagain)) {
                echo '<div class = "alert alert-danger">' . $tryagain . '</div>';
            }
            ?>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="User_name" class="textbox"
                class="form-control <?php echo (!empty($User_name_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $User_name; ?>">
                <span class="invalid-feedback">
                    <?php echo $User_name_err; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="Password" name="Pass_word" class="textbox"
                class="form-control <?php echo (!empty($Pass_word_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback">
                    <?php echo $Pass_word_err; ?>
                </span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn_login" value="Login">
            </div>
            <div class="login_bottom">
                <p>Don't have an account? <a href="register.php" class="buttonlog">Sign up now</a></p>
            </div>
        </form>
    </div>

</body>

</html>