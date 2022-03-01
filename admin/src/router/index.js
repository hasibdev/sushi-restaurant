import Vue from "vue";
import VueRouter from "vue-router";
import VueMeta from "vue-meta";

import routes from "./routes";

import middlewarePipeline from "@/middleware/middlewarePipeline";

Vue.use(VueRouter);
Vue.use(VueMeta, {
  // The component option name that vue-meta looks for meta info on.
  keyName: "page",
});

const router = new VueRouter({
  routes,
  mode: "history",
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { x: 0, y: 0 };
    }
  },
});

// Befor Route
router.beforeEach((to, from, next) => {
  if (document.getElementById("preloader")) {
    document.getElementById("preloader").style.display = "block";
    document.getElementById("status").style.display = "block";
  }
  if (!to.meta.middleware) {
    return next();
  }
  const middleware = Array.isArray(to.meta.middleware)
    ? to.meta.middleware
    : [to.meta.middleware];

  const context = {
    to,
    from,
    next,
    router,
  };

  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1),
  });
});

router.afterEach(() => {
  if (document.getElementById("preloader")) {
    document.getElementById("preloader").style.display = "none";
    document.getElementById("status").style.display = "none";
  }
});

router.beforeResolve(async (routeTo, routeFrom, next) => {
  try {
    for (const route of routeTo.matched) {
      await new Promise((resolve, reject) => {
        if (route.meta && route.meta.beforeResolve) {
          route.meta.beforeResolve(routeTo, routeFrom, (...args) => {
            if (args.length) {
              // Complete the redirect.
              next(...args);
              reject(new Error("Redirected"));
            } else {
              resolve();
            }
          });
        } else {
          // Otherwise, continue resolving the route.
          resolve();
        }
      });
    }
    // If a `beforeResolve` hook chose to redirect, just return.
  } catch (error) {
    return;
  }

  // If we reach this point, continue resolving the route.
  next();
});

export default router;
