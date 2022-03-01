<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <!-- <h4 class="card-title">Data Table</h4> -->
          <div class="d-flex justify-content-between align-items-center">
            <slot name="topHeader" />
          </div>
          <div class="row mt-4">
            <div v-if="jump" class="col-sm-12 col-md-6">
              <div id="tickets-table_length" class="dataTables_length">
                <label class="d-inline-flex align-items-center">
                  {{ $t("title.show") }}&nbsp;
                  <b-form-select
                    v-model="perPage"
                    size="sm"
                    :options="pageOptions"
                  ></b-form-select
                  >&nbsp;{{ $t("title.entries") }}
                </label>
              </div>
            </div>
            <!-- Search -->
            <div v-if="search" class="col-sm-12 col-md-6">
              <div
                id="tickets-table_filter"
                class="dataTables_filter text-md-right"
              >
                <label class="d-inline-flex align-items-center">
                  {{ $t("title.search") }}:
                  <b-form-input
                    v-model="filter"
                    type="search"
                    :placeholder="$t('placeholder.search')"
                    class="form-control form-control-sm ml-2"
                  ></b-form-input>
                </label>
              </div>
            </div>
            <!-- End search -->
          </div>
          <!-- Table -->
          <div class="table-responsive mb-0">
            <b-table
              :items="tableData"
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
              <template #head(actions)="data">
                <div class="d-flex justify-content-end">
                  <span class="text-info">{{ data.label }}</span>
                </div>
              </template>

              <template #cell(actions)="data">
                <div class="d-flex justify-content-end">
                  <slot name="actions" :data="data" />
                </div>
              </template>
            </b-table>
            <p v-if="tableData && !tableData.length">
              {{ $t("title.nodata_found") }}
            </p>

            <div
              v-if="tableData == null"
              class="d-flex flex-column justify-content-center align-items-center py-3"
            >
              <b-spinner :label="$t('placeholder.loading')"></b-spinner>
              <br />
              <p>{{ this.$t("placeholder.loading") }}</p>
            </div>
          </div>

          <slot name="pagination">
            <div class="row">
              <div class="col">
                <div
                  class="dataTables_paginate paging_simple_numbers float-right"
                >
                  <ul class="pagination pagination-rounded mb-0">
                    <!-- pagination -->
                    <b-pagination
                      v-model="currentPage"
                      :total-rows="rows"
                      :per-page="perPage"
                    ></b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    tableData: {
      type: Array,
      default: () => [],
    },
    fields: {
      type: Array,
      default: () => [],
    },
    search: {
      type: Boolean,
      default: true,
    },
    jump: {
      type: Boolean,
      default: true,
    },
    pagination: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50, 100],
      filter: null,
      filterOn: [],
      sortBy: "id",
      sortDesc: true,
    };
  },
  computed: {
    /**
     * Total no. of records
     */
    rows() {
      return this.tableData?.length;
    },
    /**
     * Get field with languege support
     */
    getfields() {
      return this.fields.map((field) => ({
        ...field,
        label: field.label || this.$t(`label.${field.key}`),
      }));
    },
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

<style lang="scss" scoped></style>
