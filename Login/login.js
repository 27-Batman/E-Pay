function togglePassword() {
    var passwordField = document.getElementById("password");
    var icon = document.querySelector(".toggle-password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.textContent = "👀";
    } else {
        passwordField.type = "password";
        icon.textContent = "🙈";
    }
}
