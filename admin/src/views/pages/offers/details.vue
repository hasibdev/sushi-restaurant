<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <div v-if="offer">
         <image-detail :label="$t('label.image')" :url="offer.image.url"></image-detail>
         <input-details :label="$t('label.image_caption')" :value="offer.image.title"></input-details>
         <input-details :label="$t('label.name')" :value="offer.name"></input-details>
         <input-details :label="$t('label.description')" :value="offer.description"></input-details>
         <input-details :label="$t('label.discount')" :value="`${Math.round(offer.discountValue * 100)}%`"></input-details>
         <input-details :label="$t('label.type')" :value="titleCase(offer.type)"></input-details>
         <input-details :label="$t('label.conditions')" :value="offer.conditions">
            <template #default="{value}">
               <b-table :items="value" :fields="conditionsField"></b-table>
            </template>
         </input-details>
         <!-- <input-details label="Value" :value="`${offer.discountValue * 100}%`"></input-details> -->
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue"
import InputDetails from "@/components/custom/InputDetails.vue"
import ImageDetail from "@/components/custom/ImageDetail.vue"
import helpers from "@/mixins/helpers.js"
export default {
   name: "offer-details",
   components: {
      LoadingView,
      InputDetails,
      ImageDetail,
   },
   mixins: [helpers],
   data() {
      return {
         title: "menuitems.offers.list.details",
         items: [{ text: "menuitems.offers.list.details", active: true }],
         offer: null,
         loading: true,
         conditionsField: [{ key: 'name' }, { key: 'type', formatter: val => this.titleCase(val) }, { key: 'value' }]
      }
   },

   async created() {
      try {
         const res = await this.axios.get(`/menu/offers/${this.$route.params.id}`)
         this.offer = res.data.payload
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
};
</script>

<style lang="scss" scoped></style>
