<?php
session_start();

if(isset($_POST['txtTitle'])) {

    include "db_conn.php";
    $mat_title = mysqli_real_escape_string($connection, $_POST["txtTitle"]);
    $category = mysqli_real_escape_string($connection, $_POST["txtType"]);
    $level = mysqli_real_escape_string($connection, $_POST["txtLevel"]);
    $noError = true;

    if (empty($mat_title)) {
        echo "<script>$('#title_error').html('<i class=\"bx bx-error-circle\"></i>Title is required.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#title_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }
    
    if ($noError === true) {
        $mat_title = ucwords(trim($mat_title));
        $mat_query = "INSERT INTO `learn_mat` (`title`, `category`, `level`, `admin_id`) 
                      VALUES ('$mat_title', '$category', '$level', {$_SESSION['user_id']})";
        if (mysqli_query($connection, $mat_query)) {
            $id = mysqli_insert_id($connection);
            mysqli_close($connection);
            echo "<script>window.location.href = 'a_add_mat.php?id=$id';</script>";
            exit();
        }
    } else {
        mysqli_close($connection);
    }    
}

?>
