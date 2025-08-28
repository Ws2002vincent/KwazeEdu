document.addEventListener("DOMContentLoaded", function () {
	const timelines = document.querySelectorAll(".timeline");

	timelines.forEach((timeline) => {
		const toggleButton = timeline.querySelector(".toggle-button");
		const toggleIcon = toggleButton.querySelector("i");
		const resultCards = timeline.querySelectorAll(
			".result-card-coll, .result-card"
		);
		const dateElement = timeline.querySelector(".date");

		toggleButton.addEventListener("click", function () {
			let isExpanded = false;

			resultCards.forEach((resultCard) => {
				const cardBanner = resultCard.querySelector(
					".card-banner, .card-banner-coll"
				);
				const cardContent = resultCard.querySelector(
					".card-content, .card-content-coll"
				);
				const cardTitle = resultCard.querySelector(
					".card-title, .card-title-coll"
				);
				const cardCategory = resultCard.querySelector(".card-category");
				const cardLevel = resultCard.querySelector(".card-level");
				const cardType = resultCard.querySelector(".card-type");

				if (resultCard.classList.contains("result-card-coll")) {
					// Expand the card
					resultCard.classList.remove("result-card-coll");
					resultCard.classList.add("result-card");
					cardBanner.classList.remove("card-banner-coll");
					cardBanner.classList.add("card-banner");
					cardContent.classList.remove("card-content-coll");
					cardContent.classList.add("card-content");
					cardTitle.classList.remove("card-title-coll");
					cardTitle.classList.add("card-title");
					cardLevel.style.display = "block";
					cardType.style.display = "block";
					isExpanded = true;
				} else {
					// Collapse the card
					resultCard.classList.remove("result-card");
					resultCard.classList.add("result-card-coll");
					cardBanner.classList.remove("card-banner");
					cardBanner.classList.add("card-banner-coll");
					cardContent.classList.remove("card-content");
					cardContent.classList.add("card-content-coll");
					cardTitle.classList.remove("card-title");
					cardTitle.classList.add("card-title-coll");
					cardLevel.style.display = "none";
					cardType.style.display = "none";
					isExpanded = false;
				}
			});

			// Change the button icon and date border based on the state
			if (isExpanded) {
				toggleIcon.classList.remove("bx-expand-alt");
				toggleIcon.classList.add("bx-collapse-alt");
				dateElement.style.borderLeft = "5px solid #1c253b";
				dateElement.style.borderTopLeftRadius = "0px";
				dateElement.style.borderTopRightRadius = "5px";
				dateElement.style.borderBottomRightRadius = "5px";
				dateElement.style.borderBottomLeftRadius = "0px";
			} else {
				toggleIcon.classList.remove("bx-collapse-alt");
				toggleIcon.classList.add("bx-expand-alt");
				dateElement.style.borderLeft = "2px solid #1c253b";
				dateElement.style.borderRadius = "";
			}
		});
	});
});
