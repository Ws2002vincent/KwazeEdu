// pop-up

document.addEventListener("DOMContentLoaded", function () {
	const popUp = document.querySelector(".pop-up-overlay");
	const resultCards = document.querySelectorAll(".result-card");
	const closeBtn = document.querySelector(".close-btn");

	// Function to show the popup
	function showPopup() {
		popUp.style.display = "flex";
		setTimeout(() => {
			popUp.style.opacity = "1";
			popUp.querySelector(".pop-up").style.transform = "scale(1)";
			popUp.querySelector(".pop-up").style.opacity = "1";
		}, 10);
		document.body.classList.add("no-scroll");
	}

	// Function to hide the popup
	function hidePopup() {
		popUp.style.opacity = "0";
		popUp.querySelector(".pop-up").style.transform = "scale(0.95)";
		popUp.querySelector(".pop-up").style.opacity = "0";
		setTimeout(() => {
			popUp.style.display = "none";
			document.body.classList.remove("no-scroll");
		}, 300);
	}

	// Event listener for each result card to show the popup
	resultCards.forEach((card) => {
		card.addEventListener("click", showPopup);
	});

	// Event listener for closing the popup by clicking on the overlay or close button
	popUp.addEventListener("click", function (e) {
		if (e.target === this || e.target.closest(".close-btn")) {
			hidePopup();
		}
	});

	// Optional: Close popup when pressing escape key
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") {
			if (popUp.style.display === "flex") {
				hidePopup();
			}
		}
	});
});

// pop-up-rank

document.addEventListener("DOMContentLoaded", function () {
	const popUp = document.querySelector(".pop-up-overlay");
	const resultCards = document.querySelectorAll(".result-card");
	const closeBtn = document.querySelector(".close-btn");

	// Function to show the popup
	function showPopup() {
		popUp.style.display = "flex";
		setTimeout(() => {
			popUp.style.opacity = "1";
			popUp.querySelector(".pop-up-rank").style.transform = "scale(1)";
			popUp.querySelector(".pop-up-rank").style.opacity = "1";
		}, 10);
		document.body.classList.add("no-scroll");
	}

	// Function to hide the popup
	function hidePopup() {
		popUp.style.opacity = "0";
		popUp.querySelector(".pop-up-rank").style.transform = "scale(0.95)";
		popUp.querySelector(".pop-up-rank").style.opacity = "0";
		setTimeout(() => {
			popUp.style.display = "none";
			document.body.classList.remove("no-scroll");
		}, 300);
	}

	// Event listener for each result card to show the popup
	resultCards.forEach((card) => {
		card.addEventListener("click", showPopup);
	});

	// Event listener for closing the popup by clicking on the overlay or close button
	popUp.addEventListener("click", function (e) {
		if (e.target === this || e.target.closest(".close-btn")) {
			hidePopup();
		}
	});

	// Optional: Close popup when pressing escape key
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") {
			if (popUp.style.display === "flex") {
				hidePopup();
			}
		}
	});
});

// pop-up-admin_store

document.addEventListener("DOMContentLoaded", function () {
	const popUp = document.querySelector(".pop-up-overlay");
	const resultCards = document.querySelectorAll(".create-btn");
	const closeBtn = document.querySelector(".close-btn");

	// Function to show the popup
	function showPopup() {
		popUp.style.display = "flex";
		setTimeout(() => {
			popUp.style.opacity = "1";
			popUp.querySelector(".pop-up-admin").style.transform = "scale(1)";
			popUp.querySelector(".pop-up-admin").style.opacity = "1";
		}, 10);
		document.body.classList.add("no-scroll");
	}

	// Function to hide the popup
	function hidePopup() {
		popUp.style.opacity = "0";
		popUp.querySelector(".pop-up-admin").style.transform = "scale(0.95)";
		popUp.querySelector(".pop-up-admin").style.opacity = "0";
		setTimeout(() => {
			popUp.style.display = "none";
			document.body.classList.remove("no-scroll");
		}, 300);
	}

	// Event listener for each result card to show the popup
	resultCards.forEach((card) => {
		card.addEventListener("click", showPopup);
	});

	// Event listener for closing the popup by clicking on the overlay or close button
	popUp.addEventListener("click", function (e) {
		if (e.target === this || e.target.closest(".close-btn")) {
			hidePopup();
		}
	});

	// Optional: Close popup when pressing escape key
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") {
			if (popUp.style.display === "flex") {
				hidePopup();
			}
		}
	});
});

// create pop up

document.addEventListener("DOMContentLoaded", function () {
	const createButton = document.querySelector(".create-btn");
	const popUp = document.querySelector(".pop-up-overlay");
	const closeBtn = document.querySelector(".close-btn");

	// Check if elements exist
	if (!createButton || !popUp || !closeBtn) {
		console.error("One or more elements are missing!");
		return; // Stop the script if essential elements are missing
	}

	// Function to show the popup
	function showPopup() {
		popUp.style.display = "flex";
		setTimeout(() => {
			popUp.style.opacity = "1";
			popUp.querySelector(".pop-up").style.transform = "scale(1)";
			popUp.querySelector(".pop-up").style.opacity = "1";
		}, 10);
		document.body.classList.add("no-scroll");
	}

	// Function to hide the popup
	function hidePopup() {
		popUp.style.opacity = "0";
		popUp.querySelector(".pop-up").style.transform = "scale(0.95)";
		popUp.querySelector(".pop-up").style.opacity = "0";
		setTimeout(() => {
			popUp.style.display = "none";
			document.body.classList.remove("no-scroll");
		}, 300);
	}

	// Add event listener to the Create button
	createButton.addEventListener("click", showPopup);

	// Event listener for closing the popup by clicking on the overlay or close button
	popUp.addEventListener("click", function (e) {
		if (e.target === this || e.target.closest(".close-btn")) {
			hidePopup();
		}
	});

	// Optional: Close popup when pressing escape key
	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape" && popUp.style.display === "flex") {
			hidePopup();
		}
	});
});
