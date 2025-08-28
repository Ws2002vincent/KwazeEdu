<?php
session_start();

//initialize error message
$code_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';

//set flag for error
$noError = true;

//set flag for code expiry
$_SESSION['expired'] = false;

if (isset($_POST["btnVerifyCode"])){

    include "db_conn.php";

    //obtain user input
    $user_code = mysqli_real_escape_string($connection, $_POST["txtCode"]);

    if (empty($user_code)) {
        $code_error = '<i class="bx bx-error-circle"></i>' . "Verification code is required.";
        $noError = false;
    } else {

        //check if code is correct
        $otp_query = "SELECT `otp`, `otp_time` FROM `email_verify` WHERE `email` = '{$_SESSION['user_email']}'";
        $otp_result = mysqli_query($connection, $otp_query);
        $otp_row = mysqli_fetch_assoc($otp_result);
        $user_otp = $otp_row['otp'];
        $otp_time = $otp_row['otp_time'];

        //if code is incorrect
        if ($user_code != $user_otp) {
            $code_error = '<i class="bx bx-error-circle"></i>' . "Verification code invalid.";
            $noError = false;
        } else {

            //if code is correct, verify expiry status
            $current_time = date('Y-m-d H:i:s');
            if (strtotime($current_time) - strtotime($otp_time) >= 93){
                $_SESSION['expired'] = true;
                $code_error = '<i class="bx bx-error-circle"></i>' . "Your OTP has expired. Please get a new OTP.";
                $noError = false;
            }
        }
    }

    //close database to connection
    mysqli_close($connection);

    if($noError){

        //insert data into database
        include "db_conn.php";
        $acc_query = "INSERT INTO `user`(`first_name`, `last_name`, `username`, `email`, `password`, `country`, `user_pfp`, `user_role`, `otp`, `otp_time`) 
        VALUES ('{$_SESSION['userAccData'][0]}', '{$_SESSION['userAccData'][1]}', '{$_SESSION['userAccData'][2]}', '{$_SESSION['userAccData'][3]}', '{$_SESSION['userAccData'][4]}', '{$_SESSION['userAccData'][5]}', '{$_SESSION['userAccData'][6]}', '{$_SESSION['userAccData'][7]}', '{$_SESSION['userAccData'][8]}', '{$_SESSION['userAccData'][9]}')";
        $_SESSION['correctInput'] = [];
        if(mysqli_query($connection, $acc_query)){
            $_SESSION['userAccData'] = "";
        } else {
            $code_error = '<i class="bx bx-error-circle"></i>' . "An unexpected error occured. Account creation failed.";
        }

        //close database connnection
        mysqli_close($connection);

        //redirect to the next page if code is correct
        header("Location: ../php/register_success.php");
        exit();
    }
}

//if code expired, get new otp
if (isset($_POST["btnResendOTP"])){

    //generate new otp & resend email
    include "functions.php";
    $new_otp = generateOTP(6, 'email_verify');

    //write email content
    $email_subject = "Verify Email";
    include "email_template.php";
    $email_message = emailTemplateOTP("Thanks for your registration!", "KwazeEdu account registration", $new_otp);
    
    //send email & store output (error) if any
    $hasError = sendEmail($_SESSION['user_email'], $email_subject, $email_message);

    if (empty($hasError)) {

        //reset code expiry status
        $_SESSION['expired'] = false;

    } else {
        $code_error = '<i class="bx bx-error-circle"></i>' . "Could not send email. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Email</title>

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
        font-size: 20px;
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
        <a href="../php/register.php">
            <i class="bx bx-chevron-left"></i>
        </a>

        <div class="card-header">
            <h1>Email Verification</h1>
            <span>We've sent an Email verification otp to your email - <br />
                <?php echo $_SESSION['user_email'] ?></span>
        </div>
        <div class="card-body">

            <!-- remember change to .php -->
            <form action="#" method="post">
                <!-- code -->
                <div class="form-control">
                    <i class="bx bx-barcode"></i>
                    <input type="text" name="txtCode" id="code" placeholder="Enter code" />
                </div>
                <div class="error">
                    <span><?php echo $code_error; ?></span>
                </div>

                <!-- submit button -->
                <div class="submit">
                    <?php
                if ($_SESSION['expired']) {
                    ?><input type="submit" value="Resend OTP" name="btnResendOTP" /><?php
                } else {
                    ?><input type="submit" value="Submit" name="btnVerifyCode" /><?php
                }
                ?>
                </div>
            </form>
        </div>
    </div>
</body>

</html>