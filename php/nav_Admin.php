<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>nav_Admin</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Unbounded:wght@200..900&display=swap"
        rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet" />

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- Js -->
    <script src="../js/scroll_navbar.js" defer></script>

    <!-- CSS -->
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Chivo", sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }

    .container {
        width: 1190px;
        margin: 0 auto;
    }

    /* nav */

    nav {
        background-color: #f3f2ff;
        height: 60px;
        display: flex;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: #8389b5 0px 0px 50px;
        transition: all 1.5s ease;
    }

    .no-shadow {
        box-shadow: none;
    }

    .nav-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo a {
        text-decoration: none;
    }

    .logo span {
        font-size: 22px;
        font-weight: bold;
        color: #33384f;
        font-family: "Shrikhand", serif;
        font-weight: 400;
        font-style: normal;
    }

    .logo .orange {
        color: #e97b49;
    }

    .nav-list {
        display: flex;
        position: relative;
    }

    .nav-list .list,
    .nav-list .redirect {
        height: 60px;
        line-height: 60px;
        list-style: none;
        margin-right: 20px;
        font-weight: bold;
        color: #33384f;
        transition: ease 0.1s;
        cursor: pointer;
        font-size: 14px;
    }

    .nav-list .list:last-child {
        margin-right: 0;
    }

    .nav-list .list>i {
        position: relative;
        top: 2px;
    }

    .nav-list .list:hover {
        color: #de581b;
    }

    .nav-list .list:hover .dropdown,
    .nav-list .list:hover .dropdown-last {
        visibility: visible;
        opacity: 1;
    }

    .dropdown {
        position: absolute;
        width: 180px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.1);
        margin-top: 5px;
        visibility: hidden;
        opacity: 0;
        transition: linear 0.1s;
        background-color: #f3f2ff;
        font-size: 12px;
    }

    .dropdown-last {
        right: 0;
        position: absolute;
        width: 180px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.1);
        margin-top: 5px;
        visibility: hidden;
        opacity: 0;
        transition: linear 0.1s;
        background-color: #f3f2ff;
        font-size: 12px;
    }

    .dropdown a,
    .dropdown-last a,
    .nav-list .redirect {
        text-decoration: none;
        color: #33384f;
        font-weight: 600;
        transition: ease 0.1s;
    }

    .dropdown a li,
    .dropdown-last a li {
        height: 40px;
        line-height: 40px;
        padding-left: 10px;
        list-style: none;
    }

    .dropdown li:hover,
    .dropdown-last li:hover {
        background-color: #ddddfc;
    }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <nav class="no-shadow">
        <div class="container nav-flex">
            <div class="logo">
                <a href="../php/a_Dashboard.php">
                    <span>Kwaze</span>
                    <span class="orange">Edu</span>
                    <span class="dot">.</span>
                </a>
            </div>
            <ul class="nav-list">
                <li class="list">
                    Manage Resources<i class="bx bx-chevron-down"></i>
                    <ul class="dropdown">
                        <a href="../php/a_mat_view.php">
                            <li>Learning Material</li>
                        </a>
                        <a href="../php/a_quiz_view.php">
                            <li>Practice Quizzes</li>
                        </a>
                        <a href="../php/a_rank_view.php">
                            <li>Ranked Quizzes</li>
                        </a>
                    </ul>
                </li>
                <li class="list">
                    Manage Account<i class="bx bx-chevron-down"></i>
                    <ul class="dropdown">
                        <a href="../php/a_cus_view.php">
                            <li>Manager User</li>
                        </a>
                        <a href="../php/a_admin_view.php">
                            <li>Manage Admin</li>
                        </a>
                    </ul>
                </li>
                <!-- ?toStore=true -->
                <a href="../php/a_store.php" class="redirect">
                    <li class="list">Gamified Store</li>
                </a>
                <li class="list">
                    Profile<i class="bx bx-chevron-down"></i>
                    <ul class="dropdown-last">
                        <a href="../php/a_profile.php">
                            <li>View Profile</li>
                        </a>
                        <a href="../php/logout.php">
                            <li>Logout</li>
                        </a>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</body>

</html>