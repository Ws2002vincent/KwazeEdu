document.addEventListener("DOMContentLoaded", function () {
	const detailId = document.getElementById("details-id");
	const detailSeries = document.getElementById("details-series");
	const detailName = document.getElementById("details-name");
	const detailCoin = document.getElementById("details-coin");
	const detailImage = document.getElementById("details-image");
	const detailImageName = document.getElementById("details-imagename");
	const rows = document.querySelectorAll(".table-data tbody tr");

	function updateProfileDetails(row) {
		const id = row.getAttribute("data-id");
		const name = row.getAttribute("data-name");
		const series = row.getAttribute("data-series");
		const coins = row.getAttribute("data-coins");
		const image = row.getAttribute("data-image");

		detailId.value = id;
		detailSeries.innerHTML = series;
		detailName.value = name;
		detailCoin.value = coins;
		detailImage.src = "../img/" + image;
		detailImageName.value = image;
	}

	// Automatically select the first row when the page loads
	if (rows.length > 0) {
		rows[0].classList.add("selected-row");
		updateProfileDetails(rows[0]);
	}

	rows.forEach((row) => {
		row.addEventListener("click", function () {
			// Remove selected class from all rows
			rows.forEach((r) => r.classList.remove("selected-row"));
			// Add selected class to the clicked row
			row.classList.add("selected-row");
			// Call the function to update the profile details
			updateProfileDetails(row);
		});
	});

	document
		.getElementById("searchIdName")
		.addEventListener("input", filterTable);
	document
		.getElementById("searchSeries")
		.addEventListener("input", filterTable);

	function filterTable() {
		const filterIdName = document
			.getElementById("searchIdName")
			.value.toLowerCase();
		const filterSeries = document
			.getElementById("searchSeries")
			.value.toLowerCase();
		const rows = document.querySelectorAll("#picTableBody tr");
		let count = 0;
		let firstVisibleRow;

		rows.forEach((row) => {
			const cells = row.querySelectorAll("td");
			const [id, name, series] = [
				cells[0].textContent.toLowerCase(),
				cells[1].textContent.toLowerCase(),
				cells[2].textContent.toLowerCase(),
			];

			if (
				(id.startsWith(filterIdName) ||
					name.startsWith(filterIdName)) &&
				series.startsWith(filterSeries)
			) {
				row.style.display = "";
				count++;
				if (!firstVisibleRow) {
					firstVisibleRow = row;
				}
			} else {
				row.style.display = "none";
			}
		});

		if (firstVisibleRow) {
			firstVisibleRow.classList.add("selected-row");
			updateProfileDetails(firstVisibleRow);
		}

		document.getElementById(
			"resultCount"
		).textContent = `Total Result(s) : ${count}`;
	}
});
