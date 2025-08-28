// Function to handle file input change and display image preview
function handleFileInputChange(event) {
	const fileInput = event.target;
	const file = fileInput.files[0];
	const previewContainer = fileInput.nextElementSibling.querySelector(
		".image-preview, .image-preview-flash"
	);
	const previewImage = previewContainer.querySelector(
		".image-preview__image"
	);
	const blurLayer = previewContainer.querySelector(".blur-layer");

	if (file) {
		const reader = new FileReader();
		reader.addEventListener("load", function () {
			previewImage.setAttribute("src", this.result);
			previewImage.style.display = "block";
			blurLayer.style.display = "none"; // Hide the blur layer
		});
		reader.readAsDataURL(file);
	} else {
		resetImagePreview(previewContainer);
	}
}

// Function to reset image preview
function resetImagePreview(previewContainer) {
	const previewImage = previewContainer.querySelector(
		".image-preview__image"
	);
	const blurLayer = previewContainer.querySelector(".blur-layer");
	previewImage.setAttribute("src", "");
	previewImage.style.display = "none";
	blurLayer.style.display = "flex"; // Show the blur layer
}

// Delegate event listener for dynamic file inputs
document.addEventListener("change", function (event) {
	if (event.target.matches('input[type="file"]')) {
		handleFileInputChange(event);
	}
});

// Reset image preview on form reset
document.getElementById("addPicForm").addEventListener("reset", function () {
	document
		.querySelectorAll(".image-preview, .image-preview-flash")
		.forEach((previewContainer) => {
			resetImagePreview(previewContainer);
		});
});
