<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <div v-if="option">
         <input-details :label="$t('label.name')" :value="option.name"></input-details>
         <input-details :label="$t('label.list')" :value="option.list">
            <template #default={value}>
               <b-table :items="value"></b-table>
               <!-- <div v-for="(val, i) in value" :key="i" class="card card-body p-2 mb-1">
                  <p class="mb-0"><strong>{{$t('label.name')}} :</strong> {{val.name}}</p>
                  <p class="mb-0"><strong>{{$t('label.price')}} :</strong> {{currencySymbol}}{{val.price}}</p>
               </div> -->
            </template>
         </input-details>
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import InputDetails from '@/components/custom/InputDetails.vue'
export default {
   name: 'Option-details',
   components: {
      LoadingView,
      InputDetails
   },
   data() {
      return {
         title: 'menuitems.options.list.details',
         items: [
            { text: "menuitems.options.list.details", active: true }
         ],
         option: null,
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
         const res = await this.axios.get(`/menu/options/${this.$route.params.id}`)
         this.option = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
}
</script>

<style lang="scss" scoped>
</style>