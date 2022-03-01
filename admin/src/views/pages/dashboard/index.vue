<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";

import Stat from "./widget";
import RevenueAnalytics from "./revenue";
import SalesAnalytics from "./sales-analytics";
// import EarningReport from "./earning";
// import Sources from "./sources";
// import RecentActivity from "./recent-activity";
// import RevenueLocation from "./revenue-location";
// import Chat from "./chat";
import Transaction from "./transaction";

/**
 * Dashboard component
 */
export default {
  components: {
    Layout,
    PageHeader,
    Stat,
    RevenueAnalytics,
    SalesAnalytics,
    //  EarningReport,
    //  Sources,
    //  RecentActivity,
    //  RevenueLocation,
    //  Chat,
    Transaction,
  },
  data() {
    return {
      title: "menuitems.dashboard.text",
      items: [
        {
          text: "menuitems.dashboard.text",
          active: true,
        },
      ],
      thisYear: null,
      loading: true,
    };
  },
  computed: {
    getStatData() {
      return {
        completed: this.thisYear.complete_orders,
        pending: this.thisYear.pending_orders,
        processing: this.thisYear.processing_orders,
      };
    },
    getSalesAnalyticsData() {
      return {
        products: this.thisYear.total_products,
        additions: this.thisYear.total_additions,
        orders: this.thisYear.total_orders,
      };
    },
  },
  async created() {
    try {
      let year = new Date().getFullYear();
      let res = await this.axios.get(`/dashboard/overview/${year}`);
      this.thisYear = res.data;
      res = await this.axios.get(`/dashboard/overview/${year - 1}`);
      this.lastYear = res.data;
    } catch (error) {
      console.log(error);
    } finally {
      this.loading = false;
    }
  },
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <div
      v-if="loading"
      class="d-flex flex-column justify-content-center align-items-center py-5"
    >
      <b-spinner label="Loading..."></b-spinner>
      <br />
      <p>Loading....</p>
    </div>

    <div v-else>
      <div class="row" v-if="thisYear">
        <div class="col-xl-8">
          <Stat :data="{ ...getStatData }" />
          <RevenueAnalytics :thisYear="thisYear" :lastYear="lastYear" />
        </div>
        <div class="col-xl-4">
          <SalesAnalytics :data="{ ...getSalesAnalyticsData }" />
          <!-- <EarningReport :thisYear="thisYear" /> -->
        </div>
      </div>
      <!-- <div class="row">
        <div class="col-lg-4">
          <Sources />
        </div>
        <div class="col-lg-4">
          <RecentActivity />
        </div>
        <div class="col-lg-4">
          <RevenueLocation />
        </div>
      </div> -->
      <div class="row">
        <!-- <div class="col-lg-4">
               <Chat />
            </div> -->
        <div class="col-lg-12" v-if="thisYear">
          <Transaction :data="thisYear.best_seller" />
        </div>
      </div>
    </div>
  </Layout>
</template>
