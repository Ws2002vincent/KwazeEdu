// for mcq

document.addEventListener("DOMContentLoaded", function () {
	// Attach event listeners to all radio buttons
	const radioButtons = document.querySelectorAll(
		'.choice input[type="radio"]'
	);
	radioButtons.forEach((radio) => {
		radio.addEventListener("change", function () {
			// Find the parent '.mcq' container of the clicked radio button
			const mcqContainer = this.closest(".mcq");

			// Reset styles for all text inputs within the same '.mcq' container
			mcqContainer
				.querySelectorAll('.choice input[type="text"]')
				.forEach((input) => {
					input.style.backgroundColor = ""; // Reset background
					input.style.color = ""; // Reset font color
				});

			// If this radio button is checked
			if (this.checked) {
				// Get the next sibling input[type="text"]
				let nextTextInput = this.nextElementSibling;
				nextTextInput.style.backgroundColor = "#d4edda"; // Light green background
				nextTextInput.style.color = "#006100"; // Dark green font color
			}
		});
	});
});

// for tof

document.addEventListener("DOMContentLoaded", function () {
	// Attach event listeners to all radio buttons for True or False questions
	const tofRadioButtons = document.querySelectorAll(
		'.ans-tof input[type="radio"]'
	);
	tofRadioButtons.forEach((radio) => {
		radio.addEventListener("change", function () {
			// Find the parent '.mcq' container of the clicked radio button
			const mcqContainer = this.closest(".mcq");

			// Reset styles for all labels within the same '.mcq' container
			mcqContainer.querySelectorAll(".label-tof").forEach((label) => {
				label.style.backgroundColor = "#fff"; // Reset background to white
				label.style.color = "#333"; // Reset text color to default
			});

			// Apply new styles based on the radio button checked
			let label = this.nextElementSibling; // Get the label right next to the radio
			if (this.id.includes("true")) {
				label.style.backgroundColor = "#c6efce"; // Light green for true
				label.style.color = "#006100"; // Dark green text
			} else if (this.id.includes("false")) {
				label.style.backgroundColor = "#f8d7da"; // Light red for false
				label.style.color = "#721c24"; // Dark red text
			}
		});
	});
});
