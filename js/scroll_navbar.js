window.addEventListener("scroll", function () {
	const nav = document.querySelector("nav");
	// You can adjust the '10' to the threshold of how far you want to scroll before the shadow appears
	if (window.scrollY > 10) {
		nav.classList.remove("no-shadow");
	} else {
		nav.classList.add("no-shadow");
	}
});
