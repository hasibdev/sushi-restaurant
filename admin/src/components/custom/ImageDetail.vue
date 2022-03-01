<template>
   <div class="row py-3 detail-input">
      <div class="col-6 col-sm-3 col-md-2">
         <h6 class="text-gray">{{ label }}</h6>
      </div>
      <div class="col-6 col-sm-9 col-md-10">
         <slot :url="url">
            <a v-if="url" target="_blank" :href="url">
               <img style="max-width: 222px;" :src="url" :alt="altText" />
            </a>
            <span v-else>-</span>
         </slot>
      </div>
   </div>
</template>

<script>
export default {
   props: {
      label: {
         type: String,
         require: true,
      },
      altText: {
         type: String,
      },
      url: {
         type: String,
         default: "",
      },
   },
};
</script>

<style lang="scss">
.detail-input:not(:last-child) {
   border-bottom: 1px solid #ddd;
}
</style>
