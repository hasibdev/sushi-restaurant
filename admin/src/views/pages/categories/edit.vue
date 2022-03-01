<template>
  <LoadingView :title="title" :items="items" :loading="loading">
    <form v-if="form" @submit.prevent="saveData">
      <!-- Image -->
      <image-upload
        v-model="form.image.data"
        :url="form.image.url"
        :validation="$v.form.image.data"
        :label="$t('label.image')"
      ></image-upload>
      <!-- Caption -->
      <valid-input
        v-model="form.image.title"
        :validation="$v.form.image.title"
        :label="$t('label.image_caption')"
      ></valid-input>
      <!-- Name -->
      <valid-input
        v-model="form.name"
        :validation="$v.form.name"
        :label="$t('label.name')"
      ></valid-input>
      <!-- order -->
      <valid-input
        v-model="form.order"
        :validation="$v.form.order"
        type="number"
        :label="$t('label.order')"
      ></valid-input>

      <!-- Options -->
      <multiselect-control
        v-model="form.options"
        :options="options"
        :validation="$v.form.options"
        :label="$t('label.options')"
      ></multiselect-control>

      <!-- Footer -->
      <div class="d-flex justify-content-end">
        <primary-button
          @click="saveData(true)"
          :loading="savingState"
          variant="info"
          >{{ $t("button.update_continue") }}</primary-button
        >
        <primary-button @click="saveData" :loading="savingState" class="mx-2">{{
          $t("button.update")
        }}</primary-button>
        <b-button to="/categories" variant="secondary">{{
          $t("button.cancle")
        }}</b-button>
      </div>
    </form>
  </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import ValidInput from "@/components/custom/ValidInput";
import PrimaryButton from "@/components/custom/PrimaryButton";
import ImageUpload from "@/components/custom/ImageUpload";
import { required, helpers } from "vuelidate/lib/validators";
import MultiselectControl from "@/components/custom/MultiselectControl";

const alpha = helpers.regex("alpha", /^[a-zA-Z0-9 ]*$/);

// Mixins
import services from "@/mixins/services.js";

export default {
  components: {
    LoadingView,
    ValidInput,
    PrimaryButton,
    ImageUpload,
    MultiselectControl,
  },
  mixins: [services],
  data() {
    return {
      title: "menuitems.categories.list.edit",
      items: [{ text: "menuitems.categories.list.edit", active: true }],
      form: null,

      options: [],
      loading: true,
      // savingState: false
    };
  },
  methods: {
    async saveData(isContinue) {
      await this.updateResource({
        url: "/menu/categories",
        data: { object: this.form, ...{ id: this.$route.params.id } },
      });
      if (!isContinue) {
        this.$router.push("/categories");
      }
    },
  },
  async created() {
    try {
      const res = await this.axios.get(
        `/menu/categories/${this.$route.params.id}`
      );
      this.form = res.data;

      const resOptions = await this.axios.get("/menu/options");
      this.options = resOptions.data;

      this.loading = false;
    } catch (error) {
      console.log(error);
    }
  },
  validations: {
    form: {
      name: { required, alpha },
      order: { required },
      image: {
        title: {},
        data: { required },
      },
      options: {},
    },
  },
};
</script>

<style lang="scss" scoped></style>
