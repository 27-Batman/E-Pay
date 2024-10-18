function togglePassword() {
    var passwordField = document.getElementById("password");
    var toggleBtn = passwordField.nextElementSibling;

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleBtn.textContent = "👀"; 
    } else {
        passwordField.type = "password";
        toggleBtn.textContent = "🙈"; 
    }
}

function toggleConfirmPassword() {
    var confirmPasswordField = document.getElementById("confirm-password");
    var toggleBtn = confirmPasswordField.nextElementSibling;

    if (confirmPasswordField.type === "password") {
        confirmPasswordField.type = "text";
        toggleBtn.textContent = "👀"; 
    } else {
        confirmPasswordField.type = "password";
        toggleBtn.textContent = "🙈"; 
    }
}
