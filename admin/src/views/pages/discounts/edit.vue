<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <form v-if="form" @submit.prevent="saveData">
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
         <div class="d-flex justify-content-end">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.update_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.update') }}</primary-button>
            <b-button to="/additions" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import InputDetails from '@/components/custom/InputDetails'
import { required } from "vuelidate/lib/validators"

// Mixins
import services from '@/mixins/services.js'

export default {
   components: {
      LoadingView, ValidInput, PrimaryButton, InputDetails
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.discounts.list.edit',
         items: [

            { text: "menuitems.discounts.list.edit", active: true }
         ],
         form: null,
         loading: true,
         // savingState: false
      }
   },
   methods: {
      async saveData(isContinue) {
         try {
            await this.updateResource({ url: '/checkout/discount', data: { object: this.form, ...{ id: this.$route.params.id } } })
            if (!isContinue) {
               this.$router.push('/discounts')
            }
         } catch (error) {
            console.log(error)
         }
      },
   },
   async created() {
      try {
         const res = await this.axios.get(`/checkout/discount/${this.$route.params.id}`)
         this.form = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
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