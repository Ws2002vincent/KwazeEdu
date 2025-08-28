<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) {

    if (isset($_GET["toStore"])) {
        $_SESSION['pic_id'] = null;
        header("Location: ../php/a_store.php");
        exit();
    }

    include 'db_conn.php';


    $query = "SELECT * FROM `admin` WHERE `admin_id`= {$_SESSION['user_id']}";
    $results = mysqli_query($connection, $query);

    //retrieve admin data
    $row = mysqli_fetch_assoc($results);
    $admin_username = $row['username'];
    $admin_first_name = $row['first_name'];
    $admin_last_name = $row['last_name'];
    $admin_email = $row['email'];
    $admin_password = $row['password'];

    $user_query = "SELECT COUNT(*) FROM `user`";
    $user_total = mysqli_query($connection, $user_query);

    $admin_query = "SELECT COUNT(*) FROM `admin` WHERE `user_role`='admin'";
    $admin_total = mysqli_query($connection, $admin_query);

    //get user total count
    $user_row = mysqli_fetch_array($user_total);
    $total_users = $user_row[0];

    //get admin total count
    $admin_row = mysqli_fetch_array($admin_total);
    $total_admin = $admin_row[0];

    //close database connnection
    mysqli_close($connection);

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,200..900;1,200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Unbounded:wght@200..900&display=swap"
        rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet" />

    <!-- Icon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/adminDashboard.css" />
    <script>
    function redirectToStore() {
        window.location.href = "?toStore=true";
    }
    </script>
</head>

<body>
    <!-- Navigation bar -->
    <!-- Include navigational bar -->
    <?php include 'nav_Admin.php'; ?>

    <!-- Main content -->
    <div class="container">
        <div class="wrapper">
            <div class="grid-wrapper">
                <div class="box box-1">
                    <div class="box-content">
                        <span class="addressing">Welcome, <?php echo ucwords($_SESSION["role"]); ?></span>
                        <span class="name"><?php echo ucwords($admin_first_name . " " . $admin_last_name); ?></span>
                        <span class="username">@<?php echo $admin_username; ?></span>
                        <button onclick="window.location.href='../php/a_profile.php'">
                            View Profile
                        </button>
                    </div>
                </div>
                <div class="box box-2">
                    <a href="../php/a_cus_view.php">
                        <div class="box-content">
                            <span><i class="bx bxs-user"></i></span>
                            <span class="desc">User</span>
                            <span class="extra">total</span>
                            <span class="total"><?php echo $total_users; ?></span>
                        </div>
                    </a>
                </div>
                <div class="box box-3">
                    <div class="box-content">
                        <span class="title">
                            <i class="bx bx-store"></i>Gamified Store
                        </span>
                        <!-- <button onclick="redirectToStore()">Enter</button> -->
                        <button onclick="window.location.href='a_store.php'">Enter</button>
                    </div>
                </div>
                <div class="box box-4">
                    <a href="../php/a_mat_view.php">
                        <div class="box-content">
                            <span><i class="bx bx-book-content"></i></span>
                            <span class="desc">Learning Material</span>
                            <span class="extra">total</span>
                            <?php 
                                include "db_conn.php";
                                $mat_query = "SELECT COUNT(*) FROM `learn_mat`";
                                $mat_total = mysqli_query($connection, $mat_query);
                                $mat_row = mysqli_fetch_array($mat_total);
                                $total_mat = $mat_row[0];
                                mysqli_close($connection);
                            ?>
                            <span class="total"><?php echo $total_mat; ?></span>
                        </div>
                    </a>
                </div>
                <div class="box box-5">
                    <a href="../php/a_quiz_view.php">
                        <div class="box-content">
                            <span><i class="bx bx-test-tube"></i></span>
                            <span class="desc">Practice Quizzes</span>
                            <span class="extra">total</span>
                            <?php 
                                include "db_conn.php";
                                $quiz_query = "SELECT COUNT(*) FROM `quiz` WHERE `type` = 'prac'";
                                $quiz_total = mysqli_query($connection, $quiz_query);
                                $quiz_row = mysqli_fetch_array($quiz_total);
                                $total_quiz = $quiz_row[0];
                                mysqli_close($connection);
                            ?>
                            <span class="total"><?php echo $total_quiz; ?></span>
                        </div>
                    </a>
                </div>
                <div class="box box-6">
                    <a href="../php/a_rank_view.php">
                        <div class="box-content">
                            <div class="title-container">
                                <span class="title">
                                    <i class="bx bxs-hot"></i>
                                    <span>Most Popular</span>
                                    Ranked Quizzes
                                </span>
                            </div>
                            <div class="list-container">
                                <ul>
                                    <?php 
                                        include "db_conn.php";
                                        $query = "
                                            SELECT q.title
                                            FROM quiz q
                                            JOIN user_result ur ON q.quiz_id = ur.quiz_id AND q.type = 'rank'
                                            GROUP BY q.quiz_id, q.title
                                            ORDER BY COUNT(ur.quiz_id) DESC
                                            LIMIT 3
                                        ";
                                        $results = mysqli_query($connection, $query);
                                        if (mysqli_num_rows($results) > 0){
                                            $index = 1;
                                            while($row = mysqli_fetch_assoc($results)) {
                                                ?>
                                    <li>
                                        <span class="number"><?php echo $index; ?></span>
                                        <span class="name"><?php echo $row["title"]; ?></span>
                                    </li>
                                    <?php
                                                $index ++;
                                            }
                                        } else { ?>
                                    <li>
                                        <span class="number"></span>
                                        <span class="name">(There are no attempts yet)</span>
                                    </li>
                                    <?php }
                                        mysqli_close($connection);
                                    ?>
                                </ul>
                            </div>
                            <div class="extra"></div>
                        </div>
                    </a>
                </div>
                <div class="box box-7">
                    <div class="box-content">
                        <canvas id="myChart"></canvas>
                    </div>

                    <!-- Bar Chart Code -->
                    <!-- Chart.js -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <?php
                    // Database connection
                    $con = new mysqli('localhost', 'root', '', 'kwazeedu');
                    
                    // Check connection
                    if ($con->connect_error) {
                        die("Connection failed: " . $con->connect_error);
                    }
                    
                    // SQL query to get user count by country
                    $query = $con->query("
                        SELECT country AS Country, COUNT(*) AS count FROM USER GROUP BY country
                    ");
                    
                    // Arrays to store country names and counts
                    $country = [];
                    $count = [];

                    // Fetch data from the query result
                    foreach($query as $data) {
                        $country[] = $data['Country'];
                        $count[] = $data['count'];
                    }
                    
                    // Close the database connection
                    $con->close();
                    ?>

                    <script>
                    // Data for the chart
                    const labels = <?php echo json_encode($country); ?>;
                    const data = {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Users by Country',
                            data: <?php echo json_encode($count); ?>,
                            backgroundColor: [
                                '#DE581B',
                                '#E97B49',
                                '#252D45',
                                '#33384F'
                            ],
                            borderColor: [
                                '#DE581B',
                                '#E97B49',
                                '#252D45',
                                '#33384F'
                            ],
                            borderWidth: 1
                        }]
                    };

                    // Configuration for the chart
                    const config = {
                        type: 'bar',
                        data: data,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    };

                    // Get the context of the canvas element and render the chart
                    const ctx = document.getElementById('myChart').getContext('2d');
                    new Chart(ctx, config);
                    </script>
                </div>

                <div class="box box-8">
                    <!-- <p>Store Image Category</p> -->
                    <div class="box-content">
                        <canvas id="materialChart"></canvas>
                    </div>

                    <?php
                    // Database connection
                    $con = new mysqli('localhost', 'root', '', 'kwazeedu');
                    
                    // Check connection
                    if ($con->connect_error) {
                        die("Connection failed: " . $con->connect_error);
                    }
                    
                    // SQL query to get user count by country
                    $query = $con->query("
                        SELECT category AS Category, COUNT(*) AS count1 FROM game_pic GROUP BY category
                    ");
                    
                    // Arrays to store category names and counts
                    $category = [];
                    $count1 = [];

                    // Fetch data from the query result
                    foreach($query as $datas) {
                        $category[] = $datas['Category'];
                        $count1[] = $datas['count1'];
                    }
                    
                    // Close the database connection
                    $con->close();
                    ?>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                    // Colors for the chart
                    const colors = ['#E97B49', '#252D45', '#DE581B', '#33384F'];
                    // const colors = ['#252D45', '#E97B49', '#33384F', '#DE581B'];
                    const backgroundColor = <?php echo json_encode($category); ?>.map((_, i) => colors[i % colors
                        .length]);
                    const hoverBackgroundColor = <?php echo json_encode($category); ?>.map((_, i) => colors[(i + 1) %
                        colors.length]);

                    // Data for the doughnut chart
                    const doughnutdata = {
                        labels: <?php echo json_encode($category); ?>,
                        datasets: [{
                            label: 'Number of Images by Category',
                            data: <?php echo json_encode($count1); ?>,
                            backgroundColor: backgroundColor,
                            hoverBackgroundColor: hoverBackgroundColor,
                        }]
                    };

                    // Options for the doughnut chart
                    const doughnutoptions = {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "top",
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return (
                                            tooltipItem.label +
                                            ": " +
                                            tooltipItem.raw
                                        );
                                    },
                                },
                            },
                        },
                    };

                    // Create the doughnut chart
                    const doughnutctx = document
                        .getElementById("materialChart")
                        .getContext("2d");
                    const materialChart = new Chart(doughnutctx, {
                        type: "doughnut",
                        data: doughnutdata,
                        options: doughnutoptions,
                    });
                    </script>
                </div>

                <div class="box box-9">
                    <a href="../php/terms_service.php">
                        <div class="box-content">
                            <span><i class="bx bx-file"></i></span>
                            <span class="desc">Terms of Service</span>
                        </div>
                    </a>
                </div>
                <div class="box box-10">
                    <a href="../php/privacy_policy.php">
                        <div class="box-content">
                            <span><i class="bx bx-file"></i></span>
                            <span class="desc">Privacy Policy</span>
                        </div>
                    </a>
                </div>
                <div class="box box-11">
                    <a href="../php/a_admin_view.php">
                        <div class="box-content">
                            <span><i class="bx bxs-user-detail"></i></span>
                            <span class="desc">Admin</span>
                            <span class="extra">total</span>
                            <span class="total"><?php echo $total_admin; ?></span>
                        </div>
                    </a>
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