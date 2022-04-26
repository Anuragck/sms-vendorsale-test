<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="salesinvoicelabel">
        {{ "Sale Invoice" }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_invoice_modal"
        @click="clearInvoice()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="text-right">
        <span>Share Invoice To: </span>
        <button
          class="btn btn-sm gmail-button rounded-pill"
          @click="sendForEmail()"
        >
          <i
            class="fas fa-envelope"
            title="Email"
            v-if="emailSending == false"
          ></i>

          <i class="fas fa-spinner fa-spin" v-if="emailSending == true"></i>
        </button>
        <button
          class="btn btn-sm whatsapp-button rounded-pill"
          @click="sendToWhatsapp()"
        >
          <i
            class="fab fa-whatsapp"
            title="Whatsapp"
            v-if="whatsappSending == false"
          ></i>
          <i class="fas fa-spinner fa-spin" v-if="whatsappSending == true"></i>
        </button>

        <!--thermal paper print button start-->
        <button
          @click.prevent="clickBluetoothPrinter()"
          class="btn btn-dark rounded-pill btn-sm"
          :disabled="thermalPrintLoading"
        >
          {{ thermal_print_btn_title }}
          <i class="fas fa-print fa-fw" v-if="thermalPrintLoading == false"></i
          ><i
            class="fas fa-spinner fa-spin fa-fw"
            v-if="thermalPrintLoading == true"
          ></i>
        </button>
        <!--thermal paper print button end-->
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

      <!---GST INVOICE START-->

      <!---***************************THERMAL-PAPPER-PRINT-NEW START 14-12-2021*********************************-------->
      <div class="container" style="display: none">
        <div id="canvas_div_pdf_bluetooth_new">
          <div id="thermal_print_new">
            <div class="ticket">
              <div class="row text-center">
                <div class="col">
                  <b>{{ companyDetails.name }}</b> <br />
                  {{ companyDetails.address_1 }}<br />
                  {{ companyDetails.district }} - {{ companyDetails.pincode }} ,
                  {{ companyDetails.state }}<br />

                  Ph: {{ companyDetails.mobile_1 }} /
                  {{ companyDetails.mobile_2 }}<br />
                  <span v-if="companyDetails.gst_no != '' && gst_bill == 1"
                    >GST No :{{ companyDetails.gst_no }}<br
                  /></span>
                  <span style="">Customer: {{ billCustomer.name }} </span
                  ><span class="ml-1"> Ph: {{ billCustomer.mobile }} </span
                  ><br />
                  <span v-if="billCustomer.gst_no != '' && gst_bill == 1"
                    >Customer GSTIN:{{ billCustomer.gst_no }}</span
                  ><br />

                  <span style="inline-block"
                    >Inv.No: {{ invoice_prefix }}/{{ invoice_no }}/{{
                      invoice_year
                    }}
                  </span>

                  <span class="ml-1"> Date: {{ invoice_date | myDate }} </span>
                </div>
              </div>

              <table class="table-center table table-sm pb-0 mb-0">
                <thead class="">
                  <tr>
                    <th class="description">Item</th>
                    <th class="quantity">Q.</th>
                    <th class="price">Rate</th>

                    <th class="price">Total</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in billItems" :key="item.id">
                    <td class="description">{{ item.item_name }}</td>

                    <td class="quantity">{{ parseInt(item.quantity) }}</td>

                    <td class="price">{{ item.rate }}</td>

                    <td class="price">{{ item.total_rate }}</td>
                  </tr>

                  <td class="description">Total</td>

                  <td class="quantity"></td>
                  <!-- <td  class="price"></td>-->

                  <td colspan="2" class="price text-right">
                    {{ total_amount }}
                  </td>
                </tbody>
              </table>

              <span class="float-right" v-if="gst_bill == 1"
                >Total GST :
                {{ parseFloat(total_cgst) + parseFloat(total_sgst) }}</span
              ><br />
              <span class="float-right"
                >Total Discount : {{ total_discount }}</span
              ><br />
              <span class="float-right">Round Off : {{ roundoff }}</span
              ><br />
              <span class="float-right">
                <b>Total Amount To Pay : {{ pay_amount }} </b></span
              ><br />
              <div class="text-center pt-3">
                <p>Thanks for your purchase!</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!---***************************THERMAL-PAPPER-PRINT-NEW END 14-14-2021************************************************-------->

      <div class="container" v-if="gst_bill == 1">
        <div id="canvas_div_pdf">
          <div class="text-center">
            <h6>GST Invoice</h6>
          </div>

          <img
            class="d-block img-fluid"
            :src="'/images/' + companyDetails.logo"
            alt="logo"
            style="width: 80px"
          />

          <!--{{ billItems }}-->
          <table
            class="table table-bordered mb-5"
            width="100%"
            style="margin-bottom: 0"
          >
            <tbody>
              <tr>
                <td colspan="8">
                  <b>{{ companyDetails.name }}</b> <br />
                  {{ companyDetails.address_1 }}<br />
                  {{ companyDetails.address_2 }}<br />
                  {{ companyDetails.district }} - {{ companyDetails.pincode
                  }}<br />
                  {{ companyDetails.state }}<br />

                  Ph: {{ companyDetails.mobile_1 }} /
                  {{ companyDetails.mobile_2 }}<br />

                  <span v-if="companyDetails.gst_no !== null"
                    >GSTIN/UIN :{{ companyDetails.gst_no }}</span
                  >
                </td>
                <td colspan="3" class="w-25">
                  Invoice No. :
                  <b>{{ invoice_prefix }}/{{ invoice_no }}/{{ invoice_year }}</b
                  ><br />
                  Bill Type. :
                  <span v-if="bill_type == 1"
                    ><b>{{ "Credit" }}</b></span
                  >
                  <span v-if="bill_type == 2"
                    ><b>{{ "Cash" }}</b></span
                  >

                  <br />
                  <br />
                </td>
                <td colspan="3">
                  Dated:
                  <b>{{ invoice_date | myDate }}</b>
                  <br />
                  <br />
                </td>
              </tr>

              <tr>
                <td colspan="14">
                  Customer Details: <br />
                  <b>{{ billCustomer.name }}</b> <br />
                  {{ billCustomer.mobile }} <br />

                  <span v-if="billCustomer.gst_no !== null"
                    >GSTIN/UIN :{{ billCustomer.gst_no }}</span
                  >

                  <br />
                </td>
              </tr>
            </tbody>

            <tr class="">
              <td class="items-tr-h">SL No.</td>
              <td colspan="5" class="items-tr-h" style="width: 90%">
                Item Details
              </td>
              <td class="items-tr-h">QTY</td>
              <td class="items-tr-h">Taxable Value</td>
              <td class="items-tr-h">SGST (%)</td>
              <td class="items-tr-h">SGST Amount</td>
              <td class="items-tr-h">CGST (%)</td>
              <td class="items-tr-h">CGST Amount</td>
              <!-- <td class="items-tr-h">CESS (%)</td>
              <td class="items-tr-h">CESS Amount</td> -->
              <td class="items-tr-h">Total Tax Amount</td>
              <td class="items-tr-h">Total Amount</td>
            </tr>

            <tr v-for="(item, index) in billItems" :key="item.id">
              <td colspan="1" align="center" class="items-tr">
                <b>{{ index + 1 }}</b>
              </td>
              <td colspan="5" align="" class="items-tr" style="width: 90%">
                <b> {{ item.item_name }}</b>
              </td>

              <td colspan="1" align="center" class="items-tr">
                {{ item.quantity }}
              </td>

              <td colspan="1" align="center" class="items-tr">
                {{ item.taxable_value }}
              </td>
              <td colspan="1" align="center" class="items-tr">
                {{ item.gst / 2 }}
              </td>
              <td colspan="1" align="center" class="items-tr">
                {{ item.sgst_amount }}
              </td>
              <td colspan="1" align="center" class="items-tr">
                {{ item.gst / 2 }}
              </td>
              <td colspan="1" align="center" class="items-tr">
                {{ item.cgst_amount }}
              </td>

              <!-- <td colspan="1" align="center" class="items-tr">0</td>

              <td colspan="1" align="center" class="items-tr">
                0.00
              </td> -->

              <td colspan="1" align="center" class="items-tr">
                {{
                  parseFloat(item.sgst_amount) + parseFloat(item.cgst_amount)
                }}
              </td>

              <td colspan="1" align="center" class="items-tr">
                {{ item.total_rate }}
              </td>
            </tr>

            <!-- <tr>
              <td class="items-tr"></td>
              <td colspan="3" class="items-tr"></td>

              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
              <td class="items-tr"></td>
            </tr> -->

            <tr>
              <td colspan="7" align="right" class="xtra-size-txt">
                <b>Total Amount (Rs.)</b>
              </td>
              <td colspan="1" align="right" class="xtra-size-txt">
                <b>{{ total_taxable_amount }}</b>
              </td>
              <td colspan="2" align="center" class="xtra-size-txt">
                <b>{{ total_sgst }}</b>
              </td>
              <td colspan="2" align="center" class="xtra-size-txt">
                <b>{{ total_cgst }}</b>
              </td>
              <!-- <td colspan="2" align="center" class="">
                <b>{{ total_cess }}</b>
              </td> -->
              <td colspan="1" align="right" class="xtra-size-txt">
                <b>{{ parseFloat(total_cgst) + parseFloat(total_sgst) }} </b>
              </td>
              <td colspan="1" align="right" class="xtra-size-txt">
                <b>{{ total_amount }}</b>
              </td>
            </tr>

            <tr>
              <td
                colspan="7"
                rowspan="5"
                align="center"
                class="align-middle items-tr"
              >
                <b>Grand Total:&nbsp;&nbsp;{{ total_pay_amount_words }}</b>
              </td>
              <!-- <td colspan="6" style="border: 0">Total Taxable Amount</td>
              <td colspan="3" align="right" style="border: 0">
                {{ total_taxable_amount }}
              </td> -->
            </tr>

            <!-- <tr>
              <td colspan="5" style="border: 0">Total SGST</td>
              <td colspan="2" align="right" style="border: 0">
                {{ total_sgst }}
              </td>
            </tr> -->

            <!-- <tr>
              <td colspan="5" style="border: 0">Total CGST</td>
              <td colspan="2" align="right" style="border: 0">
                {{ total_cgst }}
              </td>
            </tr> -->

            <!-- <tr>
              <td colspan="5" style="border: 0">Total Cess</td>
              <td colspan="2" align="right" style="border: 0">
                {{ total_cess }}
              </td>
            </tr> -->

            <!-- <tr>
              <td colspan="5" align="left" style="border: 0">Total Amount</td>
              <td colspan="2" align="right" style="border: 0">
                {{ total_amount }}
              </td>
            </tr>-->

            <tr>
              <td colspan="5" style="border: 0">Discount Amount</td>
              <td colspan="2" align="right" style="border: 0">
                {{ total_discount }}
              </td>
            </tr>
            <tr>
              <td colspan="5" style="border: 0">Round Off</td>
              <td colspan="2" align="right" style="border: 0">
                {{ roundoff }}
              </td>
            </tr>
            <tr>
              <td colspan="5" class="border-bottom-only">
                <b>Net Amount</b>
              </td>
              <td colspan="2" align="right" class="border-bottom-only">
                <b>{{ pay_amount }}</b>
              </td>
            </tr>

            <tr>
              <td colspan="10" class="items-tr text-center">
                <b>FOR {{ companyDetails.name }}</b>
              </td>
            </tr>
            <tr>
              <td colspan="7" class="items-tr"></td>
              <td colspan="7" class="items-tr text-left">
                <b>Signature:</b><br />
                <b>Name:</b>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <!----GST INVOICE END------>

      <div v-if="gst_bill == 0">
        <div class="container">
          <div id="canvas_div_pdf">
            <div class="text-center">
              <h6>Estimate Invoice</h6>
            </div>

            <img
              class="d-block img-fluid"
              :src="'/images/' + companyDetails.logo"
              alt="logo"
              style="width: 80px"
            />

            <table
              class="table table-bordered mb-5"
              width="100%"
              style="margin-bottom: 0"
            >
              <tbody>
                <tr>
                  <td colspan="11">
                    <b>{{ companyDetails.name }}</b> <br />
                    {{ companyDetails.address_1 }}<br />
                    {{ companyDetails.address_2 }}<br />
                    {{ companyDetails.district }} - {{ companyDetails.pincode
                    }}<br />
                    {{ companyDetails.state }}<br />

                    Ph: {{ companyDetails.mobile_1 }} /
                    {{ companyDetails.mobile_2 }}<br />
                  </td>
                  <td colspan="2">
                    Invoice No. :
                    <b
                      >{{ invoice_prefix }}/{{ invoice_no }}/{{
                        invoice_year
                      }}</b
                    ><br />
                    Bill Type. :
                    <span v-if="bill_type == 1"
                      ><b>{{ "Credit" }}</b></span
                    >
                    <span v-if="bill_type == 2"
                      ><b>{{ "Cash" }}</b></span
                    >

                    <br />
                    <br />
                  </td>
                  <td colspan="2">
                    Dated :
                    <b>{{ invoice_date | myDate }}</b>
                    <br />
                    <br />
                  </td>
                </tr>

                <tr>
                  <td colspan="14">
                    Customer Details: <br />
                    <b>{{ billCustomer.name }}</b> <br />
                    {{ billCustomer.mobile }} <br />
                  </td>
                </tr>
              </tbody>

              <tr>
                <td class="items-tr-h" colspan="1">sl.no</td>

                <td class="items-tr-h" colspan="10" style="width: 300px">
                  Item Details
                </td>
                <td class="items-tr-h" colspan="1">Qty</td>
                <td class="items-tr-h" colspan="1">Value</td>
                <td class="items-tr-h" colspan="1">total amount</td>
              </tr>

              <tr v-for="(item, index) in billItems" :key="item.id">
                <td class="items-tr" colspan="1">
                  {{ index + 1 }}
                </td>

                <td class="items-tr" colspan="10">
                  {{ item.item_name }}
                </td>
                <td class="items-tr" colspan="1">
                  {{ item.quantity }}
                </td>
                <td class="items-tr" colspan="1">
                  {{ item.rate }}
                </td>
                <td class="items-tr" colspan="1">
                  {{ item.total_rate }}
                </td>
              </tr>

              <tr>
                <td colspan="12" align="right" class="items-ftr">
                  <b>Total Amount (Rs.)</b>
                </td>

                <td colspan="2" align="right" class="items-ftr">
                  <b>{{ total_amount }}</b>
                </td>
              </tr>

              <tr>
                <td colspan="14" align="center" style="border: 0">
                  <b>Grand Total:&nbsp;&nbsp;{{ total_pay_amount_words }}</b>
                </td>
              </tr>

              <tr>
                <td colspan="12" align="left" style="border: 0">
                  Total Amount
                </td>
                <td colspan="2" align="right" style="border: 0">
                  {{ total_amount }}
                </td>
              </tr>

              <tr>
                <td colspan="12" style="border: 0">Discount Amount</td>
                <td colspan="2" align="right" style="border: 0">
                  {{ total_discount }}
                </td>
              </tr>
              <tr>
                <td colspan="12" style="border: 0">Round Off</td>
                <td colspan="2" align="right" style="border: 0">
                  {{ roundoff }}
                </td>
              </tr>
              <tr>
                <td colspan="12" style="border: 0">
                  <b>Net Amount</b>
                </td>
                <td colspan="2" align="right" style="border: 0">
                  <b>{{ pay_amount }}</b>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { ToWords } from "to-words";

import moment from "moment";
import html2pdf from "html2pdf.js";
export default {
  data() {
    return {
      printLoading: false,
      thermalPrintLoading: false,
      emailSending: false,
      whatsappSending: false,

      gst_bill: "",
      bill_type: "",
      invoice_date: "",
      invoice_year: "",
      invoice_prefix: "",
      invoice_no: "",
      email_status: "",
      pay_amount: "",
      total_amount: "",
      total_discount: "",
      total_pay_amount_words: "",

      roundoff: "",

      total_sgst: "",
      total_cgst: "",
      total_cess: "",

      billCustomer: [],

      billItems: [],

      productNames: [],

      productsDataBill: "",

      totalTaxCalculate: [],
      companyDetails: {},
      company_id: 1,
    };
  },

  methods: {
    sendToWhatsapp() {
      var invoice_url =
        "http://sms.test/get-invoice?id=" +
        this.invoice_no +
        "%26gst_bill=" +
        this.gst_bill;
      this.whatsappSending = true;
      window.open(
        "https://api.whatsapp.com/send/?phone=" +
          this.billCustomer.mobile +
          "&&text=Hello " +
          this.billCustomer.name +
          " Here is your invoice " +
          invoice_url +
          " ,Please go through the link to download , Thank you for your purchase . "
      );
      this.whatsappSending = false;
    },

    sendForEmail() {
      if (this.billCustomer.email == null) {
        Toast.fire({
          icon: "warning",
          title: "This customer does not have an email id.",
        });
        return false;
      }

      if (this.email_status == 1) {
        Swal.fire({
          title: "Already Shared?",
          text: "Do You want to share again ?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes",
        }).then((result) => {
          if (result.isConfirmed) {
            let invoice_data = {
              invoice_no: this.invoice_no,
              gst_bill: this.gst_bill,
              customer_name: this.billCustomer.name,
              customer_email: this.billCustomer.email,
            };
            this.emailSending = true;
            axios
              .post("send-invoice-mail", invoice_data)
              .then((response) => {
                if (response.data == "success") {
                  Toast.fire({
                    icon: "success",
                    title: "Invoice Successfully Sent",
                  });
                  this.$refs.close_invoice_modal.click();
                  bus.$emit("sale-added");
                }
                if (response.data == "failed") {
                  Toast.fire({
                    icon: "error",
                    title: "Some Error Occurred,Please Try Again Later",
                  });
                  this.$refs.close_invoice_modal.click();
                  bus.$emit("sale-added");
                }

                this.emailSending = false;
              })
              .catch((err) => {
                this.emailSending = false;
                this.errors = err.response.data.errors;
                this.loading = false;
              });
          }
        });
      }
      if (this.email_status == 0) {
        let invoice_data = {
          invoice_no: this.invoice_no,
          gst_bill: this.gst_bill,
          customer_name: this.billCustomer.name,
          customer_email: this.billCustomer.email,
        };
        this.emailSending = true;
        axios
          .post("send-invoice-mail", invoice_data)
          .then((response) => {
            if (response.data == "success") {
              Toast.fire({
                icon: "success",
                title: "Invoice Successfully Sent",
              });
              this.$refs.close_invoice_modal.click();
              bus.$emit("sale-added");
            }

            this.emailSending = false;
          })
          .catch((err) => {
            this.emailSending = false;
            this.errors = err.response.data.errors;
            this.loading = false;
          });
      }
    },
    clickBluetoothPrinter() {
      var _this = this;
      _this.thermalPrintLoading = true;
      var element = document.getElementById("canvas_div_pdf_bluetooth_new");

      var opt = {
        margin: 0.15,
        // filename: "Invoice_" + _this.invoice_no,
        image: { type: "jpeg", quality: 6 },
        html2canvas: { scale: 4, logging: true },
        jsPDF: { unit: "in", format: [2.4, 30], orientation: "portrait" },
      };

      //

      var result = html2pdf()
        .set(opt)
        .from(element)
        .toPdf()
        .get("pdf")
        .then(function (pdf) {
          //  pdf.autoPrint();
          window.open(pdf.output("bloburl"), "_blank");
          _this.thermalPrintLoading = false;
        });

      //  var result = html2pdf().from(element).set(opt).save();
    },

    // clickBluetoothPrinter() {
    //     var _this = this;
    //     _this.thermalPrintLoading = true;
    //     var element = document.getElementById("canvas_div_pdf_bluetooth_temp");

    //     var opt = {
    //         margin: 0.15,
    //         // filename: "Invoice_" + _this.invoice_no,
    //         image: { type: "jpeg", quality: 6 },
    //         html2canvas: { scale: 4, logging: true },
    //         jsPDF: { unit: "in", format: [2.5, 10], orientation: "portrait" },
    //     };

    //     //

    //     var result = html2pdf()
    //         .set(opt)
    //         .from(element)
    //         .toPdf()
    //         .get("pdf")
    //         .then(function (pdf) {
    //             //  pdf.autoPrint();
    //             window.open(pdf.output("bloburl"), "_blank");
    //             _this.thermalPrintLoading = false;
    //         });

    //     //  var result = html2pdf().from(element).set(opt).save();
    // },
    clickPdf() {
      var _this = this;
      _this.printLoading = true;
      var element = document.getElementById("canvas_div_pdf");

      var opt = {
        margin: 0.25,
        filename: "Invoice_" + _this.invoice_no,
        image: { type: "jpeg", quality: 4 },
        html2canvas: { scale: 4, logging: true },
        jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
        pagebreak: { avoid: "tr" },
      };

      var result = html2pdf()
        .set(opt)
        .from(element)
        .toPdf()
        .get("pdf")
        .then(function (pdf) {
          //  pdf.autoPrint();
          window.open(pdf.output("bloburl"), "_blank");
          _this.printLoading = false;
        });

      //  var result = html2pdf().from(element).set(opt).save();
    },
    //aaaaaaaaaaaaaaaaaaaaaaaa
    getProductsBill() {
      axios
        .get("get-products")
        .then((response) => {
          this.productsDataBill = response.data;
        })
        .catch((err) => {});
    },

    getCompany() {
      axios
        .post("get-company", { company_id: this.company_id })
        .then((response) => {
          this.companyDetails = response.data;
        })
        .catch((err) => {});
    },

    clearInvoice() {
      this.billItems = [];
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
    thermal_print_btn_title() {
      if (this.thermalPrintLoading == true) {
        return "Processing  ";
      } else {
        return "Thermal Paper Print";
      }
    },
  },
  created() {
    this.getCompany();
    this.getProductsBill();
    var _this = this;

    bus.$on("sale-invoice", function (sale) {
      _this.billCustomer = sale.vendor;
      // _this.billItems=sale.items;
      _this.gst_bill = sale.gst_bill;
      _this.bill_type = sale.bill_type;
      _this.invoice_date = sale.invoice_date;
      _this.invoice_no = sale.invoice_no;
      _this.invoice_year = sale.invoice_year;
      _this.invoice_prefix = sale.invoice_prefix;
      _this.pay_amount = sale.pay_amount;
      _this.total_amount = sale.total_amount;
      _this.total_discount = sale.total_discount;

      _this.roundoff = sale.roundoff;

      _this.email_status = sale.email_status;

      //gst-bill
      _this.total_taxable_amount = sale.total_taxable_amount;
      _this.total_cgst = sale.total_cgst;
      _this.total_sgst = sale.total_sgst;
      _this.total_cess = sale.total_cess;

      const toWords = new ToWords({
        localeCode: "en-IN",
        converterOptions: {
          currency: true,
          ignoreDecimal: false,
          ignoreZeroCurrency: false,
          doNotAddOnly: false,
        },
      });
      _this.total_pay_amount_words = toWords.convert(sale.pay_amount);

      var salesItem = sale.items;

      for (let item in salesItem) {
        var id = salesItem[item].product_id;
        var rate = salesItem[item].rate;
        var quantity = salesItem[item].quantity;
        var total_rate = salesItem[item].total_rate;

        //GST-BILL
        var taxable_value = salesItem[item].taxable_amount;
        var gst = salesItem[item].gst;
        var sgst_amount = salesItem[item].sgst;
        var cgst_amount = salesItem[item].cgst;
        var total_cess = salesItem[item].total_cess;

        var product_id;
        var item_name;
        var total_rate;

        for (let product in _this.productsDataBill) {
          if (_this.productsDataBill[product].id == id) {
            product_id = _this.productsDataBill[product].id;
            item_name = _this.productsDataBill[product].name;

            _this.billItems.push({
              item_name: item_name,
              product_id: product_id,
              quantity: quantity,
              rate: rate,
              total_rate: total_rate,

              //GST-BILL
              taxable_value: taxable_value,
              gst: gst,
              sgst_amount: sgst_amount,
              cgst_amount: cgst_amount,
              total_cess: total_cess,
            });
          }
        }
      }
    });
  },
};
</script>
<style scoped>
td {
  font-size: 12px;
}

.container {
  color: black;
}
table.table-bordered {
  color: black;
  border: 1px solid black;
  margin-top: 20px;
}
table.table-bordered > thead > tr > th {
  border: 1px solid black;
}
table.table-bordered > tbody > tr > td {
  border: 1px solid black;
}
td {
  padding: 5px;
}
th {
  padding: 5px;
}
table {
  background-color: transparent;
}
table {
  border-spacing: 0;
  border-collapse: collapse;
}

.items-tr-h {
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  font-size: 11px;
}

.items-tr {
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;

  font-size: 11px;
}

.xtra-size-txt {
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;

  font-size: 12px;
  text-align: center;
}

.items-ftr {
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  font-weight: bold;
  font-size: 12px;
}

.border-bottom-only {
  border-bottom: 1px solid black;
  border-right: 0px;
  border-left: 0px;
  border-top: 0px;
  font-size: 13px;
}

#thermal-bill {
  font-size: 5px;

  color: black;
}

#thermal-table td {
  color: black;
  font-size: 5px;
}

