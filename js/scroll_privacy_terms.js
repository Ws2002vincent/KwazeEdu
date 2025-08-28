document.querySelectorAll(".nav-content a").forEach((anchor) => {
	anchor.addEventListener("click", function (e) {
		e.preventDefault(); // Prevent the default anchor click behavior
		const targetId = this.getAttribute("href").substring(1); // Extract the target ID without the '#'
		const target = document.getElementById(targetId); // Get the target element
		if (target) {
			const offset = 100; // Define the offset from the top in pixels
			const targetPosition = target.offsetTop - offset; // Calculate the target position with the offset

			window.scrollTo({
				top: targetPosition, // Set the top property to the calculated position
				behavior: "smooth", // Enable smooth scrolling
			});
		}
	});
});
