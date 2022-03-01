<template>
  <footer class="footer">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">{{ year }} © iWebDesigner.</div>
        <div class="col-sm-6">
          <div class="text-sm-right d-none d-sm-block">
            Crafted with
            <i class="mdi mdi-heart text-danger"></i> by iWebDesigner
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "Footer",
  data() {
    return {
      year: new Date().getFullYear(),
    };
  },
};
</script>
