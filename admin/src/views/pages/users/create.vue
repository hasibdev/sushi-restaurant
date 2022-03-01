<template>
  <DetailsView :title="title" :items="items">
    <form @submit.prevent="saveData">
      <!-- Image -->
      <h3 class="mb-4">Avatar</h3>
      <image-upload
        v-model="form.image"
        :validation="$v.form.image"
        :label="$t('label.image')"
      ></image-upload>
      <h3 class="mb-4">Basic Info</h3>
      <!-- Username -->
      <valid-input
        focus
        v-model="form.username"
        :validation="$v.form.username"
        :label="$t('label.username')"
      ></valid-input>
      <!-- Full Name -->
      <valid-input
        v-model="form.name"
        :validation="$v.form.name"
        :label="$t('label.full_name')"
      ></valid-input>
      <!-- Email -->
      <valid-input
        v-model="form.email"
        :validation="$v.form.email"
        :label="$t('label.email')"
      ></valid-input>
      <!-- Phone -->
      <valid-input
        v-model="form.phone"
        :validation="$v.form.phone"
        :label="$t('label.phone')"
      ></valid-input>
      <!-- Password -->
      <valid-input
        v-model="form.password"
        :validation="$v.form.password"
        type="password"
        :label="$t('label.password')"
      ></valid-input>

      <!-- Role -->
      <select-control
        v-model="form.role"
        :label="$t('label.role')"
        :options="roles"
        :validation="$v.form.role"
      ></select-control>

      <!-- contact info -->
      <h3 class="mb-4">Contact Info</h3>
      <valid-input
        focus
        v-model="form.meta.country"
        :validation="$v.form.meta"
        label="Country"
      ></valid-input>

      <valid-input
        focus
        v-model="form.meta.city"
        :validation="$v.form.meta"
        label="City"
      ></valid-input>

      <valid-input
        focus
        v-model="form.meta.zip"
        :validation="$v.form.meta"
        label="Zip Code"
      ></valid-input>

      <valid-input
        focus
        v-model="form.meta.address"
        :validation="$v.form.meta"
        label="Street"
      ></valid-input>

      <!-- Footer -->
      <div class="d-flex justify-content-end">
        <primary-button
          @click="saveData(true)"
          :loading="savingState"
          variant="info"
          >{{ $t("button.create_continue") }}</primary-button
        >
        <primary-button @click="saveData" :loading="savingState" class="mx-2">{{
          $t("button.create")
        }}</primary-button>
        <b-button to="/users" variant="secondary">{{
          $t("button.cancle")
        }}</b-button>
      </div>
    </form>
  </DetailsView>
</template>

<script>
import DetailsView from "@/views/layouts/DetailsView.vue";
import ValidInput from "@/components/custom/ValidInput";
import PrimaryButton from "@/components/custom/PrimaryButton";
import ImageUpload from "@/components/custom/ImageUpload";
import SelectControl from "@/components/custom/SelectControl";
import { required } from "vuelidate/lib/validators";

// Mixins
import services from "@/mixins/services.js";
export default {
  name: "create-user",
  components: {
    DetailsView,
    ValidInput,
    PrimaryButton,
    ImageUpload,
    SelectControl,
  },
  mixins: [services],
  data() {
    return {
      title: "menuitems.users.list.add",
      items: [{ text: "menuitems.users.list.add", active: true }],
      form: {
        image: "",
        username: "",
        name: "",
        email: "",
        phone: "",
        password: "",
        role: 5,
        meta: {
          address: "",
          zip: "",
          city: "",
          country: "",
        },
      },

      roles: [
        { name: "Super Admin", id: 0 },
        { name: "Admin", id: 1 },
        { name: "Owner", id: 2 },
        { name: "Manager", id: 3 },
        { name: "Seller", id: 4 },
        { name: "Customer", id: 5 },
      ],
      // savingState: false,
    };
  },
  methods: {
    async saveData(isContinue) {
      try {
        await this.createResource({ url: "/users", data: { ...this.form } });
        if (isContinue) {
          this.resetForm();
        } else {
          this.$router.push("/users");
        }
      } catch (error) {
        console.error(error);
      }
    },
    resetForm() {
      this.form = {
        image: "",
        username: "",
        name: "",
        email: "",
        phone: "",
        password: "",
        role: 5,
        meta: {
          address: "",
          zip: "",
          city: "",
          country: "",
        },
      };
    },
  },
  created() {},

  validations: {
    form: {
      image: {},
      username: { required },
      name: { required },
      email: { required },
      phone: { required },
      password: { required },
      role: { required },
      meta: {},
    },
  },
};
</script>

<style lang="scss" scoped></style>
