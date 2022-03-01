<template>
  <LoadingView :title="title" :items="items" :loading="loading">
    <button
      class="mx-2 btn btn-primary px-4 d-flex justify-content-center align-items-center"
      @click="printPOS"
      :disabled="disablePrint"
    >
      Print
    </button>

    <div v-if="order" id="no-print">
      <!-- <input-details :label="$t('label.id')" :value="order.guid"></input-details> -->
      <input-details
        :label="$t('label.name')"
        :value="order.content.name"
      ></input-details>
      <input-details
        :label="$t('label.email')"
        :value="order.content.email"
      ></input-details>
      <input-details
        :label="$t('label.phone')"
        :value="order.content.phone"
      ></input-details>
      <input-details
        :label="$t('label.address')"
        :value="order.content.street"
      ></input-details>
      <input-details
        :label="$t('label.total_items')"
        :value="order.content.items.length"
      ></input-details>

      <input-details label="Other Charge:" :value="order.content.other">
        <template #default="{ value }">
          <p class="mb-2">
            <strong class="font-weight-bold">Note: </strong>{{ value.note }}
          </p>
          <p class="mb-2">
            <strong class="font-weight-bold">Cost: </strong>{{ value.cost }}
          </p>
        </template>
      </input-details>

      <input-details
        :label="$t('label.total_amount')"
        :value="order.content.productsTotal"
      ></input-details>

      <input-details
        :label="$t('label.discount_amount')"
        :value="order.content.discountAmount"
      ></input-details>
      <input-details
        :label="$t('label.delivery_area')"
        :value="order.content.deliveryArea"
      >
        <template #default="{ value }">
          <p class="mb-2">
            <strong class="font-weight-bold">{{ $t("label.city") }}: </strong
            >{{ value.city }}
          </p>
          <p class="mb-2">
            <strong class="font-weight-bold"
              >{{ $t("label.delivery_cost") }}: </strong
            >{{ value.deliveryCost }}
          </p>
          <p class="mb-0">
            <strong class="font-weight-bold">{{ $t("label.zipCode") }}: </strong
            >{{ value.zipCode }}
          </p>
        </template>
      </input-details>
      <input-details
        :label="$t('label.delivery_time')"
        :value="order.content.deliveryTime"
      ></input-details>
      <input-details
        :label="$t('label.payment_method')"
        :value="order.content.paymentMethod"
      ></input-details>
      <input-details
        :label="$t('label.date')"
        :value="formatDate(order.datetime)"
      ></input-details>
      <input-details
        :label="$t('label.discount_coupon')"
        v-if="order.content.discountCoupon"
        :value="`${Math.round(order.content.discountCoupon.value * 100)}%`"
      ></input-details>
      <input-details :label="$t('label.status')" :value="order.content.status">
        <select-control
          @input="changeStatus"
          v-model="order.content.status"
          :options="statusOptions"
          :validation="$v.status"
        ></select-control>
      </input-details>

      <template v-if="order.content.items.length">
        <div
          v-for="(item, i) in order.content.items"
          :key="i"
          class="card card-body mt-2"
        >
          <h5 class="text-primary">{{ item.name }} x {{ item.quantity }}</h5>
          <p>{{ item.description }}</p>
          <p>
            <span class="font-weight-bold">{{ $t("label.note") }}:</span>
            {{ item.note }}
          </p>
          <div class="row">
            <div class="col-md-6">
              <p class="font-weight-bold">{{ $t("label.additions") }}</p>
              <b-table
                :fields="[
                  { key: 'name' },
                  {
                    key: 'price',
                    formatter: (val) => `${currencySymbol}${val}`,
                  },
                ]"
                :items="item.additions"
                class="mb-0"
              ></b-table>
            </div>
            <div class="col-md-6">
              <p class="font-weight-bold">{{ $t("label.options") }}</p>
              <b-table
                :fields="[
                  { key: 'name' },
                  { key: 'value.name', label: 'Value' },
                ]"
                :items="item.options"
                class="mb-0"
              ></b-table>
            </div>
          </div>
        </div>
      </template>
    </div>
  </LoadingView>
</template>

<script>
import LoadingView from "@/views/layouts/LoadingView.vue";
import InputDetails from "@/components/custom/InputDetails.vue";
import SelectControl from "@/components/custom/SelectControl";

// Mixins
import services from "@/mixins/services.js";

export default {
  name: "Order-details",
  components: {
    LoadingView,
    InputDetails,
    SelectControl,
  },
  mixins: [services],
  data() {
    return {
      title: "menuitems.orders.list.details",
      items: [{ text: "menuitems.orders.list.details", active: true }],
      order: null,
      loading: true,
      disablePrint: true,

      settings: window.localStorage.getItem("settings")
        ? JSON.parse(window.localStorage.getItem("settings"))
        : {},

      name: process.env.VUE_APP_NAME,

      statusOptions: [
        { name: "Pending", id: "pending" },
        { name: "Processing", id: "processing" },
        { name: "Ready to Pickup", id: "ready_to_pickup" },
        { name: "Out for delivery", id: "out_for_delivery" },
        { name: "Cancelled", id: "cancelled" },
        { name: "Complete", id: "complete" },
      ],
    };
  },
  computed: {
    currencySymbol() {
      return this.$store.state.settings.data.currencySymbol;
    },
  },
  methods: {
    formatDate(val) {
      return window.jara.Time(val);
    },
    async changeStatus(val) {
      // await this.axios.patch('/checkout/order', { id: this.$route.params.id, status: val })
      try {
        await this.updateResource({
          url: "/checkout/order",
          data: { id: this.$route.params.id, status: val },
        });
      } catch (error) {
        console.log(error);
      }
    },
    printPOS() {
      window.print_receipt();
    },
  },

  mounted() {
    this.disablePrint = false;
  },

  async created() {
    this.axios
      .get(`/checkout/order/${this.$route.params.id}`)
      .then((res) => {
        this.order = res.data;
        window.localStorage.setItem("__last_order", JSON.stringify(res.data));
        this.loading = false;
      })
      .catch((err) => {
        console.log(err);
      });
  },

  validations: {
    status: {},
  },
};
</script>

<style lang="scss"></style>
