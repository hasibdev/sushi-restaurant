<template>
  <loading-view :loading="loading">
    <div class="d-flex justify-content-end">
      <primary-button @click="saveData" :loading="savingState" class="mx-2">{{
        $t("button.save")
      }}</primary-button>
    </div>

    <!-- Hero Section -->
    <h3>{{ $t("heading.hero_section") }}</h3>
    <input-control
      v-model="pageData.sections[0].props.title"
      :label="$t('label.title')"
    ></input-control>
    <input-control
      v-model="pageData.sections[0].props.tagline"
      :label="$t('label.tagline')"
    ></input-control>
    <input-control
      v-model="pageData.sections[0].props.link.title"
      :label="$t('label.button_title')"
    ></input-control>
    <!-- <input-control v-model="pageData.sections[0].props.media.source.url" label="Video"></input-control> -->
    <input-details
      :label="$t('label.video')"
      labelClasss="text-sm-right"
      :value="null"
    >
      <template #default>
        <b-form-file accept="video/*" @change="pickFile"></b-form-file>
        <!-- <video-upload
          v-model="pageData.sections[0].props.media.source.data"
        ></video-upload> -->
      </template>
    </input-details>

    <hr />

    <!-- Features Section -->
    <h3>Features</h3>
    <dynamic-field
      :fields="featurefields"
      v-model="pageData.sections[2].props.items"
    ></dynamic-field>

    <!-- Reviews -->
    <h3>{{ $t("heading.review_section") }}</h3>
    <input-control
      v-model="pageData.sections[1].props.content.title"
      :label="$t('label.title')"
    ></input-control>
    <input-control
      v-model="pageData.sections[1].props.content.description"
      :label="$t('label.description')"
    ></input-control>
    <input-control
      v-model.number="pageData.sections[1].props.content.rate"
      :label="$t('label.rating')"
    ></input-control>
    <image-upload
      v-model="pageData.sections[1].props.image.data"
      width="100%"
      height="250px"
      :url="pageData.sections[1].props.image.data"
      :validation="$v.author.data"
      :label="$t('label.image')"
    ></image-upload>

    <h5>{{ $t("heading.first_review") }}</h5>
    <input-control
      v-model="pageData.sections[1].props.content.reviews[0].content"
      :label="$t('label.content')"
    ></input-control>
    <input-control
      v-model="pageData.sections[1].props.content.reviews[0].source"
      :label="$t('label.source')"
    ></input-control>
    <input-control
      v-model="pageData.sections[1].props.content.reviews[0].author.name"
      :label="$t('label.author_name')"
    ></input-control>
    <input-control
      v-model.number="pageData.sections[1].props.content.reviews[0].rate"
      :label="$t('label.rating')"
    ></input-control>
    <image-upload
      v-model="
        pageData.sections[1].props.content.reviews[0].author.thumbnail.data
      "
      :url="pageData.sections[1].props.content.reviews[0].author.thumbnail.url"
      :validation="$v.author.data"
      :label="$t('label.author_image')"
    ></image-upload>

    <h5>{{ $t("heading.second_review") }}</h5>
    <input-control
      v-model="pageData.sections[1].props.content.reviews[1].content"
      :label="$t('label.content')"
    ></input-control>
    <input-control
      v-model="pageData.sections[1].props.content.reviews[1].source"
      :label="$t('label.source')"
    ></input-control>
    <input-control
      v-model="pageData.sections[1].props.content.reviews[1].author.name"
      :label="$t('label.author_name')"
    ></input-control>
    <input-control
      v-model.number="pageData.sections[1].props.content.reviews[1].rate"
      :label="$t('label.rating')"
    ></input-control>
    <image-upload
      v-model="
        pageData.sections[1].props.content.reviews[1].author.thumbnail.data
      "
      :url="pageData.sections[1].props.content.reviews[1].author.thumbnail.url"
      :validation="$v.author.data"
      :label="$t('label.author_image')"
    ></image-upload>

    <hr />
    <h3>{{ $t("heading.offers") }}</h3>
    <input-control
      v-model="pageData.sections[4].props.title"
      :label="$t('label.title')"
    ></input-control>

    <hr />
    <h3>Featured Categories</h3>
    <multiselect-control
      v-model="pageData.sections[5].props.categories"
      :validation="$v.categories"
      :options="categories"
      label="Categories"
    ></multiselect-control>

    <!-- <hr />

      <h3>{{ $t('heading.features_item') }}</h3>
      <dynamic-field :fields="itemsfields" v-model="pageData.sections[1].props.items"></dynamic-field> -->

    <!-- <hr />

      <h3>{{ $t('heading.latest_posts') }}</h3>
      <input-control v-model="pageData.sections[5].props.title" :label="$t('label.title')"></input-control> -->

    <hr />

    <h3>{{ $t("heading.cta") }}</h3>
    <input-control
      v-model="pageData.sections[6].props.title"
      :label="$t('label.title')"
    ></input-control>
    <input-control
      v-model="pageData.sections[6].props.tagline"
      :label="$t('label.tagline')"
    ></input-control>
    <input-control
      v-model="pageData.sections[6].props.link.title"
      :label="$t('label.button_title')"
    ></input-control>
    <image-upload
      v-model="pageData.sections[6].props.image.data"
      :url="pageData.sections[6].props.image.url"
      width="100%"
      height="250px"
      :validation="$v.author.data"
      :label="$t('label.image')"
    ></image-upload>

    <hr />

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
import DynamicField from "@/components/custom/DynamicField";
import ImageUpload from "@/components/custom/ImageUpload";
import InputDetails from "@/components/custom/InputDetails";
import MultiselectControl from "@/components/custom/MultiselectControl";
// import VideoUpload from "@/components/custom/VideoUpload";
// import { required } from "vuelidate/lib/validators"
// Mixins
import services from "@/mixins/services.js";
export default {
  components: {
    LoadingView,
    InputControl,
    DynamicField,
    ImageUpload,
    PrimaryButton,
    InputDetails,
    // VideoUpload,
    MultiselectControl,
  },
  mixins: [services],
  data() {
    return {
      video: null,
      categories: [],
      pageData: {
        pageTitle: null,
        sections: [
          // Index 0 - Hero
          {
            name: "HeroCentered",
            props: {
              media: {
                type: "video",
                source: {
                  title: "",
                  url: "",
                },
              },
              logo: "",
              title: "",
              tagline: "",
              link: {
                title: "",
                slug: "",
              },
            },
          },
          {
            name: "ImageEdge",
            props: {
              content: {
                rate: 0,
                title: "",
                description: "",
                reviews: [
                  {
                    content: "",
                    rate: 0,
                    source: "",
                    author: {
                      name: "",
                      thumbnail: {
                        title: "",
                        url: "",
                      },
                    },
                  },
                  {
                    content: "",
                    rate: 0,
                    source: "",
                    author: {
                      name: "",
                      thumbnail: {
                        title: "",
                        url: "",
                      },
                    },
                  },
                ],
              },
              image: {
                title: "",
                url: "",
              },
              reverse: true,
            },
          },
          // Index 1 - Features
          {
            name: "",
            props: {
              theme: "dark",
              bg: "dark",
              items: [
                {
                  title: "",
                  description: "",
                  icon: "",
                },
                {
                  title: "",
                  description: "",
                  icon: "",
                },
                {
                  title: "",
                  description: "",
                  icon: "",
                },
              ],
              extend: "left",
            },
          },
          // Index 2 - Categories
          {
            name: "",
            props: {
              title: null,
            },
          },
          // Index 3 - Offers
          {
            name: "",
            props: {
              title: "",
              theme: "light",
            },
          },
          // Menu slide
          {
            name: "Menu",
            props: {
              title: "Our Menu",
              categories: [64, 65],
            },
          },
          // Index 4 - Cta
          {
            name: "",
            props: {
              theme: "dark",
              bg: "dark",
              image: {
                title: "",
                url: "",
                data: "",
              },
              title: "",
              tagline: "",
              link: {
                title: "",
                slug: "menu-grid-navigation",
              },
            },
          },
        ],
        settings: {
          headerTransparent: true,
          hideHeaderLogo: false,
        },
      },

      featurefields: [
        {
          name: "title", //For v-model
          label: this.$t("label.title"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {
            type: "text",
          },
        },
        {
          name: "description", //For v-model
          label: this.$t("label.description"),
          component: "text-field", // Render component type
          value: "", // Initial value
          listen: true,
          props: {},
        },
        {
          name: "icon", //For v-model
          label: this.$t("label.icon"),
          component: "input-control", // Render component type
          value: "", // Initial value
          listen: true,
          props: {},
        },
      ],

      loading: true,
      savingState: false,
    };
  },

  async created() {
    try {
      const res = await this.axios.get("/pages");
      console.log(res.data);
      res.data.sections[0].props.media.source.url = null;
      this.pageData = res.data;

      const catRes = await this.axios.get("/menu/categories");
      this.categories = catRes.data;
    } catch (error) {
      console.log(error);
    } finally {
      this.loading = false;
    }
  },

  methods: {
    pickFile(e) {
      const files = e.target.files;
      this.video = files[0];
    },
    async saveData() {
      let data = JSON.stringify(this.pageData);
      let video = this.video;
      this.savingState = true;

      const blob = new Blob([data], {
        type: "application/json",
      });

      const fdata = new FormData();
      fdata.append("json", blob);

      if (video) {
        fdata.append("video", video);
      }

      this.$v.$touch();
      if (this.$v.$invalid) {
        this.savingState = false;
        this.$toast.warning("Please Fill all the field correctly");
        return Promise.reject({ message: "Invalid Form Submissions" });
      }

      try {
        const res = await this.axios.post("/pages/home", fdata, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        // this.$refs.video.value = ""
        this.$v.$reset();
        this.$toast.success("Created Successfully");

        return Promise.resolve(res);
      } catch (error) {
        console.error(error);
        this.$toast.error("Create Fail");
        return Promise.reject(error);
      } finally {
        this.savingState = false;
      }
    },
  },

  validations: {
    author: {
      data: {},
    },
    categories: {},
  },
};
</script>
