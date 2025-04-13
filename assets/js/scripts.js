function closeDialogue() {
    document.getElementById("delete-dialogue").style.display = "none";
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("h1, h2, h3, h4, h5, h6").forEach(heading => {
        heading.setAttribute("tabindex", 0);
    });
})
