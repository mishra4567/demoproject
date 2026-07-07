// resources/js/vendor/Back/navConfig.js

export const sidebarLinks = [
    {
        order: 1,
        href: "/vendor",
        label: "Dashboard",
        icon: "dashboard",
    },
    {
        order: 2,
        label: "Products",
        icon: "product",
        children: [
            {
                order: 1,
                href: "/vendor/products",
                label: "All Products",
                icon: "product",
            },
            {
                order: 2,
                href: "/vendor/link-products",
                label: "Variants",
                icon: "product",
            },
            {
                order: 3,
                href: "/vendor/technical-specs",
                label: "Technical Specs",
                icon: "settings",
            },
        ],
    },
    {
        order: 3,
        label: "Catalogue",
        icon: "dashboard",
        children: [
            {
                order: 1,
                href: "/vendor/categories",
                label: "Categories",
                icon: "dashboard",
            },
            {
                order: 2,
                href: "/vendor/brands",
                label: "Brands",
                icon: "brands",
            },
            {
                order: 3,
                href: "/vendor/colors",
                label: "Colors",
                icon: "colors",
            },
            { order: 4, href: "/vendor/sizes", label: "Sizes", icon: "size" },
        ],
    },
    {
        order: 4,
        href: "/vendor/coupons",
        label: "Coupons",
        icon: "coupons",
    },
    {
        order: 5,
        href: "/vendor/media",
        label: "Gallery",
        icon: "product",
    },
    {
        order: 6,
        label: "Report",
        icon: "report",
        children: [
            {
                order: 1,
                href: "/report/new",
                label: "Add Report",
                icon: "report",
                target: "_blank",
            },
            {
                order: 2,
                href: "/report/show",
                label: "View Report",
                icon: "info",
            },
        ],
    },
];

// export const sidebarLinks = [
//     { href: "/vendor", label: "Dashboard", icon: "dashboard" },
//     // { href: "/vendor/orders", label: "Orders", icon: "orders" },
//     // { href: "/vendor/profile", label: "Profile", icon: "user" },
//     // { href: "/vendor/settings", label: "Settings", icon: "settings" },
//     { href: "/vendor/categories", label: "Categories", icon: "category" },
//     { href: "/vendor/coupons", label: "Coupons", icon: "coupons" },
//     { href: "/vendor/colors", label: "Colors", icon: "colors" },
//     { href: "/vendor/sizes", label: "Sizes", icon: "sizes" },
//     { href: "/vendor/brands", label: "Brands", icon: "brands" },
//     { href: "/vendor/products", label: "Products", icon: "product" },
//     {
//         href: "/vendor/link-products",
//         label: "Link-product",
//         icon: "link-product",
//     },
//     {
//         href: "/vendor/technical-specs",
//         label: "technical-specs",
//         icon: "technical-specs",
//     },
//     { href: "/vendor/media", label: "Gallery", icon: "media" },
//     {
//         href: "/report/new",
//         label: "Report",
//         icon: "report",
//         target: `target="_blank"`,
//     },
// ];

export const navLinks = [
    // { href: "/vendor", label: "Dashboard", icon: "dashboard" },
    // { href: "/vendor/products", label: "Products", icon: "product" },
    // { href: "/vendor/coupons", label: "Coupons", icon: "coupons" },
    // { href: "/vendor/brands", label: "brands", icon: "brands" },
    { href: "/vendor/categories", label: "Categories", icon: "category" },
    { href: "/vendor/colors", label: "Colors", icon: "colors" },
    // {
    //     href: "/vendor/link-products",
    //     label: "Link-product",
    //     icon: "link-product",
    // },
    // {
    //     href: "/vendor/technical-specs",
    //     label: "technical-specs",
    //     icon: "technical-specs",
    // },
    { href: "/vendor/sizes", label: "Sizes", icon: "size" },
];
