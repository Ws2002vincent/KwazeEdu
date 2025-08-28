<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { 

    $_SESSION['mat_id'] = isset($_GET['id']) ? $_GET['id'] : $_SESSION['mat_id'];
    // $_SESSION['mat_id'] = 4;
    $mat_id = $_SESSION['mat_id'];

    //get category
    include "db_conn.php";
    $category_query = "SELECT * FROM `learn_mat` WHERE `mat_id` = '$mat_id'";
    $category_result = mysqli_query($connection, $category_query);
    $row = mysqli_fetch_assoc($category_result);
    if ($row['category'] == "Storyboard Reading") {
        $template_type = "story";
    } else if ($row['category'] == "Paragraph Reading") {
        $template_type = "paragraph";
    } else if ($row['category'] == "Simple Flash Card") {
        $template_type = "simplecard";
    } else if ($row['category'] == "Advanced Flash Card") {
        $template_type = "advancedcard";
    }
    $mat_title = $row['title'];
    $mat_category = $row['category'];
    $mat_level = $row['level'];
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
    <title>Read Material</title>

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
    <script src="../js/c_material_reading.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkButtons = document.querySelectorAll('.wrong');
        const voiceButtons = document.querySelectorAll('.voice-btn');

        checkButtons.forEach(button => {
            button.addEventListener('click', function() {
                const container = button.closest('.sfc, .afc');
                const txtWord = container.querySelector('.txtWord').getAttribute('value');
                const textareaValue = container.querySelector('.tutorial').value.trim()
                    .toLowerCase();

                if (textareaValue === txtWord.toLowerCase()) {
                    button.classList.remove('wrong');
                    button.classList.add('confetti-button');
                    celebrate(button);
                } else {
                    button.classList.remove('confetti-button');
                    button.classList.add('wrong');
                    showError(button);
                }
            });
        });

        if (typeof speechSynthesis !== 'undefined' && speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = function() {};
        }

        voiceButtons.forEach(button => {
            button.addEventListener('click', function() {
                const container = button.closest('.sfc, .afc');
                const txtWord = container.querySelector('.txtWord').getAttribute('value');
                speak(txtWord);
            });
        });

        function celebrate(button) {
            confetti({
                particleCount: 100,
                spread: 700,
                origin: {
                    y: 0.6
                },
            });
        }

        function showError(button) {
            button.classList.add("shake");
            setTimeout(() => {
                button.classList.remove("shake");
            }, 500);
        }

        function speak(word) {
            var utterance = new SpeechSynthesisUtterance();
            utterance.text = word;

            var voices = window.speechSynthesis.getVoices();

            for (var i = 0; i < voices.length; i++) {
                if (voices[i].name.includes('Google UK English Female')) {
                    utterance.voice = voices[i];
                    break;
                }
            }
            utterance.rate = 0.8;
            window.speechSynthesis.speak(utterance);
        }
    });
    </script>
    <!-- <script>
    document.addEventListener("DOMContentLoaded", (event) => {
        const templates = document.querySelectorAll(".template");

        templates.forEach((template) => {
            const storyText = template.querySelector(".story-text span");
            const originalText = storyText.textContent.trim();

            template.addEventListener("click", function(event) {
                // Check if the click event target is the button
                if (event.target.closest(".question-header button")) {
                    return; // Do nothing if the button is clicked
                }

                if (this.classList.contains("split")) {
                    // Revert to paragraph mode
                    this.classList.remove("split");
                    storyText.innerHTML = originalText;
                } else {
                    // Split into lines
                    this.classList.add("split");
                    const lines = originalText
                        .split(". ")
                        .map((line) => `<span class="line">${line.trim()}.</span>`);
                    storyText.innerHTML = lines.join("<br/>");

                    const lineElements = template.querySelectorAll(".line");
                    lineElements.forEach((line) => {
                        line.addEventListener("mouseover", () => {
                            lineElements.forEach((l) => {
                                if (l !== line) l.classList.add(
                                    "dimmed");
                            });
                        });
                        line.addEventListener("mouseout", () => {
                            lineElements.forEach((l) =>
                                l.classList.remove("dimmed")
                            );
                        });
                    });
                }
            });
        });
    });
    </script> -->
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
                    <a href="../php/c_mat_view.php">
                        <button><i class="bx bx-arrow-back"></i></button>
                    </a>
                </div>

                <!-- title -->
                <div class="title-box">
                    <div class="title">
                        <span><?php echo $mat_title; ?></span>
                    </div>
                    <div class="title-details">
                        <span><?php echo $mat_category; ?></span>
                        <span class="symbol-directory">,</span>
                        <span><?php echo $mat_level; ?></span>
                    </div>
                    <div class="title-date">
                        <span>Created on <?php echo $date_created; ?></span>
                        <span class="symbol-directory">,</span>
                        <span>Last updated on <?php echo $last_updated; ?></span>
                    </div>
                </div>

                <div class="mat-wrapper">

                    <?php 
                        include "db_conn.php";
                        $query = "SELECT * FROM `mat_content` WHERE `mat_id` = '$mat_id'";
                        $results = mysqli_query($connection, $query);
                        if (mysqli_num_rows($results) > 0) {
                            $i = 0;
                            while ($row = mysqli_fetch_assoc($results)) {
                                $i += 1;
                                if ($template_type == "paragraph") {
                                    ?>
                    <!-- mat template paragraph-->
                    <div class="template">
                        <div class="paragraph">
                            <div class="question-header">
                                <span class="numbering">Paragraph <?php echo $i; ?></span>
                            </div>

                            <div class="mat-parag">
                                <div class="story-text" id="storyText">
                                    <span>
                                        <?php echo $row['content']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else if ($template_type == "story") { ?>
                    <!-- mat template story-->
                    <div class="template">
                        <div class="story">
                            <div class="question-header">
                                <span class="numbering">Story <?php echo $i; ?></span>
                            </div>

                            <div class="mat-story">
                                <div class="story-img-holder">
                                    <img src="../matFiles/<?php echo $row['image']; ?>" alt="story" />
                                </div>
                                <div class="story-text" id="storyText">
                                    <span>
                                        <?php echo $row['content']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div> <?php
                                } else if ($template_type == "simplecard") { ?>
                    <!-- mat template sfc-->
                    <div class="template">
                        <div class="sfc">
                            <div class="question-header">
                                <span class="numbering">
                                    Simple Fash Card <?php echo $i; ?>
                                </span>
                                <button class="voice-btn">
                                    <i class="bx bxs-user-voice"></i>
                                </button>
                            </div>

                            <div class="mat-sfc flex-row">
                                <div class="sfc-img-holder">
                                    <img src="../matFiles/<?php echo $row['image']; ?>" alt="story" />
                                </div>
                                <div class="sfc-text flex-col">
                                    <span class="lecture"><?php echo strtoupper($row['content']); ?></span>
                                    <input type="hidden" value="<?php echo $row['content']; ?>" id="textToSpeak"
                                        class="txtWord">
                                    <textarea class="tutorial" placeholder="Try spelling here..."></textarea>
                                    <input type="button" value="Check" class="wrong"
                                        id="checkButton<?php echo $i; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else if ($template_type == "advancedcard") { 
                                     $content = json_decode($row['content'], true);
                                     $title = $content['title'];
                                     $desc = $content['desc'];
                                     ?>
                    <!-- mat template afc-->
                    <div class="template">
                        <div class="afc">
                            <div class="question-header">
                                <span class="numbering">
                                    Advanced Fash Card <?php echo $i; ?>
                                </span>
                                <button class="voice-btn">
                                    <i class="bx bxs-user-voice"></i>
                                </button>
                            </div>

                            <div class="mat-afc flex-row">
                                <div class="afc-img-holder">
                                    <img src="../matFiles/<?php echo $row['image']; ?>" alt="story" />
                                </div>
                                <div class="afc-text flex-col">
                                    <span class="lecture"><?php echo strtoupper($title); ?></span>
                                    <span class="desc">
                                        <?php echo $desc; ?>
                                    </span>
                                    <input type="hidden" value="<?php echo $title; ?>" id="textToSpeak" class="txtWord">
                                    <textarea name="" id="" class="tutorial"
                                        placeholder="Try spelling here..."></textarea>
                                    <input type="button" value="Check" class="wrong"
                                        id="checkButton<?php echo $i; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                            }
                        }
                        ?>
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
?>