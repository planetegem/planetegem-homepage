import styles from "../../css/components/image-components.css" with {type: "css"};

// Simple thumbnail that can be clicked to enlarge
// Supports loading 3 different images: the (small) thumbnail that's loaded immediately,
// the large image that's shown in a dialog and only loads when the user opens the dialog,
// and a placeholder to show while loading the large image (defaults to the thumbnail)
export default class ExpandableThumbnail extends HTMLElement {

    constructor() {
        super();

        // Attach (or identify) shadow root
        if (!this.shadowRoot) this.attachShadow({ mode: "open" });
        const shadow = this.shadowRoot;

        shadow.adoptedStyleSheets = [styles];
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

    expandImage(open = true) {
        if (!this.dialog) {
            const img = this.shadowRoot.querySelector("img.thumbnail");
            this.dialog = this.createDialog(img);
            this.shadowRoot.appendChild(this.dialog);
        }

        if (open) {
            this.dialog.showModal();

        } else {
            this.dialog.close();
        }
    }


}