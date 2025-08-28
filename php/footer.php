<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Footer</title>
</head>

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

<style>
footer {
    background-color: #1c253b;
    padding: 50px 0;
    height: 350px;
    position: relative;
}

.footer-flex {
    display: flex;
    flex-direction: column;
}

.footer-logo {
    cursor: pointer;
    margin: 0 auto 20px;
}

.footer-logo a {
    text-decoration: none;
}

.footer-logo span {
    font-size: 22px;
    font-weight: bold;
    color: #ddddfc;
    font-family: "Shrikhand", serif;
    font-weight: 400;
    font-style: normal;
}

.footer-logo .orange {
    color: #de581b;
}

.footer-list {
    display: flex;
    justify-content: center;
}

.footer-list li {
    font-size: 14px;
    list-style: none;
    padding-right: 20px;
    font-weight: bold;
}

.footer-list li a {
    text-decoration: none;
    color: #ddddfc;
}

.footer-list li a:hover {
    color: #de581b;
}

.contact {
    display: flex;
    justify-content: center;
    flex-direction: column;
    margin-top: 90px;
}

.contact span {
    color: #ddddfc;
    font-size: 14px;
    margin: 0 auto;
    line-height: 20px;
}

.social {
    display: flex;
    justify-content: center;
    margin-top: 15px;
}

.social a {
    text-decoration: none;
    color: #ddddfc;
    padding: 0 5px;
}

.social a:hover {
    color: #de581b;
}

.copyright {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    text-align: center;
    font-size: 12px;
    padding: 10px;
    background-color: #1c253b;
    color: #8389b5;
    border-top: #8389b5 1px solid;
}
</style>

<body>
    <footer>
        <div class="container footer-flex">
            <div class="footer-logo">
                <a href="../php/Index.php">
                    <span>Kwaze</span>
                    <span class="orange">Edu</span>
                    <span class="dot">.</span>
                </a>
            </div>
            <ul class="footer-list">
                <li><a href="../php/Index.php#overview">Overview</a></li>
                <li><a href="../php/Index.php#service">Our Service</a></li>
                <li><a href="../php/Index.php#feature">Feature</a></li>
                <li><a href="../php/Index.php#about">About Us</a></li>
                <li><a href="../php/privacy_policy.php">Privacy Policy</a></li>
                <li><a href="../php/terms_service.php">Terms of Service</a></li>
            </ul>

            <div class="contact">
                <span>
                    <i class="bx bx-map"></i> 123, Jalan ABC, 12345, Kuala
                    Lumpur, Malaysia
                </span>
                <span><i class="bx bx-phone"></i> +60 12-345 6789</span>
            </div>

            <div class="social">
                <a href="#">
                    <i class="bx bxl-facebook"></i>
                </a>
                <a href="#">
                    <i class="bx bxl-gmail"></i>
                </a>
                <a href="#">
                    <i class="bx bxl-instagram"></i>
                </a>
            </div>
            <div class="copyright">
                <span>
                    &copy; 2024 KwazeEdu. All rights reserved. <br />
                    Designed by KwazeEdu Team
                </span>
            </div>
        </div>
    </footer>
</body>

</html>