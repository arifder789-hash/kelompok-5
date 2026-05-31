(function () {
    const navbar = document.getElementById("navbar");
    const hamburger = document.getElementById("hamburger");
    const mobileMenu = document.getElementById("mobile-menu");
    const navLinks = document.querySelectorAll(".navbar-links a, .mobile-menu a");

    function setScrolledState() {
        if (!navbar) return;
        const isScrolled = window.scrollY > 50;
        navbar.classList.toggle("scrolled", isScrolled);
    }

    function toggleMenu() {
        if (!mobileMenu || !hamburger) return;
        const isOpen = mobileMenu.classList.toggle("open");
        hamburger.setAttribute("aria-expanded", String(isOpen));
        
        const spans = hamburger.querySelectorAll("span");
        if (spans.length === 3) {
            spans[0].style.transform = isOpen ? "rotate(45deg) translate(5px,5px)" : "";
            spans[1].style.opacity = isOpen ? "0" : "1";
            spans[2].style.transform = isOpen ? "rotate(-45deg) translate(5px,-5px)" : "";
        }
    }

    function closeMenu() {
        if (!mobileMenu || !hamburger) return;
        mobileMenu.classList.remove("open");
        hamburger.setAttribute("aria-expanded", "false");
        
        const spans = hamburger.querySelectorAll("span");
        if (spans.length === 3) {
            spans.forEach(s => { s.style.transform = ""; s.style.opacity = "1"; });
        }
    }

    function initNavbar() {
        setScrolledState();
        window.addEventListener("scroll", setScrolledState, { passive: true });

        if (hamburger) {
            hamburger.addEventListener("click", toggleMenu);
        }

        navLinks.forEach(link => {
            link.addEventListener("click", () => {
                if (window.innerWidth <= 768) {
                    closeMenu();
                }
            });
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initNavbar);
    } else {
        initNavbar();
    }
})();
