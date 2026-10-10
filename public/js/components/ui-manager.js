import { orchestrator } from "./orchestrator.js";

export default class UiManager {
    constructor() {
        // State
        this.interactionAllowed = false;

        // DOM Elements
        this.menu = document.getElementById("page-nav-menu");
        this.footer = document.getElementById("page-footer");
        this.overlay = document.getElementById("menu-overlay");
        this.mainMenu = document.getElementById("main-menu");
        this.filterMenuButton = document.getElementById("filter-button");
        this.filterMenu = document.getElementById("filter-menu");

        // Scroll
        this.lastScrollPosition = window.scrollY;
        this.ticking = false;

        // Subscribe to orchestrator
        orchestrator.subscribe("interactable", (data) => {
            this.interactionAllowed = data.newState;
            if (data.newState) {
                this.menu.classList.remove("closed");
                document.querySelector(".error.hidden")?.classList.add("visible");
            }
        });

        // Event listeners
        window.addEventListener("scroll", () => this.handleScroll());

        document.getElementById("menu-button")?.addEventListener("click", () =>
            this.toggleSubmenu(this.mainMenu, this.filterMenu)
        );
        document.getElementById("filter-button")?.addEventListener("click", () =>
            this.toggleSubmenu(this.filterMenu, this.mainMenu)
        );
        this.overlay?.addEventListener("click", () => this.closeAllMenus());

        const exitButtons = document.querySelectorAll(".menu > .exit-button");
        for (let exitButton of exitButtons) {
            exitButton.addEventListener("click", () => this.closeAllMenus());
        }
    }

    // MENU STATE HANDLERS
    // Open a submenu (and close the other one)
    toggleSubmenu(target, sibling) {
        if (!target) return;

        // Close sibling menu
        sibling?.classList.remove("opened");

        // Check if targetmenu is being opened or closed
        const willOpen = !target.classList.contains("opened");
        target.classList.toggle("opened", willOpen);

        this.syncOverlay();
    }
    // Sync overlay with current menu state (visible if any menu is open)
    syncOverlay() {
        const isAnyMenuOpen =
            Boolean(this.mainMenu?.classList.contains("opened")) ||
            Boolean(this.filterMenu?.classList.contains("opened"));

        this.overlay?.classList.toggle("visible", isAnyMenuOpen);
    }
    // Shorthand to close everything
    closeAllMenus() {
        this.overlay?.classList.remove("visible");
        this.filterMenu?.classList.remove("opened");
        this.mainMenu?.classList.remove("opened");
    }

    // SCROLL HANDLER
    // Route scroll handle through an animation frame
    onScroll() {
        if (!this.ticking) {
            requestAnimationFrame(() => {
                this.handleScroll();
                this.ticking = false;
            });
            this.ticking = true;
        }
    }

    handleScroll() {
        const currentScrollPosition = window.scrollY;
        const delta = currentScrollPosition - this.lastScrollPosition;

        // Avoid mobile jankiness
        if (Math.abs(delta) < 5) {
            return;
        }

        // Close submenu's if necessary
        this.closeAllMenus();

        // Scroll down
        if (delta > 0) {
            this.menu.classList.add("closed");

            // Footer is only revealed at the bottom of the page
            const windowHeight = window.innerHeight;
            const fullHeight = document.documentElement.scrollHeight;

            if (currentScrollPosition + windowHeight >= fullHeight - 10) {
                this.footer.classList.remove("closed");
            }
        }

        // Scroll up
        if (delta < 0) {
            this.menu.classList.remove("closed");
            this.footer.classList.add("closed");
        }

        this.lastScrollPosition = currentScrollPosition;
    }


}