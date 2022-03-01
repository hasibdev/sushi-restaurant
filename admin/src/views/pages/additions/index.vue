<template>
   <Layout>
      <PageHeader :title="title" :items="items" />

      <data-table :tableData="additions" :fields="fields">
         <template #topHeader>
            <div></div>
            <div>
               <b-button to="/additions/create" variant="outline-primary" size="sm">{{ $t('button.add_new') }}</b-button>
            </div>
         </template>
         <template #actions="{data}">
            <action-dropdown>
               <b-dropdown-item :to="`/additions/${data.item.id}`">{{ $t('dropdown.details') }}</b-dropdown-item>
               <b-dropdown-item :to="`/additions/${data.item.id}/edit`">{{ $t('dropdown.edit') }}</b-dropdown-item>
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
   name: 'Additions',
   page: {
      title: "Manage Additions",
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
         title: "menuitems.additions.list.manage",
         items: [
            { text: "menuitems.additions.list.manage", active: true }
         ],
         tablekey: 1,
         additions: null,
         fields: [
            { key: "id", sortable: true },
            { key: "name", sortable: true },
            { key: "price", sortable: true, formatter: val => `${this.currencySymbol}${val}` },
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
      async fetchAdditions() {
         const res = await this.axios.get('/menu/additions')
         this.additions = res.data
      },
      async onDelete(id) {
         try {
            await this.deleteResource({ url: '/menu/additions', data: { data: { id } } })
            this.additions = this.additions.filter(val => val.id != id)
         } catch (error) {
            console.log(error)
         }
      }
   },
   created() {
      this.fetchAdditions()
   },
}
</script>

<style lang="scss" scoped>
</style>