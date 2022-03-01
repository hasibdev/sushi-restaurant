<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <div v-if="location">
         <input-details :label="$t('label.name')" :value="location.content.name"></input-details>
         <input-details :label="$t('label.email')" :value="location.content.email"></input-details>
         <input-details :label="$t('label.phone')" :value="location.content.phone"></input-details>
         <input-details :label="$t('label.street')" :value="location.content.address.street"></input-details>
         <input-details :label="$t('label.city')" :value="location.content.address.city"></input-details>
         <input-details :label="$t('label.country')" :value="location.content.address.contry"></input-details>
         <input-details :label="$t('label.lat')" :value="location.content.lat"></input-details>
         <input-details :label="$t('label.lng')" :value="location.content.lng"></input-details>

         <h4 class="my-3">{{ $t('heading.open_hours') }}</h4>
         <b-table :items="location.content.openHours" :fields="hoursField"></b-table>
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import InputDetails from '@/components/custom/InputDetails.vue'
export default {
   name: 'Location-details',
   components: {
      LoadingView,
      InputDetails
   },
   data() {
      return {
         title: 'menuitems.locations.list.details',
         items: [
            { text: "menuitems.locations.list.details", active: true }
         ],
         location: null,
         days: [{ name: 'Sunday', id: 0 }, { name: 'Monday', id: 1 }, { name: 'Tuesday', id: 2 }, { name: 'Wednesday', id: 3 }, { name: 'Thursday', id: 4 }, { name: 'Friday', id: 5 }, { name: 'Saturday', id: 6 }],

         hoursField: [
            { key: 'openHour', label: this.$t('label.openHour') },
            { key: 'closeHour', label: this.$t('label.closeHour') },
            { key: 'days', label: this.$t('label.days'), formatter: value => value.map(id => this.days.find(d => d.id == id).name).join(', ') },
         ],

         loading: true,
      }
   },

   async created() {
      try {
         const res = await this.axios.get(`/location/${this.$route.params.id}`)
         this.location = res.data.payload
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
}
</script>

<style lang="scss" scoped>
</style>