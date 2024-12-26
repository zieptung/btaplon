function togglePasswordVisibility() {
  const togglePassword = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("password");
  const type = passwordInput.type === "password" ? "text" : "password";
  passwordInput.type = type;
  if (type === "password") {
    togglePassword.classList.remove("fa-eye-slash");
    togglePassword.classList.add("fa-eye");
  } else {
    togglePassword.classList.remove("fa-eye");
    togglePassword.classList.add("fa-eye-slash");
  }
}
const usernameInput = document.getElementById("Masv");
const passwordInput = document.getElementById("password");
const errorMessage = document.getElementById("error-message");

usernameInput.addEventListener("focus", () => {
  if (errorMessage) {
    errorMessage.style.display = "none";
  }
});

passwordInput.addEventListener("focus", () => {
  if (errorMessage) {
    errorMessage.style.display = "none";
  }
});
