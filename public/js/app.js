// Javascripty BIPITY
console.log("check if its connecting");
document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll("[data-auth-toggle]");

    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const targetId = toggle.getAttribute("data-auth-toggle");
            const target = document.getElementById(targetId);

            if (!target) {
                return;
            }

            target.classList.toggle("hidden");
        });
    });
});