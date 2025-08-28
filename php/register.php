<?php

session_start();

//set empty array if variable is not set or null
$_SESSION['correctInput'] ??= [];

include "functions.php";

$_SESSION['userAccData'] = createUserAccount();

if (!empty($_SESSION['userAccData'])){

    //insert data into email_verify table
    include "db_conn.php";
    $_SESSION['user_email'] = mysqli_real_escape_string($connection, $_POST["txtEmail"]);
    $query = "INSERT INTO `email_verify` (`id`, `email`, `otp`, `otp_time`) VALUES (NULL, '{$_SESSION['user_email']}', '@@@', '@@@')";
    mysqli_query($connection, $query);
    mysqli_close($connection);

    //generate new otp & send email
    $otp = generateOTP(6, 'email_verify');

    //write email content
    $email_subject = "Verify Email";
    include "email_template.php";
    $email_message = emailTemplateOTP("Thanks for your registration!", "KwazeEdu account registration", $otp);
    
    //send email & store output (error) if any
    $hasError = sendEmail($_SESSION['user_email'], $email_subject, $email_message);

    if (!empty($hasError)) {
        $code_error = '<i class="bx bx-error-circle"></i>' . "Could not send email. Please try again.";
    }

    header("Location: ../php/verify_email.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Unbounded:wght@200..900&display=swap"
        rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/register.css" />

    <!-- JS -->
    <script src="../js/show_hide_password.js"></script>
    <script>
    // function clearInput() {

    // }
    </script>
</head>

<body>
    <div class="card">
        <div class="card-header">
            <h1>Create Account</h1>
            <span>
                Already have an account?
                <!-- remember change to .php -->
                <a href="../php/login.php">Sign In</a>
            </span>
        </div>
        <div class="card-body">

            <!-- remember change to .php -->
            <form action="" method="post">
                <!-- name -->
                <div class="form-control-name">
                    <i class="bx bx-user"></i>
                    <input type="text" name="txtFname" id="fname" placeholder="First name"
                        value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["fname"] : ''; ?>" />
                    <input type="text" name="txtLname" id="lname" placeholder="Last name"
                        value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["lname"] : ''; ?>" />
                </div>
                <div class="error">
                    <span><?php echo $name_error; ?></span>
                </div>

                <!-- username -->
                <div class="form-control">
                    <i class="bx bx-user-pin"></i>
                    <input type="text" name="txtUsername" id="username" placeholder="Create a Username"
                        value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["username"] : ''; ?>" />
                </div>
                <div class="error">
                    <span><?php echo $username_error; ?></span>
                </div>

                <!-- email -->
                <div class="form-control">
                    <i class="bx bx-envelope"></i>
                    <input type="email" name="txtEmail" id="email" placeholder="Email"
                        value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["email"] : ''; ?>" />
                </div>
                <div class="error">
                    <span><?php echo $email_error; ?></span>
                </div>

                <!-- password -->
                <div class="form-control">
                    <i class="bx bx-lock-alt"></i>
                    <input type="password" name="txtPassword" id="password" placeholder="Password"
                        value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["password"] : ''; ?>" />
                    <i class="bx bx-hide" id="pass-icon2" onclick="pass('password', 'pass-icon2')"></i>
                </div>
                <div class="error">
                    <span><?php echo $psw_error; ?></span>
                </div>

                <!-- confirm password -->
                <div class="form-control">
                    <i class="bx bx-lock"></i>
                    <input type="password" name="txtConfirmPassword" id="confirmPassword" placeholder="Confirm Password"
                        value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["confpsw"] : ''; ?>" />
                    <i class="bx bx-hide" id="pass-icon3" onclick="pass('confirmPassword', 'pass-icon3')"></i>
                </div>
                <div class="error">
                    <span><?php echo $confpsw_error; ?></span>
                </div>

                <!-- country -->
                <div class="form-control">
                    <i class="bx bx-planet"></i>
                    <select id="country" name="txtCountry" class="form-control">
                        <?php
                        //store all countries in an array
                        $countries = array(
                            "Afghanistan", "Åland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, The Democratic Republic of The", "Cook Islands", "Costa Rica", "Cote D'ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-bissau", "Guyana", "Haiti", "Heard Island and Mcdonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran, Islamic Republic of", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Helena", "Saint Kitts and Nevis", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and The Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and The South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Timor-leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Viet Nam", "Virgin Islands, British", "Virgin Islands, U.S.", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"
                        );

                        //declare variable to store user selected country
                        $userCountry = !empty($_SESSION['correctInput']['country']) ? $_SESSION['correctInput']['country'] : '';

                        //loop through the countries array
                        //within each element/country, check if it matches with the user selected country
                        foreach ($countries as $country) {
                            //select malaysia by default
                            if (empty($userCountry) && $country === "Malaysia") {
                                echo "<option value=\"$country\" selected> $country </option>";
                            } else {
                                //if user has selected a country previously, select the same country again
                                $selected = ($country === $userCountry) ? 'selected' : '';
                                echo "<option value=\"$country\" $selected>$country</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Role -->
                <input type="hidden" name="rdoRole" value="user">

                <!-- button -->
                <div class="btn">
                    <div class="reset">
                        <input type="submit" value="Reset" name="btnReset" />
                    </div>
                    <div class="submit">
                        <input type="submit" value="Sign Up" name="btnRegister" />
                    </div>
                </div>
            </form>
        </div>
        <div class="ex-text">
            <span>By signing up button, I agree to the
                <a href="../php/terms_service.php" target="_blank">Terms & Conditions</a> and
                <a href="../php/privacy_policy.php" target="_blank">Privacy Policy</a></span>
        </div>
    </div>
</body>

</html>