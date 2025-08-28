<?php

if(isset($_POST['txtFname'])) {

    include "functions.php";
    include "db_conn.php";
    //obtain manager input
    $first_name = mysqli_real_escape_string($connection, $_POST["txtFname"]);
    $last_name = mysqli_real_escape_string($connection, $_POST["txtLname"]);
    $username = mysqli_real_escape_string($connection, $_POST["txtUsername"]);
    $email = mysqli_real_escape_string($connection, $_POST["txtEmail"]);
    $password = mysqli_real_escape_string($connection, $_POST["txtPassword"]);
    $role = mysqli_real_escape_string($connection, $_POST["txtRole"]);
    $noError = true;

    //hidden/default settings
    $otp = "@@@@";

    //admin first name & last name validation
    $fullname = $first_name . " " . $last_name;
    if (empty($first_name) || empty($last_name)){
        if (empty($first_name) && empty($last_name)) {
            echo "<script>$('#lname_error').html('<i class=\"bx bx-error-circle\"></i>Last name is required.');</script>";
            echo "<script>$('#fname_error').html('<i class=\"bx bx-error-circle\"></i>First name is required.');</script>";
        } else if (!empty($first_name)){
            echo "<script>$('#fname_error').html('');</script>";
            echo "<script>$('#lname_error').html('<i class=\"bx bx-error-circle\"></i>Last name is required.');</script>";
        } else if (!empty($last_name)){
            echo "<script>$('#lname_error').html('');</script>";
            echo "<script>$('#fname_error').html('<i class=\"bx bx-error-circle\"></i>First name is required.');</script>";
        }
        $noError = false;
    } else if (!preg_match("/^[a-zA-Z ]*$/", $first_name)){
        echo "<script>$('#fname_error').html('<i class=\"bx bx-error-circle\"></i>Name should contain letters and whitespaces only.');</script>";
        $noError = false;
    } else if (!preg_match("/^[a-zA-Z ]*$/", $last_name)){
        echo "<script>$('#lname_error').html('<i class=\"bx bx-error-circle\"></i>Name should contain letters and whitespaces only.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#fname_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
        echo "<script>$('#lname_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }

    //user username validation
    $username_query = "SELECT `username` FROM `user` WHERE `username`='$username' 
                        UNION
                        SELECT `username` FROM `admin` WHERE `username`='$username'";
    $username_result = mysqli_query($connection, $username_query);
    if (empty($username)) {
        echo "<script>$('#username_error').html('<i class=\"bx bx-error-circle\"></i>Please enter a username.');</script>";
        $noError = false;
    } else if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_-]*$/", $username)){
        echo "<script>$('#username_error').html('<i class=\"bx bx-error-circle\"></i>Username must start with a letter and can only <br> contain letters, numbers, underscores, and hyphens.');</script>";
        $noError = false;
    } else if (mysqli_num_rows($username_result) > 0){
        echo "<script>$('#username_error').html('<i class=\"bx bx-error-circle\"></i>Username has already been taken.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#username_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }

    //user email validation
    $email_query = "SELECT `email` FROM `user` WHERE `email`='$email' 
                    UNION
                    SELECT `email` FROM `admin` WHERE `email`='$email'";
    $email_result = mysqli_query($connection, $email_query);
    if (empty($email)) {
        echo "<script>$('#email_error').html('<i class=\"bx bx-error-circle\"></i>Email is required.');</script>";
        $noError = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>$('#email_error').html('<i class=\"bx bx-error-circle\"></i>Email is invalid.');</script>";
        $noError = false;
    } else if (mysqli_num_rows($email_result) > 0){
        echo "<script>$('#email_error').html('<i class=\"bx bx-error-circle\"></i>Email has already been used.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#email_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }

    //user password validation
    if (empty($password)) {
        echo "<script>$('#psw_error').html('<i class=\"bx bx-error-circle\"></i>Password is required.');</script>";
        $noError = false;
    } else if (strlen($password) < 8){
        echo "<script>$('#psw_error').html('<i class=\"bx bx-error-circle\"></i>Password should be at least 8 characters long.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#psw_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }
    
    //account creation successful
    if ($noError === true) {
        $first_name = ucwords(trim($first_name));
        $last_name = ucwords(trim($last_name));
        $otp_time = date('Y-m-d H:i:s');
        // echo "Form submitted successfully!";
        $acc_query = "INSERT INTO `admin` (`first_name`, `last_name`, `username`, `email`, `password`, `user_role`, `otp`, `otp_time`) 
        VALUES ('$first_name', '$last_name', '$username', '$email', '$password', '$role', '$otp', '$otp_time')";
        if(mysqli_query($connection, $acc_query)){
            mysqli_close($connection);
            echo "<script>window.location.reload();</script>";
        }
    } else {
        mysqli_close($connection);
    }
}

?>
