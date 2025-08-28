<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nav Index</title>

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

    <!-- Js -->
    <script src="../js/scroll_Index.js" defer></script>
    <script src="../js/scroll_navbar.js" defer></script>

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

    nav {
        background-color: #f3f2ff;
        height: 60px;
        width: 100%;
        display: flex;
        /* position: fixed; */
        top: 0;
        /* z-index: 999; */
        box-shadow: #8389b5 0px 0px 50px;
        transition: all 1s ease;
        /* border-bottom: #161a30 1px solid !important; */
        /* box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
		0 2px 4px -2px rgba(0, 0, 0, 0.1); */
    }

    .no-shadow {
        box-shadow: none;
    }

    .nav-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        cursor: pointer;
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
        color: #de581b;
    }
    </style>
</head>

<body>
    <nav class="no-shadow">
        <div class="container nav-flex">
            <div class="logo">
                <a href="#">
                    <span>Kwaze</span>
                    <span class="orange">Edu</span>
                    <span class="dot">.</span>
                </a>

                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelector(".logo a").addEventListener("click", function(event) {
                        event.preventDefault();
                        window.history.back();
                    });
                });
                </script>

            </div>
        </div>
    </nav>
</body>

</html>