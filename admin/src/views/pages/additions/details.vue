<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <div v-if="addition">
         <input-details :label="$t('label.name')" :value="addition.name"></input-details>
         <input-details :label="$t('label.price')" :value="`${currencySymbol}${addition.price}`"></input-details>
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import InputDetails from '@/components/custom/InputDetails.vue'
export default {
   name: 'Addition-details',
   components: {
      LoadingView,
      InputDetails
   },
   data() {
      return {
         title: 'menuitems.additions.list.details',
         items: [

            { text: "menuitems.additions.list.details", active: true }
         ],
         addition: null,
         loading: true,
      }
   },

   computed: {
      currencySymbol() {
         return this.$store.state.settings.data.currencySymbol
      }
   },

   async created() {
      try {
         const res = await this.axios.get(`/menu/additions/${this.$route.params.id}`)
         this.addition = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
}
</script>

<style lang="scss" scoped>
</style>