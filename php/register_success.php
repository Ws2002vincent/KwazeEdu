<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Chivo", sans-serif;
    }

    body {
        background-color: #1c253b;
        background-image: url(../img/background.svg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .card {
        max-width: 450px;
        margin: 100px auto;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 0 60px #111a26;
        background-color: #f3f2ff;
    }

    .card a {
        text-decoration: none;
        color: #333;
        font-size: 25px;
        float: left;
    }

    .card-header {
        text-align: center;
        margin-top: 20px;
    }

    .card-header h1 {
        width: 110px;
        margin: auto;
        font-size: 28px;
        color: #333;
        border-bottom: 1.5px solid #33384f;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }

    .card-header span {
        font-size: 14px;
        line-height: 20px;
        color: #333;
    }

    .card-body {
        margin-top: 25px;
    }

    .submit {
        display: flex;
        flex-direction: column;
    }

    .submit a {
        text-decoration: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .submit button {
        width: 300px;
        margin: 10px auto;
        padding: 10px;
        border: 1px solid #33384f;
        border-radius: 20px;
        cursor: pointer;
        font-size: 15px;
        background-color: #1c253b;
        color: #f3f2ff;
        font-weight: 500px;
    }

    .submit button:hover {
        background-color: #343950;
    }
	
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            <h1>Hello!</h1>
            <span>Your account created successfully.</span>
            <br />
            <span>Go to Login now.</span>
        </div>
        <div class="card-body">
            <!-- submit button -->
            <div class="submit">
                <a href="../php/login.php"><button>Login Now</button></a>
            </div>
        </div>
    </div>
</body>

</html>

