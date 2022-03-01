<template>
   <DetailsView :title="title" :items="items">
      <form @submit.prevent="saveData">
         <!-- Name -->
         <valid-input focus v-model="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>

         <!-- List -->
         <input-details label="List" labelClasss="text-sm-right">
            <dynamic-field :fields="fields" v-model="form.list"></dynamic-field>
         </input-details>

         <!-- Footer -->
         <div class="d-flex justify-content-end mt-3">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.create_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.create') }}</primary-button>
            <b-button to="/options" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </DetailsView>
</template>

<script>
import DetailsView from '@/views/layouts/DetailsView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import { required } from "vuelidate/lib/validators"
import InputDetails from '@/components/custom/InputDetails'
import DynamicField from '@/components/custom/DynamicField'

// Mixins
import services from '@/mixins/services.js'
export default {
   name: 'create-addition',
   components: {
      DetailsView, ValidInput, PrimaryButton, InputDetails, DynamicField
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.options.list.add',
         items: [{ text: "menuitems.options.list.add", active: true }],
         form: {
            name: '',
            list: [{ name: '', price: '', data: "", url: '' }]
         },
      }
   },
   computed: {
      fields() {
         return [
            {
               name: "data", //For v-model
               label: this.$t('label.image'),
               component: "image-upload", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  validation: this.$v.fake
               },
            },
            {
               name: "name", //For v-model
               label: this.$t('label.name'),
               component: "input-control", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "text",
               },
            },
            {
               name: "price", //For v-model
               label: this.$t('label.price'),
               component: "input-control", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "number",
               },
            },
         ]
      }
   },
   methods: {
      async saveData(isContinue) {
         // const modData = {
         //    ...this.form,
         //    list: this.form.list.map(l => ({ ...l, image: { data: l.image, title: '' } }))
         // }

         try {
            await this.createResource({ url: '/menu/options', data: { object: this.form } })
            if (isContinue) {
               this.resetForm()
            } else {
               this.$router.push('/options')
            }
         } catch (error) {
            console.error(error)
         }
      },
      resetForm() {
         this.form = {
            name: '',
            list: [{ name: '', price: '' }]
         }
      }
   },
   created() {

   },

   validations: {
      form: {
         name: { required },
         list: {},
      },
      fake: {}
   }
}
</script>

<style lang="scss" scoped>
</style>