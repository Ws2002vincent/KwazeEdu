<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "user") {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

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
    <link rel="stylesheet" href="../css/c_Dashboard.css" />
    <link rel="stylesheet" href="../css/c_view.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
    <script src="../js/filter.js"></script>
    <script src="../js/popup.js"></script>
    <script defer>
    document.addEventListener('DOMContentLoaded', function() {
        //general elements
        const buttons = document.querySelectorAll('.filter-item');
        const link = document.getElementById('redirect-link');
        const resultCardsPrac = document.querySelectorAll(".result-card-prac");
        const resultCardsRank = document.querySelectorAll(".result-card-rank");
        const resultCardsMat = document.querySelectorAll(".result-card-learnmat");
        const learnMatPopup = document.getElementById('learning-material-popup');
        const pracQuizPopup = document.getElementById('practice-quizzes-popup');
        const rankQuizPopup = document.getElementById('ranked-quizzes-popup');
        const popUp = document.querySelector(".pop-up-overlay");

        function hideAllExceptMat() {
            resultCardsMat.forEach(card => card.style.display = 'block');
            resultCardsPrac.forEach(card => card.style.display = 'none');
            resultCardsRank.forEach(card => card.style.display = 'none');
        }

        //elements within the popup window (practice quiz)
        const popUpPracTitle = document.querySelector(".pop-up-prac-title");
        const popUpPracDate = document.querySelector(".pop-up-prac-date");
        const popUpPracCategory = document.querySelector(".pop-up-prac-category");
        const popUpPracLevel = document.querySelector(".pop-up-prac-level");
        const popUpPracQuizID = document.getElementById("pop-up-prac-quiz-id");
        const popUpPracAttButton = document.getElementById("pop-up-prac-button-attempt");

        //clear event listeners
        const clearEventListeners = (element) => {
            const oldElement = element;
            const newElement = oldElement.cloneNode(true);
            oldElement.parentNode.replaceChild(newElement, oldElement);
            return newElement;
        };

        //for learn mat popup
        const popUpLearnMatButton = document.getElementById("pop-up-learnmat-button");
        const popUpLearnMatTitle = document.querySelector(".pop-up-learnmat-title");
        const popUpLearnMatDate = document.querySelector(".pop-up-learnmat-date");
        const popUpLearnMatCategory = document.querySelector(".pop-up-learnmat-category");
        const popUpLearnMatLevel = document.querySelector(".pop-up-learnmat-level");
        const popUpLearnMatID = document.getElementById("pop-up-learnmat-id");

        //learn mat popup window
        resultCardsMat.forEach(matcard => {
            matcard.addEventListener("click", function() {
                const id = matcard.getAttribute("data-id");
                const title = matcard.getAttribute("data-title");
                const category = matcard.getAttribute("data-category");
                const level = matcard.getAttribute("data-level");
                const date_created = matcard.getAttribute("data-date");
                const last_updated = matcard.getAttribute("data-update");

                popUpLearnMatTitle.textContent = title;
                popUpLearnMatCategory.textContent = category;
                popUpLearnMatLevel.textContent = level;
                popUpLearnMatID.value = id;
                const dateHTML =
                    `${date_created}<span class="edited">Last edited: ${last_updated}</span>`;
                popUpLearnMatDate.innerHTML = dateHTML;

                const newpopUpLearnMatButton = clearEventListeners(popUpLearnMatButton);
                newpopUpLearnMatButton.addEventListener("click", function() {
                    window.location.href = 'c_mat.php?id=' + id;
                });
            });
        });

        //for each practice quiz card
        resultCardsPrac.forEach(praccard => {
            praccard.addEventListener("click", function() {
                //obtain data for practice quiz
                const level = praccard.getAttribute("data-level");
                const quiz_id = praccard.getAttribute("data-id");
                const title = praccard.getAttribute("data-title");
                const date_created = praccard.getAttribute("data-date");
                const last_updated = praccard.getAttribute("data-update");
                const category = praccard.getAttribute("data-category");

                //assign value/data to the parts within the popup window
                popUpPracTitle.textContent = title;
                popUpPracCategory.textContent = category;
                popUpPracLevel.textContent = level;
                popUpPracQuizID.value = quiz_id;
                const dateHTML =
                    `${date_created}<span class="edited">Last edited: ${last_updated}</span>`;
                popUpPracDate.innerHTML = dateHTML;

                //clear and set new event listener for the attempt button
                const newPopUpPracAttButton = clearEventListeners(popUpPracAttButton);
                newPopUpPracAttButton.addEventListener("click", function() {
                    window.location.href = 'c_quiz.php?id=' + quiz_id;
                });
            });
        });

        //elements within the popup window (ranked quiz)
        const popUpRankTitle = document.querySelector(".pop-up-rank-title");
        const popUpRankDate = document.getElementById("pop-up-rank-date"); // Corrected: Removed dot (.)
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

                if (myRank === "--") {
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
                popUpRankDate.innerHTML =
                    `${date_created}<span class="edited">Last edited: ${last_updated}</span>`;
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
            });
        });

        document.querySelectorAll('.close-btn').forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                popUp.style.display = 'none';
                learnMatPopup.style.display = 'none';
                pracQuizPopup.style.display = 'none';
                rankQuizPopup.style.display = 'none';
            });
        });

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                buttons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                selectedFilter = this.textContent.trim();

                resultCardsPrac.forEach(card => card.style.display = 'none');
                resultCardsRank.forEach(card => card.style.display = 'none');
                resultCardsMat.forEach(card => card.style.display = 'none');

                switch (selectedFilter) {
                    case 'Learning Material':
                        link.href = '../php/c_mat_view.php';
                        resultCardsMat.forEach(card => card.style.display = 'block');
                        resultCardsPrac.forEach(card => card.style.display = 'none');
                        resultCardsRank.forEach(card => card.style.display = 'none');
                        break;
                    case 'Practice Quizzes':
                        link.href = '../php/c_quiz_view.php';
                        resultCardsPrac.forEach(card => card.style.display = 'block');
                        break;
                    case 'Ranked Quizzes':
                        link.href = '../php/c_rank_view.php';
                        resultCardsRank.forEach(card => card.style.display = 'block');
                        break;
                    default:
                        link.href = '#';
                        break;
                }
            });
        });

        const resultCards = document.querySelectorAll('.result-card');
        resultCards.forEach(card => {
            card.addEventListener('click', function() {
                learnMatPopup.style.display = 'none';
                pracQuizPopup.style.display = 'none';
                rankQuizPopup.style.display = 'none';

                switch (selectedFilter) {
                    case 'Practice Quizzes':
                        pracQuizPopup.style.display = 'block';
                        break;
                    case 'Ranked Quizzes':
                        rankQuizPopup.style.display = 'block';
                        break;
                    case 'Learning Material':
                        learnMatPopup.style.display = 'block';
                        break;
                    default:
                        link.href = '#';
                        break;
                }
            });
        });
        document.querySelector('.filter-item[data-filter="Learning Material"]').classList.add('active');
        const learningMaterialButton = document.querySelector('.filter-item[data-filter="Learning Material"]');
        if (learningMaterialButton) {
            learningMaterialButton.click();
        }
    });
    </script>

