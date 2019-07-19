const Menu = [
    { header: "Apps" },
    {
        title: "Profil",
        group: "apps",
        icon: "face",
        name: "profile",
        href: null
    },
    {
        title: "Template",
        group: "apps",
        icon: "format_paint",
        name: "template",
        href: null
    },
    {
        title: "Print",
        group: "apps",
        icon: "print",
        name: "print",
        href: null
    },
    {
        title: "Print Transaction",
        group: "apps",
        icon: "print",
        name: "print_transaction",
        href: null
    },
    {
        title: "Daftar Pengguna",
        group: "apps",
        icon: "account_circle",
        name: "users",
        admin: true,
        href: null
    },
    {
        title: "Template Gallery",
        group: "apps",
        icon: "dashboard",
        name: "template_gallery",
        admin: true,
        href: null
    }
];
// reorder menu
Menu.forEach(item => {
    if (item.items) {
        item.items.sort((x, y) => {
            let textA = x.title.toUpperCase();
            let textB = y.title.toUpperCase();
            return textA < textB ? -1 : textA > textB ? 1 : 0;
        });
    }
});

export default Menu;
