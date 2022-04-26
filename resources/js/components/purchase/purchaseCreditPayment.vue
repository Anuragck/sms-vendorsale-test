<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addpaymentLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_purchase_payment_modal"
        @click="clear_form_data()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div id="paymentForm">
      <div class="modal-body">
        <div class="row">
          <div
            class="alert information-card-warning ml-auto w-100 pl-5"
            role="alert"
          >
            <div class="float-left pl-4">
              <p class="">Vendor Name</p>
              <h4 class="font-weight-bold text-center">{{ vendor_name }}</h4>
            </div>

            <div class="float-right pr-5">
              <p class="">Credit Amount</p>
              <h4 class="font-weight-bold text-center">
                {{ credit_amount }}/-
              </h4>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="payment_date">Payment Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="purchase_payment_date"
                  name="payment_date"
                  v-model="payment.payment_date"
                />
              </div>

              <small class="text-danger"></small>
            </div>


 <div class="col">
              <div class="form-group">
                <label for="paytype" class="required">Payment For</label>
                <select
                  class="form-control"
                  id="purchase_paytype"
                  name="pay_type"
                  v-model="payment.payment_for"
                >
                  <option value="">Select</option>

                  <option value="1">Credit Sale</option>
                  <option value="2">Credit Purchase</option>
                </select>
              </div>

              <small class="text-danger" v-if="errors.payment_for">
                {{ errors.payment_for[0] }}</small
              >
            </div>



          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="payment_amount" class="required">Amount</label>
                <input
                  type="number"
                  class="form-control"
                  id="purchase_payment_amount"
                  placeholder="Enter Amount"
                  name="amount"
                  min="0"
                  oninput="validity.valid||(value='');"
                  v-model="payment.amount"
                />
              </div>

              <small class="text-danger" v-if="errors.amount">
                {{ errors.amount[0] }}</small
              >
            </div>
            <div class="col">
              <div class="form-group">
                <label for="paytype" class="required">Payment</label>
                <select
                  class="form-control"
                  id="purchase_paytype"
                  name="pay_type"
                  v-model="payment.pay_type"
                >
                  <option value="">Select payment Type</option>

                  <option value="Cash">Cash</option>
                  <option value="Bank">Bank</option>
                </select>
              </div>

              <small class="text-danger" v-if="errors.pay_type">
                {{ errors.pay_type[0] }}</small
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--pay-fee id div end-->
    <div class="modal-footer">
      <button
        type="button"
        class="btn btn-primary rounded-pill"
        id="add-purchase-payment-button"
        @click="addPurchasePayment()"
      >
        {{ button_title }}
        <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
      </button>
    </div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  components: {},
  data() {
    return {
      title: "Add Payment",
      toastTitle: "Payment added successfully",

      loading: false,

      vendor_name: "",
      credit_amount: "",

      payment: {
        vendor_id: "",
        id: "",
        payment_date: "",
        amount: "",
        pay_type: "",
        payment_for:"",
      },

      errors: [],
    };
  },

  created() {
    var _this = this;
    bus.$on("add-purchase-credit-payment", function (pcredit) {
      _this.payment.vendor_id = pcredit.vendor_id;
      _this.vendor_name = pcredit.vendor_name;
      _this.credit_amount = pcredit.amount;
    });
  },

  computed: {
    button_title() {
      if (this.loading == true) {
        return "Saving  ";
      } else {
        return "Save Changes";
      }
    },
  },

  methods: {
    addPurchasePayment() {
      this.loading = true;
      axios
        .post("add-purchase-payment", this.payment)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_purchase_payment_modal.click();
            this.clear_form_data();
            bus.$emit("purchase-payment-added");
            this.loading = false;
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_purchase_payment_modal.click();
            this.clear_form_data();
            bus.$emit("purchase-payment-added");
            this.loading = false;
          }
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    clear_form_data() {
      this.payment.vendor_id = "";
      this.payment.id = "";
      this.payment.pay_type = "";
      this.payment.amount = "";
      this.payment.payment_date = "";
this.payment.payment_for="";

      this.errors = {};
    },
  },
};
</script>
<style scoped>
</style>
