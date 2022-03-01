<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <data-table :tableData="users" :fields="fields">
      <template #topHeader>
        <div></div>
        <div>
          <b-button to="/users/create" variant="outline-primary" size="sm">{{
            $t("button.add_new")
          }}</b-button>
        </div>
      </template>
      <template #actions="{ data }">
        <action-dropdown>
          <b-dropdown-item :to="`/users/${data.item.id}`">{{
            $t("dropdown.details")
          }}</b-dropdown-item>
          <b-dropdown-item :to="`/users/${data.item.id}/edit`">{{
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
  name: "Users",
  page: {
    title: "Manage Users",
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
      title: "menuitems.users.list.manage",
      items: [{ text: "menuitems.users.list.manage", active: true }],
      tablekey: 1,
      users: null,
      fields: [
        { key: "id", sortable: true },
        { key: "username", sortable: true },
        { key: "full_name", sortable: true },
        { key: "email", sortable: true },
        { key: "phone", sortable: true },
        { key: "role", sortable: true },
        { key: "actions", label: this.$t("label.actions"), sortable: false },
      ],
    };
  },
  methods: {
    async fetchUsers() {
      const res = await this.axios.get("/users");
      this.users = res.data.payload;
    },
    async onDelete(id) {
      try {
        await this.deleteResource({ url: "/users", data: { data: { id } } });
        this.users = this.users.filter((val) => val.id != id);
      } catch (error) {
        console.log(error);
      }
    },
  },
  created() {
    this.fetchUsers();
  },
};
</script>

<style lang="scss" scoped></style>
