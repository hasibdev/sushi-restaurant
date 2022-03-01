<template>
   <DetailsView :title="title" :items="items">
      <form @submit.prevent="saveData">
         <!-- Name -->
         <valid-input focus v-model.trim="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>
         <!-- Street -->
         <valid-input v-model="form.address.street" :validation="$v.form.address.street" :label="$t('label.street')"></valid-input>
         <!-- City -->
         <valid-input v-model="form.address.city" :validation="$v.form.address.city" :label="$t('label.city')"></valid-input>
         <!-- Country -->
         <valid-input v-model="form.address.contry" :validation="$v.form.address.contry" :label="$t('label.country')"></valid-input>
         <!-- Phone -->
         <valid-input v-model="form.phone" :validation="$v.form.phone" :label="$t('label.phone')" type="tel"></valid-input>
         <!-- Email -->
         <valid-input v-model.trim="form.email" :validation="$v.form.email" :label="$t('label.email')" type="email"></valid-input>
         <!-- Lat -->
         <valid-input v-model.number.trim="form.lat" :validation="$v.form.lat" :label="$t('label.lat')" type="number"></valid-input>
         <!-- Lan -->
         <valid-input v-model.number.trim="form.lng" :validation="$v.form.lng" :label="$t('label.lng')" type="number"></valid-input>

         <!-- Opening hours -->
         <h5 class="mt-5 mb-3">{{ $t('heading.open_hours') }}</h5>
         <dynamic-field :fields="openHoursFields" v-model="form.openHours"></dynamic-field>

         <!-- Footer -->
         <div class="d-flex justify-content-end">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.create_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.create') }}</primary-button>
            <b-button to="/locations" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </DetailsView>
</template>

<script>
import DetailsView from '@/views/layouts/DetailsView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import DynamicField from '@/components/custom/DynamicField'
import { required } from "vuelidate/lib/validators"

// Mixins
import services from '@/mixins/services.js'
export default {
   name: 'create-location',
   components: {
      DetailsView, ValidInput, PrimaryButton, DynamicField
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.locations.list.add',
         items: [{ text: "menuitems.locations.list.add", active: true }],
         form: {
            name: '',
            address: {
               street: '',
               city: '',
               contry: ''
            },
            phone: '',
            email: '',
            lat: '',
            lng: '',
            openHours: [
               {
                  days: [],
                  openHour: '',
                  closeHour: ''
               },
               {
                  days: [],
                  openHour: '',
                  closeHour: ''
               },
               {
                  days: [],
                  openHour: '',
                  closeHour: ''
               }
            ]
         },

         days: [{ name: 'Sunday', id: 0 }, { name: 'Monday', id: 1 }, { name: 'Tuesday', id: 2 }, { name: 'Wednesday', id: 3 }, { name: 'Thursday', id: 4 }, { name: 'Friday', id: 5 }, { name: 'Saturday', id: 6 }],

      }
   },
   computed: {
      openHoursFields() {
         return [
            {
               name: "days", //For v-model
               label: this.$t('label.days'),
               component: "multiselect-control", // Render component type
               value: "", // Initial value     

               props: {
                  options: this.days,
                  validation: this.$v.form.openHours
               },
            },
            {
               name: "openHour", //For v-model
               label: this.$t('label.openHour'),
               component: "input-control", // Render component type
               value: "", // Initial value     

               props: {
                  type: 'time'
               },
            },
            {
               name: "closeHour", //For v-model
               label: this.$t('label.closeHour'),
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
            await this.createResource({ url: '/locations', data: { object: this.form } })
            if (isContinue) {
               this.resetForm()
            } else {
               this.$router.push('/pages/locations')
            }
         } catch (error) {
            console.error(error)
         }
      },
      resetForm() {
         this.form = {
            name: ''
         }
      }
   },
   created() {

   },

   validations: {
      form: {
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
</script>

<style lang="scss" scoped>
</style>