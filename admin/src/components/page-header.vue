<script>
export default {
   components: {},
   props: {
      title: {
         type: String,
         default: '',
      },
      items: {
         type: Array,
         default: () => {
            return []
         },
      },
   },
   computed: {
      getItems() {
         return [{ text: this.$t('menuitems.home.text'), to: '/' }, ...this.items.map(item => ({ ...item, text: this.$t(item.text) }))]
      }
   },
}
</script>

<template>
   <!-- start page title -->
   <div class="row">
      <div class="col-12">
         <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ $t(title) }}</h4>

            <div class="page-title-right">
               <b-breadcrumb :items="getItems" class="m-0"></b-breadcrumb>
            </div>
         </div>
      </div>
   </div>
   <!-- end page title -->
</template>
