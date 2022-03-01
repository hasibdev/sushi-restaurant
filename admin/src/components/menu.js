export const menuItems = [
  {
    id: 1,
    label: "menuitems.menu.text",
    isTitle: true,
  },
  // Dashboard
  {
    id: 100,
    label: "menuitems.dashboard.text",
    icon: "ri-bar-chart-line",
    link: "/",
  },
  // Categories
  {
    id: 101,
    label: "menuitems.categories.text",
    icon: "ri-artboard-2-line",
    subItems: [
      // Create Categorie
      {
        id: 102,
        label: "menuitems.categories.list.add",
        link: "/categories/create",
      },
      // Manage Categories
      {
        id: 103,
        label: "menuitems.categories.list.manage",
        link: "/categories",
      },
    ],
  },
  // Additions
  {
    id: 107,
    label: "menuitems.additions.text",
    icon: "ri-pencil-ruler-2-line",
    subItems: [
      // Create Additions
      {
        id: 108,
        label: "menuitems.additions.list.add",
        link: "/additions/create",
      },
      // Manage additions
      {
        id: 109,
        label: "menuitems.additions.list.manage",
        link: "/additions",
      },
    ],
  },
  // Options
  {
    id: 1016,
    label: "menuitems.options.text",
    icon: "ri-profile-line",
    subItems: [
      // Create Additions
      {
        id: 107,
        label: "menuitems.options.list.add",
        link: "/options/create",
      },
      // Manage options
      {
        id: 108,
        label: "menuitems.options.list.manage",
        link: "/options",
      },
    ],
  },
  // Products
  {
    id: 104,
    label: "menuitems.products.text",
    icon: "ri-store-2-line",
    subItems: [
      // Create product
      {
        id: 105,
        label: "menuitems.products.list.add",
        link: "/products/create",
      },
      // Manage products
      {
        id: 106,
        label: "menuitems.products.list.manage",
        link: "/products",
      },
    ],
  },

  // Offers
  {
    id: 110,
    label: "menuitems.offers.text",
    icon: "ri-brush-line",
    subItems: [
      // Create Offer
      {
        id: 111,
        label: "menuitems.offers.list.add",
        link: "/offers/create",
      },
      // Manage offers
      {
        id: 112,
        label: "menuitems.offers.list.manage",
        link: "/offers",
      },
    ],
  },
  // Discounts
  {
    id: 113,
    label: "menuitems.discounts.text",
    icon: "ri-eraser-fill",
    subItems: [
      // Create Offer
      {
        id: 114,
        label: "menuitems.discounts.list.add",
        link: "/discounts/create",
      },
      // Manage discounts
      {
        id: 115,
        label: "menuitems.discounts.list.manage",
        link: "/discounts",
      },
    ],
  },
  // Orders
  {
    id: 116,
    label: "menuitems.orders.text",
    icon: "ri-share-line",
    subItems: [
      // Create Offer
      {
        id: 118,
        label: "New Orders",
        link: "/new-orders",
      },
      {
        id: 117,
        label: "POS Orders",
        link: "/pos-orders",
      },
      // Manage discounts
      {
        id: 118,
        label: "Web Orders",
        link: "/web-orders",
      },
      {
        id: 118,
        label: "Completed Orders",
        link: "/completed-orders",
      },
      // Manage discounts
      {
        id: 118,
        label: "All Orders",
        link: "/orders",
      },
    ],
  },
  // POS
  {
    id: 999,
    label: "menuitems.pos.text",
    icon: "ri-calendar-2-line",
    link: "/pos",
  },

  // Users
  {
    id: 119,
    label: "menuitems.users.text",
    icon: "ri-pencil-ruler-2-line",
    subItems: [
      // Create Users
      {
        id: 120,
        label: "menuitems.users.list.add",
        link: "/users/create",
      },
      // Manage users
      {
        id: 121,
        label: "menuitems.users.list.manage",
        link: "/users",
      },
    ],
  },

  // Website Pages
  {
    id: 122,
    label: "menuitems.pages.text",
    icon: "ri-dashboard-line",
    subItems: [
      // Home
      {
        id: 123,
        label: "menuitems.pages.home",
        link: "/pages/home",
      },
      // Contact
      {
        id: 123,
        label: "menuitems.pages.contact",
        link: "/pages/contact",
      },
      // Location
      {
        id: 125,
        label: "menuitems.locations.text",
        icon: "ri-pencil-ruler-2-line",
        link: "/pages/locations",
      },
    ],
  },
  // Reports
  {
    id: 200,
    label: "menuitems.reports.text",
    icon: "ri-pie-chart-line",
    link: "/reports",
  },

  // Settings
  {
    id: 201,
    label: "menuitems.settings.text",
    icon: "ri-settings-2-line",
    link: "/settings",
  },
  // Theme
  {
    id: 226,
    label: "menuitems.theme.text",
    icon: "ri-settings-2-line",
    link: "/theme",
  },
];
