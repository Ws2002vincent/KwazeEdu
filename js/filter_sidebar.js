document.addEventListener("DOMContentLoaded", function () {
	const sidebarItems = document.querySelectorAll(".sidebar-item");

	sidebarItems.forEach((item) => {
		item.addEventListener("click", function (event) {
			// Prevent default behavior if needed
			event.preventDefault();

			// Remove the 'active' class from all items
			sidebarItems.forEach((subItem) => {
				subItem.classList.remove("active");
			});

			// Add the 'active' class to the clicked item
			this.classList.add("active");

			// Optional: perform any additional actions when a new category is selected
			updateDisplayBasedOnCategory(
				this.querySelector(".sidebar-link span").textContent.trim()
			);
		});
	});
});

function updateDisplayBasedOnCategory(category) {
	console.log("Filtering content for category:", category);
	// Here you might adjust displayed content or make an API call to retrieve data based on the category
}
