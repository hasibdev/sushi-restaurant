<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <form @submit.prevent="saveData">
         <!-- Image -->
         <image-upload v-model="form.image.data" :validation="$v.form.image.data" :label="$t('label.image')"></image-upload>
         <!-- Caption -->
         <valid-input v-model="form.image.title" :validation="$v.form.image.title" :label="$t('label.image_caption')"></valid-input>
         <!-- Name -->
         <valid-input v-model="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>
         <!-- description -->
         <text-field v-model="form.description" :label="$t('label.description')"></text-field>

         <!-- Type -->
         <select-control :label="$t('label.type')" v-model="form.type" @input="onTypeChange" :options="typeOptions" :validation="$v.form.type"></select-control>
         <!-- Product -->
         <select-control v-if="form.type == 'FREE_PRODUCT'" :label="$t('label.product')" v-model="form.freeProductId" :options="products" :validation="$v.form.freeProductId"></select-control>
         <!-- Discount Amount -->
         <input-details v-if="form.type == 'DISCOUNT'" v-model="form.discountValue" :label="$t('label.discount_amount')" :value="null">
            <b-form-input v-model.number="form.discountValue" :label="$t('label.value')" type="range" min="0" max="1" step="0.01" id="discount_range"></b-form-input>
            <p>{{Math.round(form.discountValue * 100)}}%</p>
         </input-details>
         <!-- <valid-input v-if="form.type == 'DISCOUNT'" v-model="form.discountValue" :validation="$v.form.discountValue" :label="$t('label.discount_amount')" type="number"></valid-input> -->

         <!-- Conditions -->
         <input-details :label="$t('label.conditions')" labelClasss="text-sm-right">
            <repeatable @onAdd="onRepetableAdd" @onRemove="onRepetableRemove" @fieldChange="onfieldChange" :fields="conditionfields" v-model="form.conditions"></repeatable>
         </input-details>

         <!-- Footer -->
         <div class="d-flex justify-content-end mt-3">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.create_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.create') }}</primary-button>
            <b-button to="/additions" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import TextField from '@/components/custom/TextField'
import SelectControl from '@/components/custom/SelectControl'
import ImageUpload from '@/components/custom/ImageUpload'
import InputDetails from '@/components/custom/InputDetails'
import Repeatable from '@/components/custom/Repeatable'
import { required } from "vuelidate/lib/validators"

// Mixins
import services from '@/mixins/services.js'
export default {
   name: 'create-offer',
   components: {
      LoadingView, ValidInput, PrimaryButton, TextField, SelectControl, ImageUpload, InputDetails,
      Repeatable
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.offers.list.add',
         items: [{ text: "menuitems.offers.list.add", active: true }],
         loading: true,
         form: {
            name: '',
            description: '',
            type: 'DISCOUNT',
            freeProductId: '',
            discountValue: 0,
            conditions: [{ name: '', type: '', value: '' }],
            image: {
               title: '',
               data: ''
            }
         },

         products: [],
         categories: [],

         typeOptions: [{ name: 'Free Product', id: 'FREE_PRODUCT' }, { name: 'Discount', id: 'DISCOUNT' }],
         days: [{ name: 'Sunday', id: 0 }, { name: 'Monday', id: 1 }, { name: 'Tuesday', id: 2 }, { name: 'Wednesday', id: 3 }, { name: 'Thursday', id: 4 }, { name: 'Friday', id: 5 }, { name: 'Saturday', id: 6 }],

         conditionfields: [
            [
               {
                  name: "name", //For v-model
                  label: 'Name',
                  component: "input-control", // Render component type
                  value: "", // Initial value                  
                  props: {
                     type: "text",
                  },
               },
               {
                  name: "type", //For v-model
                  label: 'Type',
                  component: "select-field", // Render component type
                  value: "", // Initial value                  
                  props: {
                     type: "text",
                     options: [{ name: 'Day', id: 'DAY' }, { name: 'Minimum Order', id: 'MINIMUM_ORDER_VALUE' }, { name: 'Category', id: 'CATEGORY_ID' }],
                  },
               },
               {
                  name: "value", //For v-model
                  label: 'Value',
                  component: "input-control", // Render component type
                  value: "", // Initial value                  
                  props: {
                     type: "text",
                  },
               },
            ],
         ],

      }
   },
   methods: {
      async saveData(isContinue) {
         if (this.form.type != 'FREE_PRODUCT') delete this.form.freeProductId
         if (this.form.type != 'DISCOUNT') delete this.form.discountValue

         try {
            await this.createResource({ url: '/menu/offers', data: { object: this.form } })
            if (isContinue) {
               this.resetForm()
            } else {
               this.$router.push('/offers')
            }
         } catch (error) {
            console.error(error)
         }
      },
      onRepetableAdd() {
         this.form.conditions.push({ name: '', type: '', value: '' })
         this.conditionfields.push([
            {
               name: "name", //For v-model
               label: 'Name',
               component: "input-control", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "text",
               },
            },
            {
               name: "type", //For v-model
               label: 'Type',
               component: "select-field", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "text",
                  options: [{ name: 'Day', id: 'DAY' }, { name: 'Minimum Order', id: 'MINIMUM_ORDER_VALUE' }, { name: 'Category', id: 'CATEGORY_ID' }],
               },
            },
            {
               name: "value", //For v-model
               label: 'Value',
               component: "input-control", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "text",
               },
            },
         ])
      },
      onRepetableRemove({ index }) {
         this.form.conditions = this.form.conditions.filter((val, i) => i != index)
         this.conditionfields = this.conditionfields.filter((val, i) => i != index)
      },
      async onTypeChange(val) {
         if (val == 'FREE_PRODUCT') {
            const resProducts = await this.axios.get('/menu/items')
            this.products = resProducts.data
         }
      },
      onfieldChange(val) {
         if (val.name == 'type') {
            if (val.value == 'CATEGORY_ID') {
               this.conditionfields[val.index][2] = {
                  name: "value", //For v-model
                  component: "select-field", // Render component type
                  value: "", // Initial value
                  listen: true,
                  props: {
                     type: "text",
                     options: this.categories
                  }
               }

            }
            if (val.value == 'DAY') {
               this.conditionfields[val.index][2] = {
                  name: "value", //For v-model
                  component: "select-field", // Render component type
                  value: "", // Initial value                  
                  props: {
                     type: "text",
                     options: this.days
                  }
               }

            }
            if (val.value == 'MINIMUM_ORDER_VALUE') {
               this.conditionfields[val.index][2] = {
                  name: "value", //For v-model
                  component: "input-control", // Render component type
                  value: "", // Initial value
                  listen: true,
                  props: {
                     type: "text",
                  },
               }

            }
         }
      },
      resetForm() {
         this.form = {
            name: '',
            description: '',
            type: 'DISCOUNT',
            freeProductId: '',
            discountValue: 0,
            conditions: [{ name: '', type: '', value: '' }],
            image: {
               title: '',
               data: ''
            }
         }
      }
   },
   async created() {
      try {
         const res = await this.axios.get('/menu/categories')
         this.categories = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },

   validations: {
      form: {
         name: { required },
         type: { required },
         freeProductId: {},
         discountValue: {},
         image: {
            title: {},
            data: { required }
         }
      },
   }
}
</script>

<style lang="scss" scoped>
</style>