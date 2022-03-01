<template>
   <div class="form-group row align-items-center">
      <label :for="getName" class="text-sm-right mb-0" :class="`${labelClasss} ${getLabelclass}`" v-if="label">{{ label }}</label>
      <div :class="`${getFieldclass}`">
         <multiselect :class="{'border border-danger rounded': validation.$dirty && validation.$anyError}" tag-placeholder="Add this as new tag" :placeholder="placeholder" :label="title" :track-by="track" :options="options" :multiple="multiple" :taggable="true" v-model="innerValue" @input="handleInput" :disabled="readonly"></multiselect>
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
import Multiselect from "vue-multiselect"
export default {
   props: {
      // Select options
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
         default: "Choose Options",
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

      // Make the controll disable
      readonly: {
         type: Boolean,
         default: false,
      },
      multiple: {
         type: Boolean,
         default: true,
      },
      labelClasss: { type: String, default: "mb-1" },
   },
   components: {
      Multiselect,
   },
   data() {
      return {
         innerValue: this.value,
      }
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
   methods: {
      handleInput(innerValue) {
         const values = innerValue.map((value) => value[this.track])
         if (values.includes("all")) {
            this.innerValue = [innerValue.find((value) => value.id === "all")]
            this.$emit("input", ["all"])
         } else {
            this.$emit("input", values)
         }
      },
   },
   watch: {
      value: {
         immediate: true,
         handler(value) {
            if (!value?.length) {
               return this.innerValue = []
            }
            const newValue = []
            value.forEach(val => {
               if (typeof val == 'object') {
                  newValue.push(val)
               } else {
                  newValue.push(this.options.find(opt => opt.id == val))
               }
            })

            this.innerValue = newValue

         },
      },
   },
};
</script>

<style lang="scss">
.form-group {
   label {
      align-self: start;
   }
}
</style>
