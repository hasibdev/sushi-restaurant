<template>
  <loading-view :loading="loading">
    <!-- <div class="d-flex justify-content-end">
         <primary-button @click="saveData" :loading="savingState" class="mx-2">Save</primary-button>
      </div> -->

    <h3>{{ $t("heading.hero_section") }}</h3>
    <input-control
      v-model="pageData.pageTitle.title"
      :label="$t('label.title')"
    ></input-control>
    <input-control
      v-model="pageData.pageTitle.tagline"
      :label="$t('label.tagline')"
    ></input-control>

    <!-- Break Time -->
    <h3>{{ $t("heading.break_time") }}</h3>
    <input-control
      v-model="pageData.breakTime.start"
      type="time"
      :label="$t('label.start_time')"
    ></input-control>
    <input-control
      v-model="pageData.breakTime.end"
      type="time"
      :label="$t('label.end_time')"
    ></input-control>

    <h3>{{ $t("heading.locations") }}</h3>
    <multiselect-control
      v-model="pageData.locations"
      :options="locations"
      :label="$t('label.select_locations')"
      :validation="$v.author.url"
    ></multiselect-control>

    <div class="d-flex justify-content-end">
      <primary-button @click="saveData" :loading="savingState" class="mx-2">{{
        $t("button.save")
      }}</primary-button>
    </div>
  </loading-view>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import InputControl from "@/components/custom/InputControl";
import PrimaryButton from "@/components/custom/PrimaryButton";
import MultiselectControl from "@/components/custom/MultiselectControl";

// Mixins
import services from "@/mixins/services.js";
export default {
  components: {
    LoadingView,
    InputControl,
    PrimaryButton,
    MultiselectControl,
  },
  mixins: [services],
  data() {
    return {
      pageData: {
        pageTitle: {
          title: "Contact",
          theme: "light", // not needed
          tagline: "Some informations about our restaurant",
        },
        locations: [],
        breakTime: {
          start: "",
          end: "",
        },
      },

      locations: [],
      loading: true,
      savingState: false,
    };
  },
  methods: {
    async saveData() {
      try {
        await this.createResource({
          url: "/pages/contact",
          data: { object: this.pageData },
        });
      } catch (error) {
        console.error(error);
      }
    },
  },
  async created() {
    try {
      const resLocations = await this.axios.get("/locations");
      this.locations = resLocations.data;
      const resPage = await this.axios.get("/pages/contact");
      this.pageData = resPage.data;
      this.pageData.locations = this.pageData.sections.map((location) => {
        return location.props.location.id;
      });
    } catch (error) {
      console.log(error);
    } finally {
      this.loading = false;
    }
  },

  validations: {
    author: {
      url: {},
    },
  },
};
</script>
