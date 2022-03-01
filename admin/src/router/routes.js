import auth from "@/middleware/auth";
import guest from "@/middleware/guest";

export default [
  {
    path: "/login",
    component: () =>
      import(/* webpackChunkName: "auth" */ "../views/pages/account/login"),
    meta: {
      middleware: [guest],
    },
  },
  {
    path: "/register",
    component: () =>
      import(/* webpackChunkName: "auth" */ "../views/pages/account/register"),
    meta: {
      middleware: [guest],
    },
  },
  {
    path: "/forgot-password",
    component: () =>
      import(
        /* webpackChunkName: "auth" */ "../views/pages/account/forgot-password"
      ),
    meta: {
      middleware: [guest],
    },
  },
  {
    path: "/logout",
    meta: {
      middleware: [auth],
    },
  },
  {
    path: "/",
    component: () =>
      import(/* webpackChunkName: "index" */ "../views/pages/dashboard/index"),
    meta: {
      middleware: [auth],
    },
  },
  {
    path: "/reports",
    component: () =>
      import(
        /* webpackChunkName: "reports" */ "../views/pages/dashboard/reports"
      ),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Categories Route
   */
  // all
  {
    path: "/categories",
    component: () =>
      import(/* webpackChunkName: "cats" */ "../views/pages/categories/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/categories/create",
    component: () =>
      import(/* webpackChunkName: "cats" */ "../views/pages/categories/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/categories/:id",
    component: () =>
      import(
        /* webpackChunkName: "cats" */ "../views/pages/categories/details"
      ),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/categories/:id/edit",
    component: () =>
      import(/* webpackChunkName: "cats" */ "../views/pages/categories/edit"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Additions Route
   */
  // all
  {
    path: "/additions",
    component: () =>
      import(/* webpackChunkName: "adds" */ "../views/pages/additions/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/additions/create",
    component: () =>
      import(/* webpackChunkName: "adds" */ "../views/pages/additions/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/additions/:id",
    component: () =>
      import(/* webpackChunkName: "adds" */ "../views/pages/additions/details"),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/additions/:id/edit",

    component: () =>
      import(/* webpackChunkName: "adds" */ "../views/pages/additions/edit"),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Options Route
   */
  // all
  {
    path: "/options",
    component: () =>
      import(/* webpackChunkName: "ops" */ "../views/pages/options/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/options/create",
    component: () =>
      import(/* webpackChunkName: "ops" */ "../views/pages/options/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/options/:id",
    component: () =>
      import(/* webpackChunkName: "ops" */ "../views/pages/options/details"),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/options/:id/edit",

    component: () =>
      import(/* webpackChunkName: "ops" */ "../views/pages/options/edit"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Products Route
   */
  // all
  {
    path: "/products",
    component: () =>
      import(/* webpackChunkName: "prods" */ "../views/pages/products/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/products/create",
    component: () =>
      import(/* webpackChunkName: "prods" */ "../views/pages/products/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/products/:id",
    component: () =>
      import(/* webpackChunkName: "prods" */ "../views/pages/products/details"),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/products/:id/edit",

    component: () =>
      import(/* webpackChunkName: "prods" */ "../views/pages/products/edit"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Offers Route
   */
  // all
  {
    path: "/offers",
    component: () =>
      import(/* webpackChunkName: "offs" */ "../views/pages/offers/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/offers/create",
    component: () =>
      import(/* webpackChunkName: "offs" */ "../views/pages/offers/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/offers/:id",
    component: () =>
      import(/* webpackChunkName: "offs" */ "../views/pages/offers/details"),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/offers/:id/edit",
    component: () =>
      import(/* webpackChunkName: "offs" */ "../views/pages/offers/edit"),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Discounts Route
   */
  // all
  {
    path: "/discounts",
    component: () =>
      import(/* webpackChunkName: "disc" */ "../views/pages/discounts/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/discounts/create",
    component: () =>
      import(/* webpackChunkName: "disc" */ "../views/pages/discounts/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/discounts/:id",
    component: () =>
      import(/* webpackChunkName: "disc" */ "../views/pages/discounts/details"),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/discounts/:id/edit",
    component: () =>
      import(/* webpackChunkName: "disc" */ "../views/pages/discounts/edit"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Orders Route
   */
  {
    path: "/orders",
    component: () =>
      import(/* webpackChunkName: "orders" */ "../views/pages/orders/index"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Web Orders Route
   */
  {
    path: "/web-orders",
    component: () =>
      import(/* webpackChunkName: "web-orders" */ "../views/pages/orders/web"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Pos Orders Route
   */
  {
    path: "/pos-orders",
    component: () =>
      import(/* webpackChunkName: "pos-orders" */ "../views/pages/orders/pos"),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Completed Orders Route
   */
  {
    path: "/completed-orders",
    component: () =>
      import(
        /* webpackChunkName: "complete-orders" */ "../views/pages/orders/complete"
      ),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Pending Orders Route
   */
  {
    path: "/new-orders",
    component: () =>
      import(
        /* webpackChunkName: "pending-orders" */ "../views/pages/orders/new"
      ),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/orders/:id",
    component: () =>
      import(/* webpackChunkName: "orders" */ "../views/pages/orders/details"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * POS Route
   */
  {
    path: "/pos",
    component: () =>
      import(/* webpackChunkName: "pos" */ "../views/pages/pos/index"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Invoice Route
   */
  {
    path: "/invoice/:id",
    component: () =>
      import(/* webpackChunkName: "pos" */ "../views/pages/pos/invoice"),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Users Route
   */
  // all
  {
    path: "/users",
    component: () =>
      import(/* webpackChunkName: "users" */ "../views/pages/users/index"),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/users/create",
    component: () =>
      import(/* webpackChunkName: "users" */ "../views/pages/users/create"),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/users/:id",
    component: () =>
      import(/* webpackChunkName: "users" */ "../views/pages/users/details"),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/users/:id/edit",
    component: () =>
      import(/* webpackChunkName: "users" */ "../views/pages/users/edit"),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Locations Route
   */
  // all
  {
    path: "/pages/locations",
    component: () =>
      import(
        /* webpackChunkName: "locs" */ "../views/pages/website/locations/index"
      ),
    meta: {
      middleware: [auth],
    },
  },
  // create
  {
    path: "/pages/locations/create",
    component: () =>
      import(
        /* webpackChunkName: "locs" */ "../views/pages/website/locations/create"
      ),
    meta: {
      middleware: [auth],
    },
  },
  // single
  {
    path: "/pages/locations/:id",
    component: () =>
      import(
        /* webpackChunkName: "locs" */ "../views/pages/website/locations/details"
      ),
    meta: {
      middleware: [auth],
    },
  },
  // edit
  {
    path: "/pages/locations/:id/edit",
    component: () =>
      import(
        /* webpackChunkName: "locs" */ "../views/pages/website/locations/edit"
      ),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Website Pages
   */
  // Home Page
  {
    path: "/pages/home",
    component: () =>
      import(/* webpackChunkName: "pages" */ "../views/pages/website/home.vue"),
    meta: {
      middleware: [auth],
    },
  },
  // Contact Page
  {
    path: "/pages/contact",
    component: () =>
      import(
        /* webpackChunkName: "pages" */ "../views/pages/website/contact.vue"
      ),
    meta: {
      middleware: [auth],
    },
  },

  /**
   * Settings Route
   */
  {
    path: "/settings",

    component: () =>
      import(/* webpackChunkName: "pages" */ "../views/pages/settings/index"),
    meta: {
      middleware: [auth],
    },
  },
  /**
   * Theme
   */
  {
    path: "/theme",

    component: () =>
      import(/* webpackChunkName: "pages" */ "../views/pages/settings/theme"),
    meta: {
      middleware: [auth],
    },
  },
];
