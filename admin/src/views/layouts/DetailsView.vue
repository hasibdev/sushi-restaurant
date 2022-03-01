<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "./main.vue"
import PageHeader from "@/components/page-header"
export default {
    props: {
        title: String,
        items: Array
    },
    components: {
        Layout,
        PageHeader
    },
}
</script>

<style lang="scss" scoped>
</style>