<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <form v-if="form" @submit.prevent="saveData">
         <!-- Image -->
         <!-- <image-upload v-model="form.image.data" :url="form.image.url" :validation="$v.form.image" :label="$t('label.image')"></image-upload> -->
         <!-- Name -->
         <valid-input v-model="form.name" :validation="$v.form.name" :label="$t('label.name')"></valid-input>
         <!-- Price -->
         <valid-input v-model="form.price" :validation="$v.form.price" :label="$t('label.price')"></valid-input>

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
// import ImageUpload from '@/components/custom/ImageUpload'
import ValidInput from '@/components/custom/ValidInput'
import PrimaryButton from '@/components/custom/PrimaryButton'
import { required } from "vuelidate/lib/validators"

// Mixins
import services from '@/mixins/services.js'

export default {
   components: {
      LoadingView, ValidInput, PrimaryButton,
   },
   mixins: [services],
   data() {
      return {
         title: 'menuitems.additions.list.edit',
         items: [

            { text: "menuitems.additions.list.edit", active: true }
         ],
         form: null,
         loading: true,
         // savingState: false
      }
   },
   methods: {
      async saveData(isContinue) {
         try {
            await this.updateResource({ url: '/menu/additions', data: { object: this.form, ...{ id: this.$route.params.id } } })
            if (!isContinue) {
               this.$router.push('/additions')
            }
         } catch (error) {
            console.log(error)
         }
      },
   },
   async created() {
      try {
         const res = await this.axios.get(`/menu/additions/${this.$route.params.id}`)
         this.form = res.data
         this.loading = false
      } catch (error) {
         console.log(error)
      }
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