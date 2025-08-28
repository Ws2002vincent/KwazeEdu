<?php
session_start();

$psw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
$confpsw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
$noError = true;

if (isset($_POST["btnResetPassword"])){
    include "db_conn.php";

    //obtain user input
    $password = mysqli_real_escape_string($connection, $_POST["txtPassword"]);
    $confpsw = mysqli_real_escape_string($connection, $_POST["txtConfirmPassword"]);

    //user password validation
    if (empty($password)) {
        $psw_error = '<i class="bx bx-error-circle"></i>' . "Password is required.";
        $noError = false;
    } else if (strlen($password) < 8){
        $psw_error = '<i class="bx bx-error-circle"></i>' . "Password should be at least 8 characters long.";
        $noError = false;
    } else if (empty($confpsw)){
        $confpsw_error = '<i class="bx bx-error-circle"></i>' . "Retype password to confirm.";
        $noError = false;
    } else {
        if ($password != $confpsw){
            $confpsw_error = '<i class="bx bx-error-circle"></i>' . "Password does not match.";
            $noError = false;
        }
    }

    //password reset successful
    if ($noError) {
        $pswChange_query = "UPDATE `user` SET `password` = '$password' WHERE `email` = '{$_SESSION['user_email']}'";
        if(mysqli_query($connection, $pswChange_query)){
            header("Location: ../php/forgot_success.php");
            exit();
        }
    }

    //close database connnection
    mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Chivo", sans-serif;
    }

    body {
        background-color: #1c253b;
        background-image: url(../img/background.svg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .card {
        max-width: 450px;
        margin: 100px auto;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 0 60px #111a26;
        background-color: #f3f2ff;
    }

    .card a {
        text-decoration: none;
        color: #333;
        font-size: 25px;
        float: left;
    }

    .card-header {
        text-align: center;
        margin-top: 20px;
    }

    .card-header h1 {
        width: 250px;
        margin: auto;
        font-size: 28px;
        color: #333;
        border-bottom: 1.5px solid #33384f;
        padding-bottom: 8px;
    }

    .card-header span {
        font-size: 14px;
        line-height: 30px;
        color: #333;
    }

    .card-body {
        margin-top: 25px;
    }

    .form-control {
        display: flex;
        justify-content: center;
        margin: 0 auto;
        position: relative;
    }

    .form-control i {
        font-size: 18px;
        margin: auto 0;
        margin-right: 9px;
    }

    .form-control input {
        width: 270px;
        padding: 10px;
        padding-left: 15px;
        border: 1px solid #33384f;
        border-radius: 20px;
        font-size: 12px;
    }

    .form-control .bx-hide,
    .form-control .bx-show {
        position: absolute;
        top: 8px;
        right: 57px;
        cursor: pointer;
    }

    .error {
        margin: 0 auto;
        color: red;
        font-size: 12px;
        margin: 2px 0 8px 90px;
        display: flex;
        visibility: visible;
    }

    .error i {
        line-height: 13px;
        margin-right: 5px;
    }

    .erroricon {
        visibility: hidden;
    }

    .submit {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .submit input {
        width: 300px;
        margin: 10px auto;
        padding: 10px;
        border: 1px solid #33384f;
        border-radius: 20px;
        cursor: pointer;
        font-size: 15px;
        background-color: #1c253b;
        color: #f3f2ff;
        font-weight: 500px;
    }

    .submit input:hover {
        background-color: #343950;
    }
    </style>

    <!-- JS -->
    <script src="../js/show_hide_password.js"></script>
</head>

<body>
    <div class="card">

        <!-- remember change to .php -->
        <!-- <a href="../php/forgot_code.php">
            <i class="bx bx-chevron-left"></i>
        </a> -->

        <div class="card-header">
            <h1>Reset Password</h1>
            <span>Please create a new password.</span>
        </div>
        <div class="card-body">

            <!-- remember change to .php -->
            <form action="#" method="post">
                <!-- password -->
                <div class="form-control">
                    <i class="bx bx-lock-alt"></i>
                    <input type="password" name="txtPassword" id="password" placeholder="New password" />
                    <i class="bx bx-hide" id="pass-icon2" onclick="pass('password', 'pass-icon2')"></i>
                </div>
                <div class="error">
                    <span><?php echo $psw_error; ?></span>
                </div>

                <!-- confirm password -->
                <div class="form-control">
                    <i class="bx bx-lock"></i>
                    <input type="password" name="txtConfirmPassword" id="confirmPassword"
                        placeholder="Confirm Password" />
                    <i class="bx bx-hide" id="pass-icon3" onclick="pass('confirmPassword', 'pass-icon3')"></i>
                </div>
                <div class="error">
                    <span><?php echo $confpsw_error; ?></span>
                </div>

                <!-- submit button -->
                <div class="submit">
                    <input type="submit" value="Confirm" name="btnResetPassword" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>