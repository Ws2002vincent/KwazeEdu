<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { 
    
    include 'db_conn.php';
    $query = "SELECT * FROM `user` WHERE `user_id`= {$_SESSION['user_id']}";
    $results = mysqli_query($connection, $query);

    //retrieve user data
    $row = mysqli_fetch_assoc($results);
    $user_coins = $row['user_coins'];

    //close database connnection
    mysqli_close($connection);

    if (isset($_POST['btnUnlock'])){
        include 'db_conn.php';
        $img_id = mysqli_real_escape_string($connection, $_POST["img_id"]);
        $img_price = mysqli_real_escape_string($connection, $_POST["img_price"]);

        $owned_query = "SELECT * FROM `user_pic` WHERE `pic_id` = '$img_id' AND `user_id` = '{$_SESSION['user_id']}'";
        $owned_results = mysqli_query($connection, $owned_query);


        if ($user_coins >= $img_price && mysqli_num_rows($owned_results) < 1) {
            $pic_query = "INSERT INTO `user_pic` (`user_id`, `pic_id`) VALUES ({$_SESSION['user_id']}, '$img_id')";
            $pic_result = mysqli_query($connection, $pic_query);
        
            $user_coins = $user_coins - $img_price;
        
            $coin_query = "UPDATE `user` SET `user_coins` = '$user_coins' WHERE `user_id` = '{$_SESSION['user_id']}'";
            $coin_result = mysqli_query($connection, $coin_query);
            mysqli_close($connection);
            header('Location:../php/c_store.php');
            exit();
        } else {
            mysqli_close($connection);
        }
    }

    ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gamified Store</title>

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
    <link rel="stylesheet" href="../css/c_store.css" />

    <!-- JS -->
    <script src="../js/filter_sidebar.js"></script>
    <script src="../js/popup.js"></script>
    <script src="../js/scroll_navbar.js"></script>
    <script defer>
    document.addEventListener("DOMContentLoaded", function() {
        const resultCards = document.querySelectorAll(".result-card");
        const popUpOverlay = document.querySelector(".pop-up-overlay");
        const popUpImg = document.querySelector(".pop-up-img img");
        const popUpName = document.getElementById("pop-up-name");
        const popUpCategory = document.getElementById("pop-up-category");
        const popUpPrice = document.getElementById("pop-up-price");
        const popUpCost = document.getElementById("pop-up-cost");
        const popUpButton = document.getElementById("pop-up-button");
        const popUpID = document.getElementById("pop-up-id");

        var unlockButton = document.getElementById("pop-up-button");
        unlockButton.addEventListener("click", checkPrice);

        resultCards.forEach(card => {
            card.addEventListener("click", function() {
                const id = card.getAttribute("data-id");
                const name = card.getAttribute("data-name");
                const category = card.getAttribute("data-category");
                const price = card.getAttribute("data-price");
                const image = card.getAttribute("data-image");
                const owned = card.getAttribute("data-owned");

                popUpName.textContent = name;
                popUpCategory.textContent = category;
                popUpImg.src = image;
                popUpID.value = id;
                popUpCost.value = price;

                if (owned) {
                    popUpPrice.innerHTML = `<br>`;
                    popUpButton.style.display = "block";
                    popUpButton.textContent = "Owned";
                    popUpButton.disabled = true;
                    popUpButton.classList.remove("unlock");
                    popUpButton.classList.add("unlock-disabled");
                } else if (price == 0) {
                    popUpPrice.textContent = "Free";
                    popUpButton.style.display = "block";
                    popUpButton.textContent = "Unlock";
                    popUpButton.disabled = false;
                    popUpButton.classList.remove("unlock-disabled");
                    popUpButton.classList.add("unlock");
                } else {
                    popUpPrice.innerHTML = `<i class="bx bxs-coin"></i>${price}`;
                    popUpButton.style.display = "block";
                    popUpButton.textContent = "Unlock";
                    popUpButton.disabled = false;
                    popUpButton.classList.remove("unlock-disabled");
                    popUpButton.classList.add("unlock");
                }
            });
        });

        const buttons = document.querySelectorAll('.sidebar-item button');
        const lists = document.querySelectorAll('.sidebar-item');

        buttons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const category = button.getAttribute('data-category');
                filterResults(category);
            });
        });

        lists.forEach(list => {
            list.addEventListener('click', function(event) {
                event.preventDefault();
                const category = list.getAttribute('data-category');
                filterResults(category);
            });
        });

        function filterResults(category) {
            resultCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (category === 'all' || cardCategory === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function checkPrice(event) {
            var userBudget = <?php echo json_encode($user_coins); ?>;

            var price = document.getElementById("pop-up-price").textContent;

            var priceValue = parseFloat(price);

            if (priceValue > userBudget) {
                alert("You do not have enough coins!");
                event.preventDefault();
            }
        }
    });
    </script>
    <style>
    .unlock-disabled {
        width: 200px;
        margin-top: 10px;
        border: none;
        background-color: #e97b49;
        color: #f3f2ff;
        padding: 5px 10px;
        border-radius: 5px;
        opacity: 0.5;
        cursor: not-allowed;
    }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <div class="sidebar-filter">
        <div class="container flex-row">
            <div class="content-container">
                <div class="sidebar">
                    <i class="bx bx-list-ol"></i>
                    <ul class="sidebar-list">
                        <li class="sidebar-item active" data-category="all">
                            <button data-category="all">
                                <a href="#" class="sidebar-link">
                                    <span>All category</span>
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
                <div class="main-content">
                    <div class="explore-title">
                        <span><i class="bx bxs-invader"></i>Gamified Store</span>
                        <div class="coin">
                            <span><i class="bx bxs-coin"></i><?php echo $user_coins; ?></span>
                        </div>
                    </div>

                    <!-- result -->
                    <div class="wrapper">
                        <?php 
                        include "db_conn.php";
                        $query = "SELECT * FROM `game_pic`";
                        $results = mysqli_query($connection, $query);

                        if (mysqli_num_rows($results) > 0) {
                            while ($row = mysqli_fetch_assoc($results)) {
                                $pic_query = "SELECT * FROM user_pic WHERE pic_id = {$row['pic_id']} AND user_id = {$_SESSION['user_id']}"; 
                                $pic_results = mysqli_query($connection, $pic_query);
                                $is_owned = ($pic_results && mysqli_num_rows($pic_results) > 0);

                                ?>
                            <div class="result-card" data-id="<?php echo htmlspecialchars($row['pic_id']); ?>"
                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                data-price="<?php echo htmlspecialchars($row['price']); ?>"
                                data-image="../img/<?php echo htmlspecialchars($row['image']); ?>"
                                data-owned="<?php echo $is_owned ? 'isOwned' : ''; ?>">
                                <div class="result-card-img">
                                    <img src="../img/<?php echo htmlspecialchars($row['image']); ?>" alt="no image" />
                                </div>
                                <div class="result-card-content">
                                    <span class="pro-name"><?php echo htmlspecialchars($row['name']); ?></span>
                                    <span class="pro-series"><?php echo htmlspecialchars($row['category']); ?></span>
                                    <?php
                                            if ($is_owned) {
                                                ?> <span class="text">Owned</span> <?php
                                            } else if (htmlspecialchars($row['price']) == 0) {
                                                ?> <span class="text">Free</span> <?php
                                            } else {
                                                ?>
                                    <span class="coin-needed"><i
                                            class="bx bxs-coin"></i><?php echo htmlspecialchars($row['price']); ?></span><?php
                                            }
                                            ?>
                                </div>
                            </div>
                            <?php
                            }
                        }
                        mysqli_close($connection);
                        ?>
                    </div>
                </div>

                <!-- pop-up -->
                <div class="pop-up-overlay" style="display: none">
                    <div class="pop-up">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>
                        <div class="pop-up-wrapper">
                            <div class="pop-up-img">
                                <img src="" alt="" />
                            </div>
                            <div class="details">
                                <div class="details-1">
                                    <span class="pop-up-details">Name:</span>
                                    <span class="pop-up-ans" id="pop-up-name"></span>
                                    <span class="pop-up-details">Series:</span>
                                    <span class="pop-up-ans" id="pop-up-category"></span>
                                </div>
                                <div class="details-2">
                                    <span id="pop-up-price"></span>
                                    <form method="post" action="#">
                                        <button class="unlock" id="pop-up-button" name="btnUnlock" onclick="return checkPrice();">Unlock</button>
                                        <input type="hidden" id="pop-up-id" name="img_id" value="">
                                        <input type="hidden" id="pop-up-cost" name="img_price" value="">
                                    </form>
                                </div>
                            </div>
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