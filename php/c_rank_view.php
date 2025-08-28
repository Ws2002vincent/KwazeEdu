<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ranked Quizzes</title>

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
    <link rel="stylesheet" href="../css/c_view.css" />

    <!-- JS -->
    <script src="../js/filter.js"></script>
    <script src="../js/filter_sidebar.js"></script>
    <script src="../js/popup.js"></script>
    <script src="../js/scroll_navbar.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popUpButton = document.getElementById("pop-up-button");
            const resultCards = document.querySelectorAll(".result-card");
            const popUp = document.querySelector(".pop-up-overlay");
            const popUpTitle = document.querySelector(".pop-up-title");
            const popUpDate = document.getElementById("pop-up-date");
            const popUpParticipation = document.getElementById("pop-up-participation");
            const popUpRank = document.getElementById("pop-up-rank");
            const popUpLeadRank = document.getElementById("pop-up-leadrank");
            const popUpScore = document.getElementById("pop-up-score");
            const popUpID = document.getElementById("pop-up-id");
            const popUpResultID = document.getElementById("pop-up-result-id");

            //mini leaderboard
            const rank1pic = document.getElementById("rank1pic");
            const rank1name = document.getElementById("rank1name");
            const rank1score = document.getElementById("rank1score");

            const rank2pic = document.getElementById("rank2pic");
            const rank2name = document.getElementById("rank2name");
            const rank2score = document.getElementById("rank2score");

            const rank3pic = document.getElementById("rank3pic");
            const rank3name = document.getElementById("rank3name");
            const rank3score = document.getElementById("rank3score");

            resultCards.forEach(card => {
                card.addEventListener("click", function() {
                    const id = card.getAttribute("data-id");
                    const result_id = card.getAttribute("data-result-id");
                    const title = card.getAttribute("data-title");
                    const dateCreated = card.getAttribute("data-date");
                    const lastUpdated = card.getAttribute("data-update");
                    const participation = card.getAttribute("data-participation");
                    const myRank = card.getAttribute("data-myrank");
                    const myScore = card.getAttribute("data-myscore");

                    const rank1PicData = card.getAttribute("data-rank1pic");
                    const rank1NameData = card.getAttribute("data-rank1name");
                    const rank1ScoreData = card.getAttribute("data-rank1score");

                    const rank2PicData = card.getAttribute("data-rank2pic");
                    const rank2NameData = card.getAttribute("data-rank2name");
                    const rank2ScoreData = card.getAttribute("data-rank2score");

                    const rank3PicData = card.getAttribute("data-rank3pic");
                    const rank3NameData = card.getAttribute("data-rank3name");
                    const rank3ScoreData = card.getAttribute("data-rank3score");

                    popUpTitle.textContent = title;
                    popUpDate.innerHTML = `${dateCreated}<span class="edited">Last edited: ${lastUpdated}</span>`;
                    popUpParticipation.textContent = participation;
                    popUpRank.textContent = myRank;
                    popUpLeadRank.textContent = myRank;
                    popUpScore.textContent = myScore;
                    popUpID.value = id;
                    popUpResultID.value = result_id;

                    if (result_id !== "" && myRank !== "--") {
                        popUpButton.innerText = 'Review';
                    } else {
                        popUpButton.innerText = 'Attempt';
                    }

                    if (rank1PicData && rank1NameData) {
                        rank1pic.src = rank1PicData;
                        rank1pic.style.display = "block";
                        rank1name.textContent = rank1NameData;
                        rank1score.textContent = rank1ScoreData;
                    } else {
                        rank1pic.style.display = "none";
                        rank1name.textContent = "--";
                        rank1score.textContent = "";
                    }

                    if (rank2PicData && rank2NameData) {
                        rank2pic.src = rank2PicData;
                        rank2pic.style.display = "block";
                        rank2name.textContent = rank2NameData;
                        rank2score.textContent = rank2ScoreData;
                    } else {
                        rank2pic.style.display = "none";
                        rank2name.textContent = "--";
                        rank2score.textContent = "";
                    }

                    if (rank3PicData && rank3NameData) {
                        rank3pic.src = rank3PicData;
                        rank3pic.style.display = "block";
                        rank3name.textContent = rank3NameData;
                        rank3score.textContent = rank3ScoreData;
                    } else {
                        rank3pic.style.display = "none";
                        rank3name.textContent = "--";
                        rank3score.textContent = "";
                    }

                    popUp.style.display = "flex";
                });
            });

            document.querySelectorAll('.close-btn').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    popUp.style.display = 'none';
                });
            });

            popUpButton.addEventListener("click", function() {
                const id = popUpID.value;
                const result_id = popUpResultID.value;
                const myRank = popUpRank.textContent;
                if (result_id == "" || myRank == "--") {
                    window.location.href = 'c_quiz.php?id=' + id;
                } else {
                    window.location.href = 'c_summary_rank.php?id=' + result_id;
                }
            });

            const filterButtons = document.querySelectorAll(".filter-item");

            filterButtons.forEach(button => {
                button.addEventListener("click", function(event) {

                    const sortType = button.getAttribute("data-sort");
                    const resultCardsArray = Array.from(resultCards);

                    if (sortType === "popular") {
                        resultCardsArray.sort((a, b) => parseInt(b.getAttribute("data-participation")) - parseInt(a.getAttribute("data-participation")));
                    } else if (sortType === "latest") {
                        resultCardsArray.sort((a, b) => new Date(b.getAttribute("data-update")) - new Date(a.getAttribute("data-update")));
                    }

                    // Update the display order
                    const container = document.querySelector(".result-list");
                    container.innerHTML = "";
                    resultCardsArray.forEach(card => container.appendChild(card));
                    
                    // Update the active button
                    filterButtons.forEach(btn => btn.classList.remove("active"));
                    button.classList.add("active");
                });
            });

            const defaultFilterButton = document.querySelector('.filter-item[data-sort="popular"]');
            if (defaultFilterButton) {
                defaultFilterButton.click();
            }
        });
    </script>

