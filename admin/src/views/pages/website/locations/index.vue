<template>
   <Layout>
      <PageHeader :title="title" :items="items" />

      <data-table :tableData="locations" :fields="fields">
         <template #topHeader>
            <div></div>
            <div>
               <b-button to="/pages/locations/create" variant="outline-primary" size="sm">{{ $t('button.add_new') }}</b-button>
            </div>
         </template>
         <template #actions="{data}">
            <action-dropdown>
               <b-dropdown-item :to="`/pages/locations/${data.item.id}`">{{ $t('dropdown.details') }}</b-dropdown-item>
               <b-dropdown-item :to="`/pages/locations/${data.item.id}/edit`">{{ $t('dropdown.edit') }}</b-dropdown-item>
               <b-dropdown-item @click="onDelete(data.item.id)">{{ $t('dropdown.delete') }}</b-dropdown-item>
            </action-dropdown>
         </template>
      </data-table>
   </Layout>
</template>

<script>
import Layout from "@/views/layouts/main"
import PageHeader from "@/components/page-header"
import DataTable from '@/components/custom/DataTable'
import ActionDropdown from '@/components/custom/ActionDropdown'


// Mixins
import services from '@/mixins/services.js'
export default {
   name: 'Locations',
   page: {
      title: "Manage Locations",
   },
   components: {
      Layout,
      PageHeader,
      DataTable,
      ActionDropdown
   },
   mixins: [services],
   data() {
      return {
         title: "menuitems.locations.list.manage",
         items: [
            { text: "menuitems.locations.list.manage", active: true }
         ],
         tablekey: 1,
         locations: null,
         fields: [
            { key: "id", sortable: true },
            { key: "name", sortable: true },
            { key: "phone", sortable: false },
            { key: "address.street", label: this.$t('label.street'), sortable: false },
            { key: "address.city", label: this.$t('label.city'), sortable: false },
            { key: "address.contry", label: this.$t('label.country'), sortable: false },
            { key: 'actions', label: this.$t('label.actions'), sortable: false }
         ]
      }
   },
   computed: {
      currencySymbol() {
         return this.$store.state.settings.data.currencySymbol
      }
   },
   methods: {
      async fetchLocations() {
         const res = await this.axios.get('/locations')
         this.locations = res.data
      },
      async onDelete(id) {
         try {
            await this.deleteResource({ url: '/locations', data: { data: { id } } })
            this.locations = this.locations.filter(val => val.id != id)
         } catch (error) {
            console.log(error)
         }
      }
   },
   created() {
      this.fetchLocations()


   },
}
</script>

<style lang="scss" scoped>
</style>