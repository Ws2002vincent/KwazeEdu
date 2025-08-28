<?php
session_start();

if (isset($_SESSION["user_id"]) != null && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) { 

    $quiz_id = $_GET['id'];

    include "db_conn.php";
    $leaderboard_available = false;
    $last_updated = null;
    $exist_query = "SELECT * FROM `quiz` WHERE `quiz_id` = '$quiz_id'";
    $exist_result = mysqli_query($connection, $exist_query);
    if (mysqli_num_rows($exist_result) > 0) {
        $row = mysqli_fetch_assoc($exist_result);
        $quiz_title = $row['title'];
        $timestamp_date = $row['date_created'];
        $date = new DateTime($timestamp_date);
        $date_created = $date->format('d M Y');
        $leaderboard_available = true;
        $last_updated = $row['last_updated'];
    }
    
    mysqli_close($connection);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Details</title>

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
    <!-- <link rel="stylesheet" href="../../css/c_summary.css" /> -->
    <!-- <link rel="stylesheet" href="../../css/c_quiz.css" /> -->
    <link rel="stylesheet" href="../css/a_leaderboard.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_Admin.php'; ?>

    <!-- content -->
    <div class="uni-wrapper">
        <div class="container flex-col">
            <div class="content-container">

                <!-- directory -->
                <div class="directory">
                    <button><i class="bx bx-arrow-back" onclick="window.location.href='../php/a_rank_view.php'"></i></button>
                </div>

                <div class="cong-header">
                    <div class="cong-details flex-col">
                        <span class="quiz-name"><?php echo $quiz_title; ?></span>
                        <div class="date">
                            <span>Creation Date: </span>
                            <span><?php echo $date_created; ?></span>
                        </div>
                    </div>
                </div>

                <!-- leaderboard -->

                <?php
                //obtain all rankings
                $rankings = [];
                if ($leaderboard_available && $last_updated != null) {
                    include "db_conn.php";
                    $sql = "SELECT * FROM `user_result` WHERE `quiz_id` = '$quiz_id' AND `date_completed` > '$last_updated' ORDER BY CAST(JSON_EXTRACT(`score`, '$.total') AS UNSIGNED) DESC, `timestamp` ASC, `date_completed` DESC";
                    $result = $connection->query($sql);
                    if ($result->num_rows > 0) {
                        $existing_users = [];
                        $rank_num = 1;
                        while ($row = $result->fetch_assoc()) {
                            if (!in_array($row["user_id"], $existing_users)) {
                                $total_score = json_decode($row["score"], true)["total"];
                                $user_id = $row['user_id'];

                                //retrieve user data
                                $username_query = "SELECT * FROM `user` WHERE `user_id`= '$user_id'";
                                $username_result = mysqli_query($connection, $username_query);
                                $user_row = mysqli_fetch_assoc($username_result);
                                $user_username = $user_row['username'];
                                $user_gamepic = $user_row['user_pfp'];
                                $query_pfp = "SELECT game_pic.image 
                                            FROM game_pic 
                                            JOIN user_pic ON game_pic.pic_id = user_pic.pic_id 
                                            WHERE user_pic.user_id = '$user_id' AND user_pic.pic_id = '$user_gamepic'";
                                $pfp_result = mysqli_query($connection, $query_pfp);
                                $pfp_row = mysqli_fetch_assoc($pfp_result);
                                $user_profilepic = isset($pfp_row) && $pfp_row['image'] ? $pfp_row['image'] : "default_profile.jpg";

                                $rankings[] = array("rank" => $rank_num, "user_id" => $user_id, "username" => $user_username, "pfp_pic" => $user_profilepic,  "score" => $total_score, "time" => $row["timestamp"]);
                                $existing_users[] = $row["user_id"];
                                $rank_num++;
                            }
                        }
                    }
                    mysqli_close($connection);
                }
                ?>

                <?php 
                if ($leaderboard_available && $last_updated != null) { ?>
                <div class="leaderboard">
                    <span class="leaderboard-title">Leaderboard</span>
                    <div class="leaderboard-wrapper">
                        <div class="lead-123">
                            <?php 
                            $ranks = count($rankings);
                            if ($ranks >= 2) {?>
                            <div class="lead-box rank-2">
                                <span class="lead-number-123">2</span>
                                <div class="outer">
                                    <div class="inner">
                                        <img src="../img/<?php echo $rankings[1]["pfp_pic"]; ?>" alt="" />
                                    </div>
                                </div>
                                <span class="username">@ <?php echo $rankings[1]["username"]; ?></span>
                                <span class="score"><?php echo $rankings[1]["score"]; ?></span>
                            </div>
                            <?php } else { ?>
                                <div class="lead-box rank-2">
                                    <span class="lead-number-123">2</span>
                                    <div class="outer">
                                        <div class="inner"></div>
                                    </div>
                                    <span class="username"></span>
                                    <span class="score">--</span>
                                </div>
                            <?php } ?>

                            <?php 
                            if ($ranks >= 1) {?>
                            <div class="lead-box rank-1">
                                <span><i class="bx bxs-trophy"></i></span>
                                <span class="lead-number-123">1</span>
                                <div class="outer">
                                    <div class="inner"><img src="../img/<?php echo $rankings[0]["pfp_pic"]; ?>" alt="" /></div>
                                </div>
                                <span class="username">@ <?php echo $rankings[0]["username"]; ?></span>
                                <span class="score"><?php echo $rankings[0]["score"]; ?></span>
                            </div>
                            <?php } else { ?>
                                <div class="lead-box rank-1">
                                    <span><i class="bx bxs-trophy"></i></span>
                                    <span class="lead-number-123">1</span>
                                    <div class="outer">
                                        <div class="inner"></div>
                                    </div>
                                    <span class="username"></span>
                                    <span class="score">--</span>
                                </div>
                            <?php } ?>

                            <?php 
                            if ($ranks >= 3) {?>
                            <div class="lead-box rank-3">
                                <span class="lead-number-123">3</span>
                                <div class="outer">
                                    <div class="inner"><div class="inner"><img src="../img/<?php echo $rankings[2]["pfp_pic"]; ?>" alt="" /></div></div>
                                </div>
                                <span class="username">@ <?php echo $rankings[2]["username"]; ?></span>
                                <span class="score"><?php echo $rankings[2]["score"]; ?></span>
                            </div>
                            <?php } else { ?>
                                <div class="lead-box rank-3">
                                    <span class="lead-number-123">3</span>
                                    <div class="outer">
                                        <div class="inner"><div class="inner"></div></div>
                                    </div>
                                    <span class="username"></span>
                                    <span class="score">--</span>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- other rank -->
                        <div class="lead-other">
                            <?php
                            $row_number = 3;
                            while ($row_number < 10) {
                                $numberString = strval($row_number + 1); 
                                if (strlen($numberString) < 2) {
                                    $numberString = "0" . $numberString;
                                }
                                if ($ranks >= ($row_number + 1)) { ?>
                                    <div class="lead-other-box">
                                        <span class="lead-number"><?php echo $numberString; ?></span>
                                        <div class="outer-other">
                                            <div class="inner-other"><img src="../img/<?php echo $rankings[$row_number]["pfp_pic"]; ?>" alt="" /></div>
                                        </div>
                                        <span class="username username-other">
                                            @ <?php echo $rankings[$row_number]["username"]; ?>
                                        </span>
                                        <span class="score score-other"><?php echo $rankings[$row_number]["score"]; ?></span>
                                    </div>
                                <?php } else { ?>
                                    <div class="lead-other-box">
                                        <span class="lead-number"><?php echo $numberString; ?></span>
                                        <div class="outer-other">
                                            <div class="inner-other"></div>
                                        </div>
                                        <span class="username username-other">
                                            --
                                        </span>
                                        <span class="score score-other">--</span>
                                    </div>
                                <?php } 
                                $row_number ++;
                            } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
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