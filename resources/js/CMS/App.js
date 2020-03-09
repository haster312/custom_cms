// Load important modules
import Vue from "vue";
import VueResource from "vue-resource";
import VueRouter from "vue-router";
import Router from "./Router";

// Load router
Vue.use(VueRouter);
const router = new Router(VueRouter);

// Load main component
import MainContent from './Template/Main';

let app = new Vue({
    el: '#cms',
    router: router,
    watch: {
        '$route' (to, from) {
            document.title = to.meta.title || 'Will CMS'
        }
    },
    components: {
        mainContent: MainContent
    }
});
