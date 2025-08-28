<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { ?>

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
    <link rel="stylesheet" href="../css/c_history.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
    <script src="../js/expand_collapse.js"></script>
    <script src="../js/popup.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const timelines = document.querySelectorAll(".timeline");
            const resultCardsPrac = document.querySelectorAll(".result-card-prac");
            const resultCardsRank = document.querySelectorAll(".result-card-rank");
            const pracQuizPopup = document.getElementById('practice-quizzes-popup');
            const rankQuizPopup = document.getElementById('ranked-quizzes-popup');
            const popUp = document.querySelector(".pop-up-overlay");
            
            //elements within the popup window (practice quiz)
            const popUpPracTitle = document.querySelector(".pop-up-prac-title");
            const popUpPracDate = document.querySelector(".pop-up-prac-date");
            const popUpPracCategory = document.querySelector(".pop-up-prac-category");
            const popUpPracLevel = document.querySelector(".pop-up-prac-level");
            const popUpPracQuizID = document.getElementById("pop-up-prac-quiz-id");
            const popUpPracResultID = document.getElementById("pop-up-prac-result-id");
            const popUpPracAttButton = document.getElementById("pop-up-prac-button-attempt");
            const popUpPracRevButton = document.getElementById("pop-up-prac-button-review");

            //for each practive quiz card:
            resultCardsPrac.forEach(card => {
                card.addEventListener("click", function() {
                    //obtain data for practice quiz
                    const level = card.getAttribute("data-level");
                    const quiz_id = card.getAttribute("data-quiz-id");
                    const result_id = card.getAttribute("data-result-id");
                    const title = card.getAttribute("data-title");
                    const date_created = card.getAttribute("data-date");
                    const last_updated = card.getAttribute("data-update");
                    const category = card.getAttribute("data-category");
                    const exist = card.getAttribute("data-exists");
                    
                    //display practice quiz popup window and hide ranked quiz popup window
                    rankQuizPopup.style.display = 'none';
                    pracQuizPopup.style.display = 'block';

                    //assign value/data to the parts within the popup window
                    popUpPracTitle.textContent = title;
                    popUpPracCategory.textContent = category;
                    popUpPracLevel.textContent = level;
                    popUpPracQuizID.value = quiz_id;
                    popUpPracResultID.value = result_id;
                    const dateHTML = `${date_created}<span class="edited">Last edited: ${last_updated}</span>`;
                    popUpPracDate.innerHTML = dateHTML;

                    //review button
                    popUpPracRevButton.addEventListener("click", function() {
                        window.location.href = 'c_summary.php?id=' + result_id;
                    });
                    
                    if (exist === "true"){
                        //attempt button
                        popUpPracAttButton.style.display = "block";
                        popUpPracAttButton.addEventListener("click", function() {
                            window.location.href = 'c_quiz.php?id=' + quiz_id;
                        });
                    } else {
                        popUpPracAttButton.style.display = "none";
                    }
                    popUp.style.display = "flex";
                });
            });

            //elements within the popup window (ranked quiz)
            const popUpRankTitle = document.querySelector(".pop-up-rank-title");
            const popUpRankDate = document.getElementById("pop-up-rank-date");  // Corrected: Removed dot (.)
            const popUpParticipation = document.getElementById("pop-up-participation");
            const popUpRank = document.getElementById("pop-up-rank");
            const popUpLeadRank = document.getElementById("pop-up-leadrank");
            const popUpScore = document.getElementById("pop-up-score");
            const popUpRankQuizID = document.getElementById("pop-up-rank-quiz-id");
            const popUpRankResultID = document.getElementById("pop-up-rank-result-id");
            const popUpRankAttButton = document.getElementById("pop-up-rank-button-attempt");
            const popUpRankRevButton = document.getElementById("pop-up-rank-button-review");
            const rank1pic = document.getElementById("rank1pic");
            const rank1name = document.getElementById("rank1name");
            const rank1score = document.getElementById("rank1score");
            const rank2pic = document.getElementById("rank2pic");
            const rank2name = document.getElementById("rank2name");
            const rank2score = document.getElementById("rank2score");
            const rank3pic = document.getElementById("rank3pic");
            const rank3name = document.getElementById("rank3name");
            const rank3score = document.getElementById("rank3score");

            resultCardsRank.forEach(rankcard => {
                rankcard.addEventListener("click", function() {
                    //get data
                    const level = rankcard.getAttribute("data-level");
                    const quiz_id = rankcard.getAttribute("data-quiz-id");
                    const result_id = rankcard.getAttribute("data-result-id");
                    const title = rankcard.getAttribute("data-title");
                    const date_created = rankcard.getAttribute("data-date");
                    const last_updated = rankcard.getAttribute("data-update");
                    const participation = rankcard.getAttribute("data-participation");
                    const myRank = rankcard.getAttribute("data-myrank");
                    const myScore = rankcard.getAttribute("data-myscore");

                    //mini leaderboard data
                    const rank1PicData = rankcard.getAttribute("data-rank1pic");
                    const rank1NameData = rankcard.getAttribute("data-rank1name");
                    const rank1ScoreData = rankcard.getAttribute("data-rank1score");

                    const rank2PicData = rankcard.getAttribute("data-rank2pic");
                    const rank2NameData = rankcard.getAttribute("data-rank2name");
                    const rank2ScoreData = rankcard.getAttribute("data-rank2score");

                    const rank3PicData = rankcard.getAttribute("data-rank3pic");
                    const rank3NameData = rankcard.getAttribute("data-rank3name");
                    const rank3ScoreData = rankcard.getAttribute("data-rank3score");

                    const exists = rankcard.getAttribute("data-exists");

                    //hide prac quiz popup
                    rankQuizPopup.style.display = 'flex';
                    pracQuizPopup.style.display = 'none';

                    if (exists === "true" && myRank === "--") {
                        popUpRankAttButton.style.display = "block";
                        popUpRankRevButton.style.display = "none";
                        //attempt button
                        popUpRankAttButton.addEventListener("click", function() {
                            window.location.href = 'c_quiz.php?id=' + quiz_id;
                        });
                    } else {
                        popUpRankAttButton.style.display = "none";
                        popUpRankRevButton.style.display = "block";
                        //review button
                        popUpRankRevButton.addEventListener("click", function() {
                            window.location.href = 'c_summary_rank.php?id=' + result_id;
                        });
                    }

                    popUpRankTitle.textContent = title;
                    popUpRankDate.innerHTML = `${date_created}<span class="edited">Last edited: ${last_updated}</span>`;
                    popUpParticipation.textContent = participation;
                    popUpRank.textContent = myRank;
                    popUpLeadRank.textContent = myRank;
                    popUpScore.textContent = myScore;
                    popUpRankQuizID.value = quiz_id;
                    popUpRankResultID.value = result_id;

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

            //filter
            const searchTitleInput = document.getElementById('search-title');
            const categoryFilter = document.getElementById('category');
            const levelFilter = document.getElementById('level');
            const resultCount = document.getElementById('resultCount');

            function checkDate(dateStr) {
                const dateObj = new Date(dateStr);
                const now = new Date();
                switch (levelFilter.value) {
                    case 'days7':
                        return (now - dateObj) / (1000 * 60 * 60 * 24) <= 7;
                    case 'days30':
                        return (now - dateObj) / (1000 * 60 * 60 * 24) <= 30;
                    case 'months3':
                        return (now - dateObj) / (1000 * 60 * 60 * 24) <= 90;
                    case 'months6':
                        return (now - dateObj) / (1000 * 60 * 60 * 24) <= 180;
                    case 'all':
                    default:
                        return true;
                }
            }

            function filterCard(card) {
                const title = card.getAttribute('data-title').toLowerCase();
                const isTitleMatch = title.startsWith(searchTitleInput.value.toLowerCase());
                let isCategoryMatch = true;
                if (categoryFilter.value === 'practice' && !card.classList.contains('result-card-prac')) {
                    isCategoryMatch = false;
                } else if (categoryFilter.value === 'ranked' && !card.classList.contains('result-card-rank')) {
                    isCategoryMatch = false;
                }
                return isTitleMatch && isCategoryMatch;
            }

            function filterCards() {
                let count = 0;  // Initialize count

                timelines.forEach(timeline => {
                    const dateText = timeline.querySelector("span").textContent.trim();
                    const dateObj = new Date(dateText);
                    const isDateMatch = checkDate(dateObj);

                    const cards = timeline.querySelectorAll(".result-card-co, .result-card-prac, .result-card-rank");
                    let timelineCardCount = 0;

                    cards.forEach(card => {
                        if (isDateMatch && filterCard(card)) {
                            card.style.display = 'block';
                            timelineCardCount++;
                            count++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (timelineCardCount > 0) {
                        timeline.style.display = 'block';
                    } else {
                        timeline.style.display = 'none';
                    }
                });

                // Update result count
                resultCount.textContent = `Total Result(s): ${count}`;
            }

            searchTitleInput.addEventListener('input', filterCards);
            categoryFilter.addEventListener('change', filterCards);
            levelFilter.addEventListener('change', filterCards);
        });
    </script>

</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <!-- Main content -->
    <div class="uni-wrapper">
        <div class="container flex-row">
            <div class="content-container flex-row">
                <!-- history -->
                <div class="history-wrapper">
                    <div class="history-details">
                        <div class="history-details-header">
                            <span>History Tracker</span>
                        </div>
                        <div class="history-details-body">
                            <div class="search">
                                <div class="search-bar">
                                    <span class="search-bar-text">Search:</span>
                                    <input type="text" id="search-title" placeholder="Search Title" />
                                    <span class="search-bar-text">Filter:</span>
                                    <select class="cat-filter" name="category" id="category">
                                        <option value="all">All category</option>
                                        <option value="practice">Practice Quizzes</option>
                                        <option value="ranked">Ranked Quizzes</option>
                                    </select>
                                    <select class="lvl-filter" name="level" id="level">
                                        <option value="all">All time</option>
                                        <option value="days7">Past 7 days</option>
                                        <option value="days30">Past 30 days</option>
                                        <option value="months3">Past 3 months</option>
                                        <option value="months6">Past 6 months</option>
                                    </select>
                                </div>
                                <?php 
                                include "db_conn.php";
                                $history_query = "SELECT COUNT(*) FROM `user_result` WHERE `user_id` = {$_SESSION['user_id']}";
                                $history_total = mysqli_query($connection, $history_query);
                                $history_row = mysqli_fetch_array($history_total);
                                $user_history = $history_row[0];
                                mysqli_close($connection);
                                ?>
                                <div class="search-result">
                                    <span id="resultCount">Total Result(s) : <?php echo $user_history;?></span>
                                </div>
                            </div>


                            <?php
                            include "db_conn.php";
                            include "functions.php";
                            $user_id = $_SESSION['user_id'];

                            //get dates ("timelines")
                            $query = "SELECT DISTINCT DATE(`date_completed`) AS date_completed FROM `user_result` WHERE `user_id` = ? ORDER BY date_completed DESC";
                            $stmt = $connection->prepare($query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $dates = [];
                            while ($row = $result->fetch_assoc()) {
                                $date = new DateTime($row['date_completed']);
                                $date_completed = $date->format('d M Y');
                                $dates[] = $date_completed;
                            }
                            $stmt->close();

                            foreach ($dates as $date) {
                                $date_obj = DateTime::createFromFormat('d M Y', $date);
                                $formatted_date = $date_obj->format('Y-m-d');
                                $sql = "SELECT * FROM `user_result` WHERE DATE(`date_completed`) = ? AND `user_id` = ?";
                                $stmt = $connection->prepare($sql);
                                $stmt->bind_param("si", $formatted_date, $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                ?>
                                <div class="timeline">
                                        <button class="toggle-button">
                                            <i class="bx bx-expand-alt"></i>
                                        </button>
                                        <span><?php echo $date; ?></span>
                                        <div class="date">
                                            <div class="result-yes">
                                                <div class="result-list">
                                <?php

                                //outer date loop
                                while ($row = $result->fetch_assoc()) {

                                    //$row used for user result specific date row
                                    //get basic data
                                    $quiz_id = $row['quiz_id'];
                                    $result_id = $row['result_id'];
                                    $quiz_category = $row['quiz_category'];
                                    $quiz_title = $row['quiz_title'];

                                    //check if this quiz exists/is available in the first place
                                    $quiz_query = "SELECT * FROM `quiz` WHERE `quiz_id` = '$quiz_id'";
                                    $quiz_results = mysqli_query($connection, $quiz_query);
                                    if (mysqli_num_rows($quiz_results) > 0) {
                                        $exists = "true";
                                        $quiz_row = mysqli_fetch_assoc($quiz_results);
                                        $timestamp_date = $quiz_row['date_created'];
                                        $date_created = date('d/m/Y', strtotime($timestamp_date));
                                        $timestamp_updated = $quiz_row['last_updated'];
                                        $last_updated = date('d/m/Y', strtotime($timestamp_updated));
                                        $quiz_level = $quiz_row['level'];
                                    } else {
                                        $exists = "false";
                                        $timestamp_updated = "";
                                        $date_created = "--";
                                        $last_updated = "--";
                                        $quiz_level = "--";
                                    }

                                    if ($row['quiz_type'] == "prac") { 
                                        ?>
                                        <!-- Practice quizzes result cards -->
                                        <div class="result-card-coll result-card result-card-prac"
                                        data-quiz-id="<?php echo htmlspecialchars($quiz_id); ?>"
                                        data-result-id="<?php echo htmlspecialchars($result_id); ?>"
                                        data-title="<?php echo htmlspecialchars($quiz_title); ?>"
                                        data-category="<?php echo htmlspecialchars($quiz_category); ?>"
                                        data-level="<?php echo htmlspecialchars($quiz_level); ?>"
                                        data-date="<?php echo htmlspecialchars($date_created); ?>"
                                        data-update="<?php echo htmlspecialchars($last_updated); ?>"
                                        data-exists="<?php echo htmlspecialchars($exists); ?>">
                                            <div class="card-banner-coll card-banner">
                                                <span>
                                                    <i class="bx bx-bookmark-alt"></i>
                                                </span>
                                                <span class="card-type" style="
                                                            display: none;
                                                        "><?php echo $quiz_category; ?></span>
                                            </div>
                                            <div class="card-content-coll card-content">
                                                <div class="card-title-coll card-title">
                                                    <?php echo $quiz_title; ?>
                                                </div>
                                                <div class="card-category">
                                                    Practice Quiz
                                                </div>
                                                <div class="card-level" style=" display: none;">
                                                    <?php echo $quiz_level; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else if ($row['quiz_type'] == "rank") {
                                        
                                        //get participation count
                                        $query_count = "SELECT COUNT(*) AS part_count FROM `user_result` WHERE `quiz_id` = '$quiz_id' AND `date_completed` > '$timestamp_updated'";
                                        $count_result = mysqli_query($connection, $query_count);
                                        $count_data = mysqli_fetch_assoc($count_result);
                                        $part_count = $count_data['part_count'];

                                        //get user rank & score
                                        $user_rank = getUserRank($_SESSION["user_id"], $timestamp_updated, $row['quiz_id']);
                                        $score_query = "SELECT * FROM `user_result` WHERE `quiz_id` = {$row['quiz_id']} AND `date_completed` > '$timestamp_updated' AND `user_id` = {$_SESSION['user_id']} ORDER BY CAST(JSON_EXTRACT(`score`, '$.total') AS UNSIGNED) DESC, `timestamp` ASC, `date_completed` DESC LIMIT 1";
                                        $score_result = $connection->query($score_query);
                                        if ($score_result->num_rows > 0) {
                                            $score_row = $score_result->fetch_assoc();
                                            $user_score = json_decode($score_row["score"], true)["total"];
                                        } else {
                                            $user_score = "--";
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
                                                $rank3pic = "../img/" . $rankings[2]["pfp_pic"];
                                                $rank3name = $rankings[2]["username"];
                                                $rank3score = $rankings[2]["score"];
                                            } else {
                                                $rank3pic = "";
                                                $rank3name = "--";
                                                $rank3score = "";
                                            }
                                        }
                                        ?>
                                        <!-- Ranked quizzes result cards -->
                                        <div class="result-card-coll result-card result-card-rank"
                                            data-quiz-id="<?php echo htmlspecialchars($quiz_id); ?>"
                                            data-result-id="<?php echo htmlspecialchars($result_id); ?>" 
                                            data-title="<?php echo htmlspecialchars($quiz_title); ?>" 
                                            data-category="<?php echo htmlspecialchars($quiz_category); ?>"  
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
                                            data-exists="<?php echo htmlspecialchars($exists); ?>"
                                            data-level="All ages">
                                            <div class="card-banner-coll card-banner">
                                                <span>
                                                    <i class="bx bx bx-medal"></i>
                                                </span>
                                                <span class="card-type" style="
                                                            display: none;
                                                        "><?php echo $quiz_category; ?></span>
                                            </div>
                                            <div class="card-content-coll card-content">
                                                <div class="card-title-coll card-title">
                                                    <?php echo $quiz_title; ?>
                                                </div>
                                                <div class="card-category">
                                                    Ranked Quiz
                                                </div>
                                                <div class="card-level" style=" display: none;">
                                                    All ages
                                                </div>
                                            </div>
                                        </div>
                                    <?php }    
                                } ?>
                                </div>
                            </div>
                        </div>
                    </div><?php
                $stmt->close();
            }
            $connection->close();
            ?>

                            <div class="end-data">
                                <span>No more records to display...</span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- practice quiz  pop-up -->
                <div class="pop-up-overlay" style="display: none">
                    <!-- practices quizzes pop-up-->

                    <div class="pop-up" id="practice-quizzes-popup">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>
                        <div class="header">
                            <span class="pop-up-type">
                                Practice Quizzes
                            </span>
                            <span class="pop-up-prac-title">
                                Quiz 1
                            </span>
                        </div>
                        <div class="details">
                            <span class="pop-up-details">
                                Creation date:
                            </span>
                            <span class="pop-up-prac-date" id="pop-up-date"></span>
                                <span class="edited"></span>
                                <span class="edited-ans"></span>
                            </span>
                            <span class="pop-up-details"> Type: </span>
                            <span class="pop-up-prac-category" id="pop-up-prac-category"></span>
                            <span class="pop-up-details"> Level: </span>
                            <span class="pop-up-prac-level" id="pop-up-prac-level"></span>
                        </div>
                        <input type="hidden" id="pop-up-prac-quiz-id" value="">
                        <input type="hidden" id="pop-up-prac-result-id" value="">
                        <div class="action">
                            <a href="#">
                                <button class="pop-up-btn-ex" id="pop-up-prac-button-review">
                                    Review
                                </button>
                            </a>
                            <a href="#">
                                <button class="pop-up-btn"  id="pop-up-prac-button-attempt">Attempt</button>
                            </a>
                        </div>
                    </div>



                    <!-- ranked quizzes pop-up-->
                    <div class="pop-up-rank" id="ranked-quizzes-popup">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>
                        <div class="pop-up-rank-wrapper">
                            <div class="pop-up-rank-left">
                                <div class="header">
                                    <span class="pop-up-type">Ranked Quizzes</span>
                                    <span class="pop-up-rank-title"></span>
                                </div>
                                <div class="details">
                                    <span class="pop-up-details">Creation date:</span>
                                    <span class="pop-up-ans" id="pop-up-rank-date">
                                        <span class="edited">Last Edited:</span>
                                        <span class="edited-ans"></span>
                                    </span>
                                    <span class="pop-up-details">Total Participants</span>
                                    <span class="pop-up-ans" id="pop-up-participation"></span>
                                    <span class="pop-up-details">Your Rank</span>
                                    <span class="pop-up-ans" id="pop-up-rank"></span>
                                </div>
                                <input type="hidden" id="pop-up-rank-quiz-id" value="" />
                                <input type="hidden" id="pop-up-rank-result-id" value="" />
                                <div class="action">
                                    <button class="pop-up-btn" id="pop-up-rank-button-attempt">Attempt</button>  <!-- Corrected: Removed duplicate 'button' -->
                                    <button class="pop-up-btn" id="pop-up-rank-button-review">Review</button>  <!-- Corrected: Removed duplicate 'button' -->
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
    <script>
        console.log(participation, myRank, myScore, rank1PicData, rank1NameData, rank1ScoreData, rank2PicData, rank2NameData, rank2ScoreData, rank3PicData, rank3NameData, rank3ScoreData);
        </script>
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