</head>

<body>

    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <div class="banner">
        <div class="container flex-center">
            <div class="advertiment">
                <span class="adv-desc">New feature Introduced !</span>
                <span class="adv-title">
                    <i class="bx bx-game"></i>Gamified Store</span>
                <a href="../php/c_store.php"><button class="adv-btn">Enter</button></a>
                <img src="../img/rabbit.png" alt="" />
            </div>
        </div>
    </div>

    <div class="explore">
        <div class="container">
            <div class="explore-title">
                <span>New Releases</span>
            </div>
            <div class="filter">
                <ul class="filter-list">
                    <li>
                        <button class="filter-item active">
                            Learning Material
                        </button>
                    </li>
                    <li>
                        <button class="filter-item">Practice Quizzes</button>
                    </li>
                    <li>
                        <button class="filter-item">
                            Ranked Quizzes
                        </button>
                    </li>
                </ul>
            </div>
            <div class="result-yes">
                <div class="result-list" style="gap :5">

                     <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Get all result cards
                        const resultCards = document.querySelectorAll('.result-card');

                        // Loop through each card and check its class
                        resultCards.forEach(card => {
                            if (!card.classList.contains('result-card-learnmat')) {
                                // Hide the card if it's not a learning material card
                                card.style.display = 'none';
                            } else {
                                // Show the card if it's a learning material card
                                card.style.display = 'block';
                            }
                        });

                        // Update the redirect link to point to the learning material view page
                        const redirectLink = document.getElementById('redirect-link');
                        redirectLink.setAttribute('href', '../php/c_mat_view.php');
                    });
                    </script> 

                    <?php 
                    include "db_conn.php";
                    $query = "SELECT * FROM `learn_mat` ORDER BY `last_updated` DESC LIMIT 5";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)) {
                            $timestamp_date = $row['date_created'];
                            $date_created = date('d/m/Y', strtotime($timestamp_date));
                            $timestamp_updated = $row['last_updated'];
                            $last_updated = date('d/m/Y', strtotime($timestamp_updated));
                            ?>
                    <div class="result-card result-card-learnmat"
                        data-id="<?php echo htmlspecialchars($row['mat_id']); ?>"
                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                        data-category="<?php echo htmlspecialchars($row['category']); ?>"
                        data-level="<?php echo htmlspecialchars($row['level']); ?>"
                        data-date="<?php echo htmlspecialchars($date_created); ?>"
                        data-update="<?php echo htmlspecialchars($last_updated); ?>">
                        <div class="card-banner">
                            <span><i class="bx bx-spreadsheet"></i></span>
                            <span class="card-type"><?php echo htmlspecialchars($row['category']); ?></span>
                        </div>
                        <div class="card-content">
                            <div class="card-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </div>
                            <div class="card-category">
                                Learning Material
                            </div>
                            <div class="card-level">
                                <?php echo htmlspecialchars($row['level']); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    }
                    mysqli_close($connection);
                ?>

                    <?php 
                    include "db_conn.php";
                    $query = "SELECT * FROM `quiz` WHERE `type` = 'prac' ORDER BY `last_updated` DESC LIMIT 5";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)) {
                            $timestamp_date = $row['date_created'];
                            $date_created = date('d/m/Y', strtotime($timestamp_date));
                            $timestamp_updated = $row['last_updated'];
                            $last_updated = date('d/m/Y', strtotime($timestamp_updated));
                            ?>
                    <div class="result-card result-card-prac" data-id="<?php echo htmlspecialchars($row['quiz_id']); ?>"
                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                        data-category="<?php echo htmlspecialchars($row['category']); ?>"
                        data-level="<?php echo htmlspecialchars($row['level']); ?>"
                        data-date="<?php echo htmlspecialchars($date_created); ?>"
                        data-update="<?php echo htmlspecialchars($last_updated); ?>">
                        <div class="card-banner">
                            <span><i class='bx bx-bookmark-alt'></i></span>
                            <span class="card-type"><?php echo htmlspecialchars($row['category']); ?></span>
                        </div>
                        <div class="card-content">
                            <div class="card-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </div>
                            <div class="card-category">
                                Practice Quizzes
                            </div>
                            <div class="card-level">
                                <?php echo htmlspecialchars($row['level']); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    }
                    mysqli_close($connection);
                ?>

                    <?php   
                include "db_conn.php";
                include "functions.php";

                $query = "SELECT * FROM `quiz` WHERE `type` = 'rank' ORDER BY `last_updated` DESC LIMIT 5";
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
                    <div class="result-card result-card-rank" data-quiz-id="<?php echo htmlspecialchars($row['quiz_id']); ?>"
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
                        data-rank3score="<?php echo htmlspecialchars($rank3score); ?>">
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
                <div class="extra">
                    <button class="extra-btn">

                        <!-- if learning material then redirected to.. -->

                        <a href="../php/c_mat_view.php" id="redirect-link">
                            <i class="bx bx-chevron-right"></i>
                        </a>

                        <!-- if pratices quizzes then redirected to.. -->

                        <!-- <a href="../php/c_quiz_view.php">
                                <i class="bx bx-chevron-right"></i>
                            </a> -->


                        <!-- if ranked quizzes then redirected to.. -->
                        <!-- <a href="../php/c_rank_view.php">
                                <i class="bx bx-chevron-right"></i>
                            </a> -->

                    </button>
                    <span>View More</span>
                </div>
            </div>

            <!-- if no data to be display -->

            <!-- <div class="result-no">
                    <div class="result-list-no">
                        <span><i class="bx bxs-binoculars"></i></span>
                        <span>Oops, nothing here yet...</span>
                        <span>Try and look for another.</span>
                    </div>
                </div> -->





            <!-- pop-up -->

            <div class="pop-up-overlay" style="display: none">


                <!-- learning material pop-up-->

                <div class="pop-up" id="learning-material-popup">
                    <div class="close-button">
                        <span class="close-btn">
                            <i class="bx bx-x"></i>
                        </span>
                    </div>
                    <div class="header">
                        <span class="pop-up-type">
                            Learning Material
                        </span>
                        <span class="pop-up-learnmat-title"></span>
                    </div>
                    <div class="details">
                        <span class="pop-up-details">
                            Creation date:
                        </span>
                        <span class="pop-up-learnmat-date" id="pop-up-learnmat-date"></span>
                        <span class="edited"></span>
                        <span class="edited-ans"></span>
                        </span>
                        <span class="pop-up-details"> Type: </span>
                        <span class="pop-up-learnmat-category" id="pop-up-learnmat-category"></span>
                        <span class="pop-up-details"> Level: </span>
                        <span class="pop-up-learnmat-level" id="pop-up-learnmat-level"></span>
                    </div>
                    <input type="hidden" id="pop-up-learnmat-id" value="">
                    <div class="action">
                        <button class="pop-up-btn" id="pop-up-learnmat-button">
                            Start Reading
                        </button>
                    </div>
                </div>

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
                    <div class="action">
                        <a href="#">
                            <button class="pop-up-btn" id="pop-up-prac-button-attempt">Attempt</button>
                        </a>
                    </div>
                </div>

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
                                <button class="pop-up-btn" id="pop-up-rank-button-attempt">Attempt</button>
                                <!-- Corrected: Removed duplicate 'button' -->
                                <button class="pop-up-btn" id="pop-up-rank-button-review">Review</button>
                                <!-- Corrected: Removed duplicate 'button' -->
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

        <!-- Footer -->
    <!-- include footer -->
    <?php include 'footer.php'; ?>


    
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