<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) { 
    
    if (isset($_GET['delete']) && isset($_GET['id'])) {
        include "db_conn.php";
        $mat_id = $_GET['id'];
        $dlt_query = "DELETE FROM `learn_mat` WHERE `mat_id`='$mat_id'";
        $dlt_content_query = "DELETE FROM `mat_content` WHERE `mat_id`='$mat_id'";
        
        if (mysqli_query($connection, $dlt_content_query)){
            if (mysqli_query($connection, $dlt_query)){
                header("Location: ../php/a_mat_view.php");
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
    <title>Learning Material</title>

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
        document.getElementById('searchName').addEventListener('input', filterTable);
        document.getElementById('category').addEventListener('change', filterTable);
        document.getElementById('level').addEventListener('change', filterTable);

        function filterTable() {
            const filterName = document.getElementById('searchName').value.toLowerCase();
            const filterCategory = document.getElementById('category').value.toLowerCase();
            const filterLevel = document.getElementById('level').value.toLowerCase();
            const rows = document.querySelectorAll('#matTableBody tr');
            let count = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const title = cells[1].textContent.toLowerCase();
                const category = cells[2].textContent.toLowerCase();
                const level = cells[3].textContent.toLowerCase();

                const matchesName = !filterName || title.startsWith(filterName);
                const matchesCategory = filterCategory === "all" || category === filterCategory;
                const matchesLevel = filterLevel === "all" || level === filterLevel;

                if (matchesName && matchesCategory && matchesLevel) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('resultCount').textContent = `Total Result(s): ${count}`;
        }

        $(document).ready(function(){
            $("#addMatForm").submit(function(e){
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "add_mat_process.php",
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
                    <span><i class="bx bx-book-content"></i>Learning Material</span>
                    <button class="create-btn">Create</button>
                </div>
                <div class="search">
                    <div class="search-bar">

                        <!-- search name -->
                        <span class="search-bar-text">Search:</span>
                        <input type="text" placeholder="Search name" id="searchName" />

                        <!-- filter -->
                        <span class="search-bar-text">Filter:</span>
                        <select class="cat-filter" name="category" id="category">
                            <option value="all">All category</option>
                            <option value="Storyboard Reading">Storyboard Reading</option>
                            <option value="Paragraph Reading">Paragraph Reading</option>
                            <option value="Simple Flash card">Simple Flash card</option>
                            <option value="Advanced Flash card">Advanced Flash card</option>
                        </select>

                        <select class="lvl-filter" name="level" id="level">
                            <option value="all">All level</option>
                            <option value="12 years old and above">12 years old and above</option>
                            <option value="16 years old and above">16 years old and above</option>
                            <option value="21 years old and above">21 years old and above</option>
                            <option value="31 years old and above">31 years old and above</option>
                        </select>

                    </div>

                    <?php 
                        include "db_conn.php";
                        $mat_query = "SELECT COUNT(*) FROM `learn_mat`";
                        $mat_total = mysqli_query($connection, $mat_query);
                        $mat_row = mysqli_fetch_array($mat_total);
                        $total_mat = $mat_row[0];
                        mysqli_close($connection);
                        ?>

                    <!-- how many result -->
                    <div class="search-result">
                        <span id="resultCount">Total Result(s) : <?php echo $total_mat; ?></span>
                    </div>

                </div>
                <div class="table-data">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Total Content</th>
                                <th>Creation Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="matTableBody">

                            <!-- data here -->

                            <?php 
                                include "db_conn.php";
                                if ($_SESSION["role"] == "manager") {
                                    $query = "SELECT * FROM `learn_mat`";
                                } else {
                                    $query = "SELECT * FROM `learn_mat` WHERE `admin_id` = {$_SESSION['user_id']}";
                                }
                                $results = mysqli_query($connection, $query);
                                if (mysqli_num_rows($results) > 0){
                                    while ($row = mysqli_fetch_assoc($results)) {
                                        $timestamp = $row['date_created'];
                                        $date_created = date('d/m/Y', strtotime($timestamp));
                                        ?>
                            <tr>
                                <td><?php echo $row['mat_id'] ?></td>
                                <td><?php echo $row['title'] ?></td>
                                <td><?php echo $row['category'] ?></td>
                                <td><?php echo $row['level'] ?></td>
                                <?php
                                    //get content count
                                    $query_count = "SELECT COUNT(*) AS content_count FROM `mat_content` WHERE `mat_id` = {$row['mat_id']}";
                                    $count_result = mysqli_query($connection, $query_count);
                                    $count_data = mysqli_fetch_assoc($count_result);
                                    $count = $count_data['content_count'];
                                ?>
                                <td><?php echo $count ?></td>
                                <td><?php echo $date_created ?></td>
                                <td>
                                    <button onclick="window.location.href='a_add_mat.php?id=<?php echo $row['mat_id']; ?>'" class="view-btn">
                                        <i class="bx bxs-pencil"></i>
                                    </button>
                                    <button class="dlt-btn" onclick="window.location.href='?delete=1&id=<?php echo $row['mat_id']; ?>'">
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
                                Create New Learning Material
                            </span>
                        </div>

                        <div class="details">
                            <form id="addMatForm" method="post">
                                <div class="form-col">
                                    <div class="form-grp">
                                        <label for="title">Title</label>
                                        <input type="text" name="txtTitle" id="title" placeholder="Title"
                                            class="input-title"/>
                                        <div class="error">
                                            <span class="errorMsg" id="title_error"><div class="erroricon"><i class="bx bx-error-circle"></i></div></span>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-grp marr">

                                            <label for="type">
                                                Select Type
                                            </label>
                                            <select name="txtType" id="type" class="input-type">
                                                <option value="Storyboard Reading">Storyboard Reading</option>
                                                <option value="Paragraph Reading">Paragraph Reading</option>
                                                <option value="Simple Flash Card">Simple Flash Card</option>
                                                <option value="Advanced Flash Card">Advanced Flash Card</option>
                                            </select>

                                        </div>
                                        <div class="form-grp">

                                            <label for="level">
                                                Select Level
                                            </label>
                                            <select name="txtLevel" id="level" class="input-type">
                                                <option value="12 years old and above">
                                                    12 years old and above
                                                </option>
                                                <option value="16 years old and above">
                                                    16 years old and above
                                                </option>
                                                <option value="21 years old and above">
                                                    21 years old and above
                                                </option>
                                                <option value="31 years old and above">
                                                    31 years old and above
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <input type="submit" value="Create" class="submit" />
                                <div id="result"></div>
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