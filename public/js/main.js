import { Background } from "./components/fake-3D-background.js";
import { ExpandableThumbnail, ExpandableGallery } from "./components/media/image-components.js";
import { ExpandableGifv } from "./components/media/video-components.js";
import FpsCounter from "./components/fps-counter.js";
import ItemManager from "./components/item-manager.js";
import Loader from "./components/loader.js";
import TerrainAnimator from "./components/terrain-animator.js";
import UiManager from "./components/ui-manager.js";

// Register Web Components
const components = {
    'expandable-thumbnail': ExpandableThumbnail,
    'expandable-gallery': ExpandableGallery,
    'expandable-gifv': ExpandableGifv
};

for (const [name, component] of Object.entries(components)) {
    if (!customElements.get(name)) {
        customElements.define(name, component);
    }
}

// Initialize Application Modules
const fpsCounter = new FpsCounter("fps-counter");

const background = new Background("fake-3D-background", false);
Object.assign(background, {
    fillColor: "white",
    strokeWidth: 2,
    strokeColor: "#05954886"
});

const terrainAnimator = new TerrainAnimator(background, "background-planet");
const itemManager = new ItemManager();
const uiManager = new UiManager();
const loader = new Loader();

// Import CSS
import "../css/base.css";
import "../css/item.css";
import "../css/header.css";
import "../css/filter-menu.css";
import "../css/highlight-menu.css";
import "../css/error.css";
import "../css/background.css";
import "../css/pager.css";
import "../css/main.css";



