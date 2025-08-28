<?php
session_start();

if(isset($_POST['txtPracTitle'])) {

    include "db_conn.php";
    $quiz_title = mysqli_real_escape_string($connection, $_POST["txtPracTitle"]);
    $category = mysqli_real_escape_string($connection, $_POST["txtType"]);
    $level = mysqli_real_escape_string($connection, $_POST["txtLevel"]);
    $noError = true;

    if (empty($quiz_title)) {
        echo "<script>$('#title_error').html('<i class=\"bx bx-error-circle\"></i>Title is required.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#title_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }
    
    //account creation successful
    if ($noError === true) {
        $quiz_title = ucwords(trim($quiz_title));
        $quiz_query = "INSERT INTO `quiz` (`title`, `category`, `level`, `type`, `admin_id`) 
                      VALUES ('$quiz_title', '$category', '$level', 'prac', {$_SESSION['user_id']})";
        if (mysqli_query($connection, $quiz_query)) {
            $id = mysqli_insert_id($connection);
            mysqli_close($connection);
            echo "<script>window.location.href = 'a_add_quiz.php?id=$id';</script>";
            exit();
        }
    } else {
        mysqli_close($connection);
    }    
}

if(isset($_POST['txtRankTitle'])) {

    include "db_conn.php";
    $quiz_title = mysqli_real_escape_string($connection, $_POST["txtRankTitle"]);
    $category = mysqli_real_escape_string($connection, $_POST["txtType"]);
    $level = "All ages";
    $noError = true;

    if (empty($quiz_title)) {
        echo "<script>$('#title_error').html('<i class=\"bx bx-error-circle\"></i>Title is required.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#title_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }
    
    //account creation successful
    if ($noError === true) {
        $quiz_title = ucwords(trim($quiz_title));
        $quiz_query = "INSERT INTO `quiz` (`title`, `category`, `level`, `type`, `admin_id`) 
                      VALUES ('$quiz_title', '$category', '$level', 'rank', {$_SESSION['user_id']})";
        if (mysqli_query($connection, $quiz_query)) {
            $id = mysqli_insert_id($connection);
            mysqli_close($connection);
            echo "<script>window.location.href = 'a_add_quiz.php?id=$id';</script>";
            exit();
        }
    } else {
        mysqli_close($connection);
    }    
}

?>
