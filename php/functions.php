<?php

//register account / account creation
function createUserAccount() {

    //initialize error messages
    global $name_error, $username_error, $email_error, $psw_error, $confpsw_error, $role_error;
    $name_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $username_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $email_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $psw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $confpsw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $role_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $noError = true;

    if(isset($_POST["btnReset"])){
        $_SESSION['correctInput'] = [];
    }

    if (isset($_POST["btnRegister"])){
        
        //session variable to store correct user input
        $_SESSION['correctInput'] = [
                                        "fname" => "", 
                                        "lname" => "", 
                                        "username" => "", 
                                        "email" => "",
                                        "password" => "",
                                        "confpsw" => "",
                                        "country" => ""
                                    ];
        include "db_conn.php";
        //obtain user input
        $first_name = mysqli_real_escape_string($connection, $_POST["txtFname"]);
        $last_name = mysqli_real_escape_string($connection, $_POST["txtLname"]);
        $username = mysqli_real_escape_string($connection, $_POST["txtUsername"]);
        $email = mysqli_real_escape_string($connection, $_POST["txtEmail"]);
        $password = mysqli_real_escape_string($connection, $_POST["txtPassword"]);
        $confpsw = mysqli_real_escape_string($connection, $_POST["txtConfirmPassword"]);
        $country = mysqli_real_escape_string($connection, $_POST["txtCountry"]);
        if (isset($_POST["rdoRole"])){
            $role = mysqli_real_escape_string($connection, $_POST["rdoRole"]);
        }
        else {
            $role = "";
        }

        //hidden/default user settings
        $user_pfp = "defaultpic.png";
        $otp = "@@@@";

        //save user-selected country
        $_SESSION['correctInput']["country"] = $country;

        //user first name & last name validation
        $fullname = $first_name . " " . $last_name;
        if (empty($first_name) || empty($last_name)){
            if (!empty($first_name)){
                $name_error = '<i class="bx bx-error-circle"></i>' . "Last name is required.";
                $_SESSION['correctInput']["fname"] = $first_name;
            } else if (!empty($last_name)){
                $name_error = '<i class="bx bx-error-circle"></i>' . "First name is required.";
                $_SESSION['correctInput']["lname"] = $last_name;
            } else {
                $name_error = '<i class="bx bx-error-circle"></i>' . "First and last name are required.";
            }
            $noError = false;
        } else if (!preg_match("/^[a-zA-Z ]*$/", $fullname)){
            $name_error = '<i class="bx bx-error-circle"></i>' . "Name should contain letters and whitespaces only.";
            $noError = false;
        } else {
            $_SESSION['correctInput']["fname"] = $first_name;
            $_SESSION['correctInput']["lname"] = $last_name;
        }

        //user username validation
        $username_query = "SELECT `username` FROM `user` WHERE `username`='$username' 
                           UNION
                           SELECT `username` FROM `admin` WHERE `username`='$username'";
        $username_result = mysqli_query($connection, $username_query);
        if (empty($username)) {
            $username_error = '<i class="bx bx-error-circle"></i>' . "Please enter a username.";
            $noError = false;
        } else if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_-]*$/", $username)){
            $username_error = '<i class="bx bx-error-circle"></i>' . "Username must start with a letter and can only <br> contain letters, numbers, underscores, and hyphens.";
            $noError = false;
        } else if (mysqli_num_rows($username_result) > 0){
            $username_error = '<i class="bx bx-error-circle"></i>' . "Username has already been taken.";
            $noError = false;
        } else {
            $_SESSION['correctInput']["username"] = $username;
        }
        
        //user email validation
        $email_query = "SELECT `email` FROM `user` WHERE `email`='$email' 
                        UNION
                        SELECT `email` FROM `admin` WHERE `email`='$email'";
        $email_result = mysqli_query($connection, $email_query);
        if (empty($email)) {
            $email_error = '<i class="bx bx-error-circle"></i>' . "Email is required.";
            $noError = false;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = '<i class="bx bx-error-circle"></i>' . "Email is invalid.";
            $noError = false;
        } else if (mysqli_num_rows($email_result) > 0){
            $email_error = '<i class="bx bx-error-circle"></i>' . "Email has already been used.";
            $noError = false;
        } else {
            $_SESSION['correctInput']["email"] = $email;
        }

        //user password validation
        if (empty($password)) {
            $psw_error = '<i class="bx bx-error-circle"></i>' . "Password is required.";
            $noError = false;
        } else if (strlen($password) < 8){
            $psw_error = '<i class="bx bx-error-circle"></i>' . "Password should be at least 8 characters long.";
            $noError = false;
        } else if (empty($confpsw)){
            $confpsw_error = '<i class="bx bx-error-circle"></i>' . "Retype password to confirm.";
            $_SESSION['correctInput']["password"] = $password;
            $noError = false;
        } else if ($password != $confpsw){
            $confpsw_error = '<i class="bx bx-error-circle"></i>' . "Password does not match.";
            $_SESSION['correctInput']["password"] = $password;
            $noError = false;
        } else {
            $_SESSION['correctInput']["password"] = $password;
            $_SESSION['correctInput']["confpsw"] = $confpsw;
        }

        //user role validation
        if (empty($role)) {
            $errorRole = "User role is required.";
            $noError = false;
        }

        //account creation successful
        if ($noError === true) {
            $first_name = ucwords(trim($first_name));
            $last_name = ucwords(trim($last_name));
            $otp_time = date('Y-m-d H:i:s');
            if ($role === "user"){
                return [$first_name, $last_name, $username, $email, $password, $country, $user_pfp, $role, $otp, $otp_time];
            } else {
                $acc_query = "INSERT INTO '$role' (`first_name`, `last_name`, `username`, `email`, `password`, `country`, `user_pfp`, `user_role`, `otp`, `otp_time`) 
                VALUES ('$first_name', '$last_name', '$username', '$email', '$password', '$country', '$user_pfp', '$role', '$otp', '$otp_time')";
                $_SESSION['correctInput'] = [];
                if(mysqli_query($connection, $acc_query)){
                    $_SESSION['userAccData'] = "";
                    return "Registration success!";
                }
            }
        }

        //close database connnection
        mysqli_close($connection);
    }
}

