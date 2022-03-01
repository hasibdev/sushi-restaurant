<template>
  <LoadingView :title="title" :items="items" :loading="loading">
    <div v-if="product">
      <image-detail
        :label="$t('label.image')"
        :url="product.image.url"
      ></image-detail>
      <input-details
        :label="$t('label.name')"
        :value="product.name"
      ></input-details>
      <input-details
        :label="$t('label.price')"
        :value="`${currencySymbol}${product.price}`"
      ></input-details>
      <input-details
        :label="$t('label.description')"
        :value="product.description"
      ></input-details>
      <input-details
        :label="$t('label.order')"
        :value="product.order"
      ></input-details>

      <input-details :label="$t('label.additions')" :value="product.additions">
        <template #default="{ value }">
          <p v-for="(ad, i) in value" :key="i" class="mb-0">
            {{ ad.name }} - {{ ad.price }}
          </p>
        </template>
      </input-details>

      <!-- <h5 class="mt-3">Options</h5> -->
      <h4 class="mt-4 mb-3">Options</h4>

      <div v-for="(val, i) in product.options" :key="i" class="card card-body">
        <h5>{{ val.name }}</h5>

        <b-table :items="val.list"></b-table>
      </div>

      <!-- <input-details :label="$t('label.options')" :value="product.options">
            <template #default={value}>
               <ol>
                  <li v-for="(val, i) in value" :key="i" class="card card-body">
                     <p><strong>{{val.name}}</strong></p>
                     <p v-for="(li, ii) in val.list" :key="ii" class="border-top mb-0 py-2">
                        <strong>{{$t('label.name')}}:</strong>{{li.name}}
                        <br>
                        <strong>{{$t('label.price')}}:</strong>{{li.price}}
                     </p>
                  </li>
               </ol>
            </template>
         </input-details> -->
    </div>
  </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import InputDetails from "@/components/custom/InputDetails.vue";
import ImageDetail from "@/components/custom/ImageDetail.vue";
export default {
  name: "product-details",
  components: {
    LoadingView,
    InputDetails,
    ImageDetail,
  },
  data() {
    return {
      title: "menuitems.products.list.details",
      items: [{ text: "menuitems.products.list.details", active: true }],
      product: null,
      loading: true,
    };
  },
  computed: {
    currencySymbol() {
      return this.$store.state.settings.data.currencySymbol;
    },
  },
  async created() {
    try {
      const res = await this.axios.get(`/menu/items/${this.$route.params.id}`);
      this.product = res.data.payload;
      this.loading = false;
    } catch (error) {
      console.log(error);
    }
  },
};
</script>

<style lang="scss" scoped></style>
