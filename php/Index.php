<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KwazeEdu</title>

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
    <link rel="stylesheet" href="../css/Index.css" />

    <!-- Js -->
    <!-- <script src="../js/scroll_Index.js" defer></script> -->
    <script src="../js/scroll_navbar.js" defer></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all nav items and sections
        const navItems = document.querySelectorAll(".nav-list li a");
        const sections = document.querySelectorAll("section[id]");

        // Set "overview" as the default active item
        const defaultActiveItem = document.querySelector(".nav-list li a[href*='overview']");
        if (defaultActiveItem) {
            defaultActiveItem.classList.add("active");
        }

        // Add click event listener to each nav item
        navItems.forEach(item => {
            item.addEventListener("click", function(event) {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Remove active class from all nav items
                navItems.forEach(nav => nav.classList.remove("active"));

                // Add active class to the clicked item
                this.classList.add("active");

                // Scroll to the section
                const sectionId = this.getAttribute("href").split("#")[1];
                const section = document.getElementById(sectionId);
                if (section) {
                    section.scrollIntoView({
                        behavior: "smooth"
                    });
                }
            });
        });

        // Create an IntersectionObserver
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                const navItem = document.querySelector(
                    `.nav-list li a[href*=${entry.target.id}]`);
                if (entry.isIntersecting) {
                    // Remove active class from all nav items
                    navItems.forEach(nav => nav.classList.remove("active"));
                    // Add active class to the intersecting section's nav item
                    navItem.classList.add("active");
                }
            });
        }, {
            threshold: 0.5
        });

        // Observe each section
        sections.forEach(section => {
            observer.observe(section);
        });
    });
    </script>
</head>

