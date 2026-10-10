import { Background } from "./components/fake-3D-background.js";
import ExpandableThumbnail from "./components/expandable-thumbnail.js";
import FpsCounter from "./components/fps-counter.js";
import ItemManager from "./components/item-manager.js";
import Loader from "./components/loader.js";
import TerrainAnimator from "./components/terrain-animator.js";
import UiManager from "./components/ui-manager.js";

// Register web component
if (!customElements.get('expandable-thumbnail')) {
    customElements.define('expandable-thumbnail', ExpandableThumbnail);
}

const fpsCounter = new FpsCounter("fps-counter");

const background = new Background("fake-3D-background", false);
background.fillColor = "white";
background.strokeWidth = 2;
background.strokeColor = "#05954886";
const terrainAnimator = new TerrainAnimator(background, "background-planet");

const itemManager = new ItemManager();
const uiManager = new UiManager();
const loader = new Loader();
