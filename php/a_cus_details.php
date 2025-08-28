<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")){ 
    
    $user_id = $_GET['id'];

    include 'db_conn.php';
    $query = "SELECT * FROM `user` WHERE `user_id`= '$user_id'";
    $results = mysqli_query($connection, $query);

    //retrieve user data
    $row = mysqli_fetch_assoc($results);
    $user_first_name = $row['first_name'];
    $user_last_name = $row['last_name'];
    $user_username = $row['username'];
    $user_email = $row['email'];
    $user_country = $row['country'];
    $user_gamepic = $row['user_pfp'];
    $user_coins = $row['user_coins'];

    $query_pfp = "SELECT game_pic.image 
                FROM game_pic 
                JOIN user_pic ON game_pic.pic_id = user_pic.pic_id 
                WHERE user_pic.user_id = '$user_id' AND user_pic.pic_id = '$user_gamepic'";
    $pfp_result = mysqli_query($connection, $query_pfp);
    $pfp_row = mysqli_fetch_assoc($pfp_result);
    $user_profilepic = isset($pfp_row) && $pfp_row['image'] ? $pfp_row['image'] : "default_profile.jpg";
    
    //close database connnection
    mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Details</title>

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
    <link rel="stylesheet" href="../css/a_cus_details.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchNameIDInput = document.getElementById('searchIDName');
            const categorySelect = document.getElementById('category');
            const rangeSelect = document.getElementById('date');

            searchNameIDInput.addEventListener('input', filterTable);
            categorySelect.addEventListener('change', filterTable);
            rangeSelect.addEventListener('change', filterTable);

            function filterTable() {
                const filter = searchNameIDInput.value.toLowerCase();
                const filterCategory = categorySelect.value.toLowerCase();
                const filterRange = rangeSelect.value.toLowerCase();
                const rows = document.querySelectorAll('#historyTableBody tr');
                let count = 0;

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const id = cells[1].textContent.toLowerCase();
                    const category = cells[2].textContent.toLowerCase();
                    const title = cells[3].textContent.toLowerCase();
                    const dateText = cells[0].textContent;
                    const date = new Date(dateText.split('/').reverse().join('-'));

                    const matchesName = id.startsWith(filter) || title.startsWith(filter);
                    const matchesCategory = filterCategory === "all" || category === filterCategory;

                    const now = new Date();
                    let matchesRange = false;
                    switch (filterRange) {
                        case "all":
                            matchesRange = true;
                            break;
                        case "past7days":
                            matchesRange = (now - date) / (1000 * 60 * 60 * 24) <= 7;
                            break;
                        case "past30days":
                            matchesRange = (now - date) / (1000 * 60 * 60 * 24) <= 30;
                            break;
                        case "past3months":
                            matchesRange = (now - date) / (1000 * 60 * 60 * 24 * 30) <= 3;
                            break;
                        case "past6months":
                            matchesRange = (now - date) / (1000 * 60 * 60 * 24 * 30) <= 6;
                            break;
                    }

                    if (matchesName && matchesCategory && matchesRange) {
                        row.style.display = '';
                        count++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('resultCount').textContent = `Total Result(s): ${count}`;
            }
        });
    </script>


