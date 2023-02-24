import { createRouter, createWebHistory } from "vue-router";

import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";
import Home from "@/views/Home.vue";
import Profile from "@/views/Profile.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "login",
      component: Login,
    },
    {
      path: "/register",
      name: "register",
      component: Register,
    },
    {
      path: "/home",
      name: "home",
      component: Home,
      meta: {
        auth: true,
      },
    },
    {
      path: "/profile",
      name: "profile",
      component: Profile,
      meta: {
        auth: true,
      },
    },
  ],
});

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.token;

  if (to.matched.some((record) => record.meta.auth)) {
    if (!loggedIn) {
      next("/");
    }
  }

  next();
});

export default router;
