<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { 
    
    include 'db_conn.php';
    $query = "SELECT * FROM `user` WHERE `user_id`= {$_SESSION['user_id']}";
    $results = mysqli_query($connection, $query);

    //retrieve user data
    $row = mysqli_fetch_assoc($results);
    $user_first_name = $row['first_name'];
    $user_last_name = $row['last_name'];
    $user_username = $row['username'];
    $user_email = $row['email'];
    $user_password = $row['password'];
    $user_country = $row['country'];
    $user_gamepic = $row['user_pfp'];

    $query_pfp = "SELECT game_pic.image 
                FROM game_pic 
                JOIN user_pic ON game_pic.pic_id = user_pic.pic_id 
                WHERE user_pic.user_id = {$_SESSION['user_id']} AND user_pic.pic_id = '$user_gamepic'";
    $pfp_result = mysqli_query($connection, $query_pfp);
    $pfp_row = mysqli_fetch_assoc($pfp_result);
    $user_profilepic = isset($pfp_row) && $pfp_row['image'] ? $pfp_row['image'] : "default_profile.jpg";
    
    //close database connnection
    mysqli_close($connection);

    $fname_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $lname_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $username_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $email_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $psw_error = '<div class="erroricon"><i class="bx bx-error-circle"></i></div>';
    $noError = true;
    
    if (isset($_POST["btnSaveUserPic"])) {
        $new_pfp = $_POST["rdoProfile"];
        include "db_conn.php";
        $pfp_query = "UPDATE `user` SET `user_pfp` = '$new_pfp' WHERE `user_id` = '{$_SESSION['user_id']}'";
        if(mysqli_query($connection, $pfp_query)){

            //close database connnection
            mysqli_close($connection);

            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }
    } 


    if (isset($_POST["btnSaveUserInfo"])) {
        
        include "db_conn.php";
        //obtain user input
        $first_name = trim(mysqli_real_escape_string($connection, $_POST["txtFirstName"]));
        $last_name = trim(mysqli_real_escape_string($connection, $_POST["txtLastName"]));
        $username = trim(mysqli_real_escape_string($connection, $_POST["txtUsername"]));
        $email = trim(mysqli_real_escape_string($connection, $_POST["txtEmail"]));
        $password = trim(mysqli_real_escape_string($connection, $_POST["txtPassword"]));
        $country = trim(mysqli_real_escape_string($connection, $_POST["txtCountry"]));


        //check if user made any changes
        if (
            $user_first_name == $first_name &&
            $user_last_name == $last_name &&
            $user_username == $username &&
            $user_email == $email &&
            $user_password == $password &&
            $user_country == $country
        ) {
            echo '<script>alert("You have not made any changes!")</script>';
            $noError = false;  
        } else {
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
            } else if ($user_username != $username && mysqli_num_rows($username_result) > 0){
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
            } else if ($user_email != $email && mysqli_num_rows($email_result) > 0){
                $email_error = '<i class="bx bx-error-circle"></i>' . "Email has already been used.";
                $noError = false;
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
            
            $acc_query = "  UPDATE `user`
                            SET `first_name` = '$first_name',
                                `last_name` = '$last_name',
                                `username` = '$username',
                                `email` = '$email',
                                `password` = '$password',
                                `country` = '$country'
                            WHERE `user_id` = '{$_SESSION['user_id']}'";

            if(mysqli_query($connection, $acc_query)){

                //close database connnection
                mysqli_close($connection);

                header("Location: ../php/c_profile.php"); 
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
    <script src="../js/scroll_navbar.js"></script>
    <script src="../js/filter_sidebar.js"></script>
    <script src="../js/popup.js"></script>
    <script src="../js/show_hide_password.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.sidebar-item button');
        const lists = document.querySelectorAll('.sidebar-item');

        //event listener for buttons
        buttons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const category = button.getAttribute('data-category');
                filterRadioInputs(category);
            });
        });

        //event listener for lists
        lists.forEach(list => {
            list.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const category = list.querySelector('button').getAttribute('data-category');
                filterRadioInputs(category);
            });
        });

        //function to filter radio inputs
        function filterRadioInputs(category) {
            const radioInputs = document.querySelectorAll('.rendered-profile-wrapper input[type="radio"]');
            const labels = document.querySelectorAll('.rendered-profile-wrapper label');

            radioInputs.forEach((input, index) => {
                const label = labels[index];
                if (category === 'all' || input.getAttribute('data-category') === category) {
                    input.style.display = '';
                    label.style.display = '';
                } else {
                    input.style.display = 'none';
                    label.style.display = 'none';
                }
            });
        }
    });
    </script>

