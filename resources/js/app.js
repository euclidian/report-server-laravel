/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
import "vuetify/dist/vuetify.min.css"; // Ensure you are using css-loader

import VueRouter from "vue-router";
import UserComponent from "./components/UserComponent.vue";
import ProfileComponent from "./components/ProfileComponent.vue";
import TemplateComponent from "./components/TemplateComponent.vue";
import PrintedComponent from "./components/PrintedComponent.vue";
import PrintTransactionComponent from "./components/PrintTransactionComponent.vue";
import TemplateGalleryComponent from "./components/TemplateGalleryComponent.vue";
import colors from "vuetify/es5/util/colors";

window.Vue = require("vue");
window.Vuetify = require("vuetify");

Vue.use(Vuetify);
Vue.use(VueRouter);
window.axios = require("axios");

window.axios.defaults.headers.common = {
    "X-Requested-With": "XMLHttpRequest"
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component("login-component", require("./components/LoginComponent.vue"));
Vue.component("home-component", require("./components/App.vue"));

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/profile",
            name: "profile",
            component: ProfileComponent
        },
        {
            path: "/template",
            name: "template",
            component: TemplateComponent
        },
        {
            path: "/print",
            name: "print",
            component: PrintedComponent
        },
        {
            path: "/print_transaction",
            name: "print_transaction",
            component: PrintTransactionComponent
        },
        {
            path: "/users",
            name: "users",
            component: UserComponent
        },
        {
            path: "/template_gallery",
            name: "template_gallery",
            component: TemplateGalleryComponent
        }
    ]
});

const app = new Vue({
    el: "#app",
    router
});
Vue.prototype.$vuetify.theme = {
    primary: colors.red.darken2,
    secondary: colors.grey.darken1,
    accent: colors.shades.black,
    error: colors.red.accent3
  };
