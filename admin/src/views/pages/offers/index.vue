<template>
   <Layout>
      <PageHeader :title="title" :items="items" />

      <data-table :tableData="offers" :fields="fields">
         <template #topHeader>
            <div></div>
            <div>
               <b-button to="/offers/create" variant="outline-primary" size="sm">{{ $t('button.add_new') }}</b-button>
            </div>
         </template>
         <template #actions="{data}">
            <action-dropdown>
               <b-dropdown-item :to="`/offers/${data.item.id}`">{{ $t('dropdown.details') }}</b-dropdown-item>
               <b-dropdown-item :to="`/offers/${data.item.id}/edit`">{{ $t('dropdown.edit') }}</b-dropdown-item>
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
import helpers from '@/mixins/helpers.js'
export default {
   name: 'Offers',
   page: {
      title: "Manage Offers",
   },
   components: {
      Layout,
      PageHeader,
      DataTable,
      ActionDropdown
   },
   mixins: [services, helpers],
   data() {
      return {
         title: 'menuitems.offers.list.manage',
         items: [
            { text: "menuitems.offers.list.manage", active: true }
         ],
         tablekey: 1,
         offers: null,
         fields: [
            { key: "id", sortable: true },
            { key: "name", sortable: true },
            { key: "description", sortable: true, formatter: val => val.length > 70 ? `${val.substring(0, 70)}...` : val },
            {
               key: "type", sortable: true, formatter: value => this.titleCase(value)
            },
            { key: 'actions', label: this.$t('label.actions'), sortable: false }
         ]
      }
   },
   methods: {
      async fetchOffers() {
         const res = await this.axios.get('/menu/offers')
         this.offers = res.data
      },
      async onDelete(id) {
         try {
            await this.deleteResource({ url: '/menu/offers', data: { data: { id } } })
            this.offers = this.offers.filter(val => val.id != id)
         } catch (error) {
            console.log(error)
         }
      }
   },
   created() {
      this.fetchOffers()


   },
}
</script>

<style lang="scss" scoped>
</style>