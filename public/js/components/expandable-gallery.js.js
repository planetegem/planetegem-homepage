import styles from "../../css/components/image-components.css" with {type: "css"};

// Gallery: has everything that the simple thumbnail has,
// expanded with a gallery function to browse through images
export default class ExpandableGallery extends HTMLElement {
    constructor() {
        super();

        // Attach (or identify) shadow root
        if (!this.shadowRoot) this.attachShadow({ mode: "open" });
        const shadow = this.shadowRoot;

        shadow.adoptedStyleSheets = [styles];

        // Prepare some props for usage further down the line
        this.currentIndex = 0;
        this.images = [];
        this.dialog = null;
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
        this.shadowRoot.querySelector("button.nav-button.left").addEventListener("click", () => this.cycleNav(-1));
        this.shadowRoot.querySelector("button.nav-button.right").addEventListener("click", () => this.cycleNav(1));

        this.currentIndex = 0;

        // Prepare each thumbnail
        this.images.forEach((img, index) => {
            img.addEventListener("click", () => this.expandImage());
            img.classList.add("removed");

            const navImage = new Image();
            navImage.dataset.src = img.dataset.src;
            navImage.dataset.priority = img.dataset.priority;
            navImage.alt = img.alt;
            navImage.classList.add("thumbnail-preview");

            const navLink = document.createElement("a");
            navLink.appendChild(navImage);
            navLink.addEventListener("click", () => this.selectImage(navLink, index));
            this.navigation.appendChild(navLink);

            if (index === 0) this.selectImage(navLink, index);
        });

        // Enable keyboard arrow navigation when component has focus
        this.addEventListener("keydown", (e) => {
            if (e.key === "ArrowLeft") this.cycleNav(-1);
            if (e.key === "ArrowRight") this.cycleNav(1);
        });
    }

    selectImage(element, index) {
        this.currentIndex = index;

        // Remove selection from all links, add to current
        const links = this.navigation.querySelectorAll("a");
        links.forEach(link => link.classList.remove("selected"));
        element.classList.add("selected");

        // Toggle image visibility
        this.images.forEach((img) => {
            img.classList.add("removed");
        });
        this.images[index].classList.remove("removed");

        // Ensure selected thumbnail is scrolled into view within the nav
        element.scrollIntoView({ behavior: "smooth", inline: "nearest", block: "nearest" });

        // If dialog is currently open, update its image too
        if (this.dialog && this.dialog.open) {
            const figure = this.dialog.querySelector("figure");
            figure.innerHTML = "";
            this.loadImage(figure, this.images[index]);
        }
    }

    // Cycle through thumbnails via the arrow buttons
    cycleNav(direction) {
        const links = this.navigation.querySelectorAll("a");
        if (links.length === 0) return;

        this.currentIndex = (this.currentIndex + direction + links.length) % links.length;
        this.selectImage(links[this.currentIndex], this.currentIndex);
    }

    // Create the dialog to show enlarged image
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

    // Begin loading the full size image (with placeholders)
    loadImage(container, img) {
        const placeholder = new Image();
        placeholder.classList.add("placeholder");
        placeholder.alt = img.alt;
        placeholder.src = img.dataset.placeholder ?? img.src;

        const largeImage = new Image();
        largeImage.classList.add("removed");
        largeImage.addEventListener("load", () => {
            placeholder.classList.add("removed");
            largeImage.classList.remove("removed");
        });
        largeImage.src = img.dataset.expanded ?? img.src;

        container.appendChild(placeholder);
        container.appendChild(largeImage);
    }

    // Open the dialog
    expandImage(open = true) {
        // Fallback to the first thumbnail if currentIndex isn't set yet
        const currentImg = this.images[this.currentIndex] || this.shadowRoot.querySelector("img.thumbnail");

        if (!this.dialog) {
            // Create the dialog for the first time with the active image
            this.dialog = this.createDialog(currentImg);
            this.shadowRoot.appendChild(this.dialog);
        } else {
            // If the dialog already exists, update its figure with the newly selected image
            const figure = this.dialog.querySelector("figure");
            figure.innerHTML = ""; // Clear out the previous image elements
            this.loadImage(figure, currentImg);
        }

        if (open) {
            this.dialog.showModal();
        } else {
            this.dialog.close();
        }
    }

    disconnectedCallback() {
        if (this.dialog && this.dialog.open) {
            this.dialog.close();
        }
    }
}