document.addEventListener("DOMContentLoaded", function () {
	document.getElementById("file").addEventListener("change", function () {
		const file = this.files[0];
		if (file) {
			const reader = new FileReader();
			const previewContainer = document.getElementById("imagePreview");
			const previewImage = previewContainer.querySelector(
				".image-preview__image"
			);
			const previewDefaultText = previewContainer.querySelector(
				".image-preview__default-text"
			);

			reader.addEventListener("load", function () {
				previewImage.setAttribute("src", this.result);
				previewImage.style.display = "block";
				previewDefaultText.style.display = "none";
			});

			reader.readAsDataURL(file);
		} else {
			resetImagePreview();
		}
	});

	document
		.getElementById("addPicForm")
		.addEventListener("reset", function () {
			resetImagePreview();
		});

	function resetImagePreview() {
		const previewContainer = document.getElementById("imagePreview");
		const previewImage = previewContainer.querySelector(
			".image-preview__image"
		);
		const previewDefaultText = previewContainer.querySelector(
			".image-preview__default-text"
		);

		previewImage.setAttribute("src", "");
		previewImage.style.display = "none";
		previewDefaultText.style.display = "block";
	}
});
