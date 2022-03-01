<template>
  <div v-if="order && user" class="invoice">
    <header class="header">
      <img
        src="@/assets/images/Sushitown.png"
        alt="Sushitown"
        width="80"
        class="logo"
      />
      <ul class="main-heading">
        <li class="text-center mb-1">
          <h4><strong>Ticket Vente</strong></h4>
        </li>
        <li class="text-center">
          Sushitown, {{ settings.address.street }}, {{ settings.address.city }},
          {{ settings.address.contry }}
        </li>
        <hr class="style-1" />
        <li>
          Ticket no:
          <i
            ><b>#{{ order.id }}</b></i
          >
        </li>
        <li>Mode de paiement: {{ translate(order.content.paymentMethod) }}</li>
        <li>Date: {{ order.datetime }}</li>
      </ul>
      <hr class="style-1" />
      <div class="row">
        <div class="col">
          <ul>
            <li>
              <b>Client #{{ order.content.customerId || "000" }}</b>
            </li>
            <li>{{ order.content.name }}</li>
            <li>
              {{ order.content.phone || order.content.email }}
            </li>
            <li></li>
          </ul>
        </div>
        <div class="col">
          <ul>
            <li>
              <b>Le Caissier #{{ user.id }}</b>
            </li>
            <li>{{ user.full_name }}</li>
            <li>{{ settings.phone.replace("+", "") }}</li>
          </ul>
        </div>
      </div>
    </header>
    <hr class="style-2" />
    <!-- invoice table -->
    <table>
      <thead>
        <tr>
          <th>Produits</th>
          <th class="text-right">Opt.</th>
          <th class="text-right">Extra</th>
          <th class="text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in order.content.items" :key="index">
          <td>{{ item.name }} ({{ item.price }} x {{ item.quantity }})</td>
          <td class="text-right">
            {{ item.option_price }}
          </td>
          <td class="text-right">
            {{ item.addition_price }}
          </td>
          <td class="text-right">
            {{ item.total == 0 ? "Gratuit" : item.total }}
          </td>
        </tr>
        <tr v-if="order.content.other > 0">
          <td>{{ order.content.other.note || "Autres charges" }}</td>
          <td class="text-right">0</td>
          <td class="text-right">0</td>
          <td class="text-right">
            {{ order.content.other.cost }}
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3"><b>Total HT</b></td>
          <td class="text-right">
            {{ settings.currencySymbol }}
            {{
              parseFloat(order.content.productsTotal) -
              parseFloat(order.content.tax)
            }}
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <b>TVA ({{ settings.tax }})</b>
          </td>
          <td class="text-right">
            {{ settings.currencySymbol }} {{ order.content.tax || 0 }}
          </td>
        </tr>
        <tr v-if="parseFloat(order.content.deliveryArea.deliveryCost || 0) > 0">
          <td colspan="3">
            <b>Livraison</b>
          </td>
          <td class="text-right">
            {{ settings.currencySymbol }}
            {{ order.content.deliveryArea.deliveryCost }}
          </td>
        </tr>
        <tr>
          <td colspan="3"><b>Remise</b></td>
          <td class="text-right">
            {{ settings.currencySymbol }} {{ order.content.discountAmount }}
          </td>
        </tr>
        <tr>
          <td colspan="3"><strong>Total TTC</strong></td>
          <td class="text-right">
            <strong>
              {{ settings.currencySymbol }}
              {{ parseFloat(order.content.total).toFixed(2) }}
            </strong>
          </td>
        </tr>
      </tfoot>
    </table>
    <hr class="style-2" />
    <h5 class="text-center">Merci de votre visite!</h5>
    <hr class="style-2" />

    <div class="break-the-page"></div>

    <div class="d-flex align-items-center justify-content-between pt-4">
      <div class="invoice-no">
        <h1>ID #{{ order.id }}</h1>
      </div>
      <div class="qr-code"><qrcode-vue :value="url"></qrcode-vue></div>
    </div>
  </div>
</template>

<script>
import QrcodeVue from "qrcode.vue";

export default {
  name: "cInvoice",
  components: {
    QrcodeVue,
  },
  data() {
    return {
      settings: JSON.parse(window.localStorage.getItem("settings")),
      url: window.location.href.replace("/invoice", "/orders"),
    };
  },
  computed: {
    user() {
      return this.$store.state.auth.currentUser;
    },
  },
  props: {
    order: {
      type: Object,
      required: false,
    },
  },
  methods: {
    translate(text) {
      if (text == "Card") {
        return "Carte";
      } else if (text == "Cash") {
        return "Espèce";
      } else if (text == "Card+Cash") {
        return "Carte + Espèce";
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@media print {
  body {
    background: white !important;
    color: black !important;
    border-color: black !important;
  }
  .break-the-page {
    break-before: page;
    page-break-before: page;
  }
}

.invoice {
  font-family: inter, sans-serif, monospace;
  max-width: 80mm;
  padding: 5mm;
  font-size: 14px;
  line-height: 1.4;
  background: white !important;
  color: black !important;
  border-color: black !important;
  filter: grayscale(100%);

  hr.style-1 {
    margin: 2.5px;
    border-top: 2px;
    border-style: dashed;
  }

  hr.style-2 {
    margin: 2.5px;
    border-top: 3px;
    border-style: dashed;
  }

  h5,
  h4,
  h1 {
    margin: 0;
    color: black !important;
  }

  .header {
    ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }
  }

  strong {
    font-weight: 900;
  }

  .logo {
    display: block;
    margin: 10px auto;
  }

  table {
    width: 100%;
    thead {
      border-bottom: 2px dashed;
    }
    tbody tr {
      border-bottom: 1px dashed;
    }
    td,
    th {
      padding-right: 5px;
    }
  }
}
.text-right {
  min-width: 65px;
}
</style>
