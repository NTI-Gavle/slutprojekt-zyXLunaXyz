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

        const postMenuButtons = document.querySelectorAll("[data-post-menu-toggle]");

    postMenuButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.stopPropagation();

            const targetId = button.getAttribute("data-post-menu-toggle");
            const menu = document.getElementById(targetId);

            if (!menu) {
                return;
            }

            document.querySelectorAll("[data-post-menu]").forEach((openMenu) => {
                if (openMenu !== menu) {
                    openMenu.classList.add("hidden");
                }
            });

            menu.classList.toggle("hidden");
        });
    });

    document.addEventListener("click", () => {
        document.querySelectorAll("[data-post-menu]").forEach((menu) => {
            menu.classList.add("hidden");
        });
    });
});

