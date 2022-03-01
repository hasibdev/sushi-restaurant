<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <data-table
      :search="false"
      :jump="false"
      :tableData="orders"
      :fields="fields"
      :pagination="false"
    >
      <template #actions="{ data }">
        <action-dropdown>
          <b-dropdown-item :to="`/orders/${data.item.id}`">{{
            $t("dropdown.details")
          }}</b-dropdown-item>
        </action-dropdown>
      </template>

      <template #pagination>
        <div class="row">
          <div class="col">
            <div class="dataTables_paginate paging_simple_numbers float-right">
              <ul class="pagination pagination-rounded mb-0">
                <!-- pagination -->
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalOrderData"
                  :per-page="10"
                ></b-pagination>
              </ul>
            </div>
          </div>
        </div>
      </template>
    </data-table>
  </Layout>
</template>

<script>
import Layout from "@/views/layouts/main";
import PageHeader from "@/components/page-header";
import DataTable from "@/components/custom/DataTable";
import ActionDropdown from "@/components/custom/ActionDropdown";

// Mixins
import services from "@/mixins/services.js";
export default {
  name: "Orders",
  page: {
    title: "Manage Orders",
  },
  components: {
    Layout,
    PageHeader,
    DataTable,
    ActionDropdown,
  },
  mixins: [services],
  data() {
    return {
      title: "menuitems.orders.list.manage",
      currentPage: 1,
      totalOrderData: 1,
      items: [{ text: "menuitems.orders.list.manage", active: true }],
      orders: null,
      fields: [
        { key: "id", sortable: true },
        { key: "content.name", label: this.$t("label.name"), sortable: true },
        {
          key: "content.items",
          label: this.$t("label.total_items"),
          sortable: true,
          formatter: (value) => value.length,
        },
        {
          key: "content.total",
          label: this.$t("label.total_amount"),
          sortable: true,
        },
        {
          key: "content.discountAmount",
          label: this.$t("label.discount_amount"),
          sortable: true,
        },
        {
          key: "content.deliveryArea.city",
          label: this.$t("label.delivery_area"),
          sortable: true,
        },
        {
          key: "content.deliveryTime.name",
          label: this.$t("label.delivery_time"),
          sortable: true,
        },
        {
          key: "datetime",
          label: this.$t("label.date"),
          sortable: true,
          formatter: (value) => window.jara.Time(value),
        },
        {
          key: "content.status",
          label: this.$t("label.status"),
          sortable: true,
        },

        { key: "actions", label: this.$t("label.actions"), sortable: false },
      ],
    };
  },
  methods: {
    async fetchOrders(page) {
      this.orders = null;
      const res = await this.axios.get(`/checkout/orders/${page}`);
      this.orders = res.data;
    },
    async onDelete(id) {
      try {
        await this.deleteResource({
          url: "/checkout/orders",
          data: { data: { id } },
        });
        this.orders = this.orders.filter((val) => val.id != id);
      } catch (error) {
        console.log(error);
      }
    },
  },
  async created() {
    const resDatacount = await this.axios.get(`/checkout/orders/1/?count=true`);
    this.totalOrderData = resDatacount.data;

    this.fetchOrders(1);
  },

  watch: {
    currentPage(val) {
      this.fetchOrders(val);
    },
  },
};
</script>

<style lang="scss" scoped></style>
