<?php

if(isset($_POST['txtPfpName'])) {
    include "functions.php";
    include "db_conn.php";
    //obtain manager input
    $pic_name = mysqli_real_escape_string($connection, $_POST["txtPfpName"]);
    $pic_series = mysqli_real_escape_string($connection, $_POST["txtPfpSeries"]);
    $pic_price = mysqli_real_escape_string($connection, $_POST["txtPfpPrice"]);
    $image = isset($_FILES["imgToUpload"]["name"]) ? basename($_FILES["imgToUpload"]["name"]) : "";
    $noError = true;

    if (empty($pic_name)) {
        echo "<script>$('#name_error').html('<i class=\"bx bx-error-circle\"></i>Description name is required.');</script>";
        $noError = false;
    } else if (!preg_match("/^[a-zA-Z ]*$/", $pic_name)){
        echo "<script>$('#name_error').html('<i class=\"bx bx-error-circle\"></i>Name should contain letters and whitespaces only.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#name_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }

    if (empty($pic_series)) {
        echo "<script>$('#series_error').html('<i class=\"bx bx-error-circle\"></i>Series is required.');</script>";
        $noError = false;
    } else if (!preg_match("/^[a-zA-Z ]*$/", $pic_series)){
        echo "<script>$('#series_error').html('<i class=\"bx bx-error-circle\"></i>Series should contain letters and whitespaces only.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#series_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }

    if ($pic_price != 0 && empty($pic_price)) {
        echo "<script>$('#price_error').html('<i class=\"bx bx-error-circle\"></i>Coins amount is required.');</script>";
        $noError = false;
    }else if (!preg_match('/^(\d+|0)$/', $pic_price)) {
        echo "<script>$('#price_error').html('<i class=\"bx bx-error-circle\"></i>Please enter a valid coin amount.');</script>";
        $noError = false;
    } else {
        echo "<script>$('#price_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }

    $img_error = uploadImage("../img/", $image, 1);
    if (empty($image)) {
        echo "<script>$('#image_error').html('<i class=\"bx bx-error-circle\"></i>Image is required.');</script>";
        $noError = false;
    } else if (!empty($img_error)) {
        echo "<script>$('#image_error').html('<i class=\"bx bx-error-circle\"></i> $img_error');</script>";
        $noError = false;
    } else {
        echo "<script>$('#image_error').html('Selected image: $image');</script>";
        // echo "<script>$('#image_error').html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');</script>";
    }
    
    //account creation successful
    if ($noError === true) {
        $pic_name = ucwords(trim($pic_name));
        $pic_series = ucwords(trim($pic_series));
        $pic_price = trim($pic_price);
        $image = trim($image);
        $date_created = date('Y-m-d H:i:s');
        // echo "Form submitted successfully!";
        $pic_query = "INSERT INTO `game_pic` (`name`, `category`, `price`, `image`, `date_created`) 
        VALUES ('$pic_name', '$pic_series', '$pic_price', '$image', '$date_created')";
        if(mysqli_query($connection, $pic_query)){
            uploadImage("../img/", $image, 2);
            mysqli_close($connection);
            echo "<script>window.location.reload();</script>";
        }
    } else {
        mysqli_close($connection);
    }
}

?>