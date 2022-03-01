<script>
/**
 * Widget Component
 */
export default {
  props: {
    data: {
      type: Object,
    },
  },
  computed: {
    statData() {
      return [
        {
          title: this.$t("label.TotalOrders"),
          icon: "ri-store-2-line",
          value: this.data.total_orders,
        },
        {
          title: this.$t("label.TotalAmount"),
          icon: "ri-store-2-line",
          value: this.data.total_amount,
        },
        {
          title: this.$t("label.CompletedOrders"),
          icon: "ri-stack-line",
          value: this.data.completed,
        },

        {
          title: this.$t("label.ProcessingOrders"),
          icon: "ri-briefcase-4-line",
          value: this.data.processing,
        },
        {
          title: this.$t("label.PendingOrders"),
          icon: "ri-store-2-line",
          value: this.data.pending,
        },
        {
          title: this.$t("label.CancelledOrders"),
          icon: "ri-store-2-line",
          value: this.data.cancelled,
        },
        {
          title: this.$t("label.OutForDeliveryOrders"),
          icon: "ri-store-2-line",
          value: this.data.out_for_delivery,
        },
        {
          title: this.$t("label.PaidOrders"),
          icon: "ri-store-2-line",
          value: this.data.paid,
        },
      ];
    },
  },
  data() {
    return {};
  },
};
</script>

<template>
  <div class="row">
    <div class="col-md-4" v-for="(data, index) in statData" :key="index">
      <div class="card">
        <div class="card-body">
          <div class="media">
            <div class="media-body overflow-hidden">
              <p class="text-truncate font-size-14 mb-2">{{ data.title }}</p>
              <h4 class="mb-0">{{ data.value }}</h4>
            </div>
            <div class="text-primary">
              <i :class="`${data.icon} font-size-24`"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
