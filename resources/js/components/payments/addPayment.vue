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
        ref="close_payment_modal"
        @click="clear_form_data()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div id="paymentForm">
      <div class="modal-body">
        <!-- payment detals  -->
        <!--  <div class="row p-3">
        <div class="col">
          <h2 class="">{{ customer_name }}</h2>
          <div class="text-gray-light mb-4" v-if="customer_credit != ''">
            AMOUNT: {{ customer_credit }}/-
          </div>
        </div>
      </div>-->

        <div class="row">
          <div
            class="alert information-card-warning ml-auto w-100 pl-5"
            role="alert"
          >
            <div class="float-left pl-4">
              <p class="">Customer Name</p>
              <h4 class="font-weight-bold text-center">{{ customer_name }}</h4>
            </div>

            <div class="float-right pr-5" v-if="customer_credit != ''">
              <p class="">Credit Amount</p>
              <h4 class="font-weight-bold text-center">
                {{ customer_credit }}/-
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
                  id="payment_date"
                  name="payment_date"
                  v-model="payment.payment_date"
                />
              </div>

              <small class="text-danger" v-if="errors.payment_date">
                {{ errors.payment_date[0] }}</small
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
                  id="payment_amount"
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
                  id="paytype"
                  v-model="payment.paytype"
                  name="paytype"
                >
                  <option value="">Select payment Type</option>

                  <option value="Cash">Cash</option>
                  <option value="Bank">Bank</option>
                </select>
              </div>

              <small class="text-danger" v-if="errors.paytype">
                {{ errors.paytype[0] }}</small
              >
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="remarks">Payment Remarks</label>
                <input
                  type="text"
                  class="form-control"
                  id="remarks"
                  name="remarks"
                  v-model="payment.remarks"
                />
              </div>

              <small class="text-danger" v-if="errors.remarks">
                {{ errors.remarks[0] }}</small
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--pay-fee id div end-->

    <!----Reciept area start*****************************-->
    <div class="p-5" style="display: none" id="pdfGenerate">
      <!----------THERMALPAPERPRINT-START------------------------------->
      <div class="thermal-paper-receipt" style="display: none">
        <div id="payment_receipt_thermal_paper">
          <!-----*********************************-->

          <div class="row">
            <div class="col">
              <b>{{ companyData.name }}</b> <br />
              {{ companyData.address_1 }}<br />
              {{ companyData.address_2 }}<br />
              {{ companyData.district }} - {{ companyData.pincode }}<br />
              {{ companyData.state }}<br />

              Ph: {{ companyData.mobile_1 }} / {{ companyData.mobile_2 }}<br />
            </div>

            <div class="col">
              <div class="float-right">
                <div class="col-auto">
                  <h6>Customer Name : {{ customer_name }}</h6>
                </div>

                <div class="col-auto ml-auto">
                  <h6>Reciept No: {{ forRecieptData.id }}</h6>
                  <h6>Date : {{ forRecieptData.paid_date | myDate }}</h6>
                </div>
              </div>
            </div>
          </div>
          <!--row end-->

          <table class="table table-sm">
            <thead>
              <tr>
                <th>Description</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="col-md-9">Amount Paid</td>
                <td class="col-md-3">
                  <i class="fas fa-rupee-sign" area-hidden="true"></i>
                  {{ forRecieptData.amount }}
                </td>
              </tr>
              <tr>
                <td class="col-md-9">Payment Type</td>
                <td class="col-md-3">
                  {{ forRecieptData.pay_type }}
                </td>
              </tr>

              <tr v-if="forRecieptData.remarks">
                <td class="col-md-9">Payment Remarks</td>
                <td class="col-md-3">
                  {{ forRecieptData.remarks }}
                </td>
              </tr>
            </tbody>
          </table>

          <div class="text-center">
            Thank You
            <!--Padding is optional-->
          </div>

          <!-------------------------------*********************************************-->
        </div>
      </div>
      <!----------------THERMALPAPER PRINT-END------------------------->
      <div id="canvas_div_pdf" class="mt-4">
        <div class="container" style="color: black">
          <div class="row">
            <div class="col">
              <b>{{ companyData.name }}</b> <br />
              {{ companyData.address_1 }}<br />
              {{ companyData.address_2 }}<br />
              {{ companyData.district }} - {{ companyData.pincode }}
              {{ companyData.state }}<br />
              Ph: {{ companyData.mobile_1 }} / {{ companyData.mobile_2 }}<br />
            </div>

            <div class="col">
              <img
                class="d-block img-fluid ml-auto"
                :src="'/images/' + companyData.logo"
                alt="logo"
                style="width: 150px"
              />
            </div>
          </div>
          <hr />
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <!-- <div class="col-md-12 "> -->
                <div class="col-auto">
                  <h6>Customer Name : {{ customer_name }}</h6>
                </div>

                <div class="col-auto ml-auto">
                  <h6>Reciept No: {{ forRecieptData.id }}</h6>
                  <h6>Date : {{ forRecieptData.paid_date | myDate }}</h6>
                </div>
                <!-- </div> -->
              </div>
              <br />
              <div>
                <table class="table">
                  <thead>
                    <tr>
                      <th>
                        <h6>Description</h6>
                      </th>
                      <th>
                        <h6>Amount</h6>
                      </th>
                    </tr>
                  </thead>
                  <tbody style="color: black">
                    <!--<tr>
                        <td class="col-md-9">Credit Amount To Pay </td>
                        <td class="col-md-3">
                          <i class="fas fa-rupee-sign" area-hidden="true"></i>
                          {{ customer_credit  }}
                        </td>
                      </tr>-->
                    <tr>
                      <td class="col-md-9">Amount Paid</td>
                      <td class="col-md-3">
                        <i class="fas fa-rupee-sign" area-hidden="true"></i>
                        {{ forRecieptData.amount }}
                      </td>
                    </tr>
                    <tr>
                      <td class="col-md-9">Payment Type</td>
                      <td class="col-md-3">
                        {{ forRecieptData.pay_type }}
                      </td>
                    </tr>

                    <tr v-if="forRecieptData.remarks">
                      <td class="col-md-9">Payment Remarks</td>
                      <td class="col-md-3">
                        {{ forRecieptData.remarks }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div>
                <div class="col-md-12">
                  <p class="float-right pt-3"><b>Signature</b></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row d-flex justify-content-center">
        <button
          @click.prevent="clickThermalReceipt()"
          class="btn btn-dark rounded-pill btn-sm"
          :disabled="printLoadingThermal"
        >
          {{ print_btn_title_thermal_pay }}
          <i class="fas fa-print fa-fw" v-if="printLoadingThermal == false"></i
          ><i
            class="fas fa-spinner fa-spin fa-fw"
            v-if="printLoadingThermal == true"
          ></i>
        </button>
        <button
          @click.prevent="clickPdf()"
          class="btn btn-dark rounded-pill btn-sm ml-2"
          :disabled="printLoading"
        >
          {{ print_btn_title }}
          <i class="fas fa-print fa-fw" v-if="printLoading == false"></i
          ><i
            class="fas fa-spinner fa-spin fa-fw"
            v-if="printLoading == true"
          ></i>
        </button>
      </div>
    </div>

    <!-----reciept area end********************************-->
    <div class="modal-footer">
      <button
        @click.prevent="clear_reciept()"
        class="btn btn-dark rounded-pill"
        id="clear-and-close-button"
        style="display: none"
      >
        {{ "Close" }} <i class="fas fa-times fa-fw"></i>
      </button>
      <button
        type="button"
        @click="addPayment()"
        class="btn btn-primary rounded-pill"
        id="add-payment-button"
        :disabled="loading"
      >
        {{ button_title }}
        <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
      </button>
    </div>
  </div>
</template>
<script>
import html2pdf from "html2pdf.js";
import moment from "moment";
export default {
  components: {},
  props: ["edit"],
  data() {
    return {
      printLoadingThermal: false,
      printLoading: false,
      loading: false,
      company_id: 1,
      companyData: {},
      title: "Add Payment",
      toastTitle: "Payment added successfully",
      customer_name: "",
      customer_credit: "",
      payment: {
        id: "",
        customer_id: "",
        payment_date: moment().format("YYYY-MM-DD"),
        amount: "",
        paytype: "",
        remarks: "",
      },
      forRecieptData: {},

      errors: [],
    };
  },

  created() {
    this.getCompany();
    var _this = this;
    bus.$on("add-payment", function (customer) {
      _this.clear_form_data();
      _this.payment.customer_id = customer.customer_id;
      _this.customer_name = customer.customer_name;
      _this.customer_credit = customer.amount;
    });

    if (this.edit) {
      var _this = this;

      _this.title = "Edit Payment";
      _this.toastTitle = "Payment updated successfully";
      bus.$on("edit-payment", function (payment) {
        _this.clear_form_data();
        _this.payment.id = payment.id;
        _this.payment.customer_id = payment.customer["id"];
        _this.customer_name = payment.customer["name"];

        _this.payment.amount = payment.amount;
        _this.payment.paytype = payment.pay_type;
        _this.payment.payment_date = payment.payment_date;
        _this.payment.remarks = payment.remarks;
      });
    }
  },

  computed: {
    button_title() {
      if (this.loading == true) {
        return "Saving  ";
      } else {
        return "Save Changes";
      }
    },
    print_btn_title_thermal_pay() {
      if (this.printLoadingThermal == true) {
        return "Processing  ";
      } else {
        return "Thermal Paper Print";
      }
    },

    print_btn_title() {
      if (this.printLoading == true) {
        return "Processing  ";
      } else {
        return "Print Receipt";
      }
    },
  },

  methods: {
    getCompany() {
      axios
        .post("get-company", { company_id: this.company_id })
        .then((response) => {
          this.companyData = response.data;
        })
        .catch((err) => {});
    },
    clear_reciept() {
      //   document.getElementById("paymentForm").style.display = "block";
      //   document.getElementById("pdfGenerate").style.display = "none";
      document.getElementById("add-payment-button").style.display = "block";
      document.getElementById("clear-and-close-button").style.display = "none";
      this.$refs.close_payment_modal.click();
    },

    clickThermalReceipt() {
      var _this = this;
      _this.printLoadingThermal = true;
      var element = document.getElementById("payment_receipt_thermal_paper");

      var opt = {
        margin: 0.15,
        // filename: "Receipt_" + this.receipt_num,
        image: { type: "jpeg", quality: 4 },
        html2canvas: { scale: 4, logging: true },
        jsPDF: { unit: "in", format: [3, 10], orientation: "portrait" },
      };

      var result = html2pdf()
        .set(opt)
        .from(element)
        .toPdf()
        .get("pdf")
        .then(function (pdf) {
          window.open(pdf.output("bloburl"), "_blank");
          _this.printLoadingThermal = false;
        });
    },

    clickPdf() {
      var _this = this;
      _this.printLoading = true;
      var element = document.getElementById("canvas_div_pdf");

      var opt = {
        margin: 1,
        filename: "reciept.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
      };

      var result = html2pdf()
        .set(opt)
        .from(element)
        .toPdf()
        .get("pdf")
        .then(function (pdf) {
          window.open(pdf.output("bloburl"), "_blank");
          _this.printLoading = false;
        });
    },

    addPayment() {
      if (this.payment.amount > this.customer_credit) {
        Toast.fire({
          icon: "error",
          title: "Amount Exceeded !",
        });
        return false;
      }
      this.loading = true;
      axios
        .post("/admin/add-payment", this.payment)
        .then((response) => {
          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_payment_modal.click();
            this.clear_form_data();
            bus.$emit("payment-added");
            this.loading = false;
          }

          if (response.data.id) {
            this.forRecieptData = response.data;

            document.getElementById("pdfGenerate").style.display = "block";
            document.getElementById("paymentForm").style.display = "none";

            document.getElementById("add-payment-button").style.display =
              "none";
            document.getElementById("clear-and-close-button").style.display =
              "block";

            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_payment_modal.click();
            // this.clear_form_data();
            bus.$emit("payment-added");
            this.loading = false;
          }
          //   if (response.data == "success") {
          // Toast.fire({
          //   icon: "success",
          //   title: this.toastTitle,
          // });

          // this.$refs.close_payment_modal.click();
          // this.clear_form_data();
          // bus.$emit("payment-added");
          //   }
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    clear_form_data() {
      document.getElementById("paymentForm").style.display = "block";
      document.getElementById("pdfGenerate").style.display = "none";

      document.getElementById("add-payment-button").style.display = "block";
      document.getElementById("clear-and-close-button").style.display = "none";

      for (let data in this.payment) {
        this.payment[data] = "";
      }
      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },
};
</script>
<style scoped>
#payment_receipt_thermal_paper {
  color: rgb(20, 19, 19);
  font-size: 5px;
}
#payment_receipt_thermal_paper table {
  color: rgb(20, 19, 19);
  font-size: 5px;
}

#payment_receipt_thermal_paper h6 {
  color: black;
  font-size: 5px;
}
</style>
