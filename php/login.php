<?php
session_start();

//set empty if variable is not set or null
$_SESSION['correctUser'] ??= "";

//initialize the error message
$id_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
$psw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';

//after user clicks the sign in button
if (isset($_GET["btnSignin"])) {

    //establish database connection
    include "db_conn.php";

    //obtain user input
    $login_user = mysqli_real_escape_string($connection, $_GET["txtUser"]);
    $password = $_GET["txtPassword"];

    //save user input
    $_SESSION['correctUser'] = $login_user;

    //clear previous data from sign up page if any
    $_SESSION['correctInput'] = [];

    //verify criteria
    if (empty($login_user)){
        $id_error = '<i class="bx bx-error-circle"></i>' . "Username or email is required.";
    } else if (empty($password)) {
        $psw_error = '<i class="bx bx-error-circle"></i>' . "Password is required.";
    } else {
        //if login successful
        $user_query = "SELECT * FROM `user` WHERE (`email`='$login_user' OR `username`='$login_user')";
        $user_results = mysqli_query($connection, $user_query);

        $admin_query = "SELECT * FROM `admin` WHERE (`email`='$login_user' OR `username`='$login_user')";
        $admin_results = mysqli_query($connection, $admin_query);

        if (mysqli_num_rows($user_results) == 1){
            $row = mysqli_fetch_assoc($user_results);

            if ($row["password"] == $password) {
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["role"] = $row["user_role"];
    
                //reset this variable (assign empty string again)
                $_SESSION['correctUser'] = "";
    
                //redirect to home page
                header("Location: ../php/c_Dashboard.php"); 
                exit();

            } else {
                //save user input if user is found
                $_SESSION['correctUser'] = $login_user;
                $psw_error = '<i class="bx bx-error-circle"></i>' . "Password is incorrect.";
            }

        } else if (mysqli_num_rows($admin_results) == 1){
            $row = mysqli_fetch_assoc($admin_results);

            if ($row["password"] == $password) {
                $_SESSION["user_id"] = $row["admin_id"];
                $_SESSION["role"] = $row["user_role"];
    
                //reset this variable (assign empty string again)
                $_SESSION['correctUser'] = "";
    
                //redirect to home page
                header("Location: ../php/a_Dashboard.php"); 
                exit();
                
            } else {
                //save user input if user is found
                $_SESSION['correctUser'] = $login_user;
                $psw_error = '<i class="bx bx-error-circle"></i>' . "Password is incorrect.";
            }
        } else {
            $_SESSION['correctUser'] = "";
            $id_error = '<i class="bx bx-error-circle"></i>' . "Username or email is invalid.";
        }
    }

    //close connection to database
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Unbounded:wght@200..900&display=swap"
        rel="stylesheet" />

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/login.css" />


    <!-- JS -->
    <script src="../js/show_hide_password.js"></script>
</head>

<body>
    <div class="card">
        <div class="card-header">
            <h1>Welcome!</h1>
            <span>Please login to your account.</span>
        </div>
        <div class="card-body">

            <!-- remember change to .php -->
            <form action="" method="get">
                <!-- email or username -->
                <div class="form-control">
                    <i class="bx bx-envelope"></i>
                    <input type="text" name="txtUser" id="user" placeholder="Username or Email"
                        value="<?php echo $_SESSION['correctUser']; ?>" />
                </div>
                <div class="error">
                    <span><?php echo $id_error; ?></span>
                </div>

                <!-- password -->
                <div class="form-control">
                    <i class="bx bx-lock-alt"></i>
                    <input type="password" name="txtPassword" id="password" placeholder="Password" />
                    <i class="bx bx-hide" id="pass-icon1" onclick="pass('password', 'pass-icon1')"></i>
                </div>
                <div class="error">
                    <span><?php echo $psw_error; ?></span>
                </div>

                <!-- forgot password -->
                <span class="forgot"><a href="../php/forgot_forgotPass.php">Forgot Password?</a></span>

                <!-- submit button -->
                <div class="submit">
                    <input type="submit" value="Sign In" name="btnSignin" id="btnSignin" />
                    <span>
                        Don't have an account?
                        <!-- remember change to .php -->
                        <a href="../php/register.php">Sign Up Now</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</body>

</html>