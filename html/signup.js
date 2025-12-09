document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("signupForm");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const emailInput = document.getElementById("email");
    const thanhphoInput = document.getElementById("thanhpho");
    const ghichuInput = document.getElementById("ghichu");
    const btnSignup = document.getElementById("btnSignup");

    const usernameError = document.getElementById("usernameError");
    const passwordError = document.getElementById("passwordError");
    const emailError = document.getElementById("emailError");
    const thanhphoError = document.getElementById("thanhphoError");
    const successMessage = document.getElementById("successMessage");

    const isEmailValid = (e) => /^\S+@\S+\.\S+$/.test(e);

    function clearErrors() {
        if (usernameError) usernameError.textContent = "";
        if (passwordError) passwordError.textContent = "";
        if (emailError) emailError.textContent = "";
        if (thanhphoError) thanhphoError.textContent = "";
        if (successMessage) successMessage.textContent = "";
    }

    function validate() {
        clearErrors();
        let ok = true;
        if (!usernameInput.value.trim()) {
            usernameError.textContent = "Bắt buộc nhập username";
            ok = false;
        }
        if (!passwordInput.value) {
            passwordError.textContent = "Bắt buộc nhập password";
            ok = false;
        }
        if (!emailInput.value.trim()) {
            emailError.textContent = "Bắt buộc nhập email";
            ok = false;
        } else if (!isEmailValid(emailInput.value.trim())) {
            emailError.textContent = "Email không hợp lệ";
            ok = false;
        }
        if (!thanhphoInput.value) {
            thanhphoError.textContent = "Vui lòng chọn thành phố";
            ok = false;
        }
        return ok;
    }

    // clear
    if (usernameInput) usernameInput.addEventListener("input", () => usernameError.textContent = "");
    if (passwordInput) passwordInput.addEventListener("input", () => passwordError.textContent = "");
    if (emailInput) emailInput.addEventListener("input", () => emailError.textContent = "");
    if (thanhphoInput) thanhphoInput.addEventListener("change", () => thanhphoError.textContent = "");

    btnSignup.addEventListener("click", () => {
        if (!validate()) return;

        if (successMessage) successMessage.textContent = "Đăng ký thành công";

        // Reset form (không log password)
        if (form) form.reset();

        // focus lại ô username
        if (usernameInput) usernameInput.focus();

        // Xóa thông báo sau 3 giây
        setTimeout(() => {
            if (successMessage) successMessage.textContent = "";
        }, 3000);
    });
});