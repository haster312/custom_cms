import BlogList from "./Component/Blog/List";

// Define multiple routers with components
let routes = [
    {
        path: "blog",
        name: "Blog",
        component: BlogList,
        meta: {
            auth: false,
            title: 'Blog'
        }
    }
];

routes.map(route => {
    route.path = "/cms/" + route.path;

    return route;
});

export default (VueRouter) => {
    return new VueRouter({
        mode: 'history',
        routes: routes
    });
};

