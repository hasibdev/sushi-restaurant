<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <data-table :tableData="categories" :fields="fields">
      <template #topHeader>
        <div></div>
        <div>
          <b-button
            to="/categories/create"
            variant="outline-primary"
            size="sm"
            >{{ $t("button.add_new") }}</b-button
          >
        </div>
      </template>
      <template #actions="{ data }">
        <action-dropdown>
          <b-dropdown-item :to="`/categories/${data.item.id}`">{{
            $t("dropdown.details")
          }}</b-dropdown-item>
          <b-dropdown-item :to="`/categories/${data.item.id}/edit`">{{
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
import Layout from "@/views/layouts/main";
import PageHeader from "@/components/page-header";
import DataTable from "@/components/custom/DataTable";
import ActionDropdown from "@/components/custom/ActionDropdown";

// Mixins
import services from "@/mixins/services.js";
export default {
  name: "Categories",
  page: {
    title: "Manage Categories",
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
      title: "menuitems.categories.list.manage",
      items: [{ text: "menuitems.categories.list.manage", active: true }],
      tablekey: 1,
      categories: null,
      fields: [
        { key: "id", sortable: true },
        { key: "name", sortable: true },
        { key: "order", sortable: true },
        { key: "actions", label: this.$t("label.actions"), sortable: false },
      ],
    };
  },

  methods: {
    async fetchCategories() {
      const res = await this.axios.get("/menu/categories");
      this.categories = res.data;
    },
    async onDelete(id) {
      try {
        await this.deleteResource({
          url: "/menu/categories",
          data: { data: { id } },
          text: "Deleting this category will also delete all products under this category.",
        });
        this.categories = this.categories.filter((val) => val.id != id);
      } catch (error) {
        console.log(error);
      }
    },
  },
  created() {
    this.fetchCategories();
  },
};
</script>

<style lang="scss" scoped></style>