</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_Admin.php'; ?>

    <!-- Main content -->
    <div class="uni-wrapper">
        <div class="container flex-row">
            <div class="content-container flex-row">
                <div class="profile-wrapper">
                    <div class="profile-details-header">
                        <span>Customer Details</span>
                    </div>
                    <div class="profile-pic">
                        <img src="../img/<?php echo $user_profilepic; ?>" alt="" />
                    </div>

                    <div class="profile-details-body">
                        <div class="details-grp">
                            <div class="details-label">Username</div>
                            <div class="details-data"><?php echo $user_username; ?></div>
                        </div>
                        <div class="details-grp">
                            <div class="details-label">Email</div>
                            <div class="details-data">
                                <?php echo $user_email; ?>
                            </div>
                        </div>
                        <div class="details-grp">
                            <div class="details-label">First Name</div>
                            <div class="details-data"><?php echo $user_first_name; ?></div>
                        </div>
                        <div class="details-grp">
                            <div class="details-label">Last Name</div>
                            <div class="details-data"><?php echo $user_last_name; ?></div>
                        </div>
                        <div class="details-grp">
                            <div class="details-label">Country</div>
                            <div class="details-data"><?php echo $user_country; ?></div>
                        </div>
                        <div class="details-grp">
                            <div class="details-label">Total Coin</div>
                            <div class="details-data"><?php echo $user_coins; ?></div>
                        </div>
                    </div>
                </div>

                <!-- history -->
                <div class="history-wrapper">
                    <div class="history-details">
                        <div class="history-details-header">
                            <span>History Tracker</span>
                        </div>
                        <div class="history-details-body">
                            <div class="search">
                                <div class="search-bar">
                                    <span class="search-bar-text">Search:</span>
                                    <input type="text" placeholder="Search ID or title" id="searchIDName" />
                                    <span class="search-bar-text">Filter:</span>
                                    <select class="cat-filter" name="category" id="category">
                                        <option value="all">
                                            All category
                                        </option>
                                        <option value="Practice Quiz">
                                            Practice Quizzes
                                        </option>
                                        <option value="Ranked Quiz">
                                            Ranked Quizzes
                                        </option>
                                    </select>
                                    <select class="lvl-filter" name="date" id="date">
                                        <option value="all">
                                            All time
                                        </option>
                                        <option value="past7days">
                                            Past 7 days
                                        </option>
                                        <option value="past30days">
                                            Past 30 days
                                        </option>
                                        <option value="past3months">
                                            Past 3 months
                                        </option>
                                        <option value="past6months">
                                            Past 6 months
                                        </option>
                                    </select>
                                </div>

                                <?php 
                                    include "db_conn.php";
                                    $history_query = "SELECT COUNT(*) FROM `user_result` WHERE `user_id` = '$user_id'";
                                    $history_total = mysqli_query($connection, $history_query);
                                    $history_row = mysqli_fetch_array($history_total);
                                    $total_history = $history_row[0];
                                    mysqli_close($connection);
                                ?>
                                <div class="search-result">
                                    <span id="resultCount">Total Result(s) : <?php echo $total_history; ?></span>
                                </div>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Completion Date</th>
                                        <th>Quiz ID</th>
                                        <th>Quiz Type</th>
                                        <th>Quiz Title</th>
                                        <th>Score</th>
                                        <th>Ranking</th>
                                        <th>Coin Earned</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">

                                <?php 
                                include "db_conn.php";
                                include "functions.php";
                                $query = "SELECT * FROM `user_result` WHERE `user_id` = '$user_id'";
                                $results = mysqli_query($connection, $query);
                                if (mysqli_num_rows($results) > 0){
                                    $testcount = 10;
                                    while ($row = mysqli_fetch_assoc($results)) {
                                        $time_completed = $row['date_completed'];
                                        $date_completed = date('d/m/Y', strtotime($time_completed));
                                        $quiz_type;
                                        if ($row['quiz_type'] == "rank") {
                                            $quiz_type = "Ranked Quiz";
                                        } else {
                                            $quiz_type = "Practice Quiz";
                                        }
                                        $quiz_id = $row['quiz_id'];
                                        $total_score = json_decode($row["score"], true)["total"];
                                        $date_query = "SELECT * FROM `quiz` WHERE `quiz_id` = '$quiz_id'";
                                        $date_result = mysqli_query($connection, $date_query);
                                        if (mysqli_num_rows($date_result) > 0) {
                                            $date_row = mysqli_fetch_assoc($date_result);
                                            $last_updated = $date_row['last_updated'];
                                            $user_rank = getUserRank($user_id, $last_updated, $quiz_id);
                                        } else {
                                            $user_rank = "--";
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $date_completed; ?></td>
                                            <td><?php echo $quiz_id; ?></td>
                                            <td><?php echo $quiz_type; ?></td>
                                            <td><?php echo $row['quiz_title']; ?></td>
                                            <td><?php echo $total_score; ?></td>
                                            <td><?php echo $user_rank; ?></td>
                                            <td><?php echo $row['coins_earned']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                mysqli_close($connection);
                                
                                ?>
                                </tbody>
                            </table>
                            <div class="end-data">
                                <span>No more records to display...</span>
                            </div>
                        </div>
                    </div>
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