/**********NEW THERMAL PAPER  */
#thermal_print_new * {
  font-size: 12px;
  font-family: "Times New Roman";
  color: black;
}
#thermal_print_new .table-center {
  margin-left: auto;
  margin-right: auto;
}
#thermal_print_new td,
th,
tr,
table {
  border-collapse: collapse;
}
#thermal_print_new table.test {
  border-collapse: collapse;
}
#thermal_print_new td.description,
th.description {
  width: 75px;
  max-width: 75px;
}

#thermal_print_new td.quantity,
th.quantity {
  width: 40px;
  max-width: 40px;
  word-break: break-all;
}

#thermal_print_new td.price,
th.price {
  width: 50px;
  max-width: 50px;
  word-break: break-all;
}

#thermal_print_new .centered {
  text-align: center;
  align-content: center;
}

#thermal_print_new .right {
  text-align: right;
  align-content: right;
}

#thermal_print_new .ticket {
  width: 200px;
  max-width: 200px;
}

/********NEW THERMAL PAPER */

.hover-my-icon {
  transition: 0.3s cubic-bezier(0.6, 0.03, 0.28, 0.98);
  transform: scale(1);
}
.hover-my-icon:hover {
  transform: scale(1.3);
  transition: 0.3s cubic-bezier(0.6, 0.03, 0.28, 0.98);
}

.gmail-button {
  background-color: #ea4435b0;
  color: white;
  border: #ea4435b0;
}

.gmail-button:hover {
  background-color: #ea4435c5;
  color: white;
  border: #ea4435c5;
}

.whatsapp-button {
  background-color: #4fce5eb2;
  color: white;
  border-color: #4fce5eb2;
}
.whatsapp-button:hover {
  background-color: #4fce5ed8;
  color: white;
  border-color: #4fce5ed8;
}
</style>
