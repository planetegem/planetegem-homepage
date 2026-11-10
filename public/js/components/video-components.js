import styles from "../../css/components/image-components.css?inline";

// Instantiate the stylesheet
const sheet = new CSSStyleSheet();
sheet.replaceSync(styles);

export class ExpandableGifv extends HTMLElement {
    constructor() {
        super();

        // Attach (or identify) shadow root
        if (!this.shadowRoot) this.attachShadow({ mode: "open" });
        this.shadowRoot.adoptedStyleSheets = [sheet];

        this.dialog = null;
        this.img = null;
        this.video = null;
        this.modalVideo = null;
    }

    connectedCallback() {
        this.img = this.shadowRoot.querySelector("img.thumbnail");
        if (this.img) this.img.addEventListener("click", (e) => this.expandVideo());
        this.loadVideo();
    }

    disconnectedCallback() {
        this.img = this.shadowRoot.querySelector("img.thumbnail");
        if (this.img) this.img.removeEventListener("click", (e) => this.expandVideo());
        if (this.video) this.video.removeEventListener("click", (e) => this.expandVideo());
        this.expandVideo(false);
    }

    loadVideo() {
        const videoSrc = this.dataset.video;
        if (!videoSrc) return;

        const container = this.shadowRoot.querySelector("figure");
        if (!container) return;

        this.video = document.createElement("video");
        this.video.classList.add("thumbnail", "removed");
        this.video.autoplay = true;
        this.video.muted = true;
        this.video.loop = true;
        this.video.playsInline = true;
        this.video.setAttribute("muted", "");
        this.video.setAttribute("playsinline", "");

        // Swap poster for video smoothly once the video data is ready
        this.video.addEventListener("loadeddata", () => {
            if (this.img) {
                this.img.classList.add("removed");
            }
            this.video.classList.remove("removed");
            this.video.addEventListener("click", (e) => this.expandVideo());
            this.video.play().catch(() => {
                // Handle browser autoplay policy restrictions silently if needed
            });
        }, { once: true });

        this.video.src = videoSrc;
        container.appendChild(this.video);

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

        const videoSrc = this.dataset.video;

        this.modalVideo = document.createElement("video");
        this.modalVideo.classList.add("expanded-video");
        this.modalVideo.controls = true;
        this.modalVideo.autoplay = true;
        this.modalVideo.src = videoSrc;

        figure.appendChild(this.modalVideo);

        dialog.addEventListener("close", () => {
            if (this.modalVideo) {
                this.modalVideo.pause();
                this.modalVideo.currentTime = 0;
            }
        });

        return dialog;
    }

    expandVideo(open = true) {
        const targetImg = this.getActiveImage();
        if (!targetImg) return;

        if (!this.dialog) {
            this.dialog = this.createDialog(targetImg);
            this.shadowRoot.appendChild(this.dialog);
        }

        if (open) {
            this.dialog.showModal();
            if (this.modalVideo) {
                this.modalVideo.currentTime = 0;
                this.modalVideo.play().catch(() => { });
            }

        } else {
            if (this.modalVideo) this.modalVideo.pause();
            this.dialog.close();
        }
    }

    getActiveImage() {
        return this.img || this.shadowRoot.querySelector("img.thumbnail");
    }

}