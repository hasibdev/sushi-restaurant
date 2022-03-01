<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <data-table :tableData="products" :fields="fields">
      <template #topHeader>
        <div></div>
        <div>
          <b-button to="/products/create" variant="outline-primary" size="sm">{{
            $t("button.add_new")
          }}</b-button>
        </div>
      </template>
      <template #actions="{ data }">
        <action-dropdown>
          <b-dropdown-item :to="`/products/${data.item.id}`">{{
            $t("dropdown.details")
          }}</b-dropdown-item>
          <b-dropdown-item :to="`/products/${data.item.id}/edit`">{{
            $t("dropdown.edit")
          }}</b-dropdown-item>
          <b-dropdown-item @click="onDelete(data.item.id)">{{
            $t("dropdown.delete")
          }}</b-dropdown-item>
        </action-dropdown>
      </template>
    </data-table>
  </Layout>
</template>

<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import DataTable from "@/components/custom/DataTable";
import ActionDropdown from "@/components/custom/ActionDropdown";
// Mixins
import services from "@/mixins/services.js";
export default {
  name: "Products",
  page: {
    title: "Manage Products",
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
      title: "menuitems.products.list.manage",
      items: [{ text: "menuitems.products.list.manage", active: true }],
      products: null,
      fields: [
        { key: "id", sortable: true },
        { key: "name", sortable: true },
        {
          key: "price",
          sortable: true,
          formatter: (val) => `${this.currencySymbol}${val}`,
        },
        {
          key: "description",
          sortable: false,
          formatter: (val) =>
            val.length > 70 ? `${val.substring(0, 70)}...` : val,
        },
        { key: "order", sortable: true },
        { key: "actions", label: this.$t("label.actions"), sortable: false },
      ],
    };
  },
  computed: {
    currencySymbol() {
      return this.$store.state.settings.data.currencySymbol;
    },
  },
  methods: {
    async fetchProducts() {
      const res = await this.axios.get("/menu/items");
      this.products = res.data;
    },
    async onDelete(id) {
      try {
        await this.deleteResource({
          url: "/menu/items",
          data: { data: { id } },
        });
        this.products = this.products.filter((val) => val.id != id);
      } catch (error) {
        console.log(error);
      }
    },
  },
  created() {
    this.fetchProducts();
  },
};
</script>

<style lang="scss" scoped></style>
