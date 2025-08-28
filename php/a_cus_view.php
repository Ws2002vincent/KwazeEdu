<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) { 
    
    if (isset($_GET['delete']) && isset($_GET['id'])) {
        include "db_conn.php";
        $user_id = $_GET['id'];
        $dlt_user_query = "DELETE FROM `user` WHERE `user_id`='$user_id'";
        if (mysqli_query($connection, $dlt_user_query)){
            header("Location: ../php/a_cus_view.php");
            exit();
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
    <title>User Information</title>

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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTableBody tr');
            let count = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const [id, username] = [cells[0].textContent.toLowerCase(), cells[1].textContent
                    .toLowerCase()
                ];

                if (id.startsWith(filter) || username.startsWith(filter)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('resultCount').textContent = `Total Result(s) : ${count}`;
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
                    <span><i class='bx bxs-user-detail'></i>User Information</span>
                </div>
                <div class="search">
                    <div class="search-bar">

                        <!-- search name -->
                        <span class="search-bar-text">Search:</span>
                        <input type="text" id="searchInput" placeholder="Search ID or Username" />

                    </div>

                    <?php 
                        include "db_conn.php";
                        $user_query = "SELECT COUNT(*) FROM `user`";
                        $user_total = mysqli_query($connection, $user_query);
                        $user_row = mysqli_fetch_array($user_total);
                        $total_users = $user_row[0];
                        mysqli_close($connection);
                        ?>

                    <!-- how many result -->
                    <div class="search-result">
                        <span id="resultCount">Total Result(s) : <?php echo $total_users; ?></span>
                    </div>

                </div>
                <div class="table-data">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Country</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">

                            <!-- data here -->
                            <!-- SQL queries -->
                            <?php 
                                include "db_conn.php";
                                $query = "SELECT * FROM `user`";
                                $results = mysqli_query($connection, $query);
                                if (mysqli_num_rows($results) > 0){
                                    while ($row = mysqli_fetch_assoc($results)) {
                                        ?>
                            <tr>
                                <td><?php echo $row['user_id'] ?></td>
                                <td><?php echo $row['username'] ?></td>
                                <td><?php echo $row['first_name'] ?></td>
                                <td><?php echo $row['last_name'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['password'] ?></td>
                                <td><?php echo $row['country'] ?></td>
                                <td>
                                    <a target="_blank">
                                        <button onclick="window.location.href='a_cus_details.php?id=<?php echo $row['user_id']; ?>'" class="view-btn">
                                            <i class='bx bxs-navigation'></i>
                                        </button>
                                    </a>
                                    <button class="dlt-btn" onclick="window.location.href='?delete=1&id=<?php echo $row['user_id']; ?>'">
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




                <!-- view Customer Card Popup -->

                <!-- <div class="pop-up-overlay" style="display: none">
                    <div class="pop-up-view">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>

                        <div class="profile-header">
                            <div class="top-section">
                                <div class="profile-header-left">
                                    <img src="../img/profile_chunkeat.jpg" alt="" />
                                </div>
                                <div class="profile-header-right">
                                    <span class="profile-name">Abu Becker</span>
                                    <span class="profile-username">@abubeckkker</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-details">
                            <div class="bottom-section">
                                <div class="label-con">
                                    <span class="label">Email: </span>
                                    <span class="label">Country: </span>
                                </div>
                                <div class="data-con">
                                    <span class="data">
                                        AbuAbu@gmail.com
                                    </span>
                                    <span class="data">Malaysia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->



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