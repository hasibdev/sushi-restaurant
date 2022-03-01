<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <form v-if="form" @submit.prevent="saveData">
         <!-- Name -->
         <valid-input focus v-model.trim="form.content.name" :validation="$v.form.content.name" :label="$t('label.name')"></valid-input>
         <!-- Street -->
         <valid-input v-model="form.content.address.street" :validation="$v.form.content.address.street" :label="$t('label.street')"></valid-input>
         <!-- City -->
         <valid-input v-model="form.content.address.city" :validation="$v.form.content.address.city" :label="$t('label.city')"></valid-input>
         <!-- Country -->
         <valid-input v-model="form.content.address.contry" :validation="$v.form.content.address.contry" :label="$t('label.country')"></valid-input>
         <!-- Phone -->
         <valid-input v-model="form.content.phone" :validation="$v.form.content.phone" :label="$t('label.phone')" type="tel"></valid-input>
         <!-- Email -->
         <valid-input v-model.trim="form.content.email" :validation="$v.form.content.email" :label="$t('label.email')" type="email"></valid-input>
         <!-- Lat -->
         <valid-input v-model.number.trim="form.content.lat" :validation="$v.form.content.lat" :label="$t('label.lat')" type="number"></valid-input>
         <!-- Lan -->
         <valid-input v-model.number.trim="form.content.lng" :validation="$v.form.content.lng" :label="$t('label.lng')" type="number"></valid-input>

         <!-- Opening hours -->
         <h5 class="mt-5 mb-3">{{ $t('heading.open_hours') }}</h5>
         <dynamic-field :fields="openHoursFields" v-model="form.content.openHours"></dynamic-field>

         <!-- Footer -->
         <div class="d-flex justify-content-end">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.update_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.update') }}</primary-button>
            <b-button to="/pages/locations" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import { required } from "vuelidate/lib/validators"
import DynamicField from '@/components/custom/DynamicField'
// Mixins
import services from '@/mixins/services.js'

export default {
   components: {
      LoadingView, ValidInput, PrimaryButton, DynamicField
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.locations.list.edit',
         items: [
            { text: "menuitems.locations.list.edit", active: true }
         ],
         form: null,
         loading: true,
         days: [{ name: 'Sunday', id: 0 }, { name: 'Monday', id: 1 }, { name: 'Tuesday', id: 2 }, { name: 'Wednesday', id: 3 }, { name: 'Thursday', id: 4 }, { name: 'Friday', id: 5 }, { name: 'Saturday', id: 6 }],
      }
   },
   computed: {
      openHoursFields() {
         return [
            {
               name: "days", //For v-model
               label: "Days",
               component: "multiselect-control", // Render component type
               value: "", // Initial value     

               props: {
                  options: this.days,
                  validation: this.$v.form.content.openHours
               },
            },
            {
               name: "openHour", //For v-model
               label: "Open Hour",
               component: "input-control", // Render component type
               value: "", // Initial value     

               props: {
                  type: 'time'
               },
            },
            {
               name: "closeHour", //For v-model
               label: "Close Hour",
               component: "input-control", // Render component type
               value: "", // Initial value     

               props: {
                  type: 'time'
               },
            },
         ]
      }
   },
   methods: {
      async saveData(isContinue) {
         try {
            await this.updateResource({ url: '/locations', data: { object: this.form.content, ...{ id: this.$route.params.id } } })
            if (!isContinue) {
               this.$router.push('/pages/locations')
            }
         } catch (error) {
            console.log(error)
         }
      },
   },
   async created() {
      try {
         const res = await this.axios.get(`/location/${this.$route.params.id}`)
         this.form = res.data.payload
      } catch (error) {
         console.log(error)
      } finally {
         this.loading = false
      }
   },
   validations: {
      form: {
         content: {
            name: { required },
            address: {
               street: { required },
               city: { required },
               contry: { required },
            },

            phone: { required },
            email: { required },
            lat: { required },
            lng: { required },

            openHours: { required }
         }
      }
   }
}
</script>

<style lang="scss" scoped>
</style>