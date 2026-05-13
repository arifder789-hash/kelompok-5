(function () {
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
        }
    }

    function closeMenu() {
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
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initNavbar);
    } else {
        initNavbar();
    }
})();
