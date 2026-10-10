import styles from "../../css/components/image-components.css" with {type: "css"};

// Simple thumbnail that can be clicked to enlarge
// Supports loading 3 different images: the (small) thumbnail that's loaded immediately,
// the large image that's shown in a dialog and only loads when the user opens the dialog,
// and a placeholder to show while loading the large image (defaults to the thumbnail)
export default class ExpandableThumbnail extends HTMLElement {

    static get observedAttributes() {
        return ["large-image", "placeholder"];
    }
    constructor() {
        super();

        // Attach (or identify) shadow root
        if (!this.shadowRoot) this.attachShadow({ mode: "open" });
        const shadow = this.shadowRoot;

        shadow.adoptedStyleSheets = [styles];
    }

    get largeImage() {
        return this.getAttribute("large-image");
    }
    set largeImage(value) {
        if (value) {
            this.setAttribute("large-image", value);
        } else {
            this.removeAttribute("large-image");
        }
    }

    get placeholder() {
        return this.getAttribute("placeholder");
    }
    set placeholder(value) {
        if (value) {
            this.setAttribute("placeholder", value);
        } else {
            this.removeAttribute("placeholder");
        }
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (name == "large-image" && oldValue !== newValue) {
            this.loadLargeImage();
        }
        if (name == "placeholder" && oldValue !== newValue) {
            this.loadLargeImage();
        }
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

    expandImage(open = true) {
        const dialog = this.shadowRoot.querySelector("dialog.enlarged-dialog");

        if (open) {
            dialog.showModal();
            this.loadLargeImage();

        } else {
            dialog.close();
        }
    }
    loadLargeImage() {
        const dialog = this.shadowRoot.querySelector("dialog.enlarged-dialog");
        const placeholder = dialog.querySelector("img.placeholder");
        const largeImage = dialog.querySelector("img.large-image");

        largeImage.classList.add("removed");
        placeholder.src = this.placeholder ?? this.img.src;
        placeholder.classList.remove("removed");

        largeImage.addEventListener("load", () => {
            placeholder.classList.add("removed");
            largeImage.classList.remove("removed");
        });
        largeImage.src = this.largeImage ?? this.img.src;
    }
}