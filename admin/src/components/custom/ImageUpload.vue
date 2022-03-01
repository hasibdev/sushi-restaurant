<template>
   <div class="form-group row align-items-center">
      <label class="text-sm-right" :for="getName" :class="`${labelClasss} ${getLabelclass}`" v-if="label">{{ label }}</label>
      <div :class="getFieldclass">
         <label v-if="!previewImage" class="image-upload-wrapper" :class="{'border border-danger': validation.$dirty && validation.$anyError}" :style="{width: width, height: height}">
            Upload Your Image
            <input :id="getName" class="form-control" type="file" name="file" :placeholder="placeholder" :readonly="readonly" @input="pickFile" />
         </label>

         <span v-else class="upload-img-wrapper">
            <img class="preview-image" style="max-width: 222px;" :src="previewImage" alt="" />
            <div class="img-overlay">
               <i @click="removePreview" class="fas fa-times bg-danger rounded-circle m-1"></i>
            </div>
         </span>
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
   name: "image-upload",
   props: {
      readonly: {
         type: Boolean,
         default: false,
      },
      validation: { type: Object },
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
         validator: (val) => val == "text" || val == "number" || val == "password",
      },
      width: {
         type: String,
         default: "300px",
      },
      height: {
         type: String,
         default: "160px",
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
      url: {
         type: String,
         default: null,
      },
      // update column for modal use
      inModal: {
         type: Boolean,
         default: false,
      },
      labelClasss: { type: String, default: "mb-0" },
   },
   data() {
      return {
         previewImage: null,
      }
   },
   methods: {
      pickFile(e) {
         const files = e.target.files
         if (files && files[0]) {
            const reader = new FileReader()
            reader.readAsDataURL(files[0])
            reader.onload = (e) => {
               this.previewImage = e.target.result
               this.$emit("input", this.previewImage)
            }

         }
      },
      removePreview() {
         this.previewImage = null
         this.$emit("input", "")
      },
   },
   mounted() {
      if (this.url) {
         this.previewImage = this.url
      }
      // if (this.value) {
      //    const reader = new FileReader()
      //    reader.onload = (e) => {
      //       this.previewImage = e.target.result
      //    }
      //    reader.readAsDataURL(this.value)
      // }
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
      value(newValue) {
         this.$emit("change", this.value)

         if (!newValue) {
            this.previewImage = null
         }
      },
   },
};
</script>

<style lang="scss" scoped>
.image-upload-wrapper {
   // height: 160px;
   // width: 300px;
   background: #efefef;
   border: 1px solid #ddd;
   border-radius: 3px;
   display: flex;
   justify-content: center;
   align-items: center;
   cursor: pointer;
   input[type="file"] {
      display: none;
   }
}

.upload-img-wrapper {
   height: 200px;
   // width: 400px;
   box-sizing: content-box;
   position: relative;
   background-repeat: no-repeat;
   background-size: contain;
   display: inline-block;

   .preview-image {
      width: 100%;
      height: 100%;
      border-radius: 3px;
      object-fit: contain;
   }

   &:hover .img-overlay {
      display: block;
   }

   .img-overlay {
      display: none;
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 10;

      i.fa-times {
         float: right;
         // padding: 10px;
         width: 30px;
         height: 30px;
         display: flex;
         justify-content: center;
         align-items: center;
         color: #fff;
         cursor: pointer;
      }
   }
}
</style>
