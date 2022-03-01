<template>
   <Layout>
      <PageHeader :title="title" :items="items" />

      <data-table :tableData="discounts" :fields="fields">
         <template #topHeader>
            <div></div>
            <div>
               <b-button to="/discounts/create" variant="outline-primary" size="sm">{{ $t('button.add_new') }}</b-button>
            </div>
         </template>
         <template #actions="{data}">
            <action-dropdown>
               <b-dropdown-item :to="`/discounts/${data.item.code}`">{{ $t('dropdown.details') }}</b-dropdown-item>
               <b-dropdown-item :to="`/discounts/${data.item.code}/edit`">{{ $t('dropdown.edit') }}</b-dropdown-item>
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
   name: 'Discounts',
   page: {
      title: "Manage Discounts",
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
         title: "menuitems.discounts.list.manage",
         items: [
            { text: "menuitems.discounts.list.manage", active: true }
         ],
         tablekey: 1,
         discounts: null,
         fields: [
            { key: "id", sortable: true },
            { key: "name", sortable: true },
            { key: "code", sortable: false },
            { key: "value", sortable: true, formatter: val => `${Math.round(val * 100)}%` },
            { key: "expire_date", sortable: true },
            { key: 'actions', label: this.$t('label.actions'), sortable: false }
         ]
      }
   },
   methods: {
      async fetchDiscounts() {
         const res = await this.axios.get('/checkout/discount')
         this.discounts = res.data
      },
      async onDelete(code) {
         try {
            await this.deleteResource({ url: '/checkout/discount', data: { data: { id: code } } })
            this.discounts = this.discounts.filter(val => val.code != code)
         } catch (error) {
            console.log(error)
         }
      }
   },
   created() {
      this.fetchDiscounts()


   },
}
</script>

<style lang="scss" scoped>
</style>