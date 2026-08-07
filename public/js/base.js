

// 2. ONLY REVEAL ITEMS WHEN THEY SCROLL INTO VIEW
let hiddenItems = [...document.querySelectorAll(".hidden")];

function revealItems(delay = 0) {
    if (!interactionBlocked && hiddenItems.length > 0) {
        // Consider revealing entry
        for (let i = 0; i < hiddenItems.length; i++) {
            let currentElement = hiddenItems[i],
                yPos = currentElement.getBoundingClientRect().top;

            if (yPos < window.innerHeight * 0.975) {
                setTimeout(() => {
                    currentElement.classList.remove("hidden");
                    currentElement.classList.add("visible");
                    hiddenItems = [...document.querySelectorAll(".hidden")];
                }, i * delay);
            }
        }
    }
}
window.addEventListener("scroll", revealItems);

// 3. TOP NAVIGATION, CLOSE WHEN SCROLLING DOWN, OPEN WHEN SCROLLING UP
const navMenu = document.getElementById("page-nav-menu");
let menuIsOpen = false;

function showNav() {
    navMenu.classList.remove("closed");
    menuIsOpen = true;
}
function hideNav() {
    navMenu.classList.add("closed");
    menuIsOpen = false;

    mainMenu.classList.remove("opened");
    filterMenu.classList.remove("opened");
    menuOverlay.classList.remove("visible");
}

let lastScrollTop = window.scrollY || document.documentElement.scrollTop;
history.scrollRestoration = 'manual';
window.addEventListener('scroll', () => {
    const scrollTopPosition = window.scrollY || document.documentElement.scrollTop;

    if (scrollTopPosition > lastScrollTop && scrollTopPosition > 40 && menuIsOpen) {
        hideNav();
    } else if (scrollTopPosition < lastScrollTop && (!menuIsOpen || isFooterOpen)) {
        showNav();
        hideFooter();
    }

    lastScrollTop = scrollTopPosition <= 0 ? 0 : scrollTopPosition;

    // Check if footer needs to be revealed
    const windowHeight = window.innerHeight;
    const fullHeight = document.documentElement.scrollHeight;

    if (scrollTopPosition + windowHeight >= fullHeight - 10 && !isFooterOpen) {
        showFooter();
    }
});

// 4. Footer logic: open when at bottom of screen (done above)
const pageFooter = document.getElementById("page-footer");
let isFooterOpen = false;

function showFooter() {
    pageFooter.classList.remove("closed");
    isFooterOpen = true;
}
function hideFooter() {
    pageFooter.classList.add("closed");
    isFooterOpen = false;
}

// 5. OPEN AND CLOSE HAMBURGER MENU'S
const menuButton = document.getElementById("menu-button"),
    mainMenu = document.getElementById("main-menu"),
    menuOverlay = document.getElementById("menu-overlay");

menuButton.addEventListener("click", (e) => {
    mainMenu.classList.toggle("opened");
    filterMenu.classList.remove("opened");

    toggleOverlay();
});

function toggleOverlay() {
    if (mainMenu.classList.contains("opened") || filterMenu.classList.contains("opened")) {
        menuOverlay.classList.add("visible");
    } else {
        menuOverlay.classList.remove("visible");
    }
}
const filterButton = document.getElementById("filter-button"),
    filterMenu = document.getElementById("filter-menu");

filterButton.addEventListener("click", (e) => {
    filterMenu.classList.toggle("opened");
    mainMenu.classList.remove("opened");
    toggleOverlay();
});

const exitButtons = document.querySelectorAll(".menu > .exit-button");
for (let exitButton of exitButtons) {
    exitButton.addEventListener("click", (e) => {
        exitButton.parentElement.classList.remove("opened");
        menuOverlay.classList.remove("visible");
    });
}
menuOverlay.addEventListener("click", (e) => {
    menuOverlay.classList.remove("visible");

    filterMenu.classList.remove("opened");
    mainMenu.classList.remove("opened");
});