//send emails
//import PHPMailer classes to enable mail sending
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($user_email, $subject, $message) {

    //load composer autoload.php
    require dirname(__FILE__) . '\..\vendor\autoload.php';

    //email information
    $senderName = "KwazeEdu";
    $senderEmail = 'kwazeedu@gmail.com';


    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                                    //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                               //Set the SMTP server
        $mail->SMTPAuth   = true;                                           //Enable SMTP authentication
        $mail->Username   = $senderEmail;                                   //SMTP username
        $mail->Password   = 'skxbxphlwxwtmeyp';                             //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 //Enable implicit TLS encryption
        $mail->Port       = 587;                                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //this line is to test for error/exception handling, so don't remove yet
        // $mail->addAttachment('/var/tmp/file.tar.gz');

        //Recipients
        $mail->setFrom($senderEmail, $senderName);
        $mail->addAddress($user_email);
        // $mail->addAddress('ooipeiying732@gmail.com', 'Joe User');        //add a recipient, name is optional
        // $mail->addReplyTo('kwazeedu@gmail.com', 'Information');
        // $mail->addCC('$user_email');                                     //carbon copy
        // $mail->addBCC('$user_email');                                    //blind carbon copy

        //Content
        $mail->isHTML(true);                                                //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        //send email
        $mail->send();

    } catch (Exception $e) {
        return "error occured";
    }

    //close smtp connection
    $mail->smtpClose();
}

function generateOTP($length, $table){
    include "db_conn.php";
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }

    $otp = mysqli_real_escape_string($connection, $otp);

    //set flag for unique otp
    $otp_unique = false;

    //re-generate otp if it is not unique
    while ($otp_unique == false) {
        $query = "SELECT `otp` FROM $table WHERE `email` = '{$_SESSION['user_email']}'";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $current_otp = $row['otp'];

        if ($otp === $current_otp){
            for ($i = 0; $i < $length; $i++) {
                $otp .= $characters[rand(0, strlen($characters) - 1)];
            }
        } else {
            $otp_unique = true;
        }
    }

    //store otp & creation time to database
    $timestamp = date('Y-m-d H:i:s');
    $insert_timestamp = "UPDATE $table SET `otp_time` = '$timestamp', `otp` = '$otp' WHERE `email` = '{$_SESSION['user_email']}'";
    mysqli_query($connection, $insert_timestamp);

    //close database to connection
    mysqli_close($connection);

    return $otp;
}

//mode 1 for validation process, mode 2 for upload
function uploadImage($target_dir, $image, $mode) {
    $target_file = $target_dir . $image;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $noError = true;

    if ($target_file == $target_dir) { //if the filename uploaded is empty (meaning no file uploaded)
        $noError = false;
        return "Image is required";
    } else {
        // echo basename( $_FILES["imgToUpload"]["name"]); //uncomment for debugging purposes

        $check = getimagesize($_FILES["imgToUpload"]["tmp_name"]);
        if($check !== false) {
            //check if file is an image
            // echo "File is an image - " . $check["mime"] . ".";    //uncomment for debugging
        } else {
            $noError = false;
            return "Selected file is not an image.";
        }

        if (file_exists($target_file)) {
            // echo "Sorry, file already exists.";   //uncomment for debugging
            $noError = false;
            return "This image already exists in the directory.";
        }

        if ($_FILES["imgToUpload"]["size"] > 500000) {
            //file size larger than 500000 bytes
            // echo "Sorry, your file is too large.";    //uncomment for debugging
            $noError = false;
            return "File size must not be larger than 500KB.";
        }

        //accepted file types: JPG, JPEG, PNG & GIF 
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            // echo "Only JPG, JPEG, PNG & GIF files are allowed";     //uncomment for debugging
            $noError = false;
            return "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    if ($noError && $mode == 2) {
        if (move_uploaded_file($_FILES["imgToUpload"]["tmp_name"], $target_file)) {
            // echo "The file ". basename( $_FILES["imgToUpload"]["name"]). " has been uploaded.";
            return ""; //return empty string if upload successful
        } else {
            // echo "Sorry, there was an error uploading your file.";       //uncomment for debugging
            return "File not uploaded.";
        }
    } else if (!$noError && $mode != 2) {
        return "File not uploaded.";
    }
}

function getUserRank($user_id, $quiz_last_updated, $quiz_id) {
    include "db_conn.php";
    $sql = "
    SELECT user_id, rank FROM (
        SELECT user_id, 
            CAST(JSON_EXTRACT(`score`, '$.total') AS UNSIGNED) AS total_score,
            timestamp,
            RANK() OVER (PARTITION BY quiz_id ORDER BY CAST(JSON_EXTRACT(`score`, '$.total') AS UNSIGNED) DESC, timestamp ASC, date_completed DESC) AS rank
        FROM user_result
        WHERE quiz_id = ? AND date_completed > ?
    ) ranked_users
    WHERE user_id = ?
    LIMIT 1;
    ";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isi", $quiz_id, $quiz_last_updated, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["rank"];
        }
    } else {
        return "--";
    }
    mysqli_close($connection);
}


?>
