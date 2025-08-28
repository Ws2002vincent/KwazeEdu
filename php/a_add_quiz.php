<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) {
    
    $new = true;    //determine using db maybe
    $_SESSION['quiz_id'] = isset($_GET['id']) ? $_GET['id'] : $_SESSION['quiz_id'];

    //check whether to add new content or edit existing content
    include "db_conn.php";
    $exist_query = "SELECT * FROM `quiz_question` WHERE `quiz_id` = {$_SESSION['quiz_id']}";
    $exist_result = mysqli_query($connection, $exist_query);
    if (mysqli_num_rows($exist_result) > 0) {
        $new = false;
    }

    //get category
    $category_query = "SELECT * FROM `quiz` WHERE `quiz_id` = {$_SESSION['quiz_id']}";
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
    $_SESSION['last_updated'] = $last_updated;

    mysqli_close($connection);

    function resetData() {
        $quiz_id = $_SESSION['quiz_id'];
        include "db_conn.php";
        //delete existing records and insert again
        $dlt_query = "DELETE FROM `quiz_question` WHERE `quiz_id` = '$quiz_id'";
        if (mysqli_query($connection, $dlt_query)){
            //echo "deleted successfully";
            mysqli_close($connection);
        }
    }

    function updateDate(){
        $quiz_id = $_SESSION['quiz_id'];
        include "db_conn.php";
        $new_last_updated = date('Y-m-d H:i:s');
        $update_query = "UPDATE `quiz` SET `last_updated` = '$new_last_updated' WHERE `quiz_id` = '$quiz_id'";
        if (mysqli_query($connection, $update_query)) {
            mysqli_close($connection);
        }
        $_SESSION['last_updated'] = date('d/m/Y', strtotime($new_last_updated));
    }

    if ($template_type == "mcq") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $questions = $_POST['txtQuestion'] ?? [];
            $optionAs = $_POST['txtOptionA'] ?? [];
            $optionBs = $_POST['txtOptionB'] ?? [];
            $optionCs = $_POST['txtOptionC'] ?? [];
            $optionDs = $_POST['txtOptionD'] ?? [];
            $answers = $_POST['rdoAnswer'] ?? [];
            $optionA_errors = [];
            $optionB_errors = [];
            $optionC_errors = [];
            $optionD_errors = [];
            $question_errors = [];
            
            foreach ($questions as $index => $question) {
                if (empty($question) && !isset($answers[$index])) {
                    $question_errors[$index] = 'Question and answer is required';
                } else if (empty($question)) {
                    $question_errors[$index] = 'Question is required.';
                } else if (!isset($answers[$index])) {
                    $question_errors[$index] = 'Please select an answer.';
                }
            }
        
            foreach ($optionAs as $index => $optionA) {
                if (empty($optionA)) {
                    $optionA_errors[$index] = 'Option A is required';
                }
            }
            foreach ($optionBs as $index => $optionB) {
                if (empty($optionB)) {
                    $optionB_errors[$index] = 'Option B is required';
                }
            }
            foreach ($optionCs as $index => $optionC) {
                if (empty($optionC)) {
                    $optionC_errors[$index] = 'Option C is required';
                }
            }
            foreach ($optionDs as $index => $optionD) {
                if (empty($optionD)) {
                    $optionD_errors[$index] = 'Option D is required';
                }
            }
        
            if (empty($question_errors) && empty($optionA_errors) && empty($optionB_errors) && empty($optionC_errors) && empty($optionD_errors)) {
                $quiz_id = $_SESSION['quiz_id'];
                include "db_conn.php";
                resetData();
                $insertQuery = "INSERT INTO `quiz_question` (`quiz_id`, `question`, `answer`) VALUES (?, ?, ?)";
                $stmt = $connection->prepare($insertQuery);
                foreach ($questions as $index => $question) {
                    $optionA = $optionAs[$index];
                    $optionB = $optionBs[$index];
                    $optionC = $optionCs[$index];
                    $optionD = $optionDs[$index];
                    $answer = $answers[$index];
                    $question = json_encode([
                        'ques' => $question,
                        'A' => $optionA,
                        'B' => $optionB,
                        'C' => $optionC,
                        'D' => $optionD
                    ]);
                    $stmt->bind_param('iss', $quiz_id, $question, $answer);
                    $stmt->execute();
                }
                updateDate();
                echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_quiz.php';
                    </script>";
                exit();
            } else {
                echo "<script>alert('Changes are not saved. Please ensure there are no input errors.');</script>";
            }
        }         
    }

    if ($template_type == "truefalse") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $questions = $_POST['txtQuestion'] ?? [];
            $answers = $_POST['rdoAnswer'] ?? [];
            $question_errors = [];
            $answer_errors = [];
            
            foreach ($questions as $index => $question) {
                if (empty($question)) {
                    $question_errors[$index] = 'Question is required.';
                }
                
                if (!isset($answers[$index])) {
                    $answer_errors[$index] = 'Please select an answer.';
                }
            }
        
            if (empty($question_errors) && empty($answer_errors)) {
                $quiz_id = $_SESSION['quiz_id'];
                include "db_conn.php";
                resetData();
                $insertQuery = "INSERT INTO `quiz_question` (`quiz_id`, `question`, `answer`) VALUES (?, ?, ?)";
                $stmt = $connection->prepare($insertQuery);
                foreach ($questions as $index => $question) {
                    $question = ucfirst($question);
                    $answer = $answers[$index];
                    $stmt->bind_param('iss', $quiz_id, $question, $answer);
                    $stmt->execute();
                }
                updateDate();
                echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_quiz.php';
                    </script>";
                exit();
            } else {
                echo "<script>alert('Changes are not saved. Please ensure there are no input errors.');</script>";
            }
        }         
    }

    if ($template_type == "fillblank") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $questions = $_POST['txtQuestion'] ?? [];
            $question_errors = [];
            
            foreach ($questions as $index => $question) {
                if (empty($question)) {
                    $question_errors[$index] = 'Question is required.';
                } else {
                    if (substr_count($question, '<') !== 1 || substr_count($question, '>') !== 1) {
                        $question_errors[$index] = 'Question must contain one pair of angle brackets.';
                    } else {
                        $start_pos = (strpos($question, '<'));
                        $end_pos = (strpos($question, '>'));
                        $content = trim(substr($question, $start_pos + 1, $end_pos - $start_pos - 1));
                        if (empty($content)) {
                            $question_errors[$index] = 'Answer is required.';
                        }
                    }
                }
            }                 
        
            if (empty($question_errors)) {
                $quiz_id = $_SESSION['quiz_id'];
                include "db_conn.php";
                resetData();
                $insertQuery = "INSERT INTO `quiz_question` (`quiz_id`, `question`, `answer`) VALUES (?, ?, ?)";
                $stmt = $connection->prepare($insertQuery);
                $pattern = '/(.*?)<(.*?)>(.*)/';
                foreach ($questions as $index => $question) {
                    if (preg_match($pattern, $question, $parts)) {
                        $question = $parts[1] . "__" . $parts[3];
                        $answer = $parts[2];
                        $question = ucfirst($question);
                        $stmt->bind_param('iss', $quiz_id, $question, $answer);
                        $stmt->execute();
                    }
                }
                updateDate();
                echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_quiz.php';
                    </script>";
                exit();
            } else {
                echo "<script>alert('Changes are not saved. Please ensure there are no input errors.');</script>";
            }
        }         
    }
    ?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Create Learning Material</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet" />
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/a_add.css" />
        <script src="../js/scroll_navbar.js"></script>
        <script src="../js/add_mcq_correct.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            .tooltip-text { display: none; }
            /* .ques-error-mcq { display: none; color: red; } */
            .ques-error-mcq, .ques-error-tof { display: none; }
            .error { display: none; color: red; }
            .ques-error-mcq.visible { display: block; }
            .ques-error-tof.visible { display: flex; }
            .error.visible { display: block; }
            .visible { display: block; }
            
            .ans-tof input[type="radio"]:checked + .label-tof {
                background-color: #c6efce;
                color: #006100;
            }
            .ans-tof input[type="radio"]:checked[value="false"] + .label-tof {
                background-color: #ffc7ce;
                color: #9c0006;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const btnAddField = document.getElementById('btnAddField');
            const contentContainer = document.getElementById('content-container');

            btnAddField.addEventListener('click', function(e) {
                e.preventDefault();
                if ('<?php echo $template_type; ?>' === "mcq") {
                    addMCQ();
                } else if ('<?php echo $template_type; ?>' === "truefalse") {
                    addTrueFalse();
                } else if ('<?php echo $template_type; ?>' === "fillblank") {
                    addFillBlank();
                }
            });

            function addMCQ(question = '', optionA = '', optionB = '', optionC = '', optionD = '', answer = '', question_error = '', optionA_error = '', optionB_error = '', optionC_error = '', optionD_error = '') {
                const mcqCount = contentContainer.querySelectorAll('.template').length;
                const mcqDiv = document.createElement('div');
                mcqDiv.classList.add('template');
                mcqDiv.innerHTML = `
                    <div class="mcq">
                        <div class="question-header">
                            <span class="numbering">Question ${mcqCount + 1}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>
                                Type a question and the choices! Remember to select the correct answer.
                            </span>
                        </div>
                        <div class="ques-mcq">
                            <textarea name="txtQuestion[]" class="txtParagraph" placeholder="Type your question here...">${question}</textarea>
                            <div class="ques-error-mcq ${question_error ? 'visible' : 'visible'}">
                                ${question_error ? `<i class="bx bx-error-circle"></i><span>${question_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                        </div>
                        <div class="ans-mcq">
                            <div class="choice">
                                <input type="radio" name="rdoAnswer[${mcqCount}]" value="A" ${answer === 'A' ? 'checked' : ''} />
                                <input type="text" name="txtOptionA[]" placeholder="Option A" value="${optionA}" />
                            </div>
                            <div class="error ${optionA_error ? 'visible' : 'visible'}">
                                ${optionA_error ? `<i class="bx bx-error-circle"></i><span>${optionA_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                            <div class="choice">
                                <input type="radio" name="rdoAnswer[${mcqCount}]" value="B" ${answer === 'B' ? 'checked' : ''} />
                                <input type="text" name="txtOptionB[]" placeholder="Option B" value="${optionB}" />
                            </div>
                            <div class="error ${optionB_error ? 'visible' : 'visible'}">
                                ${optionB_error ? `<i class="bx bx-error-circle"></i><span>${optionB_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}    
                            </div>
                            <div class="choice">
                                <input type="radio" name="rdoAnswer[${mcqCount}]" value="C" ${answer === 'C' ? 'checked' : ''} />
                                <input type="text" name="txtOptionC[]" placeholder="Option C" value="${optionC}" />
                            </div>
                            <div class="error ${optionC_error ? 'visible' : 'visible'}">
                                ${optionC_error ? `<i class="bx bx-error-circle"></i><span>${optionC_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                            <div class="choice">
                                <input type="radio" name="rdoAnswer[${mcqCount}]" value="D" ${answer === 'D' ? 'checked' : ''} />
                                <input type="text" name="txtOptionD[]" placeholder="Option D" value="${optionD}" />
                            </div>
                            <div class="error ${optionD_error ? 'visible' : 'visible'}">
                                ${optionD_error ? `<i class="bx bx-error-circle"></i><span>${optionD_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                        </div>
                    </div>
                `;
                contentContainer.insertBefore(mcqDiv, document.getElementById('save-container'));
                attachDeleteHandler(mcqDiv.querySelector('.btnDelete'));
                updateNumbers();
                updateRadioNames();
            }

            function addTrueFalse(question = '', answer = '', question_error = '', answer_error = '') {
                const tnfCount = contentContainer.querySelectorAll('.template').length;
                const tnfDiv = document.createElement('div');
                tnfDiv.classList.add('template');
                const trueId = `true-q${tnfCount + 1}`;
                const falseId = `false-q${tnfCount + 1}`;
                tnfDiv.innerHTML = `
                    <div class="mcq">
                        <div class="question-header">
                            <span class="numbering">Question ${tnfCount + 1}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>Type a question and state either true or false.</span>
                        </div>
                        <div class="ques-tof">
                            <textarea name="txtQuestion[]" placeholder="Type your question here...">${question}</textarea>
                            <div class="ques-error-mcq ${question_error ? 'visible' : 'visible'}">
                                ${question_error ? `<i class="bx bx-error-circle"></i><span>${question_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                            <div class="ans-tof">
                                <div class="tof-button">
                                    <input type="radio" id="${trueId}" name="rdoAnswer[${tnfCount}]" value="true" ${answer === 'true' ? 'checked' : ''} />
                                    <label for="${trueId}" class="label-tof">
                                        <span>True</span>
                                    </label>
                                </div>
                                <div class="tof-button">
                                    <input type="radio" id="${falseId}" name="rdoAnswer[${tnfCount}]" value="false" ${answer === 'false' ? 'checked' : ''} />
                                    <label for="${falseId}" class="label-tof">
                                        <span>False</span>
                                    </label>
                                </div>
                            </div>
                            <div class="ques-error-tof ${answer_error ? 'visible' : 'visible'}">
                                ${answer_error ? `<i class="bx bx-error-circle"></i><span>${answer_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                        </div>
                    </div>
                `;
                contentContainer.insertBefore(tnfDiv, document.getElementById('save-container'));
                attachDeleteHandler(tnfDiv.querySelector('.btnDelete'));
                updateNumbers();
                updateRadioNames();
            }

            function addFillBlank(question = '', question_error = '') {
                const fbCount = contentContainer.querySelectorAll('.template').length;
                const fbDiv = document.createElement('div');
                fbDiv.classList.add('template');
                fbDiv.innerHTML = `
                    <div class="fitb">
                        <div class="question-header">
                            <span class="numbering">Question ${fbCount + 1}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>Type a short sentence and enclose its answer with the <> symbol. (Only one blank per question)</span>
                        </div>
                        <div class="ques-fitb">
                            <textarea name="txtQuestion[]" placeholder="Type your question here...">${question}</textarea>
                            <div class="ques-error-mcq ${question_error ? 'visible' : 'visible'}">
                                ${question_error ? `<i class="bx bx-error-circle"></i><span>${question_error}</span>` : `<div class="erroricon"><i class="bx bx-error-circle"></i></div>`}
                            </div>
                        </div>
                    </div>
                `;
                contentContainer.insertBefore(fbDiv, document.getElementById('save-container'));
                attachDeleteHandler(fbDiv.querySelector('.btnDelete'));
                updateNumbers();
            }

            function attachDeleteHandler(btnDelete) {
                btnDelete.addEventListener('click', function(e) {
                    e.preventDefault();
                    const content = btnDelete.closest('.template');
                    contentContainer.removeChild(content);
                    updateNumbers();
                    if ('<?php echo $template_type; ?>' === "mcq" || '<?php echo $template_type; ?>' === "truefalse") {
                        updateRadioNames();
                    }
                });
            }

            function updateNumbers() {
                const elements = document.querySelectorAll('.template .numbering');
                elements.forEach((element, index) => {
                    element.textContent = `Question ${index + 1}`;
                });
            }

            function updateRadioNames() {
                const templates = document.querySelectorAll('.template');
                templates.forEach((template, index) => {
                    if ('<?php echo $template_type; ?>' === "mcq") {
                        const radios = template.querySelectorAll('.choice input[type="radio"]');
                        radios.forEach(radio => {
                            radio.name = `rdoAnswer[${index}]`;
                        });
                    }else if ('<?php echo $template_type; ?>' === "truefalse") {
                        const trueRadio = template.querySelector(`input[type="radio"][value="true"]`);
                        const trueLabel = template.querySelector(`label[for="${trueRadio.id}"]`);
                        const falseRadio = template.querySelector(`input[type="radio"][value="false"]`);
                        const falseLabel = template.querySelector(`label[for="${falseRadio.id}"]`);
                        trueRadio.name = `rdoAnswer[${index}]`;
                        trueRadio.id = `true-q${index + 1}`;
                        trueLabel.htmlFor = `true-q${index + 1}`;
                        falseRadio.name = `rdoAnswer[${index}]`;
                        falseRadio.id = `false-q${index + 1}`;
                        falseLabel.htmlFor = `false-q${index + 1}`;
                    }
                });
            }

            //initial questions or an empty one if there are no questions
            <?php
            if ($template_type == "mcq") {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($questions as $index => $question) {
                        $optionA = $optionAs[$index] ?? '';
                        $optionB = $optionBs[$index] ?? '';
                        $optionC = $optionCs[$index] ?? '';
                        $optionD = $optionDs[$index] ?? '';
                        $answer = $answers[$index] ?? '';
                        $question_error = $question_errors[$index] ?? '';
                        $optionA_error = $optionA_errors[$index] ?? '';
                        $optionB_error = $optionB_errors[$index] ?? '';
                        $optionC_error = $optionC_errors[$index] ?? '';
                        $optionD_error = $optionD_errors[$index] ?? '';
                        echo "addMCQ(" . json_encode($question) . ", " . json_encode($optionA) . ", " . json_encode($optionB) . ", " . json_encode($optionC) . ", " . json_encode($optionD) . ", " . json_encode($answer) . ", " . json_encode($question_error) . ", " . json_encode($optionA_error) . ", " . json_encode($optionB_error) . ", " . json_encode($optionC_error) . ", " . json_encode($optionD_error) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `quiz_question` WHERE `quiz_id` = {$_SESSION['quiz_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)){
                            $content = json_decode($row['question'], true);
                            $question = $content['ques'];
                            $optionA = $content['A'];
                            $optionB = $content['B'];
                            $optionC = $content['C'];
                            $optionD = $content['D'];
                            $answer = $row['answer'];
                            $error = "";
                            echo "addMCQ(" . json_encode($question) . ", " . json_encode($optionA) . ", " . json_encode($optionB) . ", " . json_encode($optionC) . ", " . json_encode($optionD) . ", " . json_encode($answer) . ", " . json_encode($error) . ", " . json_encode($error) . ", " . json_encode($error) . ", " . json_encode($error) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addMCQ();";
                }
            } else if ($template_type == "truefalse") {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($questions as $index => $question) {
                        $answer = $answers[$index] ?? '';
                        $question_error = $question_errors[$index] ?? '';
                        $answer_error = $answer_errors[$index] ?? '';
                        echo "addTrueFalse(" . json_encode($question) . ", " . json_encode($answer) . ", " . json_encode($question_error) . ", " . json_encode($answer_error) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `quiz_question` WHERE `quiz_id` = {$_SESSION['quiz_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)){
                            $question = $row['question'];
                            $answer = $row['answer'];
                            $error = "";
                            echo "addTrueFalse(" . json_encode($question) . ", " . json_encode($answer) . ", " . json_encode($error) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addTrueFalse();";
                }
            } else if ($template_type == "fillblank") {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($questions as $index => $question) {
                        $question_error = $question_errors[$index] ?? '';
                        echo "addFillBlank(" . json_encode($question) . ", " . json_encode($question_error) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `quiz_question` WHERE `quiz_id` = {$_SESSION['quiz_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        $pattern = '/(.*?)_(.*?)_(.*)/';
                        while ($row = mysqli_fetch_assoc($results)){
                            $question = $row['question'];
                            $answer = $row['answer'];
                            if (preg_match($pattern, $question, $parts)) {
                                $question = $parts[1] . "<" . $answer . ">" . $parts[3];
                            }
                            $error = "";
                            echo "addFillBlank(" . json_encode($question) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addFillBlank();";
                }
            }
            ?>
        });
        </script>
    </head>
    <body>
        <!-- Navigation bar -->
        <?php include 'nav_Admin.php'; ?>

        <!-- content -->
        <div class="uni-wrapper">
            <div class="container flex-col">
                <div class="content-container" >
                    <!-- directory -->
                    <div class="directory">
                        <a href="a_Dashboard.php">Dashboard</a>
                        <span class="symbol-directory">></span>
                        <?php
                        if ($quiz_type == "prac") {
                            ?><a href="a_quiz_view.php">Manage Practice Quizzes</a><?php
                        } else if ($quiz_type == "rank") {
                            ?><a href="a_rank_view.php">Manage Ranked Quizzes</a><?php
                        }
                        ?>
                        <span class="symbol-directory">></span>
                        <span class="no-bold-directory"><?php echo $new ? "Create Quiz" : "Edit Quiz"; ?></span>
                        <span class="symbol-directory">></span>
                        <a href="#"><?php echo $quiz_title; ?></a>
                    </div>

                    <!-- title -->
                    <div class="title-box">
                        <div class="title">
                            <span><?php echo $quiz_title; ?></span>
                        </div>
                        <div class="title-details">
                            <span><?php echo $quiz_category ?></span>
                            <span class="symbol-directory">,</span>
                            <span><?php echo $quiz_level ?></span>
                        </div>
                    </div>

                    <form action="" method = "post">
                        <!-- tools -->
                        <div class="tools">
                            <div class="tool">
                                <button class="add" aria-label="Add a new template" id="btnAddField">
                                    <i class="bx bx-plus"></i>
                                    <span class="tooltip-text">
                                        Add a new question
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- quiz template mcq, true or false, fill in blank-->

                        <div id="content-container">

                        <!-- save button -->
                        <div class="btn-container" id="save-container">
                            <input type="submit" value="Save Changes" name="btnSaveMat" />
                        </div>
                    </form>

                    <div class="title-date">
                        <span>Created on <?php echo $date_created; ?></span>
                        <span class="symbol-directory">,</span>
                        <span>Last updated on <?php echo $_SESSION['last_updated']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
<?php
} else if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "user")){
    header ('Location: ../php/c_Dashboard.php');
    exit();
} else {
    header ('Location: ../php/login.php');
    exit();
}
?>