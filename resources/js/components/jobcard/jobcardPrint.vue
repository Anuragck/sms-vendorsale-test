<template>
    <div>
        <div class="modal-header bg-primary">
            <h5 class="modal-title modal-title-custom" id="jobcardInvoiceLabel">
                {{ "Jobcard" }}
            </h5>
            <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
            >
                <span aria-hidden="true" class="modal-title-custom"
                    >&times;</span
                >
            </button>
        </div>
        <div class="modal-body">
            <div class="text-right">
                <button
                    @click.prevent="clickPdf()"
                    class="btn btn-dark rounded-pill btn-sm"
                    :disabled="printLoading"
                >
                    {{ print_btn_title }}
                    <i
                        class="fas fa-print fa-fw"
                        v-if="printLoading == false"
                    ></i
                    ><i
                        class="fas fa-spinner fa-spin fa-fw"
                        v-if="printLoading == true"
                    ></i>
                </button>
            </div>

            <!---GST INVOICE START-->

            <div class="container" v-if="gst_bill == 1">
                <div id="canvas_div_pdf">
                    <div class="text-center">
                        <h6>Jobcard (G S T)</h6>
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
                                <td colspan="9" style="width: 40%">
                                    <b>{{ companyDetails.name }}</b> <br />
                                    {{ companyDetails.address_1 }}<br />
                                    {{ companyDetails.address_2 }}<br />
                                    {{ companyDetails.district }} <br />
                                    {{ companyDetails.state }}<br />

                                    Ph: {{ companyDetails.mobile_1 }} /
                                    {{ companyDetails.mobile_2 }}<br />
                                </td>
                                <td colspan="5" style="width: 25%">
                                    Jobcard No. :
                                    <b>{{ jobcard_no }}</b
                                    ><br />
                                    Vehicle :
                                    <span
                                        ><b>{{ vehicle }}</b></span
                                    ><br />
                                    Chasis No :
                                    <span
                                        ><b>{{ chasis_no }}</b></span
                                    ><br />
                                    Reg No :
                                    <span
                                        ><b>{{ register_no }}</b></span
                                    ><br />
                                    Odometer Reading :
                                    <span
                                        ><b>{{ odometer }}</b></span
                                    ><br />

                                    <br />
                                </td>
                                <td colspan="4">
                                    Dated:
                                    <b>{{ created_date }}</b>

                                    <br />
                                    <span v-if="expected_date != null">
                                        Expected Delivery Date :
                                        <b>{{
                                            expected_date | myDate
                                        }}</b></span
                                    >
                                    <br />
                                    <span v-if="completed_date != null"
                                        >Completed Date :
                                        <b>{{
                                            completed_date | myDate
                                        }}</b></span
                                    >

                                    <br />
                                    <span v-if="delivered_date != null">
                                        Delivery Date :
                                        <b>{{
                                            delivered_date | myDate
                                        }}</b></span
                                    >

                                    <br />
                                    <br />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="9">
                                    Customer Details: <br />
                                    <b>{{ jobcardCustomer }}</b> <br />
                                    {{ jobcardCustomer.mobile }} <br />
                                    <span v-if="jobcardCustomer.gst_no !== null"
                                        >GSTIN/UIN :{{
                                            jobcardCustomer.gst_no
                                        }}</span
                                    >

                                    <br />
                                </td>
                                <td colspan="9">
                                    <b>Customer Voice:</b> <br />
                                    <div class="text-justify">
                                        {{ customer_voice }}
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="9">
                                    Job Advisor Details: <br />
                                    <b>{{ jobcardAdvisor.name }}</b> <br />
                                    {{ jobcardAdvisor.mobile }} <br />
                                    <br />
                                </td>
                                <td colspan="9">
                                    <b>Technician's Voice:</b> <br />
                                    <div class="text-justify">
                                        {{ technician_voice }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tr class="">
                            <td class="items-tr-h">SL No.</td>
                            <td colspan="12" class="items-tr-h">
                                Item Details
                            </td>

                            <td class="items-tr-h">QTY</td>
                            <td class="items-tr-h">Taxable Value</td>
                            <td class="items-tr-h">SGST (%)</td>
                            <td class="items-tr-h">SGST Amount</td>
                            <td class="items-tr-h">CGST (%)</td>
                            <td class="items-tr-h">CGST Amount</td>
                            <td class="items-tr-h">CESS (%)</td>
                            <td class="items-tr-h">CESS Amount</td>
                            <td class="items-tr-h">Discount</td>
                            <td class="items-tr-h">Total Tax Amount</td>
                            <td class="items-tr-h">Total Amount</td>
                        </tr>

                        <tr
                            v-for="(item, index) in jobcardItems"
                            :key="item.id"
                        >
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ index + 1 }}</b>
                            </td>
                            <td colspan="12" align="center" class="items-tr">
                                <b>{{ item.product.name }}</b>
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ item.quantity }}</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ item.taxable_amount }}</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ item.gst / 2 }}</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                {{ item.sgst }}
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                {{ item.gst / 2 }}
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                {{ item.cgst }}
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                0
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>0.00</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ item.discount }}</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{
                                    parseFloat(item.sgst) +
                                    parseFloat(item.cgst)
                                }}</b>
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ item.total_rate }}</b>
                            </td>
                        </tr>

                        <tr>
                            <td class="items-tr"></td>
                            <td colspan="5" class="items-tr"></td>

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
                            <td class="items-tr"></td>
                            <td class="items-tr"></td>
                        </tr>

                        <tr>
                            <td colspan="8" align="right" class="items-tr">
                                <b>Total Amount (Rs.)</b>
                            </td>
                            <td colspan="1" align="right" class="items-tr">
                                <b>{{ total_taxable_amount }}</b>
                            </td>
                            <td colspan="2" align="center" class="items-tr">
                                <b>{{ total_sgst }}</b>
                            </td>
                            <td colspan="2" align="center" class="items-tr">
                                <b>{{ total_cgst }}</b>
                            </td>
                            <td colspan="2" align="center" class="items-tr">
                                <b>{{ total_cess }}</b>
                            </td>
                            <td colspan="1" align="right" class="items-tr">
                                <b>{{ total_discount }}</b>
                            </td>
                            <td colspan="1" align="right" class="items-tr">
                                <b
                                    >{{
                                        parseFloat(total_cgst) +
                                        parseFloat(total_sgst)
                                    }}
                                </b>
                            </td>
                            <td colspan="1" align="right" class="items-tr">
                                <b>{{ pay_amount }}</b>
                            </td>
                        </tr>

                        <tr>
                            <td
                                colspan="12"
                                rowspan="7"
                                align="center"
                                class="align-middle items-tr"
                            >
                                <b
                                    >Grand Total:&nbsp;&nbsp;{{
                                        total_pay_amount_words
                                    }}</b
                                >
                            </td>
                            <td colspan="4" style="border: 0">
                                Total Taxable Amount
                            </td>
                            <td colspan="3" align="right" style="border: 0">
                                {{ total_taxable_amount }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="border: 0">Total SGST</td>
                            <td colspan="3" align="right" style="border: 0">
                                {{ total_sgst }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="border: 0">Total CGST</td>
                            <td colspan="3" align="right" style="border: 0">
                                {{ total_cgst }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="border: 0">Total Cess</td>
                            <td colspan="3" align="right" style="border: 0">
                                {{ total_cess }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" align="left" style="border: 0">
                                Total Amount
                            </td>
                            <td colspan="3" align="right" style="border: 0">
                                {{ total_amount }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="border: 0">
                                Discount Amount
                            </td>
                            <td colspan="3" align="right" style="border: 0">
                                {{ total_discount }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border-bottom-only">
                                <b>Net Amount</b>
                            </td>
                            <td
                                colspan="3"
                                align="right"
                                class="border-bottom-only"
                            >
                                <b>{{ pay_amount }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!----GST INVOICE END------>

            <div>
                <div class="container" v-if="gst_bill == 0">
                    <div id="canvas_div_pdf">
                        <div class="text-center">
                            <h6>Estimate Jobcard</h6>
                        </div>

                        <img
                            class="d-block img-fluid"
                            :src="'/images/' + companyDetails.logo"
                            alt="logo"
                            style="width: 80px"
                        />

                        <table
                            class="table table-bordered"
                            width="100%"
                            style="margin-bottom: 0"
                        >
                            <tbody>
                                <tr>
                                    <td colspan="9" style="width: 40%">
                                        <b>{{ companyDetails.name }}</b> <br />
                                        {{ companyDetails.address_1 }}<br />
                                        {{ companyDetails.address_2 }}<br />
                                        {{ companyDetails.district }} <br />
                                        {{ companyDetails.state }}<br />

                                        Ph: {{ companyDetails.mobile_1 }} /
                                        {{ companyDetails.mobile_2 }}<br />
                                    </td>
                                    <td colspan="3" style="width: 25%">
                                        Jobcard No. :
                                        <b>{{ jobcard_no }}</b
                                        ><br />
                                        Vehicle :
                                        <span
                                            ><b>{{ vehicle }}</b></span
                                        ><br />
                                        Chasis No :
                                        <span
                                            ><b>{{ chasis_no }}</b></span
                                        ><br />
                                        Reg No :
                                        <span
                                            ><b>{{ register_no }}</b></span
                                        ><br />
                                        Odometer Reading :
                                        <span
                                            ><b>{{ odometer }}</b></span
                                        ><br />

                                        <br />
                                    </td>
                                    <td colspan="5">
                                        Dated:
                                        <b>{{ created_date }}</b>

                                        <br />
                                        <span v-if="expected_date != null">
                                            Expected Delivery Date :
                                            <b>{{
                                                expected_date | myDate
                                            }}</b></span
                                        >
                                        <br />
                                        <span v-if="completed_date != null"
                                            >Completed Date :
                                            <b>{{
                                                completed_date | myDate
                                            }}</b></span
                                        >

                                        <br />
                                        <span v-if="delivered_date != null">
                                            Delivery Date :
                                            <b>{{
                                                delivered_date | myDate
                                            }}</b></span
                                        >

                                        <br />
                                        <br />
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="9">
                                        Customer Details: <br />
                                        <b>{{ jobcardCustomer.name }}</b> <br />
                                        {{ jobcardCustomer.mobile }} <br />
                                        <span
                                            v-if="
                                                jobcardCustomer.gst_no !== null
                                            "
                                            >GSTIN/UIN :{{
                                                jobcardCustomer.gst_no
                                            }}</span
                                        >

                                        <br />
                                    </td>
                                    <td colspan="9">
                                        <b>Customer Voice:</b> <br />
                                        <div class="text-justify">
                                            {{ customer_voice }}
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="9">
                                        Job Advisor Details: <br />
                                        <b>{{ jobcardAdvisor.name }}</b> <br />
                                        {{ jobcardAdvisor.mobile }} <br />
                                        <br />
                                    </td>
                                    <td colspan="9">
                                        <b>Technician's Voice:</b> <br />
                                        <div class="text-justify">
                                            {{ technician_voice }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table
                            class="table table-bordered"
                            width="100%"
                            style="margin: 0"
                        >
                            <tr>
                                <td class="items-tr-h" colspan="1">sl.no</td>

                                <td class="items-tr-h" colspan="3">
                                    Item Details
                                </td>

                                <td class="items-tr-h" colspan="1">Qty</td>
                                <td class="items-tr-h" colspan="1">Rate</td>
                                <td class="items-tr-h" colspan="1">
                                    Total amount
                                </td>
                            </tr>
                            <tr
                                v-for="(item, index) in jobcardItems"
                                :key="item.id"
                            >
                                <td class="items-tr" colspan="1">
                                    {{ index + 1 }}
                                </td>

                                <td colspan="3" align="center" class="items-tr">
                                    <b>{{ item.product.name }}</b>
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
                                <td colspan="5" align="right" class="items-ftr">
                                    <b>Total Amount (Rs.)</b>
                                </td>

                                <td colspan="2" align="right" class="items-ftr">
                                    <b>{{ total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td
                                    colspan="7"
                                    align="center"
                                    style="border: 0"
                                >
                                    <b
                                        >Grand Total:&nbsp;&nbsp;{{
                                            total_pay_amount_words
                                        }}</b
                                    >
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5" align="left" style="border: 0">
                                    Total Amount
                                </td>
                                <td colspan="2" align="right" style="border: 0">
                                    {{ total_amount }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5" style="border: 0">
                                    Discount Amount
                                </td>
                                <td colspan="2" align="right" style="border: 0">
                                    {{ total_discount }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style="border: 0">
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

import html2pdf from "html2pdf.js";
export default {
    data() {
        return {
            printLoading: false,

            companyDetails: {},
            company_id: 1,

            //jobcard
            gst_bill: "",
            jobcard_no: "",
            vehicle: "",
            chasis_no: "",
            register_no: "",
            odometer: "",

            created_date: "",
            expected_date: "",
            completed_date: "",
            delivered_date: "",

            jobcardCustomer: {},

            jobcardAdvisor: {},

            jobadvisor_id: "",

            customer_voice: "",
            technician_voice: "",

            jobcardItems: [],

            total_taxable_amount: "",
            total_sgst: "",
            total_cgst: "",
            total_cess: "",
            total_discount: "",
            pay_amount: "",

            total_amount: "",

            total_pay_amount_words: "",
        };
    },

    methods: {
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
                    _this.printLoading = false;
                });

            //  var result = html2pdf().from(element).set(opt).save();
        },

        getCompany() {
            axios
                .post("get-company", { company_id: this.company_id })
                .then((response) => {
                    this.companyDetails = response.data;
                })
                .catch((err) => {});
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
    },
    created() {
        this.getCompany();
        var _this = this;
        bus.$on("print-jobcard", function (jobcard) {
            _this.gst_bill = jobcard.gst_bill;
            _this.jobcard_no = jobcard.id;
            _this.vehicle = jobcard.vehicle;
            _this.chasis_no = jobcard.chasis_no;
            _this.register_no = jobcard.register_no;
            _this.odometer = jobcard.odometer;

            _this.created_date = jobcard.created_date;
            _this.expected_date = jobcard.expected_date;
            _this.completed_date = jobcard.completed_date;
            _this.delivered_date = jobcard.delivered_date;

            _this.jobcardCustomer = jobcard.customer;
            _this.jobcardAdvisor = jobcard.advisor;

            _this.jobadvisor_id = jobcard.jobadvisor_id;

            _this.customer_voice = jobcard.customer_voice;
            _this.technician_voice = jobcard.technician_voice;

            _this.jobcardItems = jobcard.items;

            _this.total_taxable_amount = jobcard.total_taxable_amount;
            _this.total_sgst = jobcard.total_sgst;
            _this.total_cgst = jobcard.total_cgst;
            _this.total_cess = jobcard.total_cess;
            _this.total_discount = jobcard.total_discount;
            _this.pay_amount = jobcard.pay_amount;
            _this.total_amount = jobcard.total_amount;

            const toWords = new ToWords({
                localeCode: "en-IN",
                converterOptions: {
                    currency: true,
                    ignoreDecimal: false,
                    ignoreZeroCurrency: false,
                    doNotAddOnly: false,
                },
            });
            _this.total_pay_amount_words = toWords.convert(jobcard.pay_amount);

            //   console.log(jobcard);
        });
    },
};
</script>
<style scoped>
td {
    font-size: 11px;
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
    font-size: 10px;
}

.items-tr {
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;
    font-weight: bold;
    font-size: 11px;
    text-align: center;
}

.items-ftr {
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;
    font-weight: bold;
    font-size: 11px;
}

.border-bottom-only {
    border-bottom: 1px solid black;
    border-right: 0px;
    border-left: 0px;
    border-top: 0px;
}

#thermal-bill {
    font-size: 5px;

    color: black;
}

#thermal-table td {
    color: black;
    font-size: 5px;
}
</style>