</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <!-- sidebar -->

    <div class="sidebar-filter">
        <div class="container flex-row">
            <div class="content-container">
                <div class="main-content">
                    <div class="explore-title">
                        <span>
                            <i class="bx bx-trophy"></i>
                            Ranked Quizzes
                        </span>
                    </div>
                    <div class="filter">
                        <ul class="filter-list">
                            <li></li>
                            <li data-sort="popular">
                                <button class="filter-item active" data-sort="popular">
                                    Most Popular
                                </button>
                            </li>
                            <li data-sort="latest">
                                <button class="filter-item" data-sort="latest">Latest</button>
                            </li>
                        </ul>
                    </div>

                    <!-- if got result -->

                    <div class="result-yes">
                        <div class="result-list">
                        <?php 
                        include "db_conn.php";
                        include "functions.php";

                        $query = "SELECT * FROM `quiz` WHERE `type` = 'rank'";
                        $results = mysqli_query($connection, $query);

                        if (mysqli_num_rows($results) > 0) {
                            while ($row = mysqli_fetch_assoc($results)) {
                                $timestamp_date = $row['date_created'];
                                $date_created = date('d/m/Y', strtotime($timestamp_date));
                                $timestamp_updated = $row['last_updated'];
                                $last_updated = date('d/m/Y', strtotime($timestamp_updated));
                                $query_count = "SELECT COUNT(*) AS part_count FROM `user_result` WHERE `quiz_id` = {$row['quiz_id']} AND `date_completed` > '$timestamp_updated'";
                                $count_result = mysqli_query($connection, $query_count);
                                $count_data = mysqli_fetch_assoc($count_result);
                                $part_count = $count_data['part_count'];
                                $user_rank = getUserRank($_SESSION["user_id"], $timestamp_updated, $row['quiz_id']);
                                $score_query = "SELECT * FROM `user_result` WHERE `quiz_id` = {$row['quiz_id']} AND `date_completed` > '$timestamp_updated' AND `user_id` = {$_SESSION['user_id']} ORDER BY CAST(JSON_EXTRACT(`score`, '$.total') AS UNSIGNED) DESC, `timestamp` ASC, `date_completed` DESC LIMIT 1";
                                $score_result = $connection->query($score_query);
                                if ($score_result->num_rows > 0) {
                                    $score_row = $score_result->fetch_assoc();
                                    $user_score = json_decode($score_row["score"], true)["total"];
                                    $result_id = $score_row["result_id"];
                                } else {
                                    $user_score = "--";
                                    $result_id = "";
                                }
                                //get learderboard data
                                $rankings = [];
                                $rank_query = "SELECT * FROM `user_result` WHERE `quiz_id` = {$row['quiz_id']} AND `date_completed` > '$timestamp_updated' ORDER BY CAST(JSON_EXTRACT(`score`, '$.total') AS UNSIGNED) DESC, `timestamp` ASC, `date_completed` DESC LIMIT 3";
                                $rank_result = $connection->query($rank_query);
                                if ($rank_result->num_rows > 0) {
                                    $existing_users = [];
                                    while ($rank_row = $rank_result->fetch_assoc()) {
                                        if (!in_array($rank_row["user_id"], $existing_users)) {
                                            $total_score = json_decode($rank_row["score"], true)["total"];
                                            $user_id = $rank_row['user_id'];
                                            
                                            // Retrieve user data
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
                                            
                                            $rankings[] = array("username" => $user_username, "pfp_pic" => $user_profilepic, "score" => $total_score);
                                            $existing_users[] = $rank_row["user_id"];
                                        }
                                    }
                                }
                                if (count($rankings) < 1){
                                    $rank1pic = "";
                                    $rank1name = "--";
                                    $rank1score = "";
                                    $rank2pic = "";
                                    $rank2name = "--";
                                    $rank2score = "";
                                    $rank3pic = "";
                                    $rank3name = "--";
                                    $rank3score = "";
                                } else {
                                    if (count($rankings) >= 1) {
                                        $rank1pic = "../img/" . $rankings[0]["pfp_pic"];
                                        $rank1name = $rankings[0]["username"];
                                        $rank1score = $rankings[0]["score"];
                                    }
                                    if (count($rankings) >= 2) {
                                        $rank2pic = "../img/" . $rankings[1]["pfp_pic"];
                                        $rank2name = $rankings[1]["username"];
                                        $rank2score = $rankings[1]["score"];
                                    } else {
                                        $rank2pic = "";
                                        $rank2name = "--";
                                        $rank2score = "";
                                    }
                                    if (count($rankings) >= 3) {
                                        $rank3pic = "../img/" . $rankings[3]["pfp_pic"];
                                        $rank3name = $rankings[3]["username"];
                                        $rank3score = $rankings[3]["score"];
                                    } else {
                                        $rank3pic = "";
                                        $rank3name = "--";
                                        $rank3score = "";
                                    }
                                }
                        ?>
                        <div class="result-card" 
                            data-id="<?php echo htmlspecialchars($row['quiz_id']); ?>" 
                            data-result-id="<?php echo htmlspecialchars($result_id); ?>" 
                            data-title="<?php echo htmlspecialchars($row['title']); ?>" 
                            data-category="<?php echo htmlspecialchars($row['category']); ?>"  
                            data-date="<?php echo htmlspecialchars($date_created); ?>" 
                            data-update="<?php echo htmlspecialchars($last_updated); ?>" 
                            data-participation="<?php echo htmlspecialchars($part_count); ?>" 
                            data-myrank="<?php echo htmlspecialchars($user_rank); ?>" 
                            data-myscore="<?php echo htmlspecialchars($user_score); ?>"
                            data-rank1pic="<?php echo htmlspecialchars($rank1pic); ?>"
                            data-rank1name="<?php echo htmlspecialchars($rank1name); ?>"
                            data-rank1score="<?php echo htmlspecialchars($rank1score); ?>"
                            data-rank2pic="<?php echo htmlspecialchars($rank2pic); ?>"
                            data-rank2name="<?php echo htmlspecialchars($rank2name); ?>"
                            data-rank2score="<?php echo htmlspecialchars($rank2score); ?>"
                            data-rank3pic="<?php echo htmlspecialchars($rank3pic); ?>"
                            data-rank3name="<?php echo htmlspecialchars($rank3name); ?>"
                            data-rank3score="<?php echo htmlspecialchars($rank3score); ?>"
                            >
                            <div class="card-banner">
                                <span><i class="bx bx-medal"></i></span>
                                <span class="card-type">Ranking</span>
                            </div>
                            <div class="card-content">
                                <div class="card-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="card-category">Ranked Quizzes</div>
                                <div class="card-level">Your Rank : <?php echo $user_rank; ?></div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        mysqli_close($connection);
                        ?>
                        </div>
                    </div>

                    <!-- if no result -->

                    <!-- <div class="result-no">
						<div class="result-list-no">
							<span><i class="bx bxs-binoculars"></i></span>
							<span>Oops, nothing here yet...</span>
							<span>Try and look for another.</span>
						</div> -->
                </div>




                <!-- pop-up -->

                <div class="pop-up-overlay" style="display: none">
                    <div class="pop-up-rank">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>
                        <div class="pop-up-rank-wrapper">
                            <div class="pop-up-rank-left">
                                <div class="header">
                                    <span class="pop-up-type">
                                        Ranked Quizzes
                                    </span>
                                    <span class="pop-up-title">
                                    </span>
                                </div>
                                <div class="details">
                                    <span class="pop-up-details">
                                        Creation date:
                                    </span>
                                    <span class="pop-up-ans" id="pop-up-date">
                                        <span class="edited">Last Edited:</span>
                                        <span class="edited-ans"></span>
                                    </span>
                                    <span class="pop-up-details">
                                        Total Participants</span>
                                    <span class="pop-up-ans" id="pop-up-participation"></span>
                                    <span class="pop-up-details">Your Rank</span>
                                    <span class="pop-up-ans" id="pop-up-rank"></span>
                                </div>
                                <input type="hidden" id="pop-up-id" value="" />
                                <input type="hidden" id="pop-up-result-id" value="" />
                                <div class="action">
                                    <button button class="pop-up-btn" id="pop-up-button">Attempt</button>
                                </div>
                            </div>
                            <div class="pop-up-rank-right">
                                <div class="leaderboard">
                                    <div class="lead-title">
                                        <span>Leaderboard</span>
                                    </div>
                                    <div class="rank123">
                                        <!-- rank 2 -->

                                        <div class="rank2">
                                            <div class="rank-profile">
                                                <img id="rank2pic" alt="" />
                                            </div>
                                            <div class="rank-details">
                                                <span class="rank-name" id="rank2name"></span>
                                                <span class="rank-score" id="rank2score"></span>
                                            </div>
                                            <div class="stand stand-2">
                                                <span>2</span>
                                            </div>
                                        </div>

                                        <!-- rank 1 -->
                                        <div class="rank1">
                                            <div class="rank-profile">
                                                <img id="rank1pic" alt="" />
                                            </div>
                                            <div class="rank-details">
                                                <span class="rank-name" id="rank1name"></span>
                                                <span class="rank-score" id="rank1score"></span>
                                            </div>
                                            <div class="stand stand-1">
                                                <span>1</span>
                                            </div>
                                        </div>

                                        <!-- rank 3 -->
                                        <div class="rank3">
                                            <div class="rank-profile">
                                                <img id="rank3pic" alt="" />
                                            </div>
                                            <div class="rank-details">
                                                <span class="rank-name" id="rank3name"></span>
                                                <span class="rank-score" id="rank3score"></span>
                                            </div>
                                            <div class="stand stand-3">
                                                <span>3</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rank-me">
                                        <div class="rank-bar">
                                            <span class="rank-num" id="pop-up-leadrank"></span>
                                            <span class="rank-me-name">You</span>
                                            <span class="rank-me-score" id="pop-up-score"></span>
                                        </div>
                                    </div>
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