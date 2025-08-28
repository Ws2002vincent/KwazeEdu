document.addEventListener("DOMContentLoaded", function () {
	const sections = document.querySelectorAll("section");
	const navLi = document.querySelectorAll(".nav-list li a");

	const observerOptions = {
		root: null,
		rootMargin: "0px",
		threshold: 0.5, // Adjusted threshold for better intersection detection
	};

	const observerCallback = (entries, observer) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				navLi.forEach((link) => {
					link.classList.remove("active");
					if (
						link.getAttribute("href").substring(1) ===
						entry.target.id
					) {
						link.classList.add("active");
					}
				});
			}
		});
	};

	const observer = new IntersectionObserver(
		observerCallback,
		observerOptions
	);

	sections.forEach((section) => {
		observer.observe(section);
	});

	// Smooth scrolling for nav links
	navLi.forEach((link) => {
		link.addEventListener("click", function (e) {
			e.preventDefault();

			// Remove active class from all links
			navLi.forEach((link) => {
				link.classList.remove("active");
			});

			// Add active class to the clicked link
			this.classList.add("active");

			const targetId = this.getAttribute("href").substring(1);
			const targetSection = document.getElementById(targetId);

			window.scrollTo({
				top: targetSection.offsetTop,
				behavior: "smooth",
			});
		});
	});
});
