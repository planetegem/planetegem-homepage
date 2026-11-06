import { orchestrator } from "./orchestrator.js";

export default class UiManager {
    constructor() {
        // State
        this.interactionAllowed = false;
        this.lastScrollPosition = window.scrollY;
        this.ticking = false;

        // DOM Elements
        this.menu = document.getElementById("page-nav-menu");
        this.footer = document.getElementById("page-footer");
        this.overlay = document.getElementById("menu-overlay");
        this.mainMenu = document.getElementById("main-menu");
        this.filterMenuButton = document.getElementById("filter-button");
        this.filterMenu = document.getElementById("filter-menu");

        this.submenus = [this.mainMenu, this.filterMenu];

        // Subscribe to orchestrator
        orchestrator.subscribe("interactable", (data) => {
            this.interactionAllowed = data.newState;
            if (data.newState) {
                this.menu?.classList.remove("closed");
                document.querySelector(".error.hidden")?.classList.add("visible");
            }
        });

        // Event listeners
        window.addEventListener("scroll", () => this.onScroll(), { passive: true });

        document.getElementById("menu-button")?.addEventListener("click", () =>
            this.toggleSubmenu(this.mainMenu, this.filterMenu)
        );
        document.getElementById("filter-button")?.addEventListener("click", () =>
            this.toggleSubmenu(this.filterMenu, this.mainMenu)
        );
        this.overlay?.addEventListener("click", () => this.closeAllMenus());

        // Attach listeners to all exit buttons
        document.querySelectorAll(".menu > .exit-button").forEach(button => {
            button.addEventListener("click", () => this.closeAllMenus());
        });

        // ADDITIONAL LOGIC FOR FILTER FORM
        
        // Filter menu elements
        this.filterSummary = document.getElementById("filter-summary");
        this.filterForm = document.getElementById("filter-form");
        this.orderSelector = document.getElementById("order-filter");

        // Filter form event delegation
        this.filterForm?.addEventListener("change", () => this.setFilterSummary());
        
        // Order selector change
        this.orderSelector?.addEventListener("change", () => this.setFilterSummary());

        // Initialize filter summary on load
        this.setFilterSummary();
    }

    // MENU HANDLERS
    toggleSubmenu(target, sibling) {
        if (!target) return;

        // Close sibling menu
        sibling?.classList.remove("opened");

        // Check if targetmenu is being opened or closed
        const willOpen = !target.classList.contains("opened");
        target.classList.toggle("opened", willOpen);

        this.syncOverlay();
    }
    syncOverlay() {
        const isAnyMenuOpen = this.submenus.some(menu => menu?.classList.contains("opened"));
        this.overlay?.classList.toggle("visible", isAnyMenuOpen);
    }
    closeAllMenus() {
        this.overlay?.classList.remove("visible");
        this.submenus.forEach(menu => menu?.classList.remove("opened"));
    }

    // SCROLL HANDLER
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

    // FILTER SUMMARY LOGIC
    setFilterSummary() {
        if (!this.filterSummary) return;

        const checkedLanguages = Array.from(this.filterForm?.querySelectorAll("input[type='checkbox'][name='language']:checked") || []);
        const checkedCategories = Array.from(this.filterForm?.querySelectorAll("input[type='checkbox'][name='category']:checked") || []);

        const langLabels = checkedLanguages.map(el => el.dataset.label).filter(Boolean);
        const catLabels = checkedCategories.map(el => el.dataset.label).filter(Boolean);

        let parts = ["(will show"];

        if (langLabels.length > 0) {
            const langFormatter = new Intl.ListFormat('en', { style: 'long', type: 'conjunction' });
            parts.push(` ${langFormatter.format(langLabels)} items`);
        } else {
            parts.push(" all items");
        }

        if (catLabels.length > 0) {
            const catFormatter = new Intl.ListFormat('en', { style: 'long', type: 'disjunction' });
            parts.push(`, that are categorised as ${catFormatter.format(catLabels)},`);
        }

        const orderValue = this.orderSelector?.value;
        if (orderValue === "descending" || orderValue === "ascending") {
            parts.push(` in ${orderValue} order`);
        }

        parts.push(")");

        this.filterSummary.innerText = parts.join('');
    }
}