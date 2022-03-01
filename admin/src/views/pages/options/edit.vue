<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <form v-if="form" @submit.prevent="saveData">
         <!-- Name -->
         <valid-input focus v-model="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>

         <!-- List -->
         <input-details :label="$t('label.list')" labelClasss="text-sm-right">
            <dynamic-field :fields="fields" v-model="form.list"></dynamic-field>
         </input-details>

         <!-- Footer -->
         <div class="d-flex justify-content-end mt-3">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.update_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.update') }}</primary-button>
            <b-button to="/options" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </LoadingView>
</template>

<script>
import LoadingView from '@/views/layouts/LoadingView.vue'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import { required } from "vuelidate/lib/validators"
import InputDetails from '@/components/custom/InputDetails'
import DynamicField from '@/components/custom/DynamicField'

// Mixins
import services from '@/mixins/services.js'

export default {
   components: {
      LoadingView, ValidInput, PrimaryButton,
      InputDetails, DynamicField
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.options.list.edit',
         items: [
            { text: "menuitems.options.list.edit", active: true }
         ],
         form: null,
         loading: true,
         fields: [
            {
               name: "name", //For v-model
               label: "Name",
               component: "input-control", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "text",
               },
            },
            {
               name: "price", //For v-model
               label: "Price",
               component: "input-control", // Render component type
               value: "", // Initial value
               listen: true,
               props: {
                  type: "number",
               },
            },
         ],
      }
   },
   methods: {
      async saveData(isContinue) {
         try {
            await this.updateResource({ url: '/menu/options', data: { object: this.form, ...{ id: this.$route.params.id } } })
            if (!isContinue) {
               this.$router.push('/options')
            }
         } catch (error) {
            console.log(error)
         }
      },
   },
   async created() {
      try {
         const res = await this.axios.get(`/menu/options/${this.$route.params.id}`)
         this.form = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
   },
   validations: {
      form: {
         name: { required },
         list: {}
      }
   }
}
</script>

<style lang="scss" scoped>
</style>