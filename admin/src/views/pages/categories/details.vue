<template>
  <LoadingView :title="title" :items="items" :loading="loading">
    <div v-if="category">
      <image-detail
        :label="$t('label.image')"
        :url="category.image.url"
      ></image-detail>
      <input-details
        :label="$t('label.image_caption')"
        :value="category.image.title"
      ></input-details>
      <input-details
        :label="$t('label.name')"
        :value="category.name"
      ></input-details>
      <input-details
        :label="$t('label.order')"
        :value="category.order"
      ></input-details>

      <h4 class="mt-4 mb-3">Options</h4>

      <div v-for="(val, i) in options" :key="i" class="card card-body">
        <h5>{{ val.name }}</h5>

        <b-table :items="val.list"></b-table>
      </div>
    </div>
  </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import InputDetails from "@/components/custom/InputDetails.vue";
import ImageDetail from "@/components/custom/ImageDetail.vue";
export default {
  name: "category-details",
  components: {
    LoadingView,
    InputDetails,
    ImageDetail,
  },
  data() {
    return {
      title: "menuitems.categories.list.details",
      items: [{ text: "menuitems.categories.list.details", active: true }],
      category: null,

      options: [],
      loading: true,
    };
  },

  async created() {
    try {
      const res = await this.axios.get(
        `/menu/categories/${this.$route.params.id}`
      );
      this.category = res.data;
      const resOptions = await this.axios.get(`/menu/options`);
      const newOptions = [];
      res.data.options.forEach((id) => {
        newOptions.push(resOptions.data.find((opt) => opt.id == id));
      });

      this.options = newOptions;

      this.loading = false;
    } catch (error) {
      console.log(error);
    }
  },
};
</script>

<style lang="scss" scoped></style>
