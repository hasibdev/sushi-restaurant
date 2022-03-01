<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <div v-if="user">
         <image-detail :label="$t('label.image')" :url="user.image || ''"></image-detail>
         <input-details :label="$t('label.id')" :value="user.id"></input-details>
         <input-details :label="$t('label.username')" :value="user.username"></input-details>
         <input-details :label="$t('label.full_name')" :value="user.full_name"></input-details>
         <input-details :label="$t('label.email')" :value="user.email"></input-details>
         <input-details :label="$t('label.phone')" :value="user.phone"></input-details>
         <input-details :label="$t('label.role')" :value="user.role"></input-details>
         <input-details :label="$t('label.last_seen')" :value="getLastSeen(user.last_seen)"></input-details>
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import InputDetails from '@/components/custom/InputDetails.vue'
import ImageDetail from '@/components/custom/ImageDetail.vue'
export default {
   name: 'User-details',
   components: {
      LoadingView,
      InputDetails,
      ImageDetail
   },
   data() {
      return {
         title: 'menuitems.users.list.details',
         items: [

            { text: "menuitems.users.list.details", active: true }
         ],
         user: null,
         loading: true,
      }
   },
   methods: {
      getLastSeen(date) {
         return window.jara.Time(date)
      }
   },

   async created() {
      try {
         const res = await this.axios.get(`/users/${this.$route.params.id}`)
         this.user = res.data.payload
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
}
</script>

<style lang="scss" scoped>
</style>