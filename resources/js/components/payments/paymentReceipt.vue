<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="paymentReceiptLabel">
        {{ "Payment Receipt" }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        @click="clearData()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <!----Reciept area start*****************************-->
      <div class="float-right pb-4">
        <button
          @click.prevent="clickThermalReceipt()"
          class="btn btn-dark rounded-pill btn-sm"
          :disabled="printLoadingThermal"
        >
          {{ print_btn_title_thermal }}
          <i class="fas fa-print fa-fw" v-if="printLoadingThermal == false"></i
          ><i
            class="fas fa-spinner fa-spin fa-fw"
            v-if="printLoadingThermal == true"
          ></i>
        </button>

        <button
          @click.prevent="clickPdf()"
          class="btn btn-dark rounded-pill btn-sm"
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

      <!----------THERMALPAPERPRINT-START------------------------------->

      <!----------------THERMALPAPER PRINT-END------------------------->

      <!-------------******************THRMAL PAPER RECEIPT NEW START******************************-->
      <div class="container" style="display:none;">
      <div
        :id="
          jobcard_payment
            ? 'j_payment_receipt_thermal_paper'
            : 'payment_receipt_thermal_paper'
        "
      >
        <div id="j_thermal_print_new">
          <div class="ticket">
            <div class="row text-center">
              <div class="col">
                <b>{{ companyData.name }}</b> <br />
                {{ companyData.address_1 }}<br />
                {{ companyData.district }} - {{ companyData.pincode }} ,
                {{ companyData.state }}<br />

                Ph: {{ companyData.mobile_1 }} / {{ companyData.mobile_2
                }}<br />
                <span v-if="companyData.gst_no != ''"
                  >GST No :{{ companyData.gst_no }}<br
                /></span>
              
              </div>
            </div>

            <table class="table-center table table-sm pb-0 mb-0 text-center">
             <!-- <thead class="">
                <tr>
                  <th class="description">Description</th>

                  <th class="price">Amount</th>
                </tr>
              </thead>-->

              <tbody>
               <tr>
                  <td class="description">{{ 'Receipt No'  }}</td>

                  <td class="price">{{ receipt_num  }}</td>
                </tr>
                 <tr>
                  <td class="description">{{ 'Date'  }}</td>

                  <td class="price">{{ payment_date | myDate  }}</td>
                </tr>
                <tr v-if="jobcard_no">
                  <td class="description">{{ 'Jobcard No'  }}</td>

                  <td class="price">{{ jobcard_no  }}</td>
                </tr>
                 
                 <tr>
                  <td class="description">{{ 'Customer'  }}</td>

                  <td class="price">{{ customerDetails.name  }}</td>
                </tr>
                 <tr>
                  <td class="description">{{ 'Phone'  }}</td>

                  <td class="price">{{ customerDetails.mobile  }}</td>
                </tr>

                <tr>
                  <td class="description"><b>{{ 'Amount Paid'  }}</b></td>

                  <td class="price"><b>{{  paid_amount  }}</b></td>
                </tr>
                 <tr>
                  <td class="description">{{ 'Payment Type'  }}</td>

                  <td class="price">{{ pay_type   }}</td>
                </tr>
                
              </tbody>
            </table>

            <p class="centered mt-3">Thanks You!</p>
          </div>
        </div>
      </div>
      </div>

      <!-------------******************THRMAL PAPER RECEIPT NEW END******************************-->

      <div class="p-5">
        <div
          :id="
            jobcard_payment ? 'j_payment_receipt_pdf' : 'payment_receipt_pdf'
          "
        >
          <div class="container pt-3">
            <div class="row">
              <div class="col">
                <b>{{ companyData.name }}</b> <br />
                {{ companyData.address_1 }}<br />
                {{ companyData.address_2 }}<br />
                {{ companyData.district }} - {{ companyData.pincode }}
                {{ companyData.state }}<br />
                Ph: {{ companyData.mobile_1 }} / {{ companyData.mobile_2
                }}<br />
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

            <div class="row pt-2">
              <div class="col-md-12">
                <div class="row">
                  <!-- <div class="col-md-12 "> -->
                  <div class="col-auto">
                    <h6>Customer Name : {{ customerDetails.name }}</h6>
                    <h6>Customer Phone : {{ customerDetails.mobile }}</h6>
                    <h6 v-if="jobcard_no">
                      Jobcard No :
                      {{ jobcard_no }}
                    </h6>
                  </div>

                  <div class="col-auto ml-auto">
                    <h6>Reciept No: {{ receipt_num }}</h6>
                    <h6>Date : {{ payment_date | myDate }}</h6>
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
                    <tbody>
                      <tr>
                        <td class="col-md-9">Amount Paid</td>
                        <td class="col-md-3">
                          <i class="fas fa-rupee-sign" area-hidden="true"></i>
                          {{ paid_amount }}
                        </td>
                      </tr>
                      <tr>
                        <td class="col-md-9">Payment Type</td>
                        <td class="col-md-3">
                          {{ pay_type }}
                        </td>
                      </tr>
                      <tr v-if="remarks">
                        <td class="col-md-9">Payment Remarks</td>
                        <td class="col-md-3">
                          {{ remarks }}
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
      </div>

      <!-----reciept area end********************************-->
    </div>
  </div>
</template>

<script>
import html2pdf from "html2pdf.js";
export default {
  props: ["jobcard_payment"],
  data() {
    return {
      printLoading: false,
      printLoadingThermal: false,
      companyData: {},
      customerDetails: {},
      paid_amount: "",
      pay_type: "",
      receipt_num: "",
      payment_date: "",

      remarks: "",
      jobcard_no: "",
      company_id: 1,
    };
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

    clearData() {
      this.customerDetails = {};
      this.paid_amount = "";
      this.pay_type = "";
      this.receipt_num = "";
      this.payment_date = "";

      this.remarks = "";
      this.jobcard_no = "";
    },

    clickThermalReceipt() {
      var _this = this;
      _this.printLoadingThermal = true;

      if (_this.jobcard_payment == true) {
        var element = document.getElementById(
          "j_payment_receipt_thermal_paper"
        );
      } else {
        var element = document.getElementById("payment_receipt_thermal_paper");
      }

      var opt = {
        margin: 0.15,
        // filename: "Receipt_" + this.receipt_num,
        image: { type: "jpeg", quality: 6 },
        html2canvas: { scale: 4, logging: true },
        jsPDF: { unit: "in", format: [2.4, 20], orientation: "portrait" },
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

      if (_this.jobcard_payment == true) {
        var element = document.getElementById("j_payment_receipt_pdf");
      } else {
        var element = document.getElementById("payment_receipt_pdf");
      }
      var opt = {
        margin: 1,
        filename: "Receipt_" + this.receipt_num,
        image: { type: "jpeg", quality: 4 },
        html2canvas: { scale: 4, logging: true },
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
  },
  computed: {
    print_btn_title() {
      if (this.printLoading == true) {
        return "Processing  ";
      } else {
        return "Print";
      }
    },

    print_btn_title_thermal() {
      if (this.printLoadingThermal == true) {
        return "Processing  ";
      } else {
        return "Thermal Paper Print";
      }
    },
  },
  created() {
    this.getCompany();
    var _this = this;

    bus.$on("payment-receipt", function (payment) {
      _this.customerDetails = payment.customer;

      _this.paid_amount = payment.amount;
      _this.pay_type = payment.pay_type;
      _this.receipt_num = payment.id;
      _this.payment_date = payment.payment_date;

      _this.remarks = payment.remarks;
    });

    if (_this.jobcard_payment) {
      bus.$on("jobcard-payment-receipt", function (jPayment) {
        _this.customerDetails = jPayment.jobcard.customer;

        _this.paid_amount = jPayment.amount;
        _this.pay_type = jPayment.pay_type;
        _this.receipt_num = jPayment.id;
        _this.payment_date = jPayment.payment_date;
        _this.jobcard_no = jPayment.jobcard.id;
      });
    }
  },
};
</script>
<style scoped>

#payment_receipt_pdf *{
  color: rgb(20, 19, 19);
}

#j_payment_receipt_pdf *{
color: rgb(20, 19, 19);
}


/*********THERMAL NEW */
#j_thermal_print_new * {
  font-size: 12px;
  font-family: "Times New Roman";
  color: black;
}
#j_thermal_print_new .table-center {
  margin-left: auto;
  margin-right: auto;
}
#j_thermal_print_new td,
th,
tr,
table {
  border-collapse: collapse;
}
#j_thermal_print_new table.test {
  border-collapse: collapse;
}
#j_thermal_print_new td.description,
th.description {
  width: 50px;
  max-width: 50px;
}


#j_thermal_print_new td.price,
th.price {
  width: 70px;
  max-width: 70px;
  word-break: break-all;
}

#j_thermal_print_new .centered {
  text-align: center;
  align-content: center;
}

#j_thermal_print_new .right {
  text-align: right;
  align-content: right;
}

#j_thermal_print_new .ticket {
  width: 200px;
  max-width: 200px;
}

/*****THERMAL PAPER NEW END***** */
</style>

