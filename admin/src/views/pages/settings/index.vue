<template>
  <LoadingView :title="title" :items="items" :loading="loading">
    <div v-if="form">
      <!-- Tab -->
      <div class="d-flex justify-content-center align-items-center mb-4">
        <b-button variant="primary" @click="activeTab = 0" class="mr-1">{{
          $t("heading.site_information")
        }}</b-button>
        <b-button variant="info" @click="activeTab = 1" class="ml-1">{{
          $t("heading.delivery_settings")
        }}</b-button>
      </div>

      <!-- Site Info -->
      <div v-if="activeTab == 0">
        <input-details :label="$t('label.is_open')" labelClasss="text-sm-right">
          <switches v-model="form.isOpen" color="primary"></switches>
        </input-details>
        <br />

        <input-control
          :label="$t('label.minimum_cart_value')"
          v-model="form.minimumCartValue"
          type="number"
        ></input-control>

        <input-control
          :label="$t('label.tax')"
          v-model="form.tax"
          type="number"
        ></input-control>

        <valid-input
          :label="$t('label.street')"
          :validation="$v.form.address.street"
          v-model="form.address.street"
        ></valid-input>
        <valid-input
          :label="$t('label.city')"
          :validation="$v.form.address.city"
          v-model="form.address.city"
        ></valid-input>
        <valid-input
          :label="$t('label.country')"
          :validation="$v.form.address.contry"
          v-model="form.address.contry"
        ></valid-input>
        <valid-input
          :label="$t('label.phone')"
          :validation="$v.form.phone"
          v-model="form.phone"
        ></valid-input>
        <valid-input
          :label="$t('label.email')"
          :validation="$v.form.email"
          v-model="form.email"
        ></valid-input>
        <valid-input
          :label="$t('label.currency')"
          :validation="$v.form.currency"
          v-model="form.currency"
        ></valid-input>
        <valid-input
          :label="$t('label.currency_symbol')"
          :validation="$v.form.currencySymbol"
          v-model="form.currencySymbol"
        ></valid-input>
        <valid-input
          :label="$t('label.menu_default_view')"
          :validation="$v.form.menuDefaultView"
          v-model="form.menuDefaultView"
        ></valid-input>

        <valid-input
          :label="$t('label.meta_title')"
          :validation="$v.form.meta.title"
          v-model="form.meta.title"
        ></valid-input>
        <valid-input
          :label="$t('label.meta_description')"
          :validation="$v.form.meta.description"
          v-model="form.meta.description"
        ></valid-input>
        <valid-input
          :label="$t('label.meta_keywords')"
          :validation="$v.form.meta.keywords"
          v-model="form.meta.keywords"
        ></valid-input>
      </div>
      <!-- Delivery settings -->
      <div v-if="activeTab == 1">
        <!-- Delevery areas Repeatable -->
        <h5 class="my-4">{{ $t("heading.delevery_areas") }}</h5>
        <dynamic-field
          :fields="areafields"
          v-model="form.deliveryAreas"
        ></dynamic-field>

        <hr />

        <!-- Delevery Time Options -->
        <h5 class="my-4">{{ $t("heading.delevery_time") }}</h5>
        <dynamic-field
          :fields="timefields"
          v-model="form.deliveryTimeOptions"
        ></dynamic-field>

        <hr />

        <!-- Open Hours -->
        <h5 class="my-4">{{ $t("heading.open_hours") }}</h5>
        <dynamic-field
          :fields="hourfields"
          v-model="form.openHours"
        ></dynamic-field>

        <hr />

        <!-- Social Media -->
        <h5 class="my-4">{{ $t("heading.social_media") }}</h5>
        <dynamic-field
          :fields="socialfields"
          v-model="form.socialMedia"
        ></dynamic-field>
      </div>

      <!-- Footer Buttons -->
      <div class="d-flex justify-content-end align-items-center">
        <primary-button
          @click="updateSettings"
          :loading="savingState"
          class="mx-2"
          >{{ $t("button.save") }}</primary-button
        >
      </div>
    </div>
  </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import ValidInput from "@/components/custom/ValidInput";
import { required } from "vuelidate/lib/validators";
import DynamicField from "@/components/custom/DynamicField";
import InputDetails from "@/components/custom/InputDetails";
import InputControl from "@/components/custom/InputControl";
import Switches from "vue-switches";
import PrimaryButton from "@/components/custom/PrimaryButton";

export default {
  name: "settings",
  components: {
    LoadingView,
    ValidInput,
    DynamicField,
    InputDetails,
    Switches,
    InputControl,
    PrimaryButton,
  },
  data() {
    return {
      title: "menuitems.settings.text",
      items: [{ text: "menuitems.settings.text", active: true }],
      activeTab: 0,
      form: null,

      areafields: [
        {
          name: "zipCode", //For v-model
          label: this.$t("label.zipCode"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
          },
        },
        {
          name: "city", //For v-model
          label: this.$t("label.city"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
          },
        },
        {
          name: "deliveryCost", //For v-model
          label: this.$t("label.deliveryCost"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "number",
          },
        },
      ],
      timefields: [
        {
          name: "startTime", //For v-model
          label: this.$t("label.startTime"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "time",
          },
        },
        {
          name: "endTime", //For v-model
          label: this.$t("label.endTime"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "time",
          },
        },
        {
          name: "name", //For v-model
          label: this.$t("label.name"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
          },
        },
      ],
      socialfields: [
        {
          name: "type", //For v-model
          label: this.$t("label.type"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
          },
        },
        {
          name: "url", //For v-model
          label: this.$t("label.url"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
          },
        },
      ],
      days: [
        { name: "Sunday", id: 0 },
        { name: "Monday", id: 1 },
        { name: "Tuesday", id: 2 },
        { name: "Wednesday", id: 3 },
        { name: "Thursday", id: 4 },
        { name: "Friday", id: 5 },
        { name: "Saturday", id: 6 },
      ],
      loading: true,
      savingState: false,
    };
  },
  computed: {
    hourfields() {
      return [
        {
          name: "days", //For v-model
          label: this.$t("label.days"),
          component: "multiselect-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
            options: this.days,
            validation: this.$v.form.openHours,
          },
        },
        {
          name: "openHour", //For v-model
          label: this.$t("label.openHour"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "time",
          },
        },
        {
          name: "closeHour", //For v-model
          label: this.$t("label.closeHour"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "time",
          },
        },
      ];
    },
  },
  methods: {
    async updateSettings() {
      this.savingState = true;
      try {
        await this.axios.post("/settings", { object: this.form });
        this.$toast.success("Updated Successfully");
        this.$store.commit("settings/SET_SETTINGS", this.form);
      } catch (error) {
        console.log(error);
      } finally {
        this.savingState = false;
      }
    },
  },
  validations: {
    form: {
      address: {
        street: { required },
        city: { required },
        contry: { required },
      },
      phone: { required },
      email: { required },
      currency: { required },
      currencySymbol: { required },
      menuDefaultView: { required },
      openHours: {},
      headerLogo: { required },
      footerLogo: { required },
      meta: {
        title: { required },
        description: { required },
        keywords: { required },
      },
    },
  },
  async created() {
    try {
      const res = await this.axios.get("/settings");
      this.form = res.data;
    } catch (error) {
      console.log(error);
    } finally {
      this.loading = false;
    }
  },
};
</script>

<style lang="scss" scoped></style>
