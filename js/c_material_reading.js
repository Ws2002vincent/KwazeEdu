document.addEventListener("DOMContentLoaded", (event) => {
	const templates = document.querySelectorAll(".template");

	templates.forEach((template) => {
		const storyText = template.querySelector(".story-text span");
		const originalText = storyText.textContent.trim();

		template.addEventListener("click", function (event) {
			// Check if the click event target is the button
			if (event.target.closest(".question-header button")) {
				return; // Do nothing if the button is clicked
			}

			if (this.classList.contains("split")) {
				// Revert to paragraph mode
				this.classList.remove("split");
				storyText.innerHTML = originalText;
			} else {
				// Split into lines
				this.classList.add("split");
				const lines = originalText
					.split(". ")
					.map((line) => `<span class="line">${line.trim()}.</span>`);
				storyText.innerHTML = lines.join("<br/>");

				const lineElements = template.querySelectorAll(".line");
				lineElements.forEach((line) => {
					line.addEventListener("mouseover", () => {
						lineElements.forEach((l) => {
							if (l !== line) l.classList.add("dimmed");
						});
					});
					line.addEventListener("mouseout", () => {
						lineElements.forEach((l) =>
							l.classList.remove("dimmed")
						);
					});
				});
			}
		});
	});
});