<body>
    <!-- Navigation bar -->
    <nav class="no-shadow">
        <div class="container nav-flex">
            <div class="logo">
                <a href="#">
                    <span>Kwaze</span>
                    <span class="orange">Edu</span>
                    <span class="dot">.</span>
                </a>
            </div>
            <ul class="nav-list">
                <li><a href="../php/Index.php#overview">Overview</a></li>
                <li><a href="../php/Index.php#service">Our Service</a></li>
                <li><a href="../php/Index.php#feature">Feature</a></li>
                <li><a href="../php/Index.php#about">About Us</a></li>
            </ul>
            <div class="login">
                <a href="../php/login.php">
                    <button>Login <i class="bx bx-log-in"></i></button>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->

    <section class="hero" id="overview">
        <div class="container hero-flex">
            <div class="hero-info">
                <h1>Dyslexia-Friendly <br />Learning Environment</h1>
                <p>
                    We provide the best online learning experience for you.
                </p>
                <p>Join us and learn from the best.</p>

                <!-- remember change to .php -->

                <button class="btn">
                    <a href="../php/login.php">
                        Get Started
                        <span> <i class="bx bx-right-arrow-alt"></i></span></a>
                </button>
            </div>
            <div class="object">
                <script type="module" src="https://unpkg.com/@splinetool/viewer@1.0.91/build/spline-viewer.js"></script>
                <spline-viewer url="https://prod.spline.design/DDyJY-G0-Jv9Jic6/scene.splinecode">
                </spline-viewer>
            </div>
        </div>
    </section>

    <!-- Banner Section -->

    <section class="banner" id="overview">
        <div class="container banner-flex">
            <div class="banner-info">
                <i class="bx bx-book"></i>
                <h2>Our Mission</h2>
                <p>
                    We aim to provide a dyslexia-friendly learning
                    environment for students to learn and grow.
                </p>
            </div>
            <div class="banner-info">
                <i class="bx bx-globe"></i>
                <h2>Our Vision</h2>
                <p>
                    We envision a world where everyone has access to quality
                    education.
                </p>
            </div>

            <div class="banner-info">
                <i class="bx bx-target-lock"></i>
                <h2>Our Goal</h2>
                <p>
                    We strive to provide the best online learning experience
                    for students.
                </p>
            </div>
        </div>
    </section>

    <!-- Service Section -->

    <section class="service" id="service">
        <div class="container service-flex">
            <h2>We Provided</h2>
            <div class="service-wrapper">
                <div class="service-card" id="learning-module-card">
                    <h3>Learning Module</h3>
                    <div class="label">Learn by yourself</div>
                    <span>Includes:</span>
                    <ul class="service-list">
                        <li><i class="bx bx-check-double"></i>Flash Card</li>
                        <li><i class="bx bx-check-double"></i>Spelling Support</li>
                        <li><i class="bx bx-check-double"></i>Storyboard Learning</li>
                    </ul>
                    <!-- remember change to .php -->
                    <a href="../php/login.php">
                        <button>Quick Start</button>
                    </a>
                </div>
                <div class="service-card" id="quiz-card">
                    <h3>Quiz</h3>
                    <div class="label">Test your self</div>
                    <span>Includes:</span>
                    <ul class="service-list">

                        <li>
                            <i class="bx bx-check-double"></i>True or False
                        </li>
                        <li>
                            <i class="bx bx-check-double"></i>Fill in the
                            blank
                        </li>
                        <li>
                            <i class="bx bx-check-double"></i>Multiple
                            Choice Question
                        </li>
                    </ul>
                    <!-- remember change to .php -->
                    <a href="../php/login.php">
                        <button>Quick Start</button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
    window.onload = function() {
        var quizCard = document.getElementById("quiz-card");
        var learningModuleCard = document.getElementById(
            "learning-module-card"
        );

        quizCard.addEventListener("mouseover", function() {
            learningModuleCard.classList.add("darken");
        });

        quizCard.addEventListener("mouseout", function() {
            learningModuleCard.classList.remove("darken");
        });

        var learningModuleCard = document.getElementById(
            "learning-module-card"
        );
        var quizCard = document.getElementById("quiz-card");

        learningModuleCard.addEventListener("mouseover", function() {
            quizCard.classList.add("darken");
        });

        learningModuleCard.addEventListener("mouseout", function() {
            quizCard.classList.remove("darken");
        });
    };
    </script>

    <!-- Benefits Section -->

    <section class="benefits" id="service">
        <div class="container benefits-flex">
            <div class="benefits-title">
                <h2>Benefits</h2>
                <p>
                    Wide range of benefits to enhance <br />your learning
                    experience.
                </p>
                <img src="../img/study.png" alt="Guy" />
            </div>

            <div class="benefits-wrapper">
                <div class="benefits-card">
                    <span class="num">1</span>
                    <h3>Structured Content</h3>
                    <p>
                        Eases the search for information with organized
                        learning materials.
                    </p>
                </div>
                <div class="benefits-card">
                    <span class="num">2</span>
                    <h3>Writing and Spelling Support</h3>
                    <p>
                        Helps improve writing and spelling skills with
                        targeted guides and templates.
                    </p>
                </div>
                <div class="benefits-card">
                    <span class="num">3</span>
                    <h3>Diverse Quizzes</h3>
                    <p>
                        Stimulates various cognitive skills through multiple
                        question formats.
                    </p>
                </div>
                <div class="benefits-card">
                    <span class="num">4</span>
                    <h3>Accessibility</h3>
                    <p>
                        Offers dyslexia-friendly settings to accommodate
                        unique learning needs.
                    </p>
                </div>
                <div class="benefits-card">
                    <span class="num">5</span>
                    <h3>Engagement</h3>
                    <p>
                        Encourages active learning with interactive elements
                        and achievement tracking.
                    </p>
                </div>
                <div class="benefits-card">
                    <span class="num">6</span>
                    <h3>Confidence Building</h3>
                    <p>
                        Creates an inclusive environment to enhance learner
                        self-esteem.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Section -->

    <section class="feature" id="feature">
        <div class="container feature-flex">
            <div class="feature-info">
                <h2>Features</h2>
                <p>
                    We provide a wide range of features to enhance your
                    learning experience.
                </p>
            </div>
            <div class="feature-wrapper">
                <div class="feature-col">
                    <div class="feature-row">
                        <div class="feature-card">
                            <i class="bx bx-book-content"></i>
                            <div class="shape"></div>
                            <span>Structured Content</span>
                        </div>
                        <div class="feature-card">
                            <i class="bx bxs-edit-alt"></i>
                            <div class="shape"></div>
                            <span>Writing and Spelling Support</span>
                        </div>
                        <div class="feature-card">
                            <i class="bx bx-question-mark"></i>
                            <div class="shape"></div>
                            <span>Variety of Question Formats</span>
                        </div>
                    </div>
                    <div class="feature-row">
                        <div class="feature-card">
                            <i class="bx bxs-flag-checkered"></i>
                            <div class="shape"></div>
                            <span>Engagement Features</span>
                        </div>
                        <div class="feature-card">
                            <i class="bx bxs-invader"></i>
                            <div class="shape"></div>
                            <span>Gamification System</span>
                        </div>
                        <div class="feature-card">
                            <i class="bx bx-wink-smile"></i>
                            <div class="shape"></div>
                            <span>User-Friendly Interface</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="add-feature-info">
                <h2>Color Scheme and Font Family</h2>
                <p>
                    We provide a dyslexia-friendly color scheme and font
                    family to enhance readability.
                </p>
            </div>
            <div class="add-feature-wrapper">
                <div class="add-feature-two">
                    <div class="add-feature-color">
                        <div class="color color1">
                            <span>#1C253B</span>
                        </div>
                        <div class="color color2">
                            <span>#33384F</span>
                        </div>
                        <div class="color color3">
                            <span>#8389B5</span>
                        </div>
                        <div class="color color4">
                            <span>#BABEE9</span>
                        </div>
                        <div class="color color5">
                            <span>#DDDDFC</span>
                        </div>
                        <div class="color color6">
                            <span>#E97B49</span>
                        </div>
                    </div>
                    <div class="add-feature-font">
                        <span class="font-fam">
                            <a href="https://fonts.google.com/specimen/Chivo" target="_blank">
                                Chivo
                            </a>
                        </span>
                        <span class="font-desc">San-Serif Font</span>
                        <span class="font-author">Designed by
                            <a href="https://fonts.google.com/?query=Omnibus-Type" target="_blank">
                                Omnibus-Type
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->

    <section class="about" id="about">
        <div class="container about-flex">
            <div class="about-info">
                <h2>About Us</h2>
                <p>
                    We are a team of passionate website Developer who aim to provide
                    the best online learning experience for dyslexia students.
                </p>
            </div>
            <div class="about-wrapper">
                <div class="card">
                    <img src="../img/profile_jacky.jpg" alt="" />
                    <div class="title">
                        <span class="name">Khoo Jiun Kang</span>
                        <span class="position">Website Developer</span>
                    </div>
                    <div class="description">
                        <span class="tp">
                            <i class="bx bx-user"></i>tp067542
                        </span>
                    </div>
                    <div class="description">
                        <span class="email">
                            <i class="bx bx-envelope"></i>jackykhoo@gmail.com
                        </span>
                    </div>
                    <div class="button-container">
                        <a href="https://www.instagram.com/jacky__khoo/" target="_blank">
                            <button type="submit">
                                <i class="bx bxl-instagram"></i>
                                <span>View More</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <img src="../img/profile_munlok.jpg" alt="" />
                    <div class="title">
                        <span class="name">Chew Mun Lok</span>
                        <span class="position">Website Developer</span>
                    </div>
                    <div class="description">
                        <span class="tp">
                            <i class="bx bx-user"></i>tp068835
                        </span>
                    </div>
                    <div class="description">
                        <span class="email">
                            <i class="bx bx-envelope"></i>munlok@gmail.com
                        </span>
                    </div>
                    <div class="button-container">
                        <a href="https://www.instagram.com/munlok_/" target="_blank">
                            <button type="submit">
                                <i class="bx bxl-instagram"></i>
                                <span>View More</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <img src="../img/profile_chunkeat.jpg" alt="" />
                    <div class="title">
                        <span class="name">Lim Chun Keat</span>
                        <span class="position">Website Developer</span>
                    </div>
                    <div class="description">
                        <span class="tp">
                            <i class="bx bx-user"></i>tp068689
                        </span>
                    </div>
                    <div class="description">
                        <span class="email">
                            <i class="bx bx-envelope"></i>chunssjuyy@icloud.com
                        </span>
                    </div>
                    <div class="button-container">
                        <a href="https://www.instagram.com/3_cks/" target="_blank">
                            <button type="submit">
                                <i class="bx bxl-instagram"></i>
                                <span>View More</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <img src="../img/profile_peiying.jpeg" alt="" />
                    <div class="title">
                        <span class="name">Ooi Pei Ying</span>
                        <span class="position">Website Developer</span>
                    </div>
                    <div class="description">
                        <span class="tp">
                            <i class="bx bx-user"></i>tp070054
                        </span>
                    </div>
                    <div class="description">
                        <span class="email">
                            <i class="bx bx-envelope"></i>peiying@gmail.com
                        </span>
                    </div>
                    <div class="button-container">
                        <a href="https://www.instagram.com/yows_mona_6189/" target="_blank">
                            <button type="submit">
                                <i class="bx bxl-instagram"></i>
                                <span>View More</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <img src="../img/profile_wensheng.jpg" alt="" />
                    <div class="title">
                        <span class="name">Kwan Wen Sheng</span>
                        <span class="position">Website Developer</span>
                    </div>
                    <div class="description">
                        <span class="tp">
                            <i class="bx bx-user"></i>tp069585
                        </span>
                    </div>
                    <div class="description">
                        <span class="email">
                            <i class="bx bx-envelope"></i>wensheng@gmail.com
                        </span>
                    </div>
                    <div class="button-container">
                        <a href="https://www.instagram.com/kwanwensheng/" target="_blank">
                            <button type="submit">
                                <i class="bx bxl-instagram"></i>
                                <span>View More</span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review -->
    <section class="review" id="about">
        <div class="container review-flex">
            <div class="review-info">
                <p>Feedback</p>
                <h2>What Our Client Say</h2>
            </div>
            <div class="review-wrapper">
                <div class="review-col">
                    <div class="review-row">
                        <div class="profile-cus">
                            <span><i class="bx bx-user"></i></span>
                        </div>
                        <div class="review-card review-card-1">
                            <div class="icon">
                                <span><i class="bx bxs-quote-right"></i></span>
                            </div>
                            <div class="comment">
                                <span>
                                    "Improved navigation and more frequent
                                    content updates would greatly enhance
                                    the platform."
                                </span>
                            </div>
                            <div class="comment-name">
                                <span class="cus-name">vwxyz</span>
                                <span class="cus-username">@learn123</span>
                            </div>
                        </div>
                    </div>
                    <div class="review-row">
                        <div class="review-card review-card-2">
                            <div class="icon">
                                <span><i class="bx bxs-quote-right"></i></span>
                            </div>
                            <div class="comment">
                                <span>
                                    "KwazeEdu’s accessible design
                                    significantly enhances my learning
                                    experience and boosts confidence."
                                </span>
                            </div>
                            <div class="comment-name">
                                <span class="cus-name">abcde</span>
                                <span class="cus-username">@learnAbc</span>
                            </div>
                        </div>
                        <div class="profile-cus">
                            <span><i class="bx bx-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <!-- include footer -->
    <?php include 'footer.php'; ?>

</body>

</html>