import styles from "./media-component.css?inline";

// Instantiate the stylesheet
const sheet = new CSSStyleSheet();
sheet.replaceSync(styles);

// BASE CLASS
// Provides logic shared by all media components
export class MediaComponent extends HTMLElement {
    constructor() {
        super();

        // Attach (or identify) shadow root
        if (!this.shadowRoot) this.attachShadow({ mode: "open" });
        this.shadowRoot.adoptedStyleSheets = [sheet];

        // Dialog to be used when 'zooming'
        this.dialog = null;
    }

    // Create the base dialog (to be filled by extended class)
    createDialog() {
        const dialog = document.createElement("dialog");
        dialog.classList.add("enlarged-dialog");

        // Exit button to close dialog
        const exitForm = document.createElement("form");
        exitForm.setAttribute('method', 'dialog');

        const button = document.createElement('button');
        button.setAttribute('type', 'submit');
        button.textContent = 'X';
        exitForm.appendChild(button);
        dialog.appendChild(exitForm);

        // Add an event listener to close the dialog when clicking outside it
        dialog.addEventListener("click", (e) => {
            const rect = dialog.getBoundingClientRect();
            if (
                e.clientX < rect.left || e.clientX > rect.right ||
                e.clientY < rect.top || e.clientY > rect.bottom
            ) {
                this.expand(false);
            }
        });

        return dialog;
    }

    expand(open = true){
        if (!this.dialog) {
            this.dialog = this.createDialog();
            this.shadowRoot.appendChild(this.dialog);
        }

        if (open) {
            if (!this.dialog.open) this.dialog.showModal();
        } else {
            if (this.dialog.open) this.dialog.close();
        }
    }
}
