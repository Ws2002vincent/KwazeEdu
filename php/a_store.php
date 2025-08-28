<?php
session_start();

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager")) {

    // $name_error = "Unknown name.";
    $name_error = '<div class="erroricon"><i class="bx bx-error-circle"></i><span></span></div>';
    $price_error = '<div class="erroricon"><i class="bx bx-error-circle"></i><span></span></div>';

    if(isset($_POST["btnDelete"])){
        include "db_conn.php";
        $pic_id = trim($_POST["txtPfpId"]);
        $pic_image = trim($_POST["txtPfpImage"]);
        $dlt_query = "DELETE FROM `game_pic` WHERE `pic_id`='$pic_id'";
        if (mysqli_query($connection, $dlt_query)){
            $_SESSION['pic_id'] = null;
            $file_to_delete = "../img/" . $pic_image;
            if (file_exists($file_to_delete)) {
                if (unlink($file_to_delete)) {
                    echo "File " . $_POST["delete_image"] . " has been deleted successfully.";
                } else {
                    echo "Sorry, there was an error deleting the file.";
                }
            } else {
                echo "File does not exist.";
            }
            mysqli_close($connection);
            header("Location: ../php/a_store.php"); 
            exit();
        }else{
            mysqli_close($connection);
        }
    }

    if (isset($_POST["btnSavePfpInfo"])) {
        
        include "db_conn.php";
        //obtain user input
        $pic_id = trim($_POST["txtPfpId"]);
        $pic_name = trim($_POST["txtPfpName"]);
        $pic_price = trim($_POST["txtPfpPrice"]);
        $noError = true;

        if (!preg_match("/^[a-zA-Z ]*$/", $pic_name)){
            $name_error = '<i class="bx bx-error-circle"></i>' . "<span>Name should contain letters and whitespaces only.</span>";
            $noError = false;
        }

        if (filter_var($pic_price, FILTER_VALIDATE_INT) === false) {
            $price_error = '<i class="bx bx-error-circle"></i>' . "<span>Please enter a valid coin amount.</span>";
            $noError = false;
        }

        if ($noError === true) {
            $_SESSION['pic_id'] = $pic_id;
            $pic_query = "  UPDATE `game_pic`
                            SET `name` = '$pic_name',
                                `price` = '$pic_price'
                            WHERE `pic_id` = '$pic_id'";
            if(mysqli_query($connection, $pic_query)){
                mysqli_close($connection);
                header("Location: ../php/a_store.php"); 
                exit();
            }
        }

        //close database connnection
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
    <link rel="stylesheet" href="../css/c_store.css" />

    <!-- JS -->
    <script src="../js/popup.js"></script>
    <script src="../js/scroll_navbar.js"></script>
    <script src="../js/store_selected.js"></script>
    <script src="../js/preview_img.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        $("#addPicForm").submit(function(e) {
            e.preventDefault();

            // Create a new FormData object
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "add_pic_process.php",
                data: formData,
                processData: false, //important for file upload
                contentType: false,
                success: function(data) {
                    if (data) {
                        $("#result").html(data);
                    }
                }
            });
        });

        $("#addPicForm").on('reset', function() {
            $(".errorMsg").html('<div class="erroricon"><i class="bx bx-error-circle"></i></div>');
        });
    });
    </script>
    <script>
    function checkInput() {
        const inputBox = document.getElementById('details-name');
        const inputBox2 = document.getElementById('details-coin');
        const submitButton = document.getElementById('btnSavePfpInfo');
        if (inputBox.value.trim() !== "" && inputBox2.value.trim() !== "") {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }
    </script>
</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_Admin.php'; ?>

    <!-- content -->
    <div class="uni-wrapper">
        <div class="container flex-row space-b">
            <div class=".content-container-store">
                <div class="title-row">
                    <span><i class="bx bxs-invader"></i>Gamified Store</span>
                    <button class="create-btn">Add New Profile</button>
                </div>
                <div class="search">
                    <div class="search-bar">
                        <!-- search name -->
                        <span class="search-bar-text">Search:</span>
                        <input type="text" placeholder="Search ID or Name" id="searchIdName" />
                        <input type="text" placeholder="Search Series" id="searchSeries" />
                    </div>


                    <?php 
                        include "db_conn.php";
                        $pic_query = "SELECT COUNT(*) FROM `game_pic`";
                        $pic_total = mysqli_query($connection, $pic_query);
                        $pic_row = mysqli_fetch_array($pic_total);
                        $total_pics = $pic_row[0];
                        mysqli_close($connection);
                        ?>

                    <!-- how many result -->
                    <div class="search-result">
                        <span id="resultCount">Total Result(s) : <?php echo $total_pics; ?></span>
                    </div>

                </div>
                <div class="table-data">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Description Name</th>
                                <th>Series</th>
                                <th>Coin Needed</th>
                                <th>Creation Date</th>
                            </tr>
                        </thead>
                        <tbody id="picTableBody">
                            <!-- data here -->
                            <?php 
                                include "db_conn.php";
                                $query = "SELECT * FROM `game_pic`";
                                $results = mysqli_query($connection, $query);
                                if (mysqli_num_rows($results) > 0){
                                    while ($row = mysqli_fetch_assoc($results)) {
                                    $timestamp = $row['date_created'];
                                    $date_created = date('d/m/Y', strtotime($timestamp));
                                    ?>

                            <tr data-id="<?php echo $row['pic_id'] ?>" data-name="<?php echo $row['name'] ?>"
                                data-series="<?php echo $row['category'] ?>" data-coins="<?php echo $row['price'] ?>"
                                data-image="<?php echo $row['image'] ?>" data-date="<?php echo $date_created ?>">
                                <td><?php echo $row['pic_id'] ?></td>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['category'] ?></td>
                                <td><?php echo $row['price'] ?></td>
                                <td><?php echo $date_created ?></td>
                            </tr>
                            <?php
                                    }
                                }
                                
                                ?>

                            <!-- data end -->
                        </tbody>

                        <script>
                        // for showing the selected row of record
                        document.addEventListener("DOMContentLoaded", function() {
                            const detailId = document.getElementById("details-id");
                            const detailSeries = document.getElementById("details-series");
                            const detailName = document.getElementById("details-name");
                            const detailCoin = document.getElementById("details-coin");
                            const detailImage = document.getElementById("details-image");
                            const detailImageName = document.getElementById("details-imagename");
                            const rows = document.querySelectorAll(".table-data tbody tr");

                            // Initial selection of the first row
                            selectFirstVisibleRow();

                            rows.forEach((row) => {
                                row.addEventListener("click", function() {
                                    // Remove selected class from all rows
                                    rows.forEach((r) => r.classList.remove("selected-row"));

                                    // Add selected class to the clicked row
                                    row.classList.add("selected-row");

                                    // Call the function to update the profile details
                                    updateProfileDetails(row);
                                });
                            });

                            function updateProfileDetails(row) {
                                const id = row.getAttribute("data-id");
                                const name = row.getAttribute("data-name");
                                const series = row.getAttribute("data-series");
                                const coins = row.getAttribute("data-coins");
                                const image = row.getAttribute("data-image");

                                detailId.value = id;
                                detailSeries.innerHTML = series;
                                detailName.value = name;
                                detailCoin.value = coins;
                                detailImage.src = "../img/" + image;
                                detailImageName.value = image;
                            }

                            document.getElementById('searchIdName').addEventListener('input', filterTable);
                            document.getElementById('searchSeries').addEventListener('input', filterTable);

                            function filterTable() {
                                const filterIdName = document.getElementById('searchIdName').value
                                    .toLowerCase();
                                const filterSeries = document.getElementById('searchSeries').value
                                    .toLowerCase();
                                const rows = document.querySelectorAll('#picTableBody tr');
                                let count = 0;
                                let firstVisibleRow;

                                rows.forEach(row => {
                                    const cells = row.querySelectorAll('td');
                                    const [id, name, series] = [cells[0].textContent.toLowerCase(),
                                        cells[1].textContent.toLowerCase(), cells[2].textContent
                                        .toLowerCase()
                                    ];

                                    if ((id.startsWith(filterIdName) || name.startsWith(
                                            filterIdName)) && (series.startsWith(filterSeries))) {
                                        row.style.display = '';
                                        count++;
                                        if (!firstVisibleRow) {
                                            firstVisibleRow = row;
                                        }
                                    } else {
                                        row.style.display = 'none';
                                    }
                                });

                                if (firstVisibleRow) {
                                    // Remove selected class from all rows
                                    rows.forEach((r) => r.classList.remove("selected-row"));
                                    // Select the first visible row
                                    firstVisibleRow.classList.add("selected-row");
                                    updateProfileDetails(firstVisibleRow);
                                }

                                document.getElementById('resultCount').textContent =
                                    `Total Result(s) : ${count}`;
                            }

                            function selectFirstVisibleRow() {
                                const rows = document.querySelectorAll('#picTableBody tr');
                                let firstVisibleRow = null;

                                // Remove selected class from all rows
                                rows.forEach(row => row.classList.remove("selected-row"));

                                rows.forEach(row => {
                                    if (row.style.display !== 'none' && !firstVisibleRow) {
                                        firstVisibleRow = row;
                                    }
                                });

                                if (firstVisibleRow) {
                                    firstVisibleRow.classList.add("selected-row");
                                    updateProfileDetails(firstVisibleRow);
                                }
                            }

                            // Reset search inputs and display all rows with first row selected
                            document.getElementById('searchIdName').addEventListener('input', function() {
                                if (this.value === '') {
                                    rows.forEach(row => row.style.display = '');
                                    selectFirstVisibleRow();
                                }
                            });

                            document.getElementById('searchSeries').addEventListener('input', function() {
                                if (this.value === '') {
                                    rows.forEach(row => row.style.display = '');
                                    selectFirstVisibleRow();
                                }
                            });
                        });
                        </script>
                    </table>
                    <div class="end-data">
                        <span>No more records to display...</span>
                    </div>
                </div>
            </div>

            <div class="profile-details">
                <div class="profile-holder">
                    <?php
                    if (isset($_SESSION['pic_id']) && !is_null($_SESSION['pic_id'])) {
                        $query = "SELECT * FROM `game_pic` WHERE `pic_id` = '{$_SESSION['pic_id']}' LIMIT 1";
                    } else {
                        $query = "SELECT * FROM `game_pic` LIMIT 1";
                    }
                    $results = mysqli_query($connection, $query);

                    if (mysqli_num_rows($results) > 0) {
                        while ($row = mysqli_fetch_assoc($results)) {
                            //lazy to change, variable name should not be "first_" (meaning first row) by defaut anymore but...whatever
                            $first_id = $row['pic_id'];
                            $first_series = $row['category'];
                            $first_name = $row['name'];
                            $first_price = $row['price'];
                            $first_image = $row['image'];
                        }
                    }
                    mysqli_close($connection);
                    ?>
                    <img src="../img/<?php echo $first_image; ?>" alt="" id="details-image">
                </div>
                <form action="" method="post">
                    <div class="profile-details-data">
                        <input type="hidden" name="txtPfpImage" value="<?php echo $first_image ?>"
                            id="details-imagename" />
                        <input type="hidden" name="txtPfpId" value="<?php echo $first_id ?>" id="details-id" />
                        <div class="render_data" data-type="series">
                            <span class="label">Series</span>
                            <span class="data-fixed" id="details-series"><?php echo $first_series; ?></span>
                        </div>

                        <div class="render_data" data-type="name">
                            <span class="label">Description Name</span>

                            <!-- 如果是span的话，他的class是，data-fixed。如果是input，class是data -->
                            <!-- <span class="data-fixed">abubeckkker</span> -->
                            <input type="text" name="txtPfpName" class="data" value="<?php echo $first_name; ?>"
                                id="details-name" oninput="checkInput()" />
                            <div class="error">
                                <?php echo $name_error; ?>
                            </div>
                        </div>
                        <div class="render_data" data-type="coin">
                            <span class="label">Coin Needed</span>
                            <!-- <span class="data-fixed">13</span> -->
                            <input type="text" name="txtPfpPrice" class="data" value="<?php echo $first_price; ?>"
                                id="details-coin" oninput="checkInput()" />
                            <div class="error">
                                <?php echo $price_error; ?>
                            </div>
                        </div>
                        <div class="btn-container-store">
                            <!-- <div class="btn-edit-store">Edit</div> -->
                            <input type="submit" id="btnSavePfpInfo" class="btn-edit-store" value="Save"
                                name="btnSavePfpInfo" disabled />
                            <input class="btn-dlt-store" type="submit" value="Delete" name="btnDelete">

                            <!-- <div class="btn-dlt-store" id="styledDeleteBtn">Delete</div> -->
                        </div>
                    </div>
                </form>
            </div>

            <!-- popup -->

            <div class="pop-up-overlay" style="display: none" id="adminPopup">
                <div class="pop-up-admin pop-up-add-admin">
                    <div class="close-button">
                        <span class="close-btn">
                            <i class="bx bx-x"></i>
                        </span>
                    </div>

                    <div class="header">
                        <span class="pop-up-title">
                            Add New Gamified Profile
                        </span>
                    </div>

                    <div class="details">
                        <form id="addPicForm" method="post" enctype="multipart/form-data">
                            <div class="form-col">
                                <div class="render_data-upload">
                                    <input type="file" id="file" name="imgToUpload" />
                                    <label for="file">
                                        <span>Upload Image</span>
                                        <!-- Image preview -->
                                        <div class="image-preview" id="imagePreview">
                                            <img src="" alt="Image Preview" class="image-preview__image"
                                                style="display: none" />
                                            <div class="blur-layer"></div>
                                        </div>
                                    </label>
                                    <div class="error">
                                        <span class="errorMsg" id="image_error">
                                            <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                        </span>
                                    </div>
                                </div>
                                <div class="render_data-add">
                                    <span class="label">Description Name</span>
                                    <input type="text" class="data" name="txtPfpName" id="pfpname" placeholder="Name" />
                                    <div class="error">
                                        <span class="errorMsg" id="name_error">
                                            <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="render_data-row">
                                        <span class="label">Series</span>
                                        <!-- <input type="text" class="data" name="txtPfpSeries" id="pfpSeries"
                                            placeholder="Series" /> -->
                                        <input placeholder="Series" name="txtPfpSeries" list="all-series" id="series"
                                            class="data" name="series">
                                        <datalist id="all-series">
                                            <?php 
                                            include "db_conn.php";
                                            $query = "SELECT DISTINCT `category` FROM `game_pic`";
                                            $results = mysqli_query($connection, $query);
                                            if (mysqli_num_rows($results) > 0){
                                                while ($row = mysqli_fetch_assoc($results)){ ?>
                                            <option value=<?php echo $row['category']; ?>>
                                                <?php }}?>
                                        </datalist>
                                        <div class="error">
                                            <span class="errorMsg" id="series_error">
                                                <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="render_data-row">
                                        <span class="label">Coin Needed</span>
                                        <!-- <span class="data-fixed">13</span> -->
                                        <input type="text" class="data" name="txtPfpPrice" id="pfpPrice"
                                            placeholder="Coin needed" />
                                        <div class="error">
                                            <span class="errorMsg" id="price_error">
                                                <div class="erroricon"><i class="bx bx-error-circle"></i></div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-container-add">
                                <input type="reset" class="btn-clear-store" value="Clear" id="btnClear" />
                                <input type="submit" class="btn-edit-store" value="Create" />
                            </div>
                            <div id="result"></div>
                        </form>
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