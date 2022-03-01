<template>
  <div>
    <b-form-file accept="video/*" @change="pickFile"></b-form-file>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [String, Array],
      required: true,
      default: null,
    },
  },
  data() {
    return {};
  },
  methods: {
    pickFile(e) {
      const files = e.target.files;
      if (files && files[0]) {
        const reader = new FileReader();
        reader.readAsDataURL(files[0]);
        reader.onload = (e) => {
          // this.previewImage = e.target.result
          this.$emit("input", e.target.result);
        };
      }
    },
  },
};
</script>

<style lang="scss" scoped></style>
