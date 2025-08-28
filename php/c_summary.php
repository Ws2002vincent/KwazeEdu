<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { 
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $quiz_id = $_SESSION['quiz_id'];
        $user_id = $_SESSION['user_id'];
        $question_ids = $_POST['question_ids'];
        $question_ids = array_map('intval', $question_ids);
        $user_answers = $_POST['answers'];

        include "db_conn.php";
        $query = "SELECT `ques_id`, `answer`, `quiz_id` FROM `quiz_question` WHERE `ques_id` IN (" . implode(',', $question_ids) . ")";
        $result = mysqli_query($connection, $query);
        if ($result != null) {
            $score = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $question_id = $row['ques_id'];
                $correct_answer = $row['answer'];
                if (isset($user_answers[$question_id]) && ($user_answers[$question_id] == $correct_answer)) {
                    $score++;
                }
            }
            $score_correct = $score;
            $score_incorrect = count($question_ids) - $score;
            $total_score = $score;
            $final_score = json_encode([
                'correct' => $score_correct,
                'incorrect' => $score_incorrect,
                'total' => $total_score
            ]);
            $null_value = 0;

            //get quiz_title & quiz_type & quiz_category
            $quiz_query = "SELECT * FROM `quiz` WHERE `quiz_id` = '$quiz_id'";
            $quiz_result = mysqli_query($connection, $quiz_query);
            $row = mysqli_fetch_assoc($quiz_result);
            $quiz_type = "prac";
            $quiz_category = $row['category'];
            $quiz_title = $row['title'];
            $time_completed = date('Y-m-d H:i:s');


            $result_query = "INSERT INTO `user_result` (`user_id`, `quiz_id`, `quiz_type`, `quiz_category`, `score`, `coins_earned`, `timestamp`, `quiz_title`,  `date_completed`) 
            VALUES ('$user_id', '$quiz_id', '$quiz_type', '$quiz_category', '$final_score', '$null_value', '$null_value', '$quiz_title', '$time_completed')";
            if(mysqli_query($connection, $result_query)){
                $result_id = mysqli_insert_id($connection);
                $hist_query = "INSERT INTO `user_result_ques` (`result_id`, `question`, `correct_answer`, `user_answer`)
                                SELECT ?, `question`, `answer`, ?
                                FROM `quiz_question`
                                WHERE `ques_id` = ?";
                $stmt = $connection->prepare($hist_query);
                $stmt->bind_param("isi", $result_id, $user_ans, $question_id);
                foreach ($question_ids as $question_id) {
                    $user_ans = $user_answers[$question_id];
                    $stmt->bind_param("isi", $result_id, $user_ans, $question_id);
                    $stmt->execute();
                }
                $stmt->close();
                $_SESSION['result_id'] = $result_id;
            }
        }
        mysqli_close($connection);

        header("Location: ../php/c_summary.php");
        exit();
    }

    $_SESSION['result_id'] = isset($_GET['id']) ? $_GET['id'] : $_SESSION['result_id'];
    $result_id = $_SESSION['result_id'];

    include "db_conn.php";
    $result_query = "SELECT * FROM `user_result` WHERE `result_id` = '$result_id'";
    $history_result = mysqli_query($connection, $result_query);
    $row = mysqli_fetch_assoc($history_result);
    if ($row['quiz_category'] == "Multiple Choice Question") {
        $template_type = "mcq";
    } else if ($row['quiz_category'] == "True or False") {
        $template_type = "truefalse";
    } else if ($row['quiz_category'] == "Fill in the Blank") {
        $template_type = "fillblank";
    }
    $quiz_title = $row['quiz_title'];
    $score = json_decode($row['score'], true);
    $score_correct = $score['correct'];
    $score_incorrect = $score['incorrect'];
    $date_completed = $row['date_completed'];
    $date = new DateTime($date_completed);
    $date_completed = $date->format('d M Y');
    $time_completed = $date->format('g:i a');
    
    mysqli_close($connection);

    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Summary</title>

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
    <link rel="stylesheet" href="../css/c_summary.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
    <!-- <script>
        window.history.replaceState(null, null, "../php/c_Dashboard.php");
    </script> -->

