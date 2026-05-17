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

    const postImageInput = document.getElementById("postImageInput");
    const postImagePreviewWrap = document.getElementById("postImagePreviewWrap");
    const postImagePreview = document.getElementById("postImagePreview");
    const removePostImage = document.getElementById("removePostImage");

    if (postImageInput && postImagePreviewWrap && postImagePreview && removePostImage) {
        postImageInput.addEventListener("change", () => {
            const file = postImageInput.files[0];

            if (!file) {
                postImagePreviewWrap.classList.add("hidden");
                postImagePreview.src = "";
                return;
            }

            postImagePreview.src = URL.createObjectURL(file);
            postImagePreviewWrap.classList.remove("hidden");
        });

        removePostImage.addEventListener("click", () => {
            postImageInput.value = "";
            postImagePreview.src = "";
            postImagePreviewWrap.classList.add("hidden");
        });
}

const clockCanvas = document.getElementById("zClockCanvas");

if (clockCanvas) {
    const ctx = clockCanvas.getContext("2d");

    function drawClock() {
        const now = new Date();

        const width = clockCanvas.width;
        const height = clockCanvas.height;
        const centerX = width / 2;
        const centerY = height / 2;
        const radius = 92;

        ctx.clearRect(0, 0, width, height);

        ctx.fillStyle = "#000000";
        ctx.fillRect(0, 0, width, height);

        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
        ctx.strokeStyle = "#2f3336";
        ctx.lineWidth = 2;
        ctx.stroke();

        for (let i = 0; i < 12; i++) {
            const angle = (i * Math.PI) / 6 - Math.PI / 2;

            const outerX = centerX + Math.cos(angle) * (radius - 10);
            const outerY = centerY + Math.sin(angle) * (radius - 10);

            const innerX = centerX + Math.cos(angle) * (radius - 22);
            const innerY = centerY + Math.sin(angle) * (radius - 22);

            ctx.beginPath();
            ctx.moveTo(innerX, innerY);
            ctx.lineTo(outerX, outerY);
            ctx.strokeStyle = "#ffffff";
            ctx.lineWidth = 2;
            ctx.stroke();
        }

        const hours = now.getHours() % 12;
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        const hourAngle =
            ((hours + minutes / 60) * Math.PI) / 6 - Math.PI / 2;

        const minuteAngle =
            ((minutes + seconds / 60) * Math.PI) / 30 - Math.PI / 2;

        const secondAngle =
            (seconds * Math.PI) / 30 - Math.PI / 2;

        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.lineTo(
            centerX + Math.cos(hourAngle) * 48,
            centerY + Math.sin(hourAngle) * 48
        );
        ctx.strokeStyle = "#ffffff";
        ctx.lineWidth = 5;
        ctx.lineCap = "round";
        ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.lineTo(
            centerX + Math.cos(minuteAngle) * 70,
            centerY + Math.sin(minuteAngle) * 70
        );
        ctx.strokeStyle = "#ffffff";
        ctx.lineWidth = 3;
        ctx.lineCap = "round";
        ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.lineTo(
            centerX + Math.cos(secondAngle) * 78,
            centerY + Math.sin(secondAngle) * 78
        );
        ctx.strokeStyle = "#1D9BF0";
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.stroke();

        
        ctx.beginPath();
        ctx.arc(centerX, centerY, 5, 0, Math.PI * 2);
        ctx.fillStyle = "#1D9BF0";
        ctx.fill();
    }

    drawClock();
    setInterval(drawClock, 1000);
}
});

