<template>
  <LoadingView :title="title" :items="items" :loading="loading">
    <form v-if="form" @submit.prevent="saveData">
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
          >{{ $t("button.update_continue") }}</primary-button
        >
        <primary-button @click="saveData" :loading="savingState" class="mx-2">{{
          $t("button.update")
        }}</primary-button>
        <b-button to="/users" variant="secondary">{{
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
import { required } from "vuelidate/lib/validators";
import ImageUpload from "@/components/custom/ImageUpload";
import SelectControl from "@/components/custom/SelectControl";

// Mixins
import services from "@/mixins/services.js";

export default {
  components: {
    LoadingView,
    ValidInput,
    PrimaryButton,
    ImageUpload,
    SelectControl,
  },
  mixins: [services],
  data() {
    return {
      title: "menuitems.users.list.edit",
      items: [{ text: "menuitems.users.list.edit", active: true }],
      form: null,
      loading: true,

      roles: [
        { name: "Super Admin", id: 0 },
        { name: "Admin", id: 1 },
        { name: "Owner", id: 2 },
        { name: "Manager", id: 3 },
        { name: "Seller", id: 4 },
        { name: "Customer", id: 5 },
      ],
    };
  },
  methods: {
    async saveData(isContinue) {
      try {
        await this.updateResource({
          url: "/users",
          data: { ...this.form, ...{ id: this.$route.params.id } },
        });
        if (!isContinue) {
          this.$router.push("/users");
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  async created() {
    try {
      const res = await this.axios.get(`/users/${this.$route.params.id}`);

      let roles = {
        "Super Admin": 0,
        Admin: 1,
        Owner: 2,
        Manager: 3,
        Seller: 4,
        Customer: 5,
      };
      this.form = {
        ...res.data.payload,
        name: res.data.payload.full_name,
        role: roles[res.data.payload.role],
      };

      if (!this.form.meta) {
        this.form.meta = {
          country: "",
          city: "",
          zip: "",
          address: "",
        };
      }

      if (!this.form.meta.address) {
        this.form.meta.address = "";
      }

      if (!this.form.meta.zip) {
        this.form.meta.zip = "";
      }

      if (!this.form.meta.city) {
        this.form.meta.city = "";
      }

      if (!this.form.meta.country) {
        this.form.meta.country = "";
      }

      this.loading = false;
    } catch (error) {
      console.log(error);
    }
  },
  validations: {
    form: {
      image: {},
      username: { required },
      name: { required },
      phone: { required },
      email: { required },
      role: { required },
      meta: {},
    },
  },
};
</script>

<style lang="scss" scoped></style>
