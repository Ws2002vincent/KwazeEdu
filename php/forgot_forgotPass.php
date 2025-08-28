<?php
session_start();

//initialize error message
$input_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';

//set flag for error
$noError = true;

//after user clicks "continue"
if (isset($_POST["btnGetCode"])){

    //establish databse connection
    include "db_conn.php";

    //obtain user input
    $user_input = mysqli_real_escape_string($connection, $_POST["txtUser"]);

    //verify username or email
    if (empty($user_input)) {
        $input_error = '<i class="bx bx-error-circle"></i>' . "Username or email is required.";
        $noError = false;
    } else {

        //get email if user entered a username
        if (!filter_var($user_input, FILTER_VALIDATE_EMAIL)) {
            $username_query = "SELECT * FROM `user` WHERE `username`='$user_input'";
            $username_result = mysqli_query($connection, $username_query);
            if (mysqli_num_rows($username_result) != 1){
                $input_error = '<i class="bx bx-error-circle"></i>' . "Username or email is invalid.";
                $noError = false;
            } else {
                $user_row = mysqli_fetch_assoc($username_result);
                $user_input = $user_row["email"];
            }
        } else {

            //if user entered an email
            $email_query = "SELECT * FROM `user` WHERE `email`='$user_input'";
            $email_result = mysqli_query($connection, $email_query);
            if (mysqli_num_rows($email_result) != 1){
                $input_error = '<i class="bx bx-error-circle"></i>' . "Username or email is invalid.";
                $noError = false;
            }
        }
    }

    //close database to connection
    mysqli_close($connection);

    //if there is no error
    if ($noError) {

        include "functions.php";

        //session the user email
        $_SESSION['user_email'] = $user_input;

        //generate otp  that contains 6 characters
        $otp = generateOTP(6, 'user');

        //write email content
        $email_subject = "Password Reset";
        include "email_template.php";
        $email_message = emailTemplateOTP("Please reset your password!", "KwazeEdu password reset", $otp);
        
        //send email & store output (error) if any
        $hasError = sendEmail($user_input, $email_subject, $email_message);

        if (empty($hasError)) {

            //redirect to the code confirmation page if no errors occurred
            header("Location: ../php/forgot_code.php");
            exit();

        } else {
            $input_error = '<i class="bx bx-error-circle"></i>' . "Could not send email. Please try again.";
        }
    }
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
        width: 260px;
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
</head>

<body>
    <div class="card">

        <!-- remember change to .php -->
        <a href="../php/login.php">
            <i class="bx bx-chevron-left"></i>
        </a>

        <div class="card-header">
            <h1>Forget Password</h1>
            <span>Enter your Email address or Username.</span>
        </div>
        <div class="card-body">

            <!-- remember change to .php -->
            <form action="#" method="post">
                <!-- email or username -->
                <div class="form-control">
                    <i class="bx bx-envelope"></i>
                    <input type="text" name="txtUser" id="user" placeholder="Username or Email" />
                </div>
                <div class="error">
                    <span><?php echo $input_error; ?></span>
                </div>

                <!-- submit button -->
                <div class="submit">
                    <input type="submit" value="Continue" name="btnGetCode" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>