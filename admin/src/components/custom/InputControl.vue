<template>
   <div class="form-group row align-items-center">
      <label :for="getName" :class="`${labelClasss} ${getLabelclass} text-sm-right`" v-if="label">{{ label }}</label>
      <div :class="getFieldclass">
         <div class="input-group">
            <slot name="append" />
            <input :ref="getName" :id="getName" class="form-control rounded-right" :type="type" :value="value" :placeholder="placeholder" :readonly="readonly" @input="$emit('input', $event.target.value)" @focus.once="handleFocus" autocomplete="false" />
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
   name: "input-control",
   props: {
      readonly: {
         type: Boolean,
         default: false,
      },
      value: {
         required: true,
         default: null,
      },
      error: {
         type: Array,
         default: () => [],
      },
      name: {
         type: [String, Number],
         required: false,
      },
      label: {
         type: String,
         require: true,
      },
      type: {
         type: String,
         default: "text",
         validator: (val) =>
            val == "text" ||
            val == "number" ||
            val == "password" ||
            val == "tel" ||
            val == "email" ||
            val == "time" ||
            val == "date" ||
            val == "color"
      },
      placeholder: {
         type: String,
         required: false,
      },
      // Stack label
      stack: {
         type: Boolean,
         default: false,
      },
      clearonfocus: {
         type: Boolean,
         default: false,
      },
      focus: {
         type: Boolean,
         default: false,
      },
      // update column for modal use
      inModal: {
         type: Boolean,
         default: false,
      },
      labelClasss: { type: String, default: "mb-1" },
   },
   methods: {
      handleFocus() {
         if (this.clearonfocus) {
            this.$emit("input", "")
         }
      },
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
   mounted() {
      this.$nextTick(() => {
         if (this.focus) {
            setTimeout(() => {
               this.$refs[this.getName].focus()
            }, 0)
         }
      })
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

<style lang="scss"></style>
