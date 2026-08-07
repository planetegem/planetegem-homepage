// CREATE PREVIEW OF APPLIED FILTERS WHENEVER CHECKBOX CHANGES
const filterSummary = document.getElementById("filter-summary"),
    filterOptions = document.getElementById("filter-form").querySelectorAll("input[type='checkbox']"),
    orderSelector = document.getElementById("order-filter");

for (let option of filterOptions) {
    option.addEventListener("change", (e) => setFilterSummary());
}
orderSelector.addEventListener("change", (e) => setFilterSummary());
window.addEventListener("load", (e) => setFilterSummary());

function setFilterSummary() {
    let checkedLanguages = document.querySelectorAll("input[type='checkbox'][name='language']:checked"),
        checkedCategories = document.querySelectorAll("input[type='checkbox'][name='category']:checked");

    let string = "(will show ";

    if (checkedLanguages.length > 0) {
        for (let i = 0; i < checkedLanguages.length; i++) {
            string += (i === 0) ? checkedLanguages[i].dataset.label : " and " + checkedLanguages[i].dataset.label;
        }
        string += " items"
    } else {
        string += "all items"
    }
    if (checkedCategories.length > 0) {
        string += ", that are categorised as "
        for (let i = 0; i < checkedCategories.length; i++) {
            string += (i === 0) ? checkedCategories[i].dataset.label : " or " + checkedCategories[i].dataset.label;
        }
        string += ","
    }
    switch (orderSelector.value) {
        case "descending":
            string += " in descending order)";
            break;
        case "ascending":
            string += " in ascending order)";
    }
    filterSummary.innerText = string;
}
