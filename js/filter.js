document.addEventListener("DOMContentLoaded", function () {
	const filterItems = document.querySelectorAll(".filter-item");

	filterItems.forEach((item) => {
		item.addEventListener("click", function () {
			// Remove active class from all items
			filterItems.forEach((subItem) => {
				subItem.classList.remove("active");
			});

			// Add active class to the clicked item
			this.classList.add("active");

			// Optional: Perform filtering or fetch content based on the selected filter
			// fetchData(this.textContent);
		});
	});
});

// Example function to fetch data based on filter, if needed
function fetchData(filter) {
	console.log("Fetching data for:", filter);
	// Implementation depends on your backend setup
}
