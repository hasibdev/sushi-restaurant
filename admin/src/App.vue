<template>
  <div id="app">
    <router-view id="screen-view" />

    <cInvoice id="print-view" :order="lastOrder" :key="key" />

    <!-- <iframe
      v-if="lastOrder"
      :key="JSON.stringify(lastOrder)"
      id="app-receipt"
      @load="printReceipt"
      height="0"
      width="0"
      name="app-receipt"
      :src="lastOrder"
      frameborder="0"
    ></iframe> -->
  </div>
</template>

<script>
import appConfig from "@/app.config";

import PopUp from "@/assets/popup.wav";

import cInvoice from "@/components/custom/cInvoice.vue";

export default {
  name: "app",
  components: {
    cInvoice,
  },
  data() {
    return {
      lastOrder: window.localStorage.getItem("__last_order")
        ? JSON.parse(window.localStorage.getItem("__last_order"))
        : null,
      key: 0,
    };
  },
  page: {
    // All subcomponent titles will be injected into this template.
    titleTemplate(title) {
      title = typeof title === "function" ? title(this.$store) : title;
      return title ? `${title} | ${appConfig.title}` : appConfig.title;
    },
  },
  async created() {
    let settings = await this.axios.get("/settings");
    window.localStorage.setItem("settings", JSON.stringify(settings.data));
  },
  methods: {},
  mounted() {
    window.print_receipt = () => {
      let data = window.localStorage.getItem("__last_order")
        ? JSON.parse(window.localStorage.getItem("__last_order"))
        : null;
      if (data !== null) {
        this.lastOrder = data;
        this.key += 1;
        setTimeout(() => {
          window.print();
        }, 100);
      } else {
        this.lastOrder = null;
      }
    };

    // override the default print shortcut
    window.addEventListener("keydown", (e) => {
      if (e.keyCode === 80 && e.ctrlKey) {
        e.preventDefault();
        window.print_receipt();
      }
    });

    this.$firebase
      .messaging()
      .getToken({
        vapidKey:
          "BLgjpVljeGHgj_2guCLDKzSfBtfm8KLNzonTkRTTTX1zNyiEbi1sLy8d39nLk-719_OyNFxHVXxg04xLZDz1kFg",
      })
      .then((token) => {
        this.axios.post("/push/register", {
          token,
        });
      })
      .catch((err) => {
        console.log(err);
      });

    this.$firebase.messaging().onMessage((payload) => {
      new Audio(PopUp).play();

      let lastOrderId = payload.data.click_action.split("/");
      lastOrderId = lastOrderId[lastOrderId.length - 1];

      console.log(lastOrderId); // printing old order id

      let lastNotification = null;
      this.axios.get(`/checkout/order/${lastOrderId}`).then((res) => {
        if (lastNotification != res) {
          this.lastOrder = res.data;
          window.print_receipt();
          this.$toast.info(payload.notification.body, {
            position: "top-right",
            duration: 10000,
          });
          lastNotification = res;
        }
      });
    });
  },
};
</script>

<style lang="scss">
#print-view {
  display: none;
}

@media print {
  #screen-view {
    display: none;
  }

  #print-view {
    display: block;
    color: #000 !important;
  }

  .Vue-Toastification__container {
    display: none;
  }
}
</style>
