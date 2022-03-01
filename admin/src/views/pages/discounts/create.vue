<template>
   <DetailsView :title="title" :items="items">
      <form @submit.prevent="saveData">
         <!-- Code -->
         <valid-input focus v-model="form.code" :validation="$v.form.code" :label="$t('label.code')"></valid-input>
         <!-- Name -->
         <valid-input v-model="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>
         <!-- Value -->
         <input-details label="Value" :value="null">
            <b-form-input v-model.number="form.value" :label="$t('label.value')" type="range" min="0" max="1" step="0.01" id="discount_range"></b-form-input>
            <p>{{Math.round(form.value * 100)}}%</p>
         </input-details>
         <!-- <valid-input v-model="form.value" type="number" :validation="$v.form.value" :label="$t('label.value')"></valid-input> -->
         <!-- Expire Date -->
         <valid-input v-model="form.expire_date" :validation="$v.form.expire_date" type="date" :label="$t('label.expire_date')"></valid-input>

         <!-- Footer -->
         <div class="d-flex justify-content-end mt-3">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.create_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.create') }}</primary-button>
            <b-button to="/additions" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </DetailsView>
</template>

<script>
import DetailsView from '@/views/layouts/DetailsView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import InputDetails from '@/components/custom/InputDetails'
import { required } from "vuelidate/lib/validators"

// Mixins
import services from '@/mixins/services.js'
export default {
   name: 'create-discount',
   components: {
      DetailsView, ValidInput, PrimaryButton, InputDetails
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.discounts.list.add',
         items: [{ text: "menuitems.discounts.list.add", active: true }],
         form: {
            code: '',
            name: '',
            value: 0,
            expire_date: ''
         },
         // savingState: false,

      }
   },
   methods: {
      async saveData(isContinue) {
         try {
            await this.createResource({ url: '/checkout/discount', data: { object: this.form } })
            if (isContinue) {
               this.resetForm()
            } else {
               this.$router.push('/discounts')
            }
         } catch (error) {
            console.error(error)
         }
      },
      resetForm() {
         this.form = {
            code: '',
            name: '',
            value: '',
            expire_date: ''
         }
      }
   },
   created() {

   },

   validations: {
      form: {
         code: { required },
         name: { required },
         value: { required },
         expire_date: { required }
      }
   }
}
</script>

<style lang="scss" scoped>
</style>