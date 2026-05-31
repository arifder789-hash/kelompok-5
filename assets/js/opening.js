// ============================================================
// PEMBUKA.JS — Rameza Egg Farm — Opening / Splash Page Logic
// ============================================================

document.addEventListener("DOMContentLoaded", () => {

    // ===== INTRO SPLASH LOADER =====
    const loader = document.getElementById("intro-loader");
    const heroContent = document.querySelector(".hero-content");

    // Keep hero hidden until intro finishes
    heroContent.style.opacity = "0";

    // After 2.5 s: hide intro, then fade hero in
    setTimeout(() => {
        loader.classList.add("hide");

        setTimeout(() => {
            heroContent.style.transition = "opacity 1.2s ease";
            heroContent.style.opacity = "1";
        }, 700);

    }, 2500);


    // ===== START BUTTON — page transition =====
    const startBtn = document.querySelector(".start-btn");

    startBtn.addEventListener("click", (e) => {
        e.preventDefault();

        // Fade out body, then navigate
        document.body.style.transition = "opacity .8s ease";
        document.body.style.opacity = "0";

        setTimeout(() => {
            window.location.href = startBtn.getAttribute("href");
        }, 700);
    });


    // ===== PARALLAX MOUSE =====
    const hero = document.querySelector(".hero");

    document.addEventListener("mousemove", (e) => {
        const x = (window.innerWidth / 2 - e.clientX) / 40;
        const y = (window.innerHeight / 2 - e.clientY) / 40;
        hero.style.backgroundPosition = `${50 + x}% ${50 + y}%`;
    });

});