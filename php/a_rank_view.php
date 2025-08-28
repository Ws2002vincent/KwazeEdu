<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) { 
    
    if (isset($_GET['delete']) && isset($_GET['id'])) {
        include "db_conn.php";
        $quiz_id = $_GET['id'];
        $dlt_query = "DELETE FROM `quiz` WHERE `quiz_id`='$quiz_id'";
        $dlt_question_query = "DELETE FROM `quiz_question` WHERE `quiz_id`='$quiz_id'";
        
        if (mysqli_query($connection, $dlt_question_query)){
            if (mysqli_query($connection, $dlt_query)){
                header("Location: ../php/a_rank_view.php");
                exit();
            } else {
                // echo "content deletion failed.";
            }
        } else {
            // echo "deletion failed.";
        }
        mysqli_close($connection);
    } 

    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ranked Quizzed</title>

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
    <link rel="stylesheet" href="../css/a_view.css" />

    <!-- JS -->
    <script src="../js/scroll_navbar.js"></script>
    <script src="../js/popup.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchNameInput = document.getElementById('searchName');
            const categorySelect = document.getElementById('category');
            const sortSelect = document.getElementById('sort');

            searchNameInput.addEventListener('input', filterTable);
            categorySelect.addEventListener('change', filterTable);
            sortSelect.addEventListener('change', filterTable);

            sortSelect.value = 'popular';
            filterTable(); //call this when page loads to filter popular by default

            function parseDate(dateString) {
                const [day, month, year] = dateString.split('/').map(Number);
                return new Date(year, month - 1, day);
            }

            function filterTable() {
                const filterName = searchNameInput.value.toLowerCase();
                const filterCategory = categorySelect.value.toLowerCase();
                const filterSort = sortSelect.value.toLowerCase();
                const rows = Array.from(document.querySelectorAll('#quizTableBody tr'));
                let count = 0;

                if (filterSort === 'popular') {
                    rows.sort((a, b) => {
                        const participationA = parseInt(a.cells[5].textContent, 10) || 0;
                        const participationB = parseInt(b.cells[5].textContent, 10) || 0;
                        return participationB - participationA;
                    });
                } else if (filterSort === 'latest') {
                    rows.sort((a, b) => {
                        const dateA = parseDate(a.cells[4].textContent);
                        const dateB = parseDate(b.cells[4].textContent);
                        return dateB - dateA;
                    });
                }

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const title = cells[1].textContent.toLowerCase();
                    const category = cells[6].textContent.toLowerCase();

                    const matchesName = !filterName || title.startsWith(filterName);
                    const matchesCategory = filterCategory === "all" || category === filterCategory;

                    if (matchesName && matchesCategory) {
                        row.style.display = '';
                        count++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('resultCount').textContent = `Total Result(s): ${count}`;

                const tableBody = document.getElementById('quizTableBody');
                rows.forEach(row => tableBody.appendChild(row));
            }

            $(document).ready(function(){
                $("#addRankForm").submit(function(e){
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: "add_quiz_process.php",
                        data: formData,
                        success: function(data){
                            $("#result").html(data);
                        }
                    });
                });
            });
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
                <div class="title-row">
                    <span><i class="bx bx-trophy"></i>Ranked Quizzes</span>
                    <button class="create-btn">Create</button>
                </div>
                <div class="search">
                    <div class="search-bar">

                        <!-- search name -->
                        <span class="search-bar-text">Search:</span>
                        <input type="text" placeholder="Search name" id="searchName"/>

                        <!-- filter -->
                        <span class="search-bar-text">Filter:</span>
                        <select class="cat-filter cat-filter-marr" name="category" id="category">
                            <option value="all">All category</option>
                            <option value="Multiple Choice Question">Multiple Choice Question</option>
                            <option value="True or False">True or False</option>
                            <option value="Fill in the Blank">Fill in the Blank</option>
                        </select>

                        <!-- sort -->
                        <span class="search-bar-text">Sort by:</span>
                        <select class="cat-filter" name="sort" id="sort">
                            <option value="popular">Most popular</option>
                            <option value="latest">Latest</option>
                        </select>

                    </div>

                    <?php 
                        include "db_conn.php";
                        $quiz_query = "SELECT COUNT(*) FROM `quiz` WHERE `type` = 'rank'";
                        $quiz_total = mysqli_query($connection, $quiz_query);
                        $quiz_row = mysqli_fetch_array($quiz_total);
                        $total_quiz = $quiz_row[0];
                        mysqli_close($connection);
                    ?>

                    <!-- how many result -->
                    <div class="search-result">
                        <span id="resultCount">Total Result(s) : <?php echo $total_quiz; ?></span>
                    </div>

                </div>
                <div class="table-data">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Total Content</th>
                                <th>Creation Date</th>
                                <th>Last Updated</th>
                                <th>Total Participation</th>
                                <th style="display: none;">Category</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="quizTableBody">

                            <!-- data here -->

                            <?php 
                                include "db_conn.php";
                                if ($_SESSION["role"] == "manager") {
                                    $query = "SELECT * FROM `quiz` WHERE `type` = 'rank'";
                                } else {
                                    $query = "SELECT * FROM `quiz` WHERE `admin_id` = {$_SESSION['user_id']} AND `type` = 'rank'";
                                }
                                $results = mysqli_query($connection, $query);
                                if (mysqli_num_rows($results) > 0){
                                    while ($row = mysqli_fetch_assoc($results)) {
                                        $timestamp = $row['date_created'];
                                        $timestamp_update = $row['last_updated'];
                                        $date_created = date('d/m/Y', strtotime($timestamp));
                                        $last_updated = date('d/m/Y', strtotime($timestamp_update));
                                        ?>
                            <tr>
                                <td><?php echo $row['quiz_id'] ?></td>
                                <td><?php echo $row['title'] ?></td>
                                <?php
                                    //get content count
                                    $query_count = "SELECT COUNT(*) AS question_count FROM `quiz_question` WHERE `quiz_id` = {$row['quiz_id']}";
                                    $count_result = mysqli_query($connection, $query_count);
                                    $count_data = mysqli_fetch_assoc($count_result);
                                    $count = $count_data['question_count'];
                                ?>
                                <td><?php echo $count ?></td>
                                <td><?php echo $date_created ?></td>
                                <td><?php echo $last_updated ?></td>
                                <?php
                                    //get participation count
                                    $query_count = "SELECT COUNT(*) AS part_count FROM `user_result` WHERE `quiz_id` = {$row['quiz_id']} AND `date_completed` > '$timestamp_update'";
                                    $count_result = mysqli_query($connection, $query_count);
                                    $count_data = mysqli_fetch_assoc($count_result);
                                    $part_count = $count_data['part_count'];
                                ?>
                                <td><?php echo $part_count; ?></td>
                                <td style="display: none;"><?php echo $row['category'] ?></td>
                                <td>
                                    <button onclick="window.location.href='a_leaderboard.php?id=<?php echo $row['quiz_id']; ?>'" class="view-btn">
                                        <i class='bx bxs-trophy'></i>
                                    </button>
                                    <button onclick="window.location.href='a_add_quiz.php?id=<?php echo $row['quiz_id']; ?>'" class="view-btn">
                                        <i class="bx bxs-pencil"></i>
                                    </button>
                                    <button class="dlt-btn" onclick="window.location.href='?delete=1&id=<?php echo $row['quiz_id']; ?>'">
                                        <i class="bx bxs-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                                    }
                                }
                                mysqli_close($connection);
                                
                                ?>

                            <!-- data end -->

                        </tbody>
                    </table>
                    <div class="end-data">
                        <span>No more records to display...</span>
                    </div>
                </div>




                <!-- Popup -->

                <div class="pop-up-overlay" style="display: none">
                    <div class="pop-up">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>

                        <div class="header">
                            <span class="pop-up-title">
                                Create New Ranked Quiz
                            </span>
                        </div>

                        <div class="details">
                            <form id="addRankForm" method="post">
                                <div class="form-col">

                                    <div class="form-grp-rank">
                                        <label for="title">Title</label>
                                        <input type="text" name="txtRankTitle" id="title" placeholder="Title"
                                            class="input-title" />
                                    </div>
                                    <div class="error">
                                        <span class="errorMsg" id="title_error"><div class="erroricon"><i class="bx bx-error-circle"></i></div></span>
                                    </div>

                                    <div class="form-grp-rank">

                                        <label for="type">
                                            Select Type
                                        </label>
                                        <select name="txtType" id="type" class="input-type">
                                            <option value="Multiple Choice Question">Multiple Choice Question</option>
                                            <option value="True or False">True or False</option>
                                            <option value="Fill in the Blank">Fill in the Blank</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="submit" value="Create" class="submit" /><div id="result"></div>
                            </form>
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
?>