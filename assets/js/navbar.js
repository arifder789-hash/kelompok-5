(function () {
<<<<<<< HEAD
    const navbar = document.querySelector("[data-navbar]");
    const navToggle = document.querySelector(".nav-toggle");
    const navLinks = Array.from(document.querySelectorAll(".nav-menu a"));

    function normalizePath(path) {
        return path.split("#")[0].split("?")[0].replace(/\/+$/, "");
    }

    function getCurrentPage() {
        return normalizePath(window.location.pathname).split("/").pop() || "index.php";
    }

    function getLinkParts(link) {
        const href = link.getAttribute("href") || "";
        return {
            page: normalizePath(href).split("/").pop() || "index.php",
            hash: href.includes("#") ? `#${href.split("#")[1]}` : "",
        };
    }
    function setActiveNavLink() {
        const currentPage = getCurrentPage();
        const currentHash = window.location.hash || "";
        
        navLinks.forEach((link) => {
            const { page, hash } = getLinkParts(link);
            const isHomeAlias = currentPage === "index.php" && page === "beranda.php";
            const isSamePage = currentPage === page || isHomeAlias;
            const isHashLink = hash !== "";
            const isActiveHash = isSamePage && isHashLink && currentHash === hash;
            const isActivePage = isSamePage && !isHashLink && currentHash === "";

            link.classList.toggle("active", isActiveHash || isActivePage);
        });
    }

    function setScrolledState() {
        if (!navbar) return;
        const isScrolled = window.scrollY > 80;
        navbar.classList.toggle("scrolled", isScrolled);

        if (!isScrolled) {
            closeMenu();
=======
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
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
        }
    }

    function closeMenu() {
<<<<<<< HEAD
        if (!navbar || !navToggle) return;
        navbar.classList.remove("nav-open");
        navToggle.setAttribute("aria-expanded", "false");
    }

    function initMenuToggle() {
        if (!navbar || !navToggle) return;

        navToggle.addEventListener("click", () => {
            const isOpen = navbar.classList.toggle("nav-open");
            navToggle.setAttribute("aria-expanded", String(isOpen));
        });

        navLinks.forEach((link) => {
            link.addEventListener("click", closeMenu);
        });
    }

    function initNavbar() {
        setActiveNavLink();
        setScrolledState();
        initMenuToggle();

        window.addEventListener("scroll", setScrolledState, { passive: true });
        window.addEventListener("hashchange", setActiveNavLink);
=======
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
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initNavbar);
    } else {
        initNavbar();
    }
})();
