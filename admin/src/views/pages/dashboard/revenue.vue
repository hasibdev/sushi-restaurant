<script>
/**
 * Revenue Analytics component
 */

import helpes from "@/mixins/helpers.js"

export default {
   props: {
      thisYear: {
         type: Object,
      },
      lastYear: {
         type: Object,
      },
   },
   mixins: [helpes],
   data() {
      return {
         series: [
            {
               name: new Date().getFullYear(),
               type: "column",
               data: Object.values(this.thisYear.monthly_amount),
            },
            {
               name: new Date().getFullYear() - 1,
               type: "line",
               data: Object.values(this.lastYear.monthly_amount),
            },
         ],
         chartOptions: {
            chart: {
               height: 280,
               type: "line",
               toolbar: {
                  show: false,
               },
            },
            stroke: {
               width: [0, 3],
               curve: "smooth",
            },
            plotOptions: {
               bar: {
                  horizontal: false,
                  columnWidth: "20%",
               },
            },
            dataLabels: {
               enabled: false,
            },
            legend: {
               show: false,
            },
            colors: ["#5664d2", "#1cbb8c"],
            labels: Object.keys(this.thisYear.monthly_amount),
         },
      }
   },
};
</script>

<template>
   <div class="card">
      <div class="card-body">
         <h4 class="card-title mb-4">{{ $t('heading.sales_analytics') }}</h4>
         <div>
            <div id="line-column-chart" class="apex-charts" dir="ltr"></div>
            <apexchart class="apex-charts" height="280" dir="ltr" :series="series" :options="chartOptions"></apexchart>
         </div>
      </div>

      <div class="card-body border-top text-center">
         <div class="row">
            <div class="col-sm-6">
               <div class="mt-4 mt-sm-0">
                  <p class="mb-2 text-muted text-truncate">
                     <i class="mdi mdi-circle text-primary font-size-10 mr-1"></i> {{$t('heading.this_year')}}:
                  </p>
                  <div class="d-inline-flex">
                     <h5 class="mb-0 mr-2">
                        {{ this.settings.currencySymbol }}
                        {{ this.thisYear.total_amount }}
                     </h5>
                  </div>
               </div>
            </div>
            <div class="col-sm-6">
               <div class="mt-4 mt-sm-0">
                  <p class="mb-2 text-muted text-truncate">
                     <i class="mdi mdi-circle text-success font-size-10 mr-1"></i>
                     {{$t('heading.previous_year')}} :
                  </p>
                  <div class="d-inline-flex">
                     <h5 class="mb-0">
                        {{ this.settings.currencySymbol }}
                        {{ this.lastYear.total_amount }}
                     </h5>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>
