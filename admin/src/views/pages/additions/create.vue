<template>
   <DetailsView :title="title" :items="items">
      <form @submit.prevent="saveData">
         <!-- Image -->
         <image-upload v-model="form.image.data" :validation="$v.form.image" :label="$t('label.image')"></image-upload>
         <!-- Name -->
         <valid-input focus v-model="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>
         <!-- Price -->
         <valid-input v-model="form.price" :validation="$v.form.price" type="number" :label="$t('label.price')"></valid-input>

         <!-- Footer -->
         <div class="d-flex justify-content-end">
            <primary-button @click="saveData(true)" :loading="savingState" variant="info">{{ $t('button.create_continue') }}</primary-button>
            <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.create') }}</primary-button>
            <b-button to="/additions" variant="secondary">{{ $t('button.cancle') }}</b-button>
         </div>

      </form>
   </DetailsView>
</template>

<script>
import DetailsView from '@/views/layouts/DetailsView.vue'
import ImageUpload from '@/components/custom/ImageUpload'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import { required } from "vuelidate/lib/validators"

// Mixins
import services from '@/mixins/services.js'
export default {
   name: 'create-addition',
   components: {
      DetailsView, ValidInput, PrimaryButton, ImageUpload
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.additions.list.add',
         items: [{ text: "menuitems.additions.list.add", active: true }],
         form: {
            name: '',
            price: '',
            image: {
               title: '',
               data: ''
            },
         },
         // savingState: false,

      }
   },
   methods: {
      async saveData(isContinue) {
         try {
            await this.createResource({ url: '/menu/additions', data: { object: this.form } })
            if (isContinue) {
               this.resetForm()
            } else {
               this.$router.push('/additions')
            }
         } catch (error) {
            console.error(error)
         }
      },
      resetForm() {
         this.form = {
            name: '',
            price: '',
            image: {
               title: '',
               data: ''
            },
         }
      }
   },
   created() {

   },

   validations: {
      form: {
         name: { required },
         price: { required },
         image: {}
      }
   }
}
</script>

<style lang="scss" scoped>
</style>