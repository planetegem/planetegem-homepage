import styles from "../../css/components/image-components.css?inline";

// Instantiate the stylesheet
const sheet = new CSSStyleSheet();
sheet.replaceSync(styles);

// BASE CLASS
// Simple thumbnail that can be clicked to enlarge
// Supports loading a (small) thumbnail that's loaded immediately, a large image that's shown in 
// a dialog and only loads when it's opened a placeholder while loading the large image (defaults to the thumbnail)
export class ExpandableThumbnail extends HTMLElement {

    constructor() {
        super();

        // Attach (or identify) shadow root
        if (!this.shadowRoot) this.attachShadow({ mode: "open" });
        this.shadowRoot.adoptedStyleSheets = [sheet];

        this.dialog = null;
        this.img = null;
    }

    connectedCallback() {
        this.img = this.shadowRoot.querySelector("img.thumbnail");
        if (this.img) this.img.addEventListener("click", (e) => this.expandImage());
    }

    disconnectedCallback() {
        this.img = this.shadowRoot.querySelector("img.thumbnail");
        if (this.img) this.img.removeEventListener("click", (e) => this.expandImage());
        this.expandImage(false);
    }

    createDialog(img) {
        const dialog = document.createElement("dialog");
        dialog.classList.add("enlarged-dialog");

        const exitForm = document.createElement("form");
        exitForm.setAttribute('method', 'dialog');

        const button = document.createElement('button');
        button.setAttribute('type', 'submit');
        button.textContent = 'X';
        exitForm.appendChild(button);
        dialog.appendChild(exitForm);

        const figure = document.createElement('figure');
        dialog.appendChild(figure);

        this.loadImage(figure, img);

        return dialog;
    }

    loadImage(container, img) {
        if (!img) return;

        const placeholder = new Image();
        placeholder.classList.add("placeholder");
        placeholder.alt = img.alt || "";
        placeholder.src = img.dataset.placeholder ?? img.src;

        const largeImage = new Image();
        largeImage.classList.add("removed");
        largeImage.decoding = "async";

        largeImage.addEventListener("load", () => {
            placeholder.classList.add("removed");
            largeImage.classList.remove("removed");
        }, { once: true });
        largeImage.src = img.dataset.expanded ?? img.src;

        container.appendChild(placeholder);
        container.appendChild(largeImage);
    }

    expandImage(open = true) {
        const targetImg = this.getActiveImage();
        if (!targetImg) return;

        if (!this.dialog) {
            this.dialog = this.createDialog(targetImg);
            this.shadowRoot.appendChild(this.dialog);
        }

        if (open) {
            this.dialog.showModal();

        } else {
            this.dialog.close();
        }
    }

    getActiveImage() {
        return this.img || this.shadowRoot.querySelector("img.thumbnail");
    }


}
// EXPANDED CLASS
// Gallery: has everything that the simple thumbnail has,
// expanded with a gallery function to browse through images
export class ExpandableGallery extends ExpandableThumbnail {
    constructor() {
        super();

        // Prepare some props for usage further down the line
        this.currentIndex = 0;
        this.images = [];
    }

    connectedCallback() {
        // Load images and turn them into array
        this.images = Array.from(this.shadowRoot.querySelectorAll("img.thumbnail"));
        if (this.images.length === 0) {
            console.warn("No valid images attached to gallery");
            return;
        }

        // Set the navigation
        this.navigation = this.shadowRoot.querySelector("nav.gallery-nav");

        // Add event listeners for the left / right buttons
        const leftButton = this.shadowRoot.querySelector("button.nav-button.left");
        const rightButton = this.shadowRoot.querySelector("button.nav-button.right");
        leftButton?.addEventListener("click", () => this.cycleNav(-1));
        rightButton?.addEventListener("click", () => this.cycleNav(1));

        // Clear existing nav links if any
        if (this.navigation) this.navigation.replaceChildren();

        // Prepare each thumbnail
        this.images.forEach((img, index) => {
            img.addEventListener("click", () => this.expandImage());
            img.classList.add("removed");

            const navImage = new Image();
            navImage.dataset.src = img.dataset.src;
            navImage.dataset.priority = img.dataset.priority;
            navImage.alt = img.alt || "";
            navImage.classList.add("thumbnail-preview");

            const navButton = document.createElement("button");
            navButton.classList.add("nav-link");
            navButton.appendChild(navImage);
            navButton.addEventListener("click", () => this.selectImage(navButton, index));
            this.navigation.appendChild(navButton);

            if (index === 0) this.selectImage(navButton, index);
        });

        // Enable keyboard arrow navigation when component has focus
        this.addEventListener("keydown", (e) => {
            if (e.key === "ArrowLeft") this.cycleNav(-1);
            if (e.key === "ArrowRight") this.cycleNav(1);
        });
    }

    disconnectedCallback() {
        super.disconnectedCallback();
        if (this.dialog && this.dialog.open) {
            this.dialog.close();
        }
    }

    selectImage(element, index) {
        this.currentIndex = index;

        // Remove selection from all links, add to current
        const buttons = this.navigation?.querySelectorAll("button.nav-link") || [];
        buttons.forEach(btn => btn.classList.remove("selected"));
        element.classList.add("selected");

        // Toggle image visibility
        this.images.forEach((img, i) => {
            img.classList.toggle("removed", i !== index);
        });

        // Ensure selected thumbnail is scrolled into view within the nav
        element.scrollIntoView({ behavior: "smooth", inline: "nearest", block: "nearest" });

        // If dialog is currently open, update its image too
        if (this.dialog?.open) {
            const figure = this.dialog.querySelector("figure");
            figure.innerHTML = "";
            this.loadImage(figure, this.images[index]);
        }
    }

    // Cycle through thumbnails via the arrow buttons
    cycleNav(direction) {
        const buttons = this.navigation.querySelectorAll("button.nav-link");
        if (buttons.length === 0) return;

        this.currentIndex = (this.currentIndex + direction + buttons.length) % buttons.length;
        this.selectImage(buttons[this.currentIndex], this.currentIndex);
    }

    getActiveImage() {
        return this.images[this.currentIndex] || super.getActiveImage();
    }
}