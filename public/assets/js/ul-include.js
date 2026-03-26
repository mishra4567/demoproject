document.addEventListener("DOMContentLoaded", function () {
    let currentPath = window.location.pathname;

    // hide all submenus first
    document.querySelectorAll(".js-sub-list").forEach(function (menu) {
        menu.style.display = "none";
    });

    document.querySelectorAll(".js-sub-list a").forEach(function (link) {
        if (link.pathname === currentPath) {
            // active submenu item
            link.parentElement.classList.add("active");

            // find parent menu
            let parentMenu = link.closest(".has-sub");

            if (parentMenu) {
                parentMenu.classList.add("active");

                let submenu = parentMenu.querySelector(".js-sub-list");

                if (submenu) {
                    submenu.style.display = "block";
                }
            }
        }
    });
});
