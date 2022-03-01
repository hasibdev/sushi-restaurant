<template>
   <Layout>
      <PageHeader :title="title" :items="items" />
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <div v-if="loading" class="d-flex flex-column justify-content-center align-items-center py-5">
                     <b-spinner :label="$t('placeholder.loading')"></b-spinner>
                     <br>
                     <p>{{ $t('placeholder.loading') }}</p>
                  </div>
                  <slot v-else />
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
      items: Array,
      loading: {
         type: Boolean,
         default: true
      }
   },
   components: {
      Layout,
      PageHeader
   },
}
</script>

<style lang="scss" scoped>
</style>