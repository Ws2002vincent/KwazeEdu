<?php
session_start();

if (isset($_SESSION["user_id"]) != null && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) { 

    include 'db_conn.php';
    $query = "SELECT * FROM `admin` WHERE `admin_id`= {$_SESSION['user_id']}";
    $results = mysqli_query($connection, $query);

    //retrieve user data
    $row = mysqli_fetch_assoc($results);
    $admin_first_name = $row['first_name'];
    $admin_last_name = $row['last_name'];
    $admin_username = $row['username'];
    $admin_email = $row['email'];
    $admin_password = $row['password'];

    //close database connnection
    mysqli_close($connection);

    $fname_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $lname_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $username_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $email_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $psw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $noError = true;

    if (isset($_POST["btnSaveAdminInfo"])) {
        
        include "db_conn.php";
        //obtain user input
        $first_name = trim(mysqli_real_escape_string($connection, $_POST["txtFirstName"]));
        $last_name = trim(mysqli_real_escape_string($connection, $_POST["txtLastName"]));
        $username = trim(mysqli_real_escape_string($connection, $_POST["txtUsername"]));
        $email = trim(mysqli_real_escape_string($connection, $_POST["txtEmail"]));
        $password = trim(mysqli_real_escape_string($connection, $_POST["txtPassword"]));

        //check if user made any changes
        if (
            ($admin_first_name == $first_name &&
            $admin_last_name == $last_name &&
            $admin_username == $username &&
            $admin_email == $email &&
            $admin_password == $password)
            || ($_SESSION['role'] == "manager" && $admin_password == $password)
        ) {
            echo '<script>alert("You have not made any changes!")</script>';
            $noError = false;  
        } else {
            
            if ($_SESSION['role'] == "admin") {
                //user first name & last name validation
                $fullname = $first_name . " " . $last_name;
                if (empty($first_name)){
                    $fname_error = '<i class="bx bx-error-circle"></i>' . "First name is required.";
                    $noError = false;
                } else if (empty($last_name)) {
                    $lname_error = '<i class="bx bx-error-circle"></i>' . "Last name is required.";
                } else if (!preg_match("/^[a-zA-Z ]*$/", $fullname)){
                    $name_error = '<i class="bx bx-error-circle"></i>' . "Name should contain letters and whitespaces only.";
                    $noError = false;
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
                } else if ($admin_username != $username && mysqli_num_rows($username_result) > 0){
                    $username_error = '<i class="bx bx-error-circle"></i>' . "Username has already been taken.";
                    $noError = false;
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
                } else if ($admin_email != $email && mysqli_num_rows($email_result) > 0){
                    $email_error = '<i class="bx bx-error-circle"></i>' . "Email has already been used.";
                    $noError = false;
                }
            }

            //user password validation
            if (empty($password)) {
                $psw_error = '<i class="bx bx-error-circle"></i>' . "Password is required.";
                $noError = false;
            } else if (strlen($password) < 8){
                $psw_error = '<i class="bx bx-error-circle"></i>' . "Password should be at least 8 characters long.";
                $noError = false;
            }
        }

        //user account update successful
        if ($noError === true) {

            $first_name = ucwords($first_name);
            $last_name = ucwords($last_name);
            
            if ($_SESSION['role'] == "manager") {
                $acc_query = "  UPDATE `admin`
                            SET `password` = '$password'
                            WHERE `admin_id` = '{$_SESSION['user_id']}'";
            } else {
                $acc_query = "  UPDATE `admin`
                            SET `first_name` = '$first_name',
                                `last_name` = '$last_name',
                                `username` = '$username',
                                `email` = '$email',
                                `password` = '$password'
                            WHERE `admin_id` = '{$_SESSION['user_id']}'";
            }
            if(mysqli_query($connection, $acc_query)){

                //close database connnection
                mysqli_close($connection);

                header("Location: ../php/a_profile.php"); 
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
    <title>Profile</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet" />

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/c_profile.css" />

    <!-- JS -->
    <script src="../js/show_hide_password.js"></script>


</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_Admin.php'; ?>

    <!-- Profile -->

    <div class="profile-wrapper">
        <div class="container flex-col">
            <div class="profile-header">
                <div class="profile-header-left admin">
                    <img src="../img/default_profile.jpg" alt="" />
                </div>
                <div class="profile-header-right">
                    <span class="profile-name"><?php echo $admin_first_name . " " . $admin_last_name; ?></span>
                    <span class="profile-username">@ <?php echo $admin_username; ?></span>
                </div>
            </div>
            <form action="" method="post">
                <div class="profile-details">
                    <div class="profile-details-row">
                        <!-------- first name field ------->
                        <div class="render_data">
                            <span class="label">First Name</span>
                            <!-- <span class="data">Abu</span> -->
                            <input type="text" class="data" name="txtFirstName" id="firstname"
                                value="<?php echo $admin_first_name; ?>" />
                            <div class="error">
                                <span><?php echo $fname_error; ?></span>
                            </div>
                        </div>

                        <!-------- last name field ------->
                        <div class="render_data">
                            <span class="label">Last Name</span>
                            <!-- <span class="data">Becker</span> -->
                            <input type="text" class="data" name="txtLastName" id="lastname"
                                value="<?php echo $admin_last_name; ?>" />
                            <div class="error">
                                <span><?php echo $lname_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-details-row">
                        <!-------- username field ------->
                        <div class="render_data">
                            <span class="label">Username</span>
                            <!-- <span class="data">abubeckkker</span> -->
                            <input type="text" class="data" name="txtUsername" id="username"
                                value="<?php echo $admin_username; ?>" />
                            <div class="error">
                                <span><?php echo $username_error; ?></span>
                            </div>
                        </div>

                        <!-------- email field ------->
                        <div class="render_data">
                            <span class="label">Email Address</span>
                            <!-- <span class="data">abubecker@gmail.com</span> -->
                            <input type="text" class="data" name="txtEmail" id="email"
                                value="<?php echo $admin_email; ?>" />
                            <div class="error">
                                <span><?php echo $email_error; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-details-row">
                        <div class="render_data">
                            <!-------- password field ------->
                            <span class="label">Password</span>
                            <!-- <span class="data">12345678</span> -->
                            <input type="password" name="txtPassword" class="data" id="password" minlength="8"
                                value="<?php echo $admin_password; ?>" />
                            <i class="bx bx-hide" id="pass-icon1" onclick="pass('password', 'pass-icon1')"></i>
                            <div class="error">
                                <span><?php echo $psw_error; ?></span>
                            </div>
                        </div>

                        <!-------- extra field (the opacity is 0, use to just deco)------->
                        <div class="render_data opacity-no">
                            <span class="label">Last Name</span>
                            <span class="data">Becker</span>
                            <!-- <input type="text" class="data" value="" /> -->
                            <div class="error">
                                <i class="bx bx-error-circle"></i>
                                <span>Username or email is required.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-action">
                    <!-- <button class="btn-edit">Edit Profile</button> -->
                    <input type="submit" class="btn-edit" value="Save Changes" name="btnSaveAdminInfo" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
} else if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "user")) {
    header ('Location: ../php/c_Dashboard.php');
    exit();
} else {
    header ('Location: ../php/login.php');
    exit();
}
?>