<template>
   <div class="form-group row align-items-center">
      <label :for="getName" class="text-sm-right" :class="`${labelClasss} ${getLabelclass}`" v-if="label">{{ label }}</label>
      <div :class="`relative ${getFieldclass}`">
         <div class="d-flex">
            <select v-if="!loader" :id="getName" class="form-control" @change="$emit('input', $event.target.value)" :disabled="disabled" :class="{ 'bg-light': disabled, 'is-invalid': validation.$dirty && validation.$anyError }">
               <option value="" v-if="showPlaceholder">{{ placeholder }}</option>
               <option v-for="(option, index) in options" :key="index" :selected="value == option[track]" :value="option[track]">
                  <slot :option="option">{{ option[title] }}</slot>
               </option>
            </select>
            <b-spinner v-else label="Spinning" variant="primary"></b-spinner>

            <slot name="icon-right" />
         </div>

         <p class="text-muted mb-0">
            <slot name="help"></slot>
         </p>
         <span>
            <slot name="action"></slot>
         </span>
         <span class="text-danger">{{ error[0] }}</span>
      </div>
   </div>
</template>

<script>
export default {
   props: {
      // Select op
      options: {
         type: [Array, Object],
         required: true,
      },
      validation: { type: Object },

      // Input Label
      label: {
         type: String,
         required: false,
      },

      // Option title to show
      title: {
         type: String,
         default: "name",
      },

      // Return value for v-model
      track: {
         type: String,
         default: "id",
      },

      // Validation error
      error: {
         type: Array,
         default: () => [],
      },

      // Use for id and tag
      name: {
         type: [String, Number],
         required: false,
      },

      // Select placeholder
      placeholder: {
         type: String,
         default: "Select One",
      },

      // Default selected value
      value: {
         default: "",
      },

      // Control select placeholder
      showPlaceholder: {
         type: Boolean,
         default: true,
      },

      // Stack label
      stack: {
         type: Boolean,
         default: false,
      },

      // Disabled
      disabled: {
         type: Boolean,
         default: false,
      },
      // update column for modal use
      inModal: {
         type: Boolean,
         default: false,
      },
      labelClasss: { type: String, default: "mb-1" },
      loader: { type: Boolean, default: false },
   },
   computed: {
      getName() {
         if (this.label) {
            return this.name
               ? this.name
               : this.label
                  .replace(/([a-z])([A-Z])/g, "$1-$2")
                  .replace(/\s+/g, "-")
                  .toLowerCase()
         }
         return `input_label${Math.random().toFixed(5).split('.')[1]}`
      },
      getLabelclass() {
         return this.label && this.stack === false
            ? this.inModal
               ? "col-sm-4"
               : "col-sm-3 col-md-2"
            : "col-sm-12"
      },
      getFieldclass() {
         return this.label && this.stack === false
            ? this.inModal
               ? "col-sm-8"
               : "col-sm-9 col-md-10"
            : "col-sm-12"
      },
   },
   watch: {
      /**
       * Watch for value change
       */
      value() {
         this.$emit("change", this.value)
      },
   },
};
</script>

<style lang="scss" scoped></style>
