<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["role"] == "manager") { 

    if (isset($_GET['delete']) && isset($_GET['id'])) {
        include "db_conn.php";
        $admin_id = $_GET['id'];
        $dlt_query = "DELETE FROM `admin` WHERE `admin_id`='$admin_id'";
        
        if (mysqli_query($connection, $dlt_query)){
            header("Location: ../php/a_admin_view.php");
            exit();
        } else {
            // echo "deletion failed.";
        }
        mysqli_close($connection);
    } ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Information</title>

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

        $(document).ready(function() {
            $("#addAdminForm").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "add_admin_process.php",
                    data: formData,
                    success: function(data) {
                        if (data) {
                            $("#result").html(data);
                        }
                    }
                });
            });
        });

        $("#addAdminForm").on('reset', function() {
            $(".errorMsg").html('<div class=\"erroricon\"><i class=\"bx bx-error-circle\"></i></div>');
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
                    <span><i class='bx bxs-group'></i>Admin Information</span>
                    <button class="create-btn">Add Admin</button>
                </div>
                <div class="search">
                    <div class="search-bar">

                        <!-- search name -->
                        <span class="search-bar-text">Search:</span>
                        <input type="text" placeholder="Search ID or Username" id="searchInput" />

                    </div>

                    <?php 
                        include "db_conn.php";
                        $admin_query = "SELECT COUNT(*) FROM `admin` WHERE `user_role`='admin'";
                        $admin_total = mysqli_query($connection, $admin_query);
                        $admin_row = mysqli_fetch_array($admin_total);
                        $total_admin = $admin_row[0];
                        mysqli_close($connection);
                        ?>

                    <!-- how many result -->
                    <div class="search-result">
                        <span id="resultCount">Total Result(s) : <?php echo $total_admin; ?></span>
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">

                            <!-- data here -->
                            <!-- SQL queries -->
                            <?php 
                                include "db_conn.php";
                                $query = "SELECT * FROM `admin` WHERE `user_role`='admin'";
                                $results = mysqli_query($connection, $query);
                                if (mysqli_num_rows($results) > 0){
                                    while ($row = mysqli_fetch_assoc($results)) {
                                        ?>
                            <tr>
                                <td><?php echo $row['admin_id'] ?></td>
                                <td><?php echo $row['username'] ?></td>
                                <td><?php echo $row['first_name'] ?></td>
                                <td><?php echo $row['last_name'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['password'] ?></td>
                                <td>
                                    <!-- <button class="view-btn">
                                        <i class='bx bxs-navigation'></i> </button> -->
                                    <button class="dlt-btn"
                                        onclick="window.location.href='?delete=1&id=<?php echo $row['admin_id']; ?>'">
                                        <i class="bx bxs-trash"></i>
                                    </button>
                                </td>
                            </tr><?php
                                    }
                                
                                } ?>

                            <!-- data end -->

                        </tbody>
                    </table>
                    <div class="end-data">
                        <span>No more records to display...</span>
                    </div>
                </div>


                <!-- pop up -->
                <div class="pop-up-overlay" style="display: none" id="adminPopup">
                    <div class="pop-up pop-up-add-admin">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>

                        <div class="header">
                            <span class="pop-up-title"> Add Admin </span>
                        </div>

                        <div class="details">
                            <form id="addAdminForm" method="post">
                                <div class="form-col">
                                    <div class="form-row">
                                        <div class="form-grp marr">
                                            <!-- first name -->
                                            <label for="name">First Name</label>
                                            <input type="text" name="txtFname" id="fname" placeholder="First Name"
                                                value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["fname"] : ''; ?>" />
                                            <div class="error">
                                                <span class="errorMsg" id="fname_error">
                                                    <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-grp">
                                            <!-- last name -->
                                            <label for="name">Last Name</label>
                                            <input type="text" name="txtLname" id="lname" placeholder="Last Name"
                                                value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["lname"] : ''; ?>" />
                                            <div class="error">
                                                <span class="errorMsg" id="lname_error">
                                                    <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- username -->
                                    <div class="form-grp">
                                        <label for="username">Username</label>
                                        <input type="text" name="txtUsername" id="username"
                                            placeholder="Create a Username"
                                            value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["username"] : ''; ?>" />
                                        <div class="error">
                                            <span class="errorMsg" id="username_error">
                                                <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <div class="form-grp">
                                        <label for="email">Email</label>
                                        <input type="text" name="txtEmail" id="email" placeholder="Email"
                                            value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["email"] : ''; ?>" />
                                        <div class="error">
                                            <span class="errorMsg" id="email_error">
                                                <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- password -->
                                    <div class="form-grp">
                                        <label for="pass">Password</label>
                                        <input type="password" name="txtPassword" id="password" placeholder="Password"
                                            value="<?php echo !empty($_SESSION['correctInput']) ? $_SESSION['correctInput']["password"] : ''; ?>" />
                                        <div class="error">
                                            <span class="errorMsg" id="psw_error">
                                                <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <input type="hidden" name="txtRole" value="admin">
                                </div>
                                <!-- <input type="reset" value="Clear" class="submit">
                                <input type="submit" value="Create" class="submit" /> -->

                                <div class="btn-container-add">
                                    <input type="reset" class="btn-clear-store" value="Clear" />
                                    <input type="submit" class="btn-edit-store" value="Create" />
                                </div>

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
} else if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin")){
    header ('Location: ../php/a_Dashboard.php');
    exit();
} else {
    header ('Location: ../php/login.php');
    exit();
}
?>