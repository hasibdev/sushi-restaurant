<script>
/**
 * Transactions component
 */
export default {
  props: {
    data: {
      type: Array,
    },
  },
  data() {
    return {
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 25, 50],
      filter: null,
      filterOn: [],
      sortBy: "orderid",
      sortDesc: false,
      fields: [
        { key: "id", sortable: false },
        { key: "name", sortable: false },
        { key: "price", sortable: false },
        { key: "sold", sortable: false },
        { key: "total_value", sortable: false },
      ],
    };
  },
  computed: {
    /**
     * Total no. of records
     */
    rows() {
      return this.data.length;
    },
    getfields() {
      return this.fields.map((field) => ({
        ...field,
        label: this.$t(`label.${field.key}`),
      }));
    },
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.data.length;
  },
  methods: {
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
  },
};
</script>

<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">{{ $t("heading.best_seller") }}</h4>

      <div class="table-responsive">
        <b-table
          :items="data"
          :fields="getfields"
          responsive="sm"
          :per-page="perPage"
          :current-page="currentPage"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :filter="filter"
          :filter-included-fields="filterOn"
          @filtered="onFiltered"
        >
          <template v-slot:cell(paymentstatus)="row">
            <div
              class="badge font-size-12"
              :class="{
                'badge-soft-danger': `${row.value}` === 'Chargeback',
                'badge-soft-success': `${row.value}` === 'Paid',
                'badge-soft-warning': `${row.value}` === 'Unpaid',
              }"
            >
              {{ row.value }}
            </div>
          </template>

          <template v-slot:cell(action)>
            <a
              href="javascript:void(0);"
              class="mr-3 text-primary"
              v-b-tooltip.hover
              data-toggle="tooltip"
              title="Edit"
            >
              <i class="mdi mdi-pencil font-size-18"></i>
            </a>
            <a
              href="javascript:void(0);"
              class="text-danger"
              v-b-tooltip.hover
              title="Delete"
            >
              <i class="mdi mdi-trash-can font-size-18"></i>
            </a>
          </template>
        </b-table>
      </div>
    </div>
  </div>
</template>