</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <!-- Profile -->

    <div class="profile-wrapper">
        <div class="container flex-col">
            <div class="profile-header">
                <div class="result-card">
                    <div class="profile-header-left">
                        <img src="../img/<?php echo $user_profilepic; ?>" alt="" />
                        <div class="edit-dim"></div>
                        <span class="edit-dim-text">
                            <i class="bx bxs-edit"></i>
                            Edit</span>
                    </div>
                </div>
                <div class="profile-header-right">
                    <span class="profile-name"><?php echo $user_first_name . " " . $user_last_name; ?></span>
                    <span class="profile-username">@ <?php echo $user_username; ?></span>
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
                                value="<?php echo $user_first_name; ?>" />
                            <div class="error">
                                <span><?php echo $fname_error; ?></span>
                            </div>
                        </div>

                        <!-------- last name field ------->
                        <div class="render_data">
                            <span class="label">Last Name</span>
                            <!-- <span class="data">Becker</span> -->
                            <input type="text" class="data" name="txtLastName" id="lastname"
                                value="<?php echo $user_last_name; ?>" />
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
                                value="<?php echo $user_username; ?>" />
                            <div class="error">
                                <span><?php echo $username_error; ?></span>
                            </div>
                        </div>

                        <!-------- email field ------->
                        <div class="render_data">
                            <span class="label">Email Address</span>
                            <!-- <span class="data">abubecker@gmail.com</span> -->
                            <input type="text" class="data" name="txtEmail" id="email"
                                value="<?php echo $user_email; ?>" />
                            <div class="error">
                                <span><?php echo $email_error; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-details-row">

                        <!-------- country field ------->
                        <div class="render_data">
                            <span class="label">Country</span>
                            <!-- <span class="data">Malaysia</span> -->
                            <!-- <input type="text" class="data" value="" /> -->
                            <select id="country" name="txtCountry" class="data">
                                <?php
                                    //store all countries in an array
                                    $countries = array(
                                        "Afghanistan", "Åland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, The Democratic Republic of The", "Cook Islands", "Costa Rica", "Cote D'ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-bissau", "Guyana", "Haiti", "Heard Island and Mcdonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran, Islamic Republic of", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Kitts and Nevis", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and The Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and The South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Timor-leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Viet Nam", "Virgin Islands, British", "Virgin Islands, U.S.", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"
                                    );
                                    //loop through the countries array
                                    //within each element/country, check if it matches with the user selected country
                                    foreach ($countries as $country) {
                                        //select malaysia by default
                                        if ($country === $user_country) {
                                            echo "<option value=\"$country\" selected> $country </option>";
                                        } else {
                                            // $selected = ($country === $userCountry) ? 'selected' : '';
                                            echo "<option value=\"$country\">$country</option>";
                                        }
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="render_data">

                            <!-------- password field ------->
                            <span class="label">Password</span>
                            <!-- <span class="data">12345678</span> -->
                            <input type="password" name="txtPassword" class="data" id="password" minlength="8"
                                value="<?php echo $user_password; ?>" />
                            <i class="bx bx-hide" id="pass-icon1" onclick="pass('password', 'pass-icon1')"></i>
                            <div class="error">
                                <span><?php echo $psw_error; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-action">

                    <!-- <button class="btn-edit">Edit Profile</button> -->
                    <input type="submit" class="btn-edit" value="Save Changes" name="btnSaveUserInfo" />
                </div>
            </form>


            <!-- pop up -->
            <div class="pop-up-overlay" style="display: none">
                <div class="pop-up">
                    <div class="close-button">
                        <span class="close-btn">
                            <i class="bx bx-x"></i>
                        </span>
                    </div>
                    <div class="pop-up-wrapper">
                        <div class="pop-up-left">
                            <ul class="sidebar-list">
                                <li class="sidebar-item active" data-category="all">
                                    <button data-category="all">
                                        <a href="#" class="sidebar-link">
                                            <span> All category </span>
                                        </a>
                                    </button>
                                </li>

                                <?php 
                                    include "db_conn.php";
                                    $query = "SELECT DISTINCT `category` FROM `game_pic`";
                                    $results = mysqli_query($connection, $query);
                                    if (mysqli_num_rows($results) > 0){
                                        while ($row = mysqli_fetch_assoc($results)){ ?>
                                <li class="sidebar-item" data-category=<?php echo $row['category']; ?>>
                                    <button data-category=<?php echo $row['category']; ?>>
                                        <a href="#" class="sidebar-link">
                                            <span><?php echo $row['category']; ?></span>
                                        </a>
                                    </button>
                                </li>
                                <?php }
                                    }
                                    mysqli_close($connection);
                                    ?>
                            </ul>
                        </div>
                        <div class="pop-up-right">
                            <div class="pop-up-header">
                                <span>Edit Profile Photo</span>
                            </div>
                            <form action="" method="post">
                                <div class="rendered-profile-wrapper">
                                    <!-- <span>Oops! No profile picture
                                        found！(◎_◎;)</span> -->
                                    <?php 
                                        include "db_conn.php";
                                        $query = "SELECT game_pic.* FROM game_pic JOIN user_pic ON game_pic.pic_id = user_pic.pic_id WHERE user_pic.user_id = {$_SESSION['user_id']}"; 
                                        $results = mysqli_query($connection, $query);
                                        if (mysqli_num_rows($results) > 0){
                                            $i = 0;
                                            while ($row = mysqli_fetch_assoc($results)){
                                                $inp_id = "profileRadio" . $i;
                                                ?>
                                    <input type="radio" id="<?php echo $inp_id ?>" name="rdoProfile"
                                        class="radio-hidden" value="<?php echo $row['pic_id'] ?>"
                                        data-category="<?php echo $row['category'] ?>" />
                                    <label for="<?php echo $inp_id ?>" class="rendered-profile">
                                        <div class="box">
                                            <img src="../img/<?php echo $row['image'] ?>" alt="" />
                                        </div>
                                        <div class="profile-desc">
                                            <span class="desc-name">
                                                <?php echo $row['name'] ?>
                                            </span>
                                            <span class="desc-series">
                                                <?php echo $row['category'] ?>
                                            </span>
                                        </div>
                                    </label>
                                    <?php
                                                $i++;
                                            }
                                        }
                                        mysqli_close($connection);
                                        ?>
                                </div>
                                <div class="action">
                                    <input type="submit" class="btn-edit" value="Save Changes" name="btnSaveUserPic" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
} else if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin")){
    header ('Location: ../php/a_Dashboard.php');
    exit();
} else {
    header ('Location: ../php/login.php');
    exit();
}
?>