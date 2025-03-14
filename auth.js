document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector(".left");
    const registerForm = document.querySelector(".right");
    const toggleButtons = document.querySelectorAll(".toggle-btn");

    toggleButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            loginForm.classList.toggle("hidden");
            registerForm.classList.toggle("hidden");
        });
    });
});
