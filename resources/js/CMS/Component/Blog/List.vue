<template>
    <section>
        <div class="header bg-primary pb-6">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6 col-7">
                            <h6 class="h2 text-white d-inline-block mb-0">Blog</h6>
                        </div>
                        <div class="col-lg-6 col-5 text-right">
                            <a href="#" class="btn btn-sm btn-neutral">New</a>
                            <a href="#" class="btn btn-sm btn-neutral">Filters</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <data-table :columns="columns"/>
    </section>
</template>

<script>
    import DataTable from "../Table/DataTable";
    export default {
        name: "List",
        components: { DataTable },
        data() {
            return {
                columns: ['title', 'slug', 'description', 'category', 'created_at']
            }
        }
    }
</script>

<style scoped>

</style>