</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <!-- content -->
    <div class="uni-wrapper">
        <div class="container flex-col">
            <div class="content-container">
                <div class="backgound">
                    <div class="cong-header">
                        <div class="cong-title">
                            <span>Well done!</span>
                        </div>
                        <div class="cong-details flex-col">
                            <span>
                                🎉 Congratulations on completing the quiz 👏
                            </span>
                            <span class="quiz-name"><?php echo $quiz_title; ?></span>
                            <div class="date">
                                <span>Completion Date: </span>
                                <span><?php echo $date_completed; ?></span>
                                <span><?php echo $time_completed; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="summary">
                        <span class="summary-title">Summary</span>
                        <div class="summary-wrapper">
                            <div class="summary-details true">
                                <div class="summary-info">
                                    <span><i class="bx bx-check"></i></span>
                                    <span><?php echo $score_correct; ?></span>
                                </div>
                                <div class="summary-label">
                                    <span>Correct</span>
                                </div>
                            </div>
                            <div class="summary-details false">
                                <div class="summary-info">
                                    <span><i class="bx bx-x"></i></span>
                                    <span><?php echo $score_incorrect; ?></span>
                                </div>
                                <div class="summary-label">
                                    <span>Incorrect</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="review">
                    <span class="review-title">Review Answer</span>
                    <div class="review-wrapper">
                        <div class="review-question">
                            <!-- quiz template mcq-->

                            <!-- Correct ans：
									label加 class="correct-ans"
									div class="choice" 后面加 "correct-ans"-->

                            <!-- Incorrect ans：
									label加 class="incorrect-ans"
									div class="choice" 后面加 "incorrect-ans"
									然后input要放checked-->

                                    <?php 
                                    include "db_conn.php";
                                    $query = "SELECT * FROM `user_result_ques` WHERE `result_id` = '$result_id'";
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
                                                $correct_ans = $row['correct_answer'];
                                                $user_ans = $row['user_answer'];
                                                ?>                                    
                                                <div class="template">
                                                    <div class="mcq">
                                                        <div class="question-header">
                                                            <span class="numbering">Question <?php echo $i; ?></span>
                                                        </div>

                                                        <div class="ques-mcq">
                                                            <span>
                                                                <?php echo $question; ?>
                                                            </span>
                                                        </div>

                                                        <div class="ans-mcq">
                                                            <!-- ans 1 -->
                                                            <label for="choiceA-q<?php echo $i;?>" 
                                                                class="<?php echo ($correct_ans == 'A') ? 'correct-ans' : (($user_ans == 'A') ? 'incorrect-ans' : ''); ?>">
                                                                <div class="choice 
                                                                    <?php echo ($correct_ans == 'A') ? 'correct-ans' : (($user_ans == 'A') ? 'incorrect-ans' : ''); ?>">
                                                                    <input type="radio" name="choice-q<?php echo $i; ?>" id="choiceA-q<?php echo $i;?>" 
                                                                        <?php echo ($user_ans == 'A') ? 'checked' : ''; ?> disabled />
                                                                    <span><?php echo $optionA; ?></span>
                                                                </div>
                                                            </label>

                                                            <!-- ans 2 -->
                                                            <label for="choiceB-q<?php echo $i;?>" 
                                                                class="<?php echo ($correct_ans == 'B') ? 'correct-ans' : (($user_ans == 'B') ? 'incorrect-ans' : ''); ?>">
                                                                <div class="choice 
                                                                    <?php echo ($correct_ans == 'B') ? 'correct-ans' : (($user_ans == 'B') ? 'incorrect-ans' : ''); ?>">
                                                                    <input type="radio" name="choice-q<?php echo $i; ?>" id="choiceB-q<?php echo $i;?>" 
                                                                        <?php echo ($user_ans == 'B') ? 'checked' : ''; ?> disabled />
                                                                    <span><?php echo $optionB; ?></span>
                                                                </div>
                                                            </label>

                                                            <!-- ans 3 -->
                                                            <label for="choiceC-q<?php echo $i;?>" 
                                                                class="<?php echo ($correct_ans == 'C') ? 'correct-ans' : (($user_ans == 'C') ? 'incorrect-ans' : ''); ?>">
                                                                <div class="choice 
                                                                    <?php echo ($correct_ans == 'C') ? 'correct-ans' : (($user_ans == 'C') ? 'incorrect-ans' : ''); ?>">
                                                                    <input type="radio" name="choice-q<?php echo $i; ?>" id="choiceC-q<?php echo $i;?>" 
                                                                        <?php echo ($user_ans == 'C') ? 'checked' : ''; ?> disabled />
                                                                    <span><?php echo $optionC; ?></span>
                                                                </div>
                                                            </label>

                                                            <!-- ans 4 -->
                                                            <label for="choiceD-q<?php echo $i;?>" 
                                                                class="<?php echo ($correct_ans == 'D') ? 'correct-ans' : (($user_ans == 'D') ? 'incorrect-ans' : ''); ?>">
                                                                <div class="choice 
                                                                    <?php echo ($correct_ans == 'D') ? 'correct-ans' : (($user_ans == 'D') ? 'incorrect-ans' : ''); ?>">
                                                                    <input type="radio" name="choice-q<?php echo $i; ?>" id="choiceD-q<?php echo $i;?>" 
                                                                        <?php echo ($user_ans == 'D') ? 'checked' : ''; ?> disabled />
                                                                    <span><?php echo $optionD; ?></span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else if ($template_type == "truefalse") { ?>
                                                <div class="template">
                                                    <div class="tof">
                                                        <div class="question-header">
                                                            <span class="numbering">Question <?php echo $i; ?></span>
                                                        </div>

                                                        <div class="ques-tof">
                                                            <span>
                                                                <?php echo $row['question']; ?>
                                                            </span>
                                                        </div>
                                                        <div class="ans-tof">
                                                            <div class="tof-button">
                                                                <input type="radio" name="tof-q<?php echo $i;?>" id="true-q<?php echo $i;?>" disabled />
                                                                <label for="true-q<?php echo $i;?>" class="label-tof <?php echo ($row['correct_answer'] == 'true') ? 'correct-ans' : (($row['user_answer'] == 'true') ? 'incorrect-ans' : ''); ?>">
                                                                    <span>True</span>
                                                                </label>
                                                            </div>
                                                            <div class="tof-button">
                                                                <input type="radio" name="tof-q<?php echo $i;?>" id="false-q<?php echo $i;?>" disabled />
                                                                <label for="false-q<?php echo $i;?>" class="label-tof <?php echo ($row['correct_answer'] == 'false') ? 'correct-ans' : (($row['user_answer'] == 'false') ? 'incorrect-ans' : ''); ?>">
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
                                                            <div class="ques-fitb">
                                                                <!-- spawn the empty span at the ans part -->
                                                                <span>
                                                                    <?php echo $start; ?>
                                                                    <span class="empty"></span>
                                                                    <?php echo $end; ?>
                                                                    <!-- 5 underscore "_" -->
                                                                </span>
                                                            </div>

                                                            <?php if($row['correct_answer'] != $row['user_answer']) { ?>
                                                                <!-- if wrong, show correct ans, add a class to input -->
                                                                <div class="ans-fitb">
                                                                    <input type="text" value="Answer: <?php echo $row['user_answer']; ?>" class="incorrect-ans" disabled />
                                                                    <span>Correct Answer: </span>
                                                                    <span class="correct-ans">
                                                                        <?php echo $row['correct_answer']; ?>
                                                                    </span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <!-- if correct, no need show correct ans, just add a class to input -->
                                                                <div class="ans-fitb">
                                                                    <input type="text" value="Answer: <?php echo $row['correct_answer']; ?>" class="correct-ans" disabled />
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                            <?php }
                                        }
                                    }
                                    ?>

                            <!-- quiz template tof-->

                            <!-- Correct ans：
									label加 class="correct-ans"-->

                            <!-- Incorrect ans：
									label加 class="incorrect-ans"-->


                            <!-- quiz template fitb-->
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
} else if (isset($_SESSION["user_id"]) && (($_SESSION["role"] == "admin") || ($_SESSION["role"] == "manager"))){
    header ('Location: ../php/a_Dashboard.php');
    exit();
} else {
    header ('Location: ../php/login.php');
    exit();
}