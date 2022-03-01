<template>
  <cInvoice :key="JSON.stringify(order)" :order="order" />
</template>

<script>
import cInvoice from "@/components/custom/cInvoice.vue";

export default {
  name: "Invoice",
  components: {
    cInvoice,
  },
  data() {
    return {
      order: null,
    };
  },
  async created() {
    try {
      const res = await this.axios.get(
        `/checkout/order/${this.$route.params.id}`
      );
      this.order = res.data;
    } catch (error) {
      console.log(error);
    }
  },
};
</script>
