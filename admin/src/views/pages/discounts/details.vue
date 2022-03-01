<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <div v-if="discount">
         <input-details :label="$t('label.code')" :value="discount.code"></input-details>
         <input-details :label="$t('label.name')" :value="discount.name"></input-details>
         <input-details :label="$t('label.value')" :value="`${Math.round(discount.value * 100)}%`"></input-details>
         <input-details :label="$t('label.expire_date')" :value="discount.expire_date"></input-details>
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import InputDetails from '@/components/custom/InputDetails.vue'
export default {
   name: 'Discount-details',
   components: {
      LoadingView,
      InputDetails
   },
   data() {
      return {
         title: 'menuitems.discounts.list.details',
         items: [

            { text: "menuitems.discounts.list.details", active: true }
         ],
         discount: null,
         loading: true,
      }
   },

   async created() {
      try {
         const res = await this.axios.get(`/checkout/discount/${this.$route.params.id}`)
         this.discount = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
}
</script>

<style lang="scss" scoped>
</style>