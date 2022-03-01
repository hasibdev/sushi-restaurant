<script>
import Layout from "../../layouts/main";
import DataTable from "@/components/custom/DataTable";
import ActionDropdown from "@/components/custom/ActionDropdown";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import Stat from "./report-widget";
import Transaction from "./transaction";

/**
 * Reports component
 */
export default {
  components: {
    Layout,
    Stat,
    Transaction,
    DataTable,
    ActionDropdown,
    DatePicker,
  },
  data() {
    return {
      title: "menuitems.reports.text",
      daterange: null,
      items: [
        {
          text: "menuitems.reports.text",
          active: true,
        },
      ],
      report: null,
      loading: true,
      currentPage: 1,
      fields: [
        { key: "id", sortable: true },
        {
          key: "items",
          label: this.$t("label.total_items"),
          sortable: true,
          formatter: (value) => value.length,
        },
        {
          key: "total",
          label: this.$t("label.total_amount"),
          sortable: true,
        },
        {
          key: "discountAmount",
          label: this.$t("label.discount_amount"),
          sortable: true,
        },
        {
          key: "datetime",
          label: this.$t("label.date"),
          sortable: true,
          formatter: (value) => window.jara.Time(value),
        },
        {
          key: "status",
          label: this.$t("label.status"),
          sortable: true,
        },

        { key: "actions", label: this.$t("label.actions"), sortable: false },
      ],
    };
  },
  computed: {
    getStatData() {
      return {
        total_orders: this.report.total_orders,
        total_amount: this.report.total_amount,
        total_products: this.report.total_products,
        total_additions: this.report.total_additions,
        completed: this.report.complete_orders,
        pending: this.report.pending_orders,
        processing: this.report.processing_orders,
        cancelled: this.report.cancelled_orders,
        paid: this.report.paid_orders,
        out_for_delivery: this.report.out_for_delivery,
      };
    },
  },
  async created() {
    try {
      let res = await this.axios.get(`/dashboard/report/`);
      this.report = res.data;
    } catch (error) {
      console.log(error);
    } finally {
      this.loading = false;
    }
  },
  methods: {
    updateData() {
      this.loading = true;
      this.axios
        .get(
          `/dashboard/report/?start=${this.daterange[0]}&end=${this.daterange[1]}`
        )
        .then((res) => {
          this.report = res.data;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<template>
  <Layout>
    <div class="row">
      <div class="col-12">
        <div
          class="page-title-box d-flex align-items-center justify-content-between"
        >
          <h4 class="mb-0">{{ this.$t(title) }}</h4>
          <div class="page-title-right">
            <date-picker
              class="date-picker-content"
              v-model="daterange"
              @change="updateData"
              valueType="format"
              range
            ></date-picker>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="loading"
      class="d-flex flex-column justify-content-center align-items-center py-5"
    >
      <b-spinner label="Loading..."></b-spinner>
      <br />
      <p>Loading....</p>
    </div>

    <div v-else>
      <div class="row" v-if="report">
        <div class="col-xl-12">
          <Stat :data="{ ...getStatData }" />
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12" v-if="report">
          <Transaction :data="report.best_seller" />
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <h2 class="card-title mb-4">{{ $t("menuitems.orders.text") }}</h2>
          <data-table
            :tableData="report.all_orders"
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
          </data-table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style lang="scss">
.date-picker-container {
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  .date-picker-content {
    width: 320px !important;
  }
}
</style>
