<template>
  <div id="pos" class="bg-white container-fluid overflow-hidden">
    <div
      v-if="loading"
      class="d-flex flex-column justify-content-center align-items-center py-5"
    >
      <b-spinner :label="$t('placeholder.loading')"></b-spinner>
      <br />
      <p>{{ $t("placeholder.loading") }}</p>
    </div>

    <div class="row" v-if="!loading">
      <!-- Left Content -->
      <div
        class="category-content col-md-2 p-0 scrollable-div custom-scrollbar bg-light"
      >
        <!-- Categories -->
        <div class="p-3 bg-primary">
          <h5 class="mb-0 text-white bold-italic">Categories</h5>
        </div>

        <div class="p-3">
          <div
            v-for="category in categories"
            :key="category.id"
            @click="onCategorySelect(category.id)"
            :class="`mb-2 card shadow-lg  pointer category-card  ${
              category.id == selectedCategory ? 'selected' : null
            }`"
            style="overflow: hidden"
          >
            <div class="card-body p-0 bg-primary">
              <div
                class="cat-img-cover"
                :style="'background-image: url(' + category.image.url + ')'"
              ></div>
              <h6 class="m-1 text-white limited-text bold">
                {{ category.name }}
              </h6>
            </div>
          </div>
        </div>
      </div>
      <!-- Center Content -->
      <div id="left-content" class="col-md-7 custom-scrollbar p-0">
        <!-- Products -->
        <div
          class="d-flex justify-content-between align-items-center shadow-lg"
          style="top: 0; position: sticky; z-index: 100; background: white"
        >
          <h5 class="m-0 p-3 bold-italic" style="">Select Products</h5>
          <div class="col-4">
            <!-- <b-input class="shadow-xxl border-0" title="Search" placeholder="Search..." /> -->
            <b-input-group class="border-0 rounded">
              <template #append>
                <b-input-group-text class="border-0 bg-white">
                  <i class="fa fa-search"></i>
                </b-input-group-text>
              </template>
              <b-form-input
                v-model="search"
                class="border-0"
                title="Search"
                placeholder="Search..."
              ></b-form-input>
            </b-input-group>
          </div>
        </div>

        <div class="product-grid mt-3 p-3">
          <div
            v-for="product in displayProducts"
            :key="product.id"
            class="product-item shadow-lg card mb-0"
          >
            <div @click="onProductSelect(product)" class="product-item-top">
              <b-avatar
                :src="product.image.url"
                size="2.5rem"
                class="mx-auto d-block shadow-lg"
                style="margin: 0 !important"
              ></b-avatar>
              <h5 class="m-0 ml-2 text-success bold-italic">
                {{ currencySymbol }} {{ product.price }}
              </h5>
            </div>
            <div @click="onProductSelect(product)" class="product-item-bottom">
              <h4 class="item-title text-center my-1 bold-italic">
                {{ product.name }}
              </h4>
              <span class="pos-is-option">
                <p
                  class="text-gray font-weight-bold mb-0"
                  v-if="product.options.length > 0"
                >
                  * options
                </p>
                <p
                  class="text-gray font-weight-bold mb-0"
                  v-if="product.additions.length > 0"
                >
                  * additions
                </p>
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- Right Content -->
      <div
        :key="JSON.stringify(orderTemplate)"
        id="right-content"
        class="col-md-3 px-0 bg-light"
      >
        <div class="scrollable-div custom-scrollbar">
          <h5
            class="d-flex align-item-center justify-content-between bg-primary"
          >
            <span class="text-white p-3 bold-italic">Order List</span>
            <button
              v-if="holdsList()"
              class="btn text-white"
              style="background: rgba(0, 0, 0, 0.1)"
              @click="showHolds()"
            >
              Load Order ({{ holdsList().length }})
            </button>
          </h5>

          <p v-if="!orderList.length" class="p-3">Empty Order List!</p>
          <div class="p-3">
            <div v-for="(order, i) in orderList" :key="i">
              <div
                class="card card-body mb-2 order-item shadow-lg p-3"
                style="border-radius: 5px; border: 2px solid #88888888"
              >
                <div class="d-flex flex-row">
                  <b-avatar
                    :src="getOrderImageUrl(order.id)"
                    class="shadow-lg"
                  ></b-avatar>

                  <div
                    class="ml-3 w-100 d-flex justify-content-between align-items-center"
                  >
                    <p
                      class="mb-0 font-weight-bolder font-size-17"
                      v-if="order.price > 0"
                    >
                      {{ order.name }} x {{ order.quantity }}
                      <span
                        title="Addition Price"
                        v-if="order.addition_price"
                        class="add-price"
                        >{{ order.addition_price }}</span
                      ><span
                        title="Option Price"
                        v-if="order.option_price"
                        class="opt-price"
                        >{{ order.option_price }}</span
                      >
                    </p>
                    <p class="mb-0 font-weight-bolder font-size-17" v-else>
                      {{ order.name }}
                    </p>
                    <h5 class="mb-0 bold-italic text-success">
                      <span v-if="order.price > 0"
                        >{{ currencySymbol
                        }}{{
                          parseFloat(order.price * order.quantity).toFixed(2)
                        }}</span
                      >
                      <span v-else>Free</span>
                    </h5>
                  </div>
                </div>

                <!-- Remove Icon -->
                <div @click="removeOrder(i)" class="remove-order p-1 pointer">
                  <i class="far fa-times-circle font-size-20 text-danger"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="spacer-big"></div>
        </div>

        <!-- Summery -->
        <div class="card card-body order-summery mt-auto">
          <h6 class="d-flex justify-content-between bold">
            <span>Subtotal</span>
            <span>{{ parseFloat(countSubtotal - countTax).toFixed(2) }}</span>
          </h6>
          <h6 class="d-flex justify-content-between bold">
            <span>Tax ({{ taxPercent }}%) </span> <span>{{ countTax }}</span>
          </h6>
          <p v-if="deliveryCharge" class="mb-0 d-flex justify-content-between">
            <span>Delivery Charge</span>
            <span>{{ currencySymbol }} {{ deliveryCharge }}</span>
          </p>
          <p class="mb-0 d-flex justify-content-between">
            <span>Discount</span>
            <span>{{ currencySymbol }} {{ discount }}</span>
          </p>
          <hr />
          <h5 class="d-flex justify-content-between bold">
            <span>Total</span>
            <span>{{ currencySymbol }} {{ countGrandTotal }}</span>
          </h5>

          <b-btn
            @click="showCheckout"
            :disabled="processing || !orderList.length"
            class="mt-4 pos-btn bold-italic block"
            variant="info"
            >Continue</b-btn
          >
        </div>
      </div>
    </div>

    <!-- Cart Modal -->
    <div v-if="selectedProduct">
      <b-modal ref="order-modal" hide-footer :title="selectedProduct.name">
        <template #modal-header>
          <div>
            <h5>{{ selectedProduct.name }}</h5>
            <p class="mb-0">{{ selectedProduct.description }}</p>
          </div>
          <p class="mb-0 font-size-20">
            {{ currencySymbol }}{{ selectedProduct.price }}
          </p>
        </template>

        <!-- Options -->

        <h5>Options</h5>
        <div class="row">
          <div
            v-for="(opt, i) in selectedProduct.options"
            :key="i"
            class="col-6"
          >
            <select-field
              stack
              @input="onOptionSelect(opt, $event)"
              :value="opt.list[0].id"
              :label="opt.name"
              :options="opt.list"
            ></select-field>
          </div>
        </div>
        <hr class="mt-1" />

        <!-- Additions -->
        <h5 v-if="selectedProduct.additions != []">Additions</h5>
        <multiselect-control
          @input="onAdditionSelect"
          :options="selectedProduct.additions"
          :validation="$v.fake"
        ></multiselect-control>

        <!-- Item Count -->
        <h5>Quantity</h5>
        <!-- input number with plus minus buttons -->
        <div class="d-flex justify-content-between mb-4">
          <b-btn
            @click="orderItem.quantity--"
            :disabled="orderItem.quantity <= 1"
            class="btn-sm mr-2"
            variant="danger"
          >
            <i class="fas fa-minus"></i>
          </b-btn>
          <input
            type="number"
            class="form-control"
            v-model="orderItem.quantity"
          />
          <b-btn
            @click="orderItem.quantity++"
            class="btn-sm ml-2"
            variant="success"
          >
            <i class="fas fa-plus"></i>
          </b-btn>
        </div>

        <!-- Informations -->
        <h5>Informations</h5>
        <textarea v-model="orderItem.note" class="form-control"></textarea>

        <b-btn
          @click="addToOrderList"
          class="mt-4 pos-btn bold"
          block
          variant="primary"
          >ADD TO CART</b-btn
        >
      </b-modal>
    </div>

    <!-- Checkout Modal -->
    <div>
      <b-modal ref="confirm-modal" hide-footer title="Order Information">
        <template #modal-header>
          <div>
            <h5>Confirm Order</h5>
          </div>
        </template>

        <div class="d-flex justify-content-around mb-3 pb-3 top-info-bar">
          <div class="mb-0 d-flex flex-column text-center top-info-item">
            <h5>Total</h5>
            <h6>{{ currencySymbol }} {{ countGrandTotal }}</h6>
          </div>
          <div class="mb-0 d-flex flex-column text-center top-info-item">
            <h5>Due</h5>
            <h6>{{ currencySymbol }} {{ countDue > 0 ? countDue : 0.0 }}</h6>
          </div>
          <div class="mb-0 d-flex flex-column text-center top-info-item">
            <h5>Return</h5>
            <h6>
              {{ currencySymbol }} {{ countDue &lt; 0 ? -countDue : 0.0 }}
            </h6>
          </div>
        </div>

        <h5 class="mb-3">Other Charges</h5>
        <div class="row">
          <div class="col-8">
            <h6>Note</h6>
            <valid-input
              v-model="orderTemplate.other.note"
              :validation="$v.orderTemplate.other.note"
              placeholder="Ex: Ordered ouside food"
              label=""
            ></valid-input>
          </div>
          <div class="col-4">
            <h6>Amount</h6>
            <valid-input
              type="number"
              v-model="orderTemplate.other.cost"
              :validation="$v.orderTemplate.other.cost"
              label=""
            ></valid-input>
          </div>
        </div>

        <h5 class="mb-3">Flat Discount</h5>
        <valid-input
          type="number"
          v-model="orderTemplate.flatDiscount"
          :validation="$v.orderTemplate.flatDiscount"
          placeholder="Ex: 10"
          label=""
        ></valid-input>

        <!-- Calculator -->
        <h5 class="mb-3">Received</h5>
        <valid-input
          type="number"
          v-model="received_amount"
          :validation="$v.fake"
          placeholder="Ex: 10"
          min="0"
          label=""
        >
        </valid-input>

        <!-- Order type -->
        <h5 class="mb">Order Type</h5>
        <div class="row">
          <div class="col-6">
            <button
              class="pay-option"
              :class="{ active: isTakeAway }"
              @click.prevent="
                isTakeAway = true;
                userType = false;
                orderTemplate.paymentMethod = 'Cash';
              "
            >
              <i class="fa fa-box"></i> Takeaway
            </button>
          </div>
          <div class="col-6">
            <button
              class="pay-option"
              :class="{ active: isTakeAway == false && isTakeAway != null }"
              @click.prevent="
                isTakeAway = false;
                userType = 'existing';
              "
            >
              <i class="fa fa-truck"></i> Delivery
            </button>
          </div>
        </div>

        <div v-if="isTakeAway != null" class="next-step">
          <!-- Payment method -->
          <h5 class="mb-3">Payment Method</h5>
          <div class="row">
            <div class="col-4">
              <button
                class="pay-option"
                :class="{ active: orderTemplate.paymentMethod == 'Card' }"
                @click.prevent="setMethod('Card')"
              >
                <i class="fa fa-credit-card"></i> Card
              </button>
            </div>
            <div class="col-4">
              <button
                class="pay-option"
                :class="{ active: orderTemplate.paymentMethod == 'Cash' }"
                @click.prevent="setMethod('Cash')"
              >
                <i class="fa fa-euro-sign"></i> Cash
              </button>
            </div>
            <div class="col-4">
              <button
                class="pay-option"
                :class="{ active: orderTemplate.paymentMethod == 'Cash+Card' }"
                @click.prevent="setMethod('Cash+Card')"
              >
                <i class="fa fa-cash-register"></i> Cash + Card
              </button>
            </div>
          </div>

          <!-- Select User -->
          <h5 class="my-3">Select Customer</h5>

          <div class="row">
            <div class="col-4">
              <button
                class="pay-option"
                :class="{ active: userType == 'existing' }"
                @click.prevent="userType = 'existing'"
              >
                <i class="fa fa-user"></i> Existing
              </button>
            </div>
            <div class="col-4">
              <button
                class="pay-option"
                :class="{ active: userType == 'new' }"
                @click.prevent="userType = 'new'"
              >
                <i class="fa fa-user-plus"></i> New
              </button>
            </div>
            <div class="col-4">
              <button
                class="pay-option"
                :class="{ active: userType == false }"
                @click.prevent="userType = false"
              >
                <i class="fa fa-user-minus"></i> No
              </button>
            </div>
          </div>

          <div v-if="userType == 'existing'">
            <vue-select
              label="index"
              :options="options"
              :filterable="false"
              @search="onSearch"
              @input="onUserSelect"
              class="mb-3"
              placeholder="Select Customer"
            >
              <template slot="no-options"> Search users... </template>
              <template slot="option" slot-scope="option">
                <div class="d-center">
                  {{ option.full_name }} ({{
                    option.phone || option.email || "N/A"
                  }})
                </div>
              </template>
              <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">
                  {{ option.full_name }} ({{
                    option.phone || option.email || "N/A"
                  }})
                </div>
              </template>
            </vue-select>
            <valid-input
              :value="orderTemplate.name"
              :validation="$v.orderTemplate.name"
              :disabled="true"
              label="Name"
            ></valid-input>
            <valid-input
              :value="orderTemplate.phone"
              :validation="$v.orderTemplate.phone"
              :disabled="true"
              label="Phone"
            ></valid-input>
            <valid-input
              :value="orderTemplate.email"
              :validation="$v.orderTemplate.email"
              :disabled="true"
              label="Email"
            ></valid-input>
            <text-field
              v-model="orderTemplate.street"
              :disabled="false"
              label="Address"
            ></text-field>
          </div>

          <!-- User info -->
          <div v-if="userType == 'new'">
            <valid-input
              v-model="orderTemplate.name"
              :validation="$v.orderTemplate.name"
              label="Name"
            ></valid-input>
            <valid-input
              v-model="orderTemplate.phone"
              :validation="$v.orderTemplate.phone"
              label="Phone"
            ></valid-input>
            <valid-input
              v-model="orderTemplate.email"
              :validation="$v.orderTemplate.email"
              label="Email"
            ></valid-input>
            <text-field
              v-model="orderTemplate.street"
              label="Address"
            ></text-field>
          </div>

          <div v-if="isTakeAway == true && locations.length > 1">
            <h5>Select Branch</h5>
            <select-field
              v-model="branch"
              stack
              :options="locations"
            ></select-field>
          </div>

          <div v-if="!isTakeAway">
            <h5>Delivery Area</h5>
            <select-field
              v-model="deliveryArea"
              :stack="false"
              track="zipCode"
              title="city"
              :options="deliveryAreas"
            ></select-field>
          </div>

          <div class="row">
            <div class="col-6">
              <b-btn
                @click="addToHold"
                class="mt-4 pos-btn"
                block
                variant="danger"
                :disabled="processing"
              >
                Hold Order</b-btn
              >
            </div>
            <div class="col-6">
              <b-btn
                :disabled="processing"
                @click="makeSale"
                class="mt-4 pos-btn"
                block
                variant="primary"
              >
                Confirm and Print
              </b-btn>
            </div>
          </div>
        </div>
      </b-modal>
    </div>

    <!-- Hold Modal -->
    <div>
      <b-modal ref="hold-modal" hide-footer title="On Hold">
        <template #modal-header>
          <div>
            <h5>On Hold</h5>
            <p class="mb-0">Load orders from hold</p>
          </div>
        </template>

        <div v-if="holdsList().length">
          <div
            v-for="(item, index) in holdsList()"
            :key="index"
            class="hold-item"
          >
            <div>{{ item.name }} ({{ item.phone || item.email }})</div>
            <button
              class="btn bg-success text-white"
              @click="loadFromHold(index)"
            >
              Load ({{ item.items.length }} Items)
            </button>
          </div>
        </div>
        <div v-else>
          <p class="text-center">No orders on hold</p>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import SelectField from "@/components/custom/SelectField";
import MultiselectControl from "@/components/custom/MultiselectControl";
// import carousel from "vue-owl-carousel";

import TextField from "@/components/custom/TextField";
import ValidInput from "@/components/custom/ValidInput";
import { required } from "vuelidate/lib/validators";

import _ from "lodash";

import sound from "@/assets/click.wav";
import saleSound from "@/assets/popup.wav";

import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  name: "pos",
  components: {
    MultiselectControl,
    SelectField,
    // carousel,
    TextField,
    ValidInput,
    VueSelect,
  },
  data() {
    return {
      loading: true,
      processing: false,

      lastOrderId: null,
      userType: false,
      isTakeAway: null,
      received_amount: 0,

      search: "",

      options: [],

      locations: [],
      deliveryAreas: window.localStorage.getItem("settings")
        ? JSON.parse(window.localStorage.getItem("settings")).deliveryAreas
        : [],
      branch: null,
      deliveryArea: null,

      offers: [],
      categories: [],
      selectedCategory: null,
      discountPercent: 0,
      taxPercent: window.localStorage.getItem("settings")
        ? JSON.parse(window.localStorage.getItem("settings")).tax
        : 0,
      deliveryCharge: 0,

      activeOffers: [],
      activeOffersIds: [],

      products: [], // Add products from created
      displayProducts: [], // Display product after select category
      selectedProduct: null, // data to show in Modal
      orderList: [], // Which product in orderList

      // bind data when add to cart
      orderItem: {
        id: "",
        name: "",
        price: "",
        description: "",
        categoryId: "",

        additions: [],
        options: [],

        option_price: "",
        addition_price: 0,
        quantity: 1,
        total: "",
        note: "",
      },

      orderTemplate: {
        orderType: "PICKUP",
        deliveryArea: {
          zipCode: "",
          city: "",
          deliveryCost: "",
        },
        other: {
          note: "",
          cost: 0,
        },
        flatDiscount: 0,
        tax: 0,
        customerId: null,
        deliveryTime: "Instant",
        paymentMethod: "Cash",
        name: "Walk in Customer",
        street: "N/A",
        phone: "N/A",
        email: "N/A",
        accessCode: "",
        city: "N/A",
        activeOffersIds: [],
        discountCoupon: null,
        stripe: null,
        currency: "EURO",
        items: [],
        productsTotal: 0,
        total: 0,
        status: "delivered",
        orderFrom: "pos",
      },
    };
  },
  computed: {
    currencySymbol() {
      return this.$store.state.settings.data.currencySymbol || "€";
    },
    discount() {
      return parseFloat(
        this.countSubtotal * this.discountPercent +
          parseFloat(this.orderTemplate.flatDiscount || 0)
      ).toFixed(2);
    },
    countSubtotal() {
      let total = 0 + parseFloat(this.orderTemplate.other.cost || 0);
      this.orderList.forEach((item) => {
        total = parseFloat(total) + (parseFloat(item.total) || 0);
      });

      return total.toFixed(2);
    },
    countDue() {
      return parseFloat(
        parseFloat(this.countGrandTotal) - parseFloat(this.received_amount)
      ).toFixed(2);
    },
    countTax() {
      let percent = parseFloat(this.taxPercent || 0);
      percent = percent / 100;
      return parseFloat(this.countSubtotal * percent).toFixed(2);
    },
    countGrandTotal() {
      return parseFloat(
        parseFloat(this.countSubtotal) -
          parseFloat(this.discount) +
          parseFloat(this.deliveryCharge)
      ).toFixed(2);
    },
  },
  methods: {
    clickSound() {
      new Audio(sound).play();
    },
    saleSound() {
      return new Audio(saleSound).play();
    },
    showHolds() {
      this.clickSound();
      this.$refs["hold-modal"].show();
    },
    holdsList() {
      this.clickSound();
      return JSON.parse(window.localStorage.getItem("__holds__") || "[]");
    },
    addToHold() {
      this.clickSound();
      let holds = this.holdsList();
      if (!holds) {
        holds = [];
      }
      this.orderTemplate.items = this.orderList;

      holds.push(this.orderTemplate);
      window.localStorage.setItem("__holds__", JSON.stringify(holds));

      this.orderTemplate = {
        orderType: "PICKUP",
        deliveryArea: {
          zipCode: "",
          city: "",
          deliveryCost: "",
        },
        other: {
          note: "",
          cost: 0,
        },
        flatDiscount: 0,
        tax: 0,
        customerId: null,
        deliveryTime: "Instant",
        paymentMethod: "Cash",
        name: "Walk in Customer",
        street: "N/A",
        phone: "N/A",
        email: "N/A",
        accessCode: "",
        city: "N/A",
        activeOffersIds: [],
        discountCoupon: null,
        stripe: null,
        currency: "EURO",
        items: [],
        productsTotal: 0,
        total: 0,
        status: "delivered",
        orderFrom: "pos",
      };

      this.orderList = [];
      this.activeOffers = [];

      this.processing = false;

      this.$refs["confirm-modal"].hide();
      this.isTakeAway = null;
      this.saleSound();
      this.$toast.success("Order added to hold");
    },
    loadFromHold(index) {
      this.clickSound();
      this.processing = true;
      let holds = this.holdsList();
      let hold = holds[index];

      hold.items = hold.items.filter((item) => {
        return item.price > 0;
      });

      this.orderTemplate = hold;

      hold.items.forEach((item) => {
        this.orderItem = item;
        this.selectedProduct = item;
        this.addToOrderList();
      });

      // remove this index from holds
      holds.splice(index, 1);
      window.localStorage.setItem("__holds__", JSON.stringify(holds));
      this.$toast.success("Order loaded from hold");
      this.processing = false;
    },
    onCategorySelect(val) {
      this.selectedCategory = val;
      this.clickSound();
    },
    onProductSelect(product) {
      this.selectedProduct = product;
      this.orderItem.id = product.id;
      this.orderItem.name = product.name;
      this.orderItem.price = parseFloat(product.price).toFixed(2);
      this.orderItem.description = product.description;
      this.orderItem.categoryId = product.categoryId;

      this.clickSound();

      this.$nextTick(() => {
        this.$refs["order-modal"].show();
      });
    },
    removeOrder(index) {
      this.clickSound();

      let order = this.orderList[index];

      const index1 = this.orderList.indexOf(order);
      if (index1 > -1) {
        this.orderList.splice(index1, 1);
      }
    },
    onOptionSelect(opt, listId) {
      this.clickSound();
      const prevOpt = this.orderItem.options.find((o) => o.id == opt.id);

      const addData = () =>
        this.orderItem.options.push({
          id: opt.id,
          name: opt.name,
          value: opt.list.find((l) => l.id == listId),
        });
      const removeData = () =>
        (this.orderItem.options = this.orderItem.options.filter(
          (o) => o.id != opt.id
        ));

      if (prevOpt) {
        removeData();
        addData();
      } else {
        addData();
      }
    },
    onAdditionSelect(value) {
      this.clickSound();
      this.orderItem.additions = value.map((val) =>
        this.selectedProduct.additions.find((a) => a.id == val)
      );
    },
    addToOrderList() {
      this.clickSound();
      const option_price = this.orderItem.options.reduce(
        (acc, opt) => parseFloat(acc) + parseFloat(opt.value.price),
        0
      );
      const addition_price = this.orderItem.additions.reduce(
        (acc, ad) => parseFloat(acc) + parseFloat(ad.price),
        0
      );

      this.orderItem.total = parseFloat(
        (parseFloat(this.orderItem.price) +
          parseFloat(option_price) +
          parseFloat(addition_price)) *
          this.orderItem.quantity
      ).toFixed(2);

      this.orderList.push({ ...this.orderItem, option_price, addition_price });

      this.offers.forEach((offer) => {
        let offerConditions = { offerId: offer.id, condition_true: 0 };

        offer.conditions.forEach((condition) => {
          const today = new Date().getDay();
          switch (condition.type) {
            case "DAY":
              if (condition.value == today) {
                offerConditions.condition_true++;
              }
              break;
            case "MINIMUM_ORDER_VALUE":
              if (this.countSubtotal > condition.value) {
                offerConditions.condition_true++;
              }
              break;
            case "CATEGORY_ID":
              if (this.selectedProduct.categoryId == condition.value) {
                offerConditions.condition_true++;
              }
              break;
          }
        });

        this.activeOffers.push(offerConditions);
      });

      // compare activeOffers with offer conditions
      this.activeOffers.forEach((offer) => {
        // get the offer object by id
        const offerObj = this.offers.find((o) => o.id == offer.offerId);
        if (offer.condition_true == offerObj.conditions.length) {
          this.activeOffersIds.push(offerObj.id);
          if (offerObj.type == "DISCOUNT") {
            this.discountPercent = offerObj.discountValue;
          } else if (offerObj.type == "FREE_PRODUCT") {
            // check if product already in orderList
            let isInOrderList = Boolean(
              this.orderList.find((p) => p.id == offerObj.freeProductId)
            );

            if (!isInOrderList) {
              // get product from id of free product
              const product = this.products.find(
                (p) => p.id == offerObj.freeProductId
              );

              // copy the prouct
              let freeProduct = Object.assign({}, product);
              // make free product structure same as orderItem
              freeProduct.quantity = 1;
              freeProduct.options = [];
              freeProduct.additions = [];
              freeProduct.total = 0;
              freeProduct.productsTotal = 0;
              freeProduct.price = 0;

              this.orderList.push({
                ...freeProduct,
                option_price: 0,
                addition_price: 0,
              });

              freeProduct = null;
            }
          }
        }
      });

      // reset order item
      this.orderItem = {
        id: "",
        name: "",
        price: "",
        description: "",
        categoryId: "",

        additions: [],
        options: [],

        option_price: "",
        addition_price: 0,
        quantity: 1,
        total: "",
        note: "",
      };

      this.activeOffers = [];
      try {
        this.$refs["order-modal"].hide() || this.$refs["hold-modal"].hide();
      } catch (error) {
        console.error(error);
      }
    },
    getOrderImageUrl(id) {
      return this.products.find((p) => p.id == id).image.url;
    },

    setMethod(method) {
      this.orderTemplate.paymentMethod = method;
      this.clickSound();
    },

    showCheckout() {
      this.clickSound();
      this.isTakeAway = null;
      this.$refs["confirm-modal"].show();
    },

    async makeSale() {
      this.clickSound();
      this.processing = true;

      this.orderTemplate.userType = this.userType;
      this.orderTemplate.items = this.orderList;

      if (this.isTakeAway && !this.branch) {
        if (this.locations.length == 1) {
          this.branch = this.locations[0].id;
        } else {
          this.$toast.error("Please select branch");
          this.processing = false;
          return false;
        }
      }

      if (!this.isTakeAway && !this.deliveryArea) {
        this.$toast.error("Please select delivery area");
        this.processing = false;
        return false;
      }

      if (!this.isTakeAway && this.street == "N/A") {
        this.$toast.error("Please enter a valid address");
        this.processing = false;
        return false;
      }

      if (
        this.userType == "new" &&
        !this.orderTemplate.address &&
        !this.orderTemplate.address == "N/A"
      ) {
        this.$toast.error("Please enter a valid address");
        this.processing = false;
        return false;
      }

      if (
        this.userType == "new" &&
        !this.orderTemplate.phone &&
        !this.orderTemplate.phone == "N/A"
      ) {
        this.$toast.error("Please enter a valid phone number");
        this.processing = false;
        return false;
      }

      if (
        this.userType == "new" &&
        !this.orderTemplate.email &&
        !this.orderTemplate.email == "N/A"
      ) {
        this.$toast.error("Please enter a valid email");
        this.processing = false;
        return false;
      }

      if (this.userType == false) {
        this.orderTemplate.email = "N/A";
        this.orderTemplate.phone = "N/A";
        this.orderTemplate.address = "N/A";
        this.orderTemplate.name = "Walk in Customer";
        this.orderTemplate.customerId = null;
      }

      // only unique ids
      this.orderTemplate.activeOffersIds = [...new Set(this.activeOffersIds)];

      if (this.isTakeAway) {
        let branch = this.locations.filter((l) => l.id == this.branch)[0];
        this.orderTemplate.deliveryArea.city =
          branch.name +
          ", " +
          branch.address.city +
          ", " +
          branch.address.contry;
      }

      await this.axios
        .post("/checkout/order?pos=true", { object: this.orderTemplate })
        .then((response) => {
          this.clickSound();

          this.lastOrderId = response.data.id;

          let date = new Date().toISOString().replace("T", " ").split(".")[0];

          let order = {
            content: this.orderTemplate,
            id: response.data.id,
            datetime: date,
          };

          window.localStorage.setItem("__last_order", JSON.stringify(order));

          this.$refs["confirm-modal"].hide();

          this.orderList = [];
          this.activeOffers = [];

          this.orderTemplate = {
            orderType: "PICKUP",
            deliveryArea: {
              zipCode: "",
              city: "",
              deliveryCost: "",
            },
            other: {
              note: "",
              cost: 0,
            },
            flatDiscount: 0,
            tax: 0,
            customerId: null,
            deliveryTime: "Instant",
            paymentMethod: "Cash",
            name: "Walk in Customer",
            street: "N/A",
            phone: "N/A",
            email: "N/A",
            accessCode: "",
            city: "N/A",
            activeOffersIds: [],
            discountCoupon: null,
            stripe: null,
            currency: "EURO",
            items: [],
            productsTotal: 0,
            total: 0,
            status: "delivered",
            orderFrom: "pos",
          };

          this.processing = false;
        })
        .catch(() => {
          this.processing = false;
          this.$toast.error("Something went wrong");
        })
        .finally(() => {
          this.saleSound();
          window.print_receipt();
          setTimeout(() => {
            this.$toast.success("Order placed successfully");
          }, 500);
        });
    },

    onUserSelect(data) {
      if (data) {
        this.orderTemplate.customerId = data.id;
        this.orderTemplate.name = data.full_name;
        this.orderTemplate.email = data.email;
        this.orderTemplate.phone = data.phone;
        this.orderTemplate.street = data.street;
      } else {
        this.orderTemplate.customerId = null;
        this.orderTemplate.name = "";
        this.orderTemplate.email = "";
        this.orderTemplate.phone = "";
        this.orderTemplate.street = "";
      }
    },

    onSearch(search, loading) {
      if (search.length) {
        loading(true);
        this.searchHandle(loading, search, this);
      }
    },
    searchHandle: _.debounce((loading, search, vm) => {
      fetch(`${window.jara.API}/users/search/${escape(search)}`, {
        method: "GET",
        withCredentials: true,
        credentials: "include",
        headers: {
          Authorization: window.jara.getCookie("token"),
          "X-User": window.jara.getCookie("userid"),
        },
      }).then((res) => {
        let data = res.json();
        data.then((json) => (vm.options = json));
        loading(false);
      });
    }, 350),
  },

  async created() {
    try {
      // Fetch Categories
      const resCategories = await this.axios.get("/menu/categories");
      this.categories = resCategories.data;

      // Fetch Offers
      const resOffers = await this.axios.get("/menu/offers");
      this.offers = resOffers.data;

      // Fetch Products
      const resProducts = await this.axios.get("/menu/items");
      this.products = resProducts.data;
      this.displayProducts = resProducts.data;

      // Fetch Locations
      const resLocations = await this.axios.get("/locations");
      this.locations = resLocations.data;
    } catch (error) {
      console.error(error);
    } finally {
      this.loading = false;
    }
  },
  watch: {
    selectedCategory: {
      immediate: true,
      handler: function (val) {
        if (val) {
          this.displayProducts = this.products.filter(
            (product) => product.categoryId == val
          );
        }
      },
    },
    search: function (val) {
      if (val != "") {
        this.displayProducts = this.products.filter((product) =>
          product.name.toLowerCase().includes(val.toLowerCase())
        );
      } else {
        this.displayProducts = this.products;
        this.selectedCategory = "";
      }
    },
    countSubtotal: function (val) {
      this.orderTemplate.productsTotal = val;
      if (val == 0.0) {
        this.orderList = [];
      }
    },
    received_amount: function (val) {
      if (val == 0 || val == "" || val == null || val < 0) {
        this.received_amount = 0;
      }
    },
    countGrandTotal: function (val) {
      this.orderTemplate.total = val;
    },
    discount: function (val) {
      this.orderTemplate.discountAmount = val;
    },
    countTax: function (val) {
      this.orderTemplate.tax = val;
    },
    activeOffers: function (val) {
      this.activeOffersIds = val.map((offer) => offer.id);
    },
    orderList: function (val) {
      this.orderTemplate.items = val;
    },
    deliveryArea: function (val) {
      if (!this.isTakeAway) {
        this.orderTemplate.deliveryArea = this.deliveryAreas.filter(
          (d) => d.zipCode == val
        )[0];
        this.deliveryCharge = this.orderTemplate.deliveryArea.deliveryCost;
      } else {
        this.deliveryCharge = 0;
      }
    },
    isTakeAway: function (val) {
      if (val) {
        this.orderTemplate.deliveryArea = {
          zipCode: "",
          city: "",
          deliveryCost: "",
        };
        this.deliveryCharge = 0;
      } else {
        this.orderTemplate.deliveryArea = this.deliveryAreas.filter(
          (d) => d.zipCode == this.deliveryArea
        )[0];
        this.deliveryCharge = this.orderTemplate.deliveryArea
          ? this.orderTemplate.deliveryArea.deliveryCost
          : 0;
      }
    },
  },

  validations: {
    fake: {},
    orderTemplate: {
      name: { required },
      phone: {},
      address: {},
      email: {},
      flatDiscount: {},
      other: {
        note: {},
        cost: {},
      },
    },
  },
};
</script>

<style lang="scss" scoped>
@import "./pos.scss";
</style>
