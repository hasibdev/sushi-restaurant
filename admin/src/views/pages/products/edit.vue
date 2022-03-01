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
        focus
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
      <!-- Price -->
      <valid-input
        v-model="form.price"
        :validation="$v.form.price"
        :label="$t('label.price')"
        type="number"
      ></valid-input>
      <!-- Description -->
      <text-field
        v-model="form.description"
        :label="$t('label.description')"
      ></text-field>

      <!-- Category -->
      <select-control
        v-model="form.categoryId"
        :validation="$v.form.categoryId"
        :options="categories"
        :label="$t('label.category')"
      ></select-control>
      <!-- Order -->
      <valid-input
        v-model="form.order"
        :validation="$v.form.order"
        :label="$t('label.order')"
        type="number"
      ></valid-input>

      <!-- Additions -->
      <multiselect-control
        v-model="form.additions"
        :validation="$v.form.additions"
        :options="additions"
        :label="$t('label.additions')"
      ></multiselect-control>

      <!-- Options -->
      <multiselect-control
        v-model="form.options"
        :validation="$v.form.options"
        :options="options"
        :label="$t('label.options')"
      ></multiselect-control>

      <!-- Footer -->
      <div class="d-flex justify-content-end mt-3">
        <primary-button
          @click="saveData(true)"
          :loading="savingState"
          variant="info"
          >{{ $t("button.update_continue") }}</primary-button
        >
        <primary-button @click="saveData" :loading="savingState" class="mx-2">{{
          $t("button.update")
        }}</primary-button>
        <primary-button to="/products" variant="secondary">{{
          $t("button.cancle")
        }}</primary-button>
      </div>
    </form>
  </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import ValidInput from "@/components/custom/ValidInput";
import PrimaryButton from "@/components/custom/PrimaryButton";
import ImageUpload from "@/components/custom/ImageUpload";
import TextField from "@/components/custom/TextField";
import SelectControl from "@/components/custom/SelectControl";
import MultiselectControl from "@/components/custom/MultiselectControl";
import { required } from "vuelidate/lib/validators";

// Mixins
import services from "@/mixins/services.js";
export default {
  name: "edit-product",
  mixins: [services],
  components: {
    LoadingView,
    ValidInput,
    PrimaryButton,
    ImageUpload,
    TextField,
    SelectControl,
    MultiselectControl,
  },
  data() {
    return {
      title: "menuitems.products.list.edit",
      items: [{ text: "menuitems.products.list.edit", active: true }],

      form: null,
      categories: [],
      additions: [],
      options: [],
      loading: true,
      savingState: false,
      inititalData: null,
    };
  },
  methods: {
    async saveData(isContinue) {
      try {
        await this.updateResource({
          url: "/menu/items",
          data: {
            object: {
              ...this.form,
            },
            id: this.$route.params.id,
          },
        });
        if (!isContinue) {
          this.$router.push("/products");
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  async created() {
    try {
      // Fetch Options
      const res = await this.axios.get("/menu/options");
      this.options = res.data;

      // Fetch Product
      const resProduct = await this.axios.get(
        `/menu/items/${this.$route.params.id}`
      );
      this.form = {
        ...resProduct.data.payload,
        options: resProduct.data.payload.options.map((opt) =>
          opt.id.toString()
        ),
        additions: resProduct.data.payload.additions.map((opt) =>
          opt.id.toString()
        ),
      };

      // Fetch Categories
      const resCategories = await this.axios.get("/menu/categories");
      this.categories = resCategories.data;

      // Fetch Additions
      const resAdditions = await this.axios.get("/menu/additions");
      this.additions = resAdditions.data;

      this.loading = false;
    } catch (error) {
      console.error(error);
    }
  },
  validations: {
    form: {
      name: { required },
      price: { required },
      order: { required },
      categoryId: { required },
      additions: {},
      options: {},
      image: {
        title: {},
        data: { required },
      },
    },
  },
};
</script>

<style lang="scss" scoped></style>
