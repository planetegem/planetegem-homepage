import { MediaComponent } from "./media-component";

// Simple video element that shows a placeholder image while video is loading,
// starts looping the video as soon as it's loaded
// and provides logic to open an expanded dialog to examine the video
export class ExpandableGifv extends MediaComponent {
    constructor() {
        super();

        // Media elements
        this.placeholder = null;
        this.video = null;
        this.modalVideo = null;
    }

    connectedCallback() {
        this.placeholder = this.shadowRoot.querySelector("img.thumbnail");
        this.placeholder?.addEventListener("click", (e) => this.expand(true));
        this.loadVideo();
    }

    disconnectedCallback() {
        this.placeholder?.removeEventListener("click", (e) => this.expand(true));
        this.video?.removeEventListener("click", (e) => this.expand(true));
        super.disconnectedCallback();
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
            if (this.placeholder) {
                this.placeholder.classList.add("removed");
            }
            this.video.classList.remove("removed");
            this.video.addEventListener("click", (e) => this.expand(true));
            this.video.play().catch(() => {
            });
        }, { once: true });

        this.video.src = videoSrc;
        container.appendChild(this.video);

    }

    createDialog() {
        const dialog = super.createDialog();

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

    expand(open = true) {
        super.expand(open);

        if (open) {
            if (this.modalVideo) {
                this.modalVideo.currentTime = 0;
                this.modalVideo.play().catch(() => {});
            }
        } else {
            this.modalVideo?.pause();
        }
    }
}