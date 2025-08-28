<?php
session_start();

if (isset($_SESSION["user_id"]) != null && $_SESSION["role"] == "user") { ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Practices Quizzes</title>

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
    <link rel="stylesheet" href="../css/c_view.css" />

    <!-- JS -->
    <script src="../js/filter.js"></script>
    <script src="../js/filter_sidebar.js"></script>
    <script src="../js/popup.js"></script>
    <script src="../js/scroll_navbar.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popUpButton = document.getElementById("pop-up-button");
            const resultCards = document.querySelectorAll(".result-card");
            const popUp = document.querySelector(".pop-up-overlay");
            const popUpTitle = document.querySelector(".pop-up-title");
            const popUpDate = document.querySelector(".pop-up-date");
            const popUpCategory = document.querySelector(".pop-up-category");
            const popUpLevel = document.querySelector(".pop-up-level");
            const popUpID = document.getElementById("pop-up-id");

            resultCards.forEach(card => {
                card.addEventListener("click", function() {
                    const id = card.getAttribute("data-id");
                    const title = card.getAttribute("data-title");
                    const category = card.getAttribute("data-category");
                    const level = card.getAttribute("data-level");
                    const date_created = card.getAttribute("data-date");
                    const last_updated = card.getAttribute("data-update");

                    popUpTitle.textContent = title;
                    popUpCategory.textContent = category;
                    popUpLevel.textContent = level;
                    popUpID.value = id;
                    const dateHTML = `${date_created}<span class="edited">Last edited: ${last_updated}</span>`;
                    popUpDate.innerHTML = dateHTML;

                    popUp.style.display = "flex";
                });
            });
            document.querySelectorAll('.close-btn').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    popUp.style.display = 'none';
                });
            });

            popUpButton.addEventListener("click", function() {
                const id = popUpID.value;
                window.location.href = 'c_quiz.php?id=' + id;
            });

            const filterTypeButtons = document.querySelectorAll('.filter-item');
            const filterLevelButtons = document.querySelectorAll('.sidebar-item button');

            filterTypeButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const filterType = button.dataset.filter || button.dataset.type;
                    const isCategoryFilter = true;

                    toggleActiveClass(button, filterTypeButtons);

                    filterResults();
                });
            });

            filterLevelButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const filterType = button.dataset.filter || button.dataset.type;
                    const isCategoryFilter = false;

                    toggleActiveClass(button, filterLevelButtons);

                    filterResults();
                });
            });

            function toggleActiveClass(clickedButton, buttons) {
                buttons.forEach(btn => btn.classList.remove('active'));
                clickedButton.classList.add('active');
            }

            function filterResults() {
                const selectedCategory = getSelectedFilter(filterTypeButtons);
                const selectedLevel = getSelectedFilter(filterLevelButtons);

                resultCards.forEach(card => {
                    const categoryMatch = selectedCategory === 'all' || card.dataset.category === selectedCategory;
                    const levelMatch = selectedLevel === 'all' || parseInt(card.dataset.level) == parseInt(selectedLevel);

                    if (categoryMatch && levelMatch) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            function getSelectedFilter(buttons) {
                const activeButton = Array.from(buttons).find(btn => btn.classList.contains('active'));
                return activeButton ? (activeButton.dataset.filter || activeButton.dataset.type) : 'all';
            }

            filterResults()
        });
    </script>

</head>

<body>
    <!-- Navigation bar -->
    <?php include 'nav_User.php'; ?>

    <!-- <div class="banner">
			<div class="container flex-center">
				<div class="advertiment">
					<span class="ad-desc">New feature Introduced</span>
					<span class="ad-title">
						<i class="bx bx-game"></i>Gamified Store</span
					>
					<button class="ad-btn">Enter</button>
				</div>
			</div>
		</div> -->

    <!-- sidebar -->

    <div class="sidebar-filter">
        <div class="container flex-row">
            <div class="content-container">
                <div class="sidebar">
                    <i class="bx bx-filter-alt"></i>
                    <ul class="sidebar-list">
                        <li class="sidebar-item active" data-filter="all">
                            <button data-filter="all">
                                <a href="#" class="sidebar-link">
                                    <span> All category </span>
                                </a>
                            </button>
                        </li>
                        <li class="sidebar-item" data-filter="12">
                            <button data-filter="12">
                                <a href="#" class="sidebar-link">
                                    <span> 12 years old </span>
                                    <br />
                                    <span> and above </span>
                                </a>
                            </button>
                        </li>
                        <li class="sidebar-item" data-filter="16">
                            <button data-filter="16">
                                <a href="#" class="sidebar-link">
                                    <span> 16 years old </span>
                                    <br />
                                    <span> and above </span>
                                </a>
                            </button>
                        </li>
                        <li class="sidebar-item" data-filter="21">
                            <button data-filter="21">
                                <a href="#" class="sidebar-link">
                                    <span> 21 years old </span>
                                    <br />
                                    <span> and above </span>
                                </a>
                            </button>
                        </li>
                        <li class="sidebar-item" data-filter="31">
                            <button data-filter="31">
                                <a href="#" class="sidebar-link">
                                    <span> 31 years old </span>
                                    <br />
                                    <span> and above </span>
                                </a>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="main-content">
                    <div class="explore-title">
                        <span>
                            <i class="bx bx-test-tube"></i>Practice Quizzes - Test yourself
                        </span>
                    </div>
                    <div class="filter">
                        <ul class="filter-list">
                            <li data-type="all">
                                <button class="filter-item active" data-type="all">
                                    All types
                                </button>
                            </li>
                            <li data-type="Multiple Choice Question">
                                <button class="filter-item" data-type="Multiple Choice Question">Multiple Choice Question</button>
                            </li>
                            <li data-type="True or False">
                                <button class="filter-item" data-type="True or False">True or False</button>
                            </li>
                            <li data-type="Fill in the Blank">
                                <button class="filter-item" data-type="Fill in the Blank">Fill in the Blank</button>
                            </li>
                        </ul>
                    </div>

                    <!-- if got result -->

                    <div class="result-yes">
                        <div class="result-list">
                        <?php 
                        include "db_conn.php";
                        $query = "SELECT * FROM `quiz` WHERE `type` = 'prac'";
                        $results = mysqli_query($connection, $query);
                        if (mysqli_num_rows($results) > 0) {
                            while ($row = mysqli_fetch_assoc($results)) {
                                $timestamp_date = $row['date_created'];
                                $date_created = date('d/m/Y', strtotime($timestamp_date));
                                $timestamp_updated = $row['last_updated'];
                                $last_updated = date('d/m/Y', strtotime($timestamp_updated));
                                ?>
                            <div class="result-card" data-id="<?php echo htmlspecialchars($row['quiz_id']); ?>"
                                data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                data-level="<?php echo htmlspecialchars($row['level']); ?>"
                                data-date="<?php echo htmlspecialchars($date_created); ?>"
                                data-update="<?php echo htmlspecialchars($last_updated); ?>">
                                <div class="card-banner">
                                    <span><i class='bx bx-bookmark-alt'></i></span>
                                    <span class="card-type"><?php echo htmlspecialchars($row['category']); ?></span>
                                </div>
                                <div class="card-content">
                                    <div class="card-title">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </div>
                                    <div class="card-category">
                                        Practice Quizzes
                                    </div>
                                    <div class="card-level">
                                        <?php echo htmlspecialchars($row['level']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                        }
                        mysqli_close($connection);
                        ?>
                        </div>
                    </div>

                    <!-- if no result -->

                    <!-- <div class="result-no">
                        <div class="result-list-no">
                            <span><i class="bx bxs-binoculars"></i></span>
                            <span>Oops, nothing here yet...</span>
                            <span>Try and look for another.</span>
                        </div>
                    </div> -->
                </div>




                <!-- pop-up -->

                <div class="pop-up-overlay" style="display: none">
                    <div class="pop-up">
                        <div class="close-button">
                            <span class="close-btn">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>
                        <div class="header">
                            <span class="pop-up-type">
                                Practice Quizzes
                            </span>
                            <span class="pop-up-title">
                            </span>
                        </div>
                        <div class="details">
                            <span class="pop-up-details">
                                Creation date:
                            </span>
                            <span class="pop-up-date" id="pop-up-date"></span>
                                <span class="edited"></span>
                                <span class="edited-ans"></span>
                            </span>
                            <span class="pop-up-details"> Type: </span>
                            <span class="pop-up-category" id="pop-up-category"></span>
                            <span class="pop-up-details"> Level: </span>
                            <span class="pop-up-level" id="pop-up-level"></span>
                        </div>
                        <input type="hidden" id="pop-up-id" value="">
                        <div class="action">
                            <button class="pop-up-btn" id="pop-up-button">
                                Attempt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
} else if (isset($_SESSION["user_id"]) && ($_SESSION["role"] == "admin")){
    header ('Location: ../php/a_Dashboard.php');
    exit();
} else {
    header ('Location: ../php/login.php');
    exit();
}
?>