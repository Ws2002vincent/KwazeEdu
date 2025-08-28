<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) {

    $new = true;    //determine using db maybe
    $_SESSION['mat_id'] = isset($_GET['id']) ? $_GET['id'] : $_SESSION['mat_id'];

    //check whether to add new content or edit existing content
    include "db_conn.php";
    $exist_query = "SELECT * FROM `mat_content` WHERE `mat_id` = {$_SESSION['mat_id']}";
    $exist_result = mysqli_query($connection, $exist_query);
    if (mysqli_num_rows($exist_result) > 0) {
        $new = false;
    }

    //get category & other data
    $category_query = "SELECT * FROM `learn_mat` WHERE `mat_id` = {$_SESSION['mat_id']}";
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
    $_SESSION['last_updated'] = $last_updated;
    
    mysqli_close($connection);

    function resetData() {
        $mat_id = $_SESSION['mat_id'];
        include "db_conn.php";
        //delete existing records and insert again
        $dlt_query = "DELETE FROM `mat_content` WHERE `mat_id` = '$mat_id'";
        if (mysqli_query($connection, $dlt_query)){
            //echo "deleted successfully";
            mysqli_close($connection);
        }
    }

    function updateDate(){
        $mat_id = $_SESSION['mat_id'];
        include "db_conn.php";
        $new_last_updated = date('Y-m-d H:i:s');
        $update_query = "UPDATE `learn_mat` SET `last_updated` = '$new_last_updated' WHERE `mat_id` = '$mat_id'";
        if (mysqli_query($connection, $update_query)) {
            mysqli_close($connection);
        }
        $_SESSION['last_updated'] = date('d/m/Y', strtotime($new_last_updated));
    }

    function clearUnusedFiles(){
        //to remove unused files
        include "db_conn.php";
        $file_query = "SELECT `image` FROM `mat_content`";
        $file_results = mysqli_query($connection, $file_query);
        $all_files = [];
        if (mysqli_num_rows($file_results) > 0) {
            while ($row = mysqli_fetch_assoc($file_results)) {
                $all_files[] = $row['image'];
            }
        }
        $existingFiles = array_diff(scandir('../matFiles/'), array('.', '..'));
        foreach ($existingFiles as $existingFile) {  //$existingFile is filename
            if (!in_array($existingFile, $all_files)) {
                $fileToDelete = "../matFiles/" . $existingFile;
                if (file_exists($fileToDelete)) {
                    if (unlink($fileToDelete)) {
                        // echo "File " . htmlspecialchars($existingFile) . " has been deleted successfully.";
                    } else {
                        // echo "Sorry, there was an error deleting the file " . htmlspecialchars($existingFile) . ".";
                    }
                }
            }
        }
        mysqli_close($connection);
    }

    if ($template_type == "story") {
        if (!isset($_SESSION['uploaded_files'])) {
            $_SESSION['uploaded_files'] = [];
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $paragraphs = $_POST['txtParagraph'] ?? [];
            $fileNames = $_POST['fileNames'] ?? [];
            $errors = [];
            $fileErrors = [];
        
            if (!empty($paragraphs) || !empty($fileNames)) {
                foreach ($_FILES['fileStory']['name'] as $index => $fileName) {
                    $noError = true;
                    if (!empty($fileName)) {
                        $targetDir = "../matFiles/";
                        $targetFile = $targetDir . basename($_FILES['fileStory']['name'][$index]);
                        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                        
                        $check = getimagesize($_FILES["fileStory"]["tmp_name"][$index]);
                        if ($check === false) {
                            $noError = false;
                            $fileErrors[$index] = 'Selected file is not an image.';
                        }

                        if ($noError && $_FILES["fileStory"]["size"][$index] > 500000) {
                            $noError = false;
                            $fileErrors[$index] = "File size must not be larger than 500KB.";
                        }

                        if ($noError && !in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                            $noError = false;
                            $fileErrors[$index] = "Only JPG, JPEG, PNG & GIF files are allowed.";
                        }

                        if ($noError) {
                            if (move_uploaded_file($_FILES['fileStory']['tmp_name'][$index], $targetFile)) {
                                $fileNames[$index] = $targetFile;
                                $_SESSION['uploaded_files'][] = $targetFile;
                            } else {
                                $fileErrors[$index] = 'Error uploading file.';
                            }
                        }
                    } else {
                        $fileNames[$index] = $_POST['fileNames'][$index] ?? '';
                        if (empty($fileNames[$index])) {
                            $fileErrors[$index] = 'Image is required.';
                        }
                    }
                }
            
                foreach ($paragraphs as $index => $paragraph) {
                    if (empty($paragraph)) {
                        $errors['txtParagraph'][$index] = 'Paragraph is required';
                    }
                }
            
                if (empty($errors) && empty($fileErrors)) {
                    $_SESSION['uploaded_files'] = [];
                    $mat_id = $_SESSION['mat_id'];
                    include "db_conn.php";
            
                    resetData();
                    $insertQuery = "INSERT INTO `mat_content` (`mat_id`, `content`, `image`) VALUES (?, ?, ?)";
                    $stmt = $connection->prepare($insertQuery);
                    foreach ($paragraphs as $index => $paragraph) {
                        $fileName = $fileNames[$index] ?? '';
                        $filePathParts = explode("/", $fileName);
                        $filename = end($filePathParts);
                        $stmt->bind_param('iss', $mat_id, $paragraph, $filename);
                        $stmt->execute();
                    }
                    updateDate();
                    echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_mat.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>alert('Changes made are not saved. Please check if your input is correct.');</script>";
                }
            } else {
                resetData();
            }
            clearUnusedFiles();
        }
    }

    if ($template_type == "paragraph") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $paragraphs = $_POST['txtParagraph'] ?? [];
            $errors = [];
        
            if (!empty($paragraphs)) {
                foreach ($paragraphs as $index => $paragraph) {
                    if (empty($paragraph)) {
                        $errors['txtParagraph'][$index] = 'Paragraph is required';
                    }
                }

                if (empty($errors)) {
                    // Clear session temp files
                    $_SESSION['uploaded_files'] = [];
                    $mat_id = $_SESSION['mat_id'];
                    include "db_conn.php";
            
                    resetData();

                    $insertQuery = "INSERT INTO `mat_content` (`mat_id`, `content`, `image`) VALUES (?, ?, ?)";
                    $stmt = $connection->prepare($insertQuery);
                    foreach ($paragraphs as $index => $paragraph) {
                        $image = "noimage";
                        $stmt->bind_param('iss', $mat_id, $paragraph, $image);
                        $stmt->execute();
                    }
                    updateDate();
                    echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_mat.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>alert('Changes made are not saved. Please check if your input is correct.');</script>";
                }
            } else {
                resetData();
            }
        }
    }

    if ($template_type == "simplecard") {
        if (!isset($_SESSION['uploaded_files'])) {
            $_SESSION['uploaded_files'] = [];
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $texts = $_POST['txtText'] ?? [];
            $texts = array_map('ucwords', $texts);
            $fileNames = $_POST['fileNames'] ?? [];
            $errors = [];
            $fileErrors = [];

            if (!empty($texts) || !empty($fileNames)) {
                foreach ($_FILES['fileSFC']['name'] as $index => $fileName) {
                    $noError = true;
                    if (!empty($fileName)) {
                        $targetDir = "../matFiles/";
                        $targetFile = $targetDir . basename($_FILES['fileSFC']['name'][$index]);
                        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                        
                        // Check if the file is an image
                        $check = getimagesize($_FILES["fileSFC"]["tmp_name"][$index]);
                        if ($check === false) {
                            $noError = false;
                            $fileErrors[$index] = 'Selected file is not an image.';
                        }

                        // Check file size
                        if ($noError && $_FILES["fileSFC"]["size"][$index] > 500000) {
                            $noError = false;
                            $fileErrors[$index] = "File size must not be larger than 500KB.";
                        }

                        // Check file extension
                        if ($noError && !in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                            $noError = false;
                            $fileErrors[$index] = "Only JPG, JPEG, PNG & GIF files are allowed.";
                        }

                        // If no errors, upload the file
                        if ($noError) {
                            if (move_uploaded_file($_FILES['fileSFC']['tmp_name'][$index], $targetFile)) {
                                $fileNames[$index] = $targetFile;
                                $_SESSION['uploaded_files'][] = $targetFile;
                            } else {
                                $fileErrors[$index] = 'Error uploading file.';
                            }
                        }
                    } else {
                        $fileNames[$index] = $_POST['fileNames'][$index] ?? '';
                        if (empty($fileNames[$index])) {
                            $fileErrors[$index] = 'Image is required.';
                        }
                    }
                }
            
                foreach ($texts as $index => $text) {
                    if (empty($text)) {
                        $errors['txtText'][$index] = 'Text is required.';
                    } else if (!preg_match("/^[a-zA-Z\s]*$/", $text)) {
                        $errors['txtText'][$index] = 'Text cannot contain numbers or special characters.';
                    }
                }
            
                if (empty($errors) && empty($fileErrors)) {
            
                    // Clear session temp files
                    $_SESSION['uploaded_files'] = [];
            
                    $mat_id = $_SESSION['mat_id'];
                    include "db_conn.php";
            
                    resetData();

                    $insertQuery = "INSERT INTO `mat_content` (`mat_id`, `content`, `image`) VALUES (?, ?, ?)";
                    $stmt = $connection->prepare($insertQuery);
                    foreach ($texts as $index => $text) {
                        $fileName = $fileNames[$index] ?? '';
                        $filePathParts = explode("/", $fileName);
                        $filename = end($filePathParts);
                        $text = ucwords($text);
                        $stmt->bind_param('iss', $mat_id, $text, $filename);
                        $stmt->execute();
                    }
                    updateDate();
                    echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_mat.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>alert('Changes made are not saved. Please check if your input is correct.');</script>";
                }
            } else {
                resetData();
            }

            clearUnusedFiles();
        }
    }

    if ($template_type == "advancedcard") {
        if (!isset($_SESSION['uploaded_files'])) {
            $_SESSION['uploaded_files'] = [];
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $texts = $_POST['txtText'] ?? [];
            $texts = array_map('ucwords', $texts);
            $paragraphs = $_POST['txtParagraph'] ?? [];
            $paragraphs = array_map('ucfirst', $paragraphs);
            $fileNames = $_POST['fileNames'] ?? [];
            $errors = [];
            $paragraphErrors = [];
            $fileErrors = [];
        
            if (!empty($texts) || !empty($fileNames) || !empty($paragraphs)) {
                foreach ($_FILES['fileAFC']['name'] as $index => $fileName) {
                    $noError = true;
                    if (!empty($fileName)) {
                        $targetDir = "../matFiles/";
                        $targetFile = $targetDir . basename($_FILES['fileAFC']['name'][$index]);
                        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                        
                        // Check if the file is an image
                        $check = getimagesize($_FILES["fileAFC"]["tmp_name"][$index]);
                        if ($check === false) {
                            $noError = false;
                            $fileErrors[$index] = 'Selected file is not an image.';
                        }

                        // Check file size
                        if ($noError && $_FILES["fileAFC"]["size"][$index] > 500000) {
                            $noError = false;
                            $fileErrors[$index] = "File size must not be larger than 500KB.";
                        }

                        // Check file extension
                        if ($noError && !in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                            $noError = false;
                            $fileErrors[$index] = "Only JPG, JPEG, PNG & GIF files are allowed.";
                        }

                        // If no errors, upload the file
                        if ($noError) {
                            if (move_uploaded_file($_FILES['fileAFC']['tmp_name'][$index], $targetFile)) {
                                $fileNames[$index] = $targetFile;
                                $_SESSION['uploaded_files'][] = $targetFile;
                            } else {
                                $fileErrors[$index] = 'Error uploading file.';
                            }
                        }
                    } else {
                        $fileNames[$index] = $_POST['fileNames'][$index] ?? '';
                        if (empty($fileNames[$index])) {
                            $fileErrors[$index] = 'Image is required.';
                        }
                    }
                }
            
                foreach ($texts as $index => $text) {
                    if (empty($text)) {
                        $errors['txtText'][$index] = 'Text is required';
                    } else if (!preg_match("/^[a-zA-Z\s]*$/", $text)) {
                        $errors['txtText'][$index] = 'Text cannot contain numbers or special characters.';
                    }
                }

                foreach ($paragraphs as $index => $paragraph) {
                    if (empty($paragraph)) {
                        $paragraphErrors['txtParagraph'][$index] = 'Paragraph is required';
                    }
                }
            
                if (empty($errors) && empty($fileErrors) && empty($paragraphErrors)) {

                    // Clear session temp files
                    $_SESSION['uploaded_files'] = [];
            
                    $mat_id = $_SESSION['mat_id'];
                    include "db_conn.php";
            
                    resetData();

                    $insertQuery = "INSERT INTO `mat_content` (`mat_id`, `content`, `image`) VALUES (?, ?, ?)";
                    $stmt = $connection->prepare($insertQuery);
                    foreach ($texts as $index => $text) {
                        $fileName = $fileNames[$index] ?? '';
                        $paragraph = $paragraphs[$index] ?? '';
                        $filePathParts = explode("/", $fileName);
                        $filename = end($filePathParts);
                        $text = ucwords($text);
                        $paragraph = ucfirst($paragraph);
                        $content = json_encode(['title' => $text, 'desc' => $paragraph]);
                        $stmt->bind_param('iss', $mat_id, $content, $filename);
                        $stmt->execute();
                    }
                    updateDate();
                    echo "<script>
                        alert('All changes are saved!');
                        window.location.href = '../php/a_add_mat.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>alert('Changes made are not saved. Please check if your input is correct.');</script>";
                }
            } else {
                resetData();
            }

            clearUnusedFiles();
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
    <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/a_add.css" />
    <script src="../js/scroll_navbar.js"></script>
    <script src="../js/add_mcq_correct.js"></script>
    <script src="../js/preview_img_material.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
    .tooltip-text {
        display: none;
    }

    .ques-error-mcq {
        display: none;
        color: red;
    }

    .ques-error-mcq.visible {
        display: block;
    }

    .mat-story label,
    .mat-sfc label,
    .mat-afc label {
        cursor: pointer;
        z-index: 10;
        position: relative;
        overflow: hidden;
    }

    .image-preview {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .image-preview img,
    .image-preview-flash img {
        width: 100%;
        height: 100%;
        border-radius: 5px;
        z-index: 2;
    }

    .image-preview-flash {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }


    .blur-layer {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff50;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 3;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAddField = document.getElementById('btnAddField');
        const contentContainer = document.getElementById('content-container');

        btnAddField.addEventListener('click', function(e) {
            e.preventDefault();
            if ('<?php echo $template_type; ?>' === "story") {
                addStory();
            } else if ('<?php echo $template_type; ?>' === "paragraph") {
                addParagraph();
            } else if ('<?php echo $template_type; ?>' === "simplecard") {
                addSimpleCard();
            } else if ('<?php echo $template_type; ?>' === "advancedcard") {
                addAdvancedCard();
            }
        });

        function addStory(paragraph = '', fileName = '', paragraphError = '', fileError = '') {
            const storyCount = contentContainer.querySelectorAll('.template').length + 1;
            const storyDiv = document.createElement('div');
            storyDiv.classList.add('template');
            const fileInputId = `fileStory${storyCount}`;
            storyDiv.innerHTML = `
                    <div class="story">
                        <div class="question-header">
                            <span class="numbering">Story ${storyCount}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>
                                Upload a picture and start to write an interesting story!
                            </span>
                        </div>
                        <div class="mat-story">
                            <input type="file" name="fileStory[]" id="${fileInputId}" />
                            <label for="${fileInputId}">
                                <span>Upload Image</span>
                                <div class="image-preview">
                                    <img src="" alt="Image Preview" class="image-preview__image" style="display: none" />
                                    <div class="blur-layer"></div>
                                </div>
                            </label>
                            ${fileName ? `<p>Selected image: ${fileName.split("/").pop()}</p>` : ''}
                            <div class="ques-error-mcq ${fileError ? 'visible' : 'visible'}">
                                ${fileError ? `
                                    <i class="bx bx-error-circle"></i>
                                    <span>${fileError}</span>
                                ` : `
                                    <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                `}
                            </div>
                            <textarea name="txtParagraph[]" class="txtParagraph" placeholder="Type your story here...">${paragraph}</textarea>
                            <div class="ques-error-mcq ${paragraphError ? 'visible' : 'visible'}">
                                ${paragraphError ? `
                                    <i class="bx bx-error-circle"></i>
                                    <span>${paragraphError}</span>
                                ` : `
                                    <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                `}
                            </div>
                            <input type="hidden" name="fileNames[]" value="${fileName}" />
                        </div>
                    </div>
                `;
            contentContainer.insertBefore(storyDiv, document.getElementById('save-container'));
            attachDeleteHandler(storyDiv.querySelector('.btnDelete'));
            updateNumbers("Story");
        }

        function addParagraph(paragraph = '', paragraphError = '') {
            const paragraphCount = contentContainer.querySelectorAll('.template').length + 1;
            const paragraphDiv = document.createElement('div');
            paragraphDiv.classList.add('template');
            paragraphDiv.innerHTML = `
                    <div class="paragraph">
                        <div class="question-header">
                            <span class="numbering">Paragraph ${paragraphCount}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>
                                Make it in a paragraph!
                            </span>
                        </div>
                        <div class="mat-parag">
                            <textarea name="txtParagraph[]" class="txtParagraph" placeholder="Type your paragraph here...">${paragraph}</textarea>
                            <div class="ques-error-mcq ${paragraphError ? 'visible' : 'visible'}">
                                ${paragraphError ? `
                                    <i class="bx bx-error-circle"></i>
                                    <span>${paragraphError}</span>
                                ` : `
                                    <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                `}
                            </div>
                        </div>
                    </div>
                `;
            contentContainer.insertBefore(paragraphDiv, document.getElementById('save-container'));
            attachDeleteHandler(paragraphDiv.querySelector('.btnDelete'));
            updateNumbers("Paragraph");
        }

        function addSimpleCard(text = '', fileName = '', textError = '', fileError = '') {
            const cardCount = contentContainer.querySelectorAll('.template').length + 1;
            const cardDiv = document.createElement('div');
            cardDiv.classList.add('template');
            const fileInputId = `fileSFC${cardCount}`;
            cardDiv.innerHTML = `
                    <div class="sfc">
                        <div class="question-header">
                            <span class="numbering">Simple Flash Card ${cardCount}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>
                                Upload a picture and tell us what is it!
                            </span>
                        </div>
                        <div class="mat-sfc">
                            <div class="image-upload flex-col">
                                <input type="file" name="fileSFC[]" id="${fileInputId}"/>
                                <label for="${fileInputId}">
                                    <span>Upload Image</span>
                                    <div class="image-preview-flash">
                                        <img src="" alt="Image Preview" class="image-preview__image" style="display: none" />
                                        <div class="blur-layer"></div>
                                    </div>
                                </label>
                                ${fileName ? `<p>Selected image: ${fileName.split("/").pop()}</p>` : ''}
                                <div class="ques-error-mcq ${fileError ? 'visible' : 'visible'}">
                                    ${fileError ? `
                                        <i class="bx bx-error-circle"></i>
                                        <span>${fileError}</span>
                                    ` : `
                                        <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                    `}
                                </div>
                            </div>
                            <div class="textbox-field flex-col">
                                <input type="text" name="txtText[]" class="txtText" placeholder="What is this?" value="${text}"/>
                                <div class="ques-error-mcq ${textError ? 'visible' : 'visible'}">
                                    ${textError ? `
                                        <i class="bx bx-error-circle"></i>
                                        <span>${textError}</span>
                                    ` : `
                                        <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                    `}
                                </div>
                            </div>
                            <input type="hidden" name="fileNames[]" value="${fileName}" />
                        </div>
                    </div>
                `;
            contentContainer.insertBefore(cardDiv, document.getElementById('save-container'));
            attachDeleteHandler(cardDiv.querySelector('.btnDelete'));
            updateNumbers("Simple Flash Card");
        }

        function addAdvancedCard(text = '', paragraph = '', fileName = '', textError = '', paragraphError = '',
            fileError = '') {
            const cardCount = contentContainer.querySelectorAll('.template').length + 1;
            const cardDiv = document.createElement('div');
            cardDiv.classList.add('template');
            const fileInputId = `fileSFC${cardCount}`;
            cardDiv.innerHTML = `
                    <div class="afc">
                        <div class="question-header">
                            <span class="numbering">Advanced Flash Card ${cardCount}</span>
                            <button class="btnDelete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="instruction">
                            <span>
                                Upload a picture and tell us what is it!
                            </span>
                        </div>
                        <div class="mat-afc">
                            <div class="image-upload flex-col">
                                <input type="file" name="fileAFC[]" id="${fileInputId}"/>
                                <label for="${fileInputId}">
                                <span>Upload Image</span>
                                    <div class="image-preview-flash">
                                        <img src="" alt="Image Preview" class="image-preview__image" style="display: none" />
                                        <div class="blur-layer"></div>
                                    </div>
                                </label>
                                ${fileName ? `<p>Selected image: ${fileName.split("/").pop()}</p>` : ''}
                                <div class="ques-error-mcq ${fileError ? 'visible' : 'visible'}">
                                    ${fileError ? `
                                        <i class="bx bx-error-circle"></i>
                                        <span>${fileError}</span>
                                    ` : `
                                        <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                    `}
                                </div>
                            </div>
                            <div class="textbox-field flex-col">
                                <input type="text" name="txtText[]" class="txtText" placeholder="What is this?" value="${text}"/>
                                <div class="ques-error-mcq ${textError ? 'visible' : 'visible'}">
                                    ${textError ? `
                                        <i class="bx bx-error-circle"></i>
                                        <span>${textError}</span>
                                    ` : `
                                        <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                    `}
                                </div>
                                <textarea name="txtParagraph[]" class="txtParagraph" placeholder="Description here...">${paragraph}</textarea>
                                <div class="ques-error-mcq ${paragraphError ? 'visible' : 'visible'}">
                                    ${paragraphError ? `
                                        <i class="bx bx-error-circle"></i>
                                        <span>${paragraphError}</span>
                                    ` : `
                                        <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                    `}
                                </div>
                            </div>
                            <input type="hidden" name="fileNames[]" value="${fileName}" />
                        </div>
                    </div>
                `;
            contentContainer.insertBefore(cardDiv, document.getElementById('save-container'));
            attachDeleteHandler(cardDiv.querySelector('.btnDelete'));
            updateNumbers("Advanced Flash Card");
        }

        function attachDeleteHandler(btnDelete) {
            btnDelete.addEventListener('click', function(e) {
                e.preventDefault();
                const content = btnDelete.closest('.template');
                contentContainer.removeChild(content);
                if ('<?php echo $template_type; ?>' === "story") {
                    updateNumbers("Story");
                } else if ('<?php echo $template_type; ?>' === "paragraph") {
                    updateNumbers("Paragraph");
                } else if ('<?php echo $template_type; ?>' === "simplecard") {
                    updateNumbers("Simple Flash Card");
                } else if ('<?php echo $template_type; ?>' === "advancedcard") {
                    updateNumbers("Advanced Flash Card");
                }
            });
        }

        function updateNumbers(type) {
            const elements = document.querySelectorAll('.template .numbering');
            elements.forEach((element, index) => {
                element.textContent = `${type} ${index + 1}`;
            });
        }

        //initial stories or paragraphs based on previous input or empty
        <?php
            if ($template_type == "story") {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($paragraphs as $index => $paragraph) {
                        $paragraphError = $errors['txtParagraph'][$index] ?? '';
                        $fileError = $fileErrors[$index] ?? '';
                        $fileName = $fileNames[$index] ?? ($_POST['fileNames'][$index] ?? '');
                        echo "addStory(" . json_encode($paragraph) . ", " . json_encode($fileName) . ", " . json_encode($paragraphError) . ", " . json_encode($fileError) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `mat_content` WHERE `mat_id` = {$_SESSION['mat_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)){
                            $paragraph = $row['content'];
                            $filename = "../matFiles/" . $row['image'];
                            $error = "";
                            echo "addStory(" . json_encode($paragraph) . ", " . json_encode($filename) . ", " . json_encode($error) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addStory();";
                }
            } else if ($template_type == "paragraph"){
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($paragraphs as $index => $paragraph) {
                        $paragraphError = $errors['txtParagraph'][$index] ?? '';
                        echo "addParagraph(" . json_encode($paragraph) . ", " . json_encode($paragraphError) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `mat_content` WHERE `mat_id` = {$_SESSION['mat_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)){
                            $paragraph = $row['content'];
                            $error = "";
                            echo "addParagraph(" . json_encode($paragraph) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addParagraph();";
                }
            }  else if ($template_type == "simplecard") {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($texts as $index => $text) {
                        $textError = $errors['txtText'][$index] ?? '';
                        $fileError = $fileErrors[$index] ?? '';
                        $fileName = $fileNames[$index] ?? ($_POST['fileNames'][$index] ?? '');
                        echo "addSimpleCard(" . json_encode($text) . ", " . json_encode($fileName) . ", " . json_encode($textError) . ", " . json_encode($fileError) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `mat_content` WHERE `mat_id` = {$_SESSION['mat_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)){
                            $text = $row['content'];
                            $filename = "../matFiles/" . $row['image'];
                            $error = "";
                            echo "addSimpleCard(" . json_encode($text) . ", " . json_encode($filename) . ", " . json_encode($error) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addSimpleCard();";
                }
            }  else if ($template_type == "advancedcard") {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($texts as $index => $text) {
                        $textError = $errors['txtText'][$index] ?? '';
                        $paragraphError = $paragraphErrors['txtParagraph'][$index] ?? '';
                        $fileError = $fileErrors[$index] ?? '';
                        $paragraph = $paragraphs[$index] ?? ($_POST['txtParagraph'][$index] ?? '');
                        $fileName = $fileNames[$index] ?? ($_POST['fileNames'][$index] ?? '');
                        echo "addAdvancedCard(" . json_encode($text) . ", " . json_encode($paragraph) . ", " . json_encode($fileName) . ", " .  json_encode($textError) . ", " . json_encode($paragraphError) . ", " . json_encode($fileError) . ");";
                    }
                } else if (!$new) {
                    include "db_conn.php";
                    $query = "SELECT * FROM `mat_content` WHERE `mat_id` = {$_SESSION['mat_id']}";
                    $results = mysqli_query($connection, $query);
                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)){
                            $content = json_decode($row['content'], true);
                            $title = $content['title'];
                            $desc = $content['desc'];
                            $filename = "../matFiles/" . $row['image'];
                            $error = "";
                            echo "addAdvancedCard(" . json_encode($title) . ", " . json_encode($desc) . ", " . json_encode($filename) . ", " . json_encode($error) . ", "  . json_encode($error) . ", " . json_encode($error) . ");";
                        }
                    }
                    mysqli_close($connection);
                } else {
                    echo "addAdvancedCard();";
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
            <div class="content-container">
                <!-- directory -->
                <div class="directory">
                    <a href="a_Dashboard.php">Dashboard</a>
                    <span class="symbol-directory">></span>
                    <a href="a_mat_view.php">Manage Learning Material</a>
                    <span class="symbol-directory">></span>
                    <span
                        class="no-bold-directory"><?php echo $new ? "Create Learning Material" : "Edit Learning Material"; ?></span>
                    <span class="symbol-directory">></span>
                    <a href="#"><?php echo $mat_title; ?></a>
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
                </div>

                <form action="" method="post" enctype="multipart/form-data">
                    <!-- tools -->
                    <div class="tools">
                        <div class="tool">
                            <button class="add" aria-label="Add a new template" id="btnAddField">
                                <i class="bx bx-plus"></i>
                                <span class="tooltip-text">
                                    Add a new template
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- mat template story-->

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