<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { 

    $_SESSION['quiz_id'] = isset($_GET['id']) ? $_GET['id'] : $_SESSION['quiz_id'];
    // $_SESSION['quiz_id'] = 24;
    $quiz_id = $_SESSION['quiz_id'];
    // echo $quiz_id;

    //get category
    include "db_conn.php";
    $category_query = "SELECT * FROM `quiz` WHERE `quiz_id` = '$quiz_id'";
    $category_result = mysqli_query($connection, $category_query);
    $row = mysqli_fetch_assoc($category_result);
    if ($row['category'] == "Multiple Choice Question") {
        $template_type = "mcq";
    } else if ($row['category'] == "True or False") {
        $template_type = "truefalse";
    } else if ($row['category'] == "Fill in the Blank") {
        $template_type = "fillblank";
    }
    $quiz_title = $row['title'];
    $quiz_category = $row['category'];
    $quiz_level = $row['level'];
    $quiz_type = $row['type'];
    $timestamp_date = $row['date_created'];
    $timestamp_update = $row['last_updated'];
    $date_created = date('d/m/Y', strtotime($timestamp_date));
    $last_updated = date('d/m/Y', strtotime($timestamp_update));
    
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attempt Quiz</title>

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
    <link rel="stylesheet" href="../css/c_quiz.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
    <script>
        <?php if ($quiz_type === "rank") { ?>
            window.history.replaceState(null, null, "../php/c_rank_view.php");
        <?php } else { ?>
            window.history.replaceState(null, null, "../php/c_quiz_view.php");
        <?php } ?>
    </script>
    <script>
        let seconds = 0;
        let minutes = 0;
        let hours = 0;
        let timerInterval;

        function pad(value) {
            return value.toString().padStart(2, '0');
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                seconds++;
                if (seconds >= 60) {
                    seconds = 0;
                    minutes++;
                    if (minutes >= 60) {
                        minutes = 0;
                        hours++;
                    }
                }
                document.getElementById('timer').textContent = `Time: ${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            }, 1000);
        }

        window.onload = function() {
            startTimer();
        };

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('quizForm').onsubmit = function(event) {
                event.preventDefault();
                clearInterval(timerInterval);
                const elapsedSeconds = hours * 3600 + minutes * 60 + seconds;
                const timeInput = document.createElement('input');
                timeInput.type = 'hidden';
                timeInput.name = 'elapsedTime';
                timeInput.value = elapsedSeconds;
                this.appendChild(timeInput);
                this.submit();
            };
        });
    </script>
</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <!-- content -->
    <div class="uni-wrapper">
        <div class="container flex-col">
            <div class="content-container">
                <!-- directory -->
                <div class="directory">
                    <a href="">
                        <button><i class="bx bx-arrow-back"></i></button>
                    </a>
                </div>

                <?php 
                if ($quiz_type === "rank") { ?>
                    <div class="timer">
                        <div class="time">
                            <span id="timer">Time : 00:00:00</span>
                        </div>
                    </div>
                <?php }
                ?>

                <!-- title -->
                <div class="title-box">
                    <div class="title">
                        <span><?php echo $quiz_title; ?></span>
                    </div>
                    <div class="title-details">
                        <span><?php echo $quiz_category; ?></span>
                        <span class="symbol-directory">,</span>
                        <span><?php echo $quiz_level; ?></span>
                    </div>
                    <div class="title-date">
                        <span>Created on <?php echo $date_created; ?></span>
                        <span class="symbol-directory">,</span>
                        <span>Last updated on <?php echo $last_updated; ?></span>
                    </div>
                </div>

                <form id="quizForm" method="post" action="<?php echo $quiz_type == 'prac' ? 'c_summary.php' : 'c_summary_rank.php'; ?>">
                    <div class="quiz-wrapper">

                    <?php 
                        include "db_conn.php";
                        $query = "SELECT * FROM `quiz_question` WHERE `quiz_id` = '$quiz_id'";
                        $results = mysqli_query($connection, $query);
                        if (mysqli_num_rows($results) > 0) {
                            $i = 0;
                            while ($row = mysqli_fetch_assoc($results)) {
                                $i += 1;
                                if ($template_type == "mcq") {
                                    $content = json_decode($row['question'], true);
                                    $question = $content['ques'];
                                    $optionA = $content['A'];
                                    $optionB = $content['B'];
                                    $optionC = $content['C'];
                                    $optionD = $content['D'];
                                    ?>
                                    <!-- mat template paragraph-->
                                    <div class="template">
                                        <div class="mcq">
                                            <div class="question-header">
                                                <span class="numbering">Question <?php echo $i; ?></span>
                                            </div>
                                            <input type="hidden" name="question_ids[]" value="<?php echo $row['ques_id']; ?>">
                                            <div class="ques-mcq">
                                                <span>
                                                    <?php echo $question; ?>
                                                </span>
                                            </div>

                                            <div class="ans-mcq">
                                                <!-- ans 1 -->
                                                <label for="choiceA-q<?php echo $i;?>">
                                                    <div class="choice">
                                                        <input type="radio" name="answers[<?php echo $row['ques_id']; ?>]" id="choiceA-q<?php echo $i;?>" value="A"/>
                                                        <span><?php echo $optionA; ?></span>
                                                    </div>
                                                </label>

                                                <!-- ans 2 -->
                                                <label for="choiceB-q<?php echo $i;?>">
                                                    <div class="choice">
                                                    <input type="radio" name="answers[<?php echo $row['ques_id']; ?>]" id="choiceB-q<?php echo $i;?>" value="B"/>
                                                        <span><?php echo $optionB; ?></span>
                                                    </div>
                                                </label>

                                                <!-- ans 3 -->
                                                <label for="choiceC-q<?php echo $i;?>">
                                                    <div class="choice">
                                                    <input type="radio" name="answers[<?php echo $row['ques_id']; ?>]" id="choiceC-q<?php echo $i;?>" value="C"/>
                                                        <span><?php echo $optionC; ?></span>
                                                    </div>
                                                </label>

                                                <!-- ans 4 -->
                                                <label for="choiceD-q<?php echo $i;?>">
                                                    <div class="choice">
                                                    <input type="radio" name="answers[<?php echo $row['ques_id']; ?>]" id="choiceD-q<?php echo $i;?>" value="D"/>
                                                        <span><?php echo $optionD; ?></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else if ($template_type == "truefalse") {
                                    ?>
                                    <div class="template">
                                        <div class="tof">
                                            <div class="question-header">
                                                <span class="numbering">Question <?php echo $i; ?></span>
                                            </div>
                                            <input type="hidden" name="question_ids[]" value="<?php echo $row['ques_id']; ?>">
                                            <div class="ques-tof">
                                                <span>
                                                    <?php echo $row['question']; ?>
                                                </span>
                                            </div>
                                            <div class="ans-tof">
                                                <div class="tof-button">
                                                    <input type="radio" name="answers[<?php echo $row['ques_id']; ?>]" id="true-q<?php echo $i;?>" value="true"/>
                                                    <label for="true-q<?php echo $i;?>" class="label-tof">
                                                        <span>True</span>
                                                    </label>
                                                </div>
                                                <div class="tof-button">
                                                    <input type="radio" name="answers[<?php echo $row['ques_id']; ?>]" id="false-q<?php echo $i;?>" value="false"/>
                                                    <label for="false-q<?php echo $i;?>" class="label-tof">
                                                        <span>False</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else if ($template_type == "fillblank") {
                                        $pattern = '/(.*?)_(.*?)_(.*)/';
                                        $question = $row['question'];
                                        if (preg_match($pattern, $question, $parts)) {
                                            $start = $parts[1];
                                            $end = $parts[3];
                                        }
                                    ?>
                                    <div class="template">
                                        <div class="fitb">
                                            <div class="question-header">
                                                <span class="numbering">Question <?php echo $i; ?></span>
                                            </div>
                                            <input type="hidden" name="question_ids[]" value="<?php echo $row['ques_id']; ?>">
                                            <div class="ques-fitb">
                                                <span>
                                                    <?php echo $start; ?>
                                                    <span class="empty"></span>
                                                    <?php echo $end; ?>
                                                </span>
                                            </div>
                                            <div class="ans-fitb">
                                                <input type="text" name="answers[<?php echo $row['ques_id']; ?>]" placeholder="Type your answer here..." />
                                            </div>
                                        </div>
                                    </div>
                                <?php } 
                            }
                        } ?>
                    </div>
                    <!-- save button -->
                    <?php if($quiz_type == "rank") { ?>
                        <input type="hidden" id="elapsedTime" name="elapsedTime">
                    <?php } ?>
                    <div class="btn-container">
                        <input type="submit" value="Submit" />
                    </div>
                    <div class="title-date">
                        <span>Make sure all is done before submitting. Good
                            Luck!</span>
                    </div>
                </form>
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