<template>
    <section>
        <sidebar/>
        <div class="main-content" id="panel">
            <navbar/>
            <div class="content">
                <router-view />
            </div>
            <footer/>
        </div>
    </section>
</template>

<script>
    import Sidebar from "./Sidebar";
    import Navbar from "./Navbar";
    import Footer from "./Footer";

    export default {
        name: "MainContent",
        components: {Footer, Navbar, Sidebar}
    }
</script>
