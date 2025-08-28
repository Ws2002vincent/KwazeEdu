// var a;
// function pass() {
// 	var passwordInput = document.getElementById("password");
// 	var passIcon = document.getElementById("pass-icon");

// 	if (a == 1) {
// 		passwordInput.type = "password";
// 		passIcon.className = "bx bx-hide";
// 		a = 0;
// 	} else {
// 		passwordInput.type = "text";
// 		passIcon.className = "bx bx-show";
// 		a = 1;
// 	}
// }

function pass(passwordId, iconId) {
	var passwordInput = document.getElementById(passwordId);
	var passIcon = document.getElementById(iconId);

	if (passwordInput.type === "password") {
		passwordInput.type = "text";
		passIcon.className = "bx bx-show";
	} else {
		passwordInput.type = "password";
		passIcon.className = "bx bx-hide";
	}
}
