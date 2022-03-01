<template>
   <LoadingView :title="title" :items="items" :loading="loading">
      <image-upload :label="$t('label.header_logo')" v-model="form.headerLogoLight" :url="form.headerLogoLight" :validation="$v.form.headerLogoLight"></image-upload>
      <image-upload :label="$t('label.footer_logo')" v-model="form.footerLogo" :url="form.footerLogo" :validation="$v.form.footerLogo"></image-upload>
      <input-control :label="$t('label.theme_color')" v-model="form.themeColor" type="color"></input-control>

      <!-- Footer Buttons -->
      <div class="d-flex justify-content-end align-items-center mt-4">
         <primary-button @click="saveData" :loading="savingState" class="mx-2">{{ $t('button.save') }}</primary-button>
      </div>
   </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue"
import ImageUpload from "@/components/custom/ImageUpload"
import InputControl from "@/components/custom/InputControl"
import PrimaryButton from "@/components/custom/PrimaryButton"
import { required } from "vuelidate/lib/validators"

export default {
   components: {
      LoadingView,
      ImageUpload,
      InputControl,
      PrimaryButton,
   },
   data() {
      return {
         title: "menuitems.theme.text",
         items: [{ text: "menuitems.theme.text", active: true }],
         form: {
            headerLogoLight: "",
            footerLogo: "",
            themeColor: "",
         },

         savingState: false,
         loading: false,
      }
   },
   methods: {
      async saveData() {
         this.savingState = true
         try {
            await this.axios.post("/theme", { object: this.form })
            this.$toast.success("Updated Successfully")
         } catch (error) {
            console.log(error)
         } finally {
            this.savingState = false
         }
      },
   },
   async created() {
      this.loading = true
      try {
         const res = await this.axios.get("/theme")
         this.form = res.data
      } catch (error) {
         console.log(error)
      } finally {
         this.loading = false
      }
   },
   validations: {
      form: {
         headerLogoLight: { required },
         footerLogo: { required },
      },
   },
};
</script>

<style lang="scss" scoped></style>
