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
