<template>
  <div>
    <!-- modal -->
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom">Jobcard Payments</h5>
      <button
        type="button"
        class="close"
        ref="jobcardPayment"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <div class="modal-body" ref="paymentSection">
      <!-- payment card -->
      <div class="card">
        <div class="card-header">{{ title }}</div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Customer Name</label>
                <h5>{{ customer_name }}</h5>
                <small class="text-danger"> </small>
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <label>Total Amount</label>
                <h5>{{ total_amount }}</h5>
                <small class="text-danger"> </small>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Received Amount</label>
                <h5>{{ received_amount }}</h5>
                <small class="text-danger"> </small>
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <label>Balance Amount</label>
                <h5>
                  {{ parseFloat(total_amount - received_amount).toFixed(2) }}
                </h5>
                <small class="text-danger"> </small>
              </div>
            </div>

            <!-- <div class="col">
              <div class="form-group">
                <label>Jobcard No</label>
                <h5> {{ payment.jobcard_id}} </h5>
                <small class="text-danger"> </small>
              </div>
            </div> -->
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label class="required">Payment Type</label>
                <select
                  class="form-control"
                  name="pay_type"
                  v-model="payment.pay_type"
                >
                  <option value="">Select Payment Type</option>

                  <option value="Cash">Cash</option>
                  <option value="Bank">Bank</option>
                </select>

                <small class="text-danger" v-if="errors.pay_type">
                  {{ errors.pay_type[0] }}</small
                >
              </div>
            </div>
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
                <small class="text-danger" v-if="errors.amount">
                  {{ errors.amount[0] }}</small
                >
              </div>
            </div>
          </div>

          <div class="row justify-content-end mr-2">
            <button
              type="button"
              @click="clear_form_data()"
              class="btn btn-secondary rounded-pill px-5 mr-1"
              :disabled="loading"
              v-if="edit_payment"
            >
              cancel
              <i
                class="fas fa-spinner fa-spin fa-fw"
                v-if="loading == true"
              ></i>
            </button>

            <button
              type="button"
              @click="addPayment()"
              class="btn btn-primary rounded-pill px-5"
              id="add-payment-button"
              :disabled="loading"
            >
              {{ button_title }}
              <i
                class="fas fa-spinner fa-spin fa-fw"
                v-if="loading == true"
              ></i>
            </button>
          </div>
        </div>
      </div>
      <!-- payment card end-->

      <!-- payment History -->
      <div class="card mt-3">
        <div class="card-header">Payment History</div>
        <div class="card-body">
          <div class="table-responsive">
            <table
              class="table table-striped table-sm"
              ref="jobcardPaymentsTable"
            >
              <thead>
                <tr>
                  <th scope="col">#</th>

                  <th scope="col">Payment Date</th>
                  <th scope="col">Type</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(jPayments, index) in jobcard_payments"
                  :key="jPayments.id"
                >
                  <td>{{ index + 1 }}</td>

                  <td>{{ jPayments.payment_date }}</td>
                  <td>{{ jPayments.pay_type }}</td>
                  <td>{{ jPayments.amount }}</td>

                  <td class="pl-3">
                    <button
                      class="btn btn-primary btn-xs"
                      @click="editPayment(jPayments)"
                    >
                      <i class="fas fa-edit" title="Edit"></i>
                    </button>

                    <button
                      class="btn btn-success btn-xs"
                      @click.prevent="clickPdf(jPayments)"
                      :disabled="printLoading"
                    >
                      <i class="fas fa-file-invoice" title="Receipt"></i>
                    </button>

                    <button
                      class="btn btn-danger btn-xs ml-1"
                      @click="deletePayment(jPayments)"
                    >
                      <i class="fas fa-trash fafw" title="Move to trash"></i>
                    </button>
                  </td>

                  <!--**********************JOBCARD PAYMENT RECEIPT AREA START ***********************------->
                  <div class="div" style="display: none">
                    <div id="j_payment_receipt_pdf">
                      <h6 class="text-center pb-2">JOBCARD PAYMENT RECEIPT</h6>
                      <div class="p-3">
                        <div class="container pt-3">
                          <div class="row">
                            <div class="col">
                              <b>{{ companyData.name }}</b> <br />
                              {{ companyData.address_1 }}<br />
                              {{ companyData.address_2 }}<br />
                              {{ companyData.district }} -
                              {{ companyData.pincode }} {{ companyData.state
                              }}<br />
                              Ph: {{ companyData.mobile_1 }} /
                              {{ companyData.mobile_2 }}<br />
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
                                  <h6>Customer Name : {{ customer_name }}</h6>
                                  <h6>
                                    Jobcard No :
                                    {{ jPaymentsReceipt.jobcard_id }}
                                  </h6>
                                </div>

                                <div class="col-auto ml-auto">
                                  <h6>Reciept No: {{ jPaymentsReceipt.id }}</h6>
                                  <h6>
                                    Date :
                                    {{ jPaymentsReceipt.payment_date | myDate }}
                                  </h6>
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
                                        <i
                                          class="fas fa-rupee-sign"
                                          area-hidden="true"
                                        ></i>
                                        {{ jPaymentsReceipt.amount }}
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="col-md-9">Payment Type</td>
                                      <td class="col-md-3">
                                        {{ jPaymentsReceipt.pay_type }}
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <div>
                                <div class="col-md-12">
                                  <p class="float-right pt-3">
                                    <b>Signature</b>
                                  </p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--**********************JOBCARD PAYMENT RECEIPT AREA END ***********************------->
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- table card ends -->
      </div>

      <!-- payment History ends-->

      <!-- modal body end -->
    </div>
    <div class="modal-footer"></div>
    <!-- modal end-->
  </div>
</template>

<script>
import html2pdf from "html2pdf.js";
import { get } from "jquery";
export default {
  data() {
    return {
      button_title: "pay",
      title: "Add New Payment",
      toastTitle: "Payment Successfully Added",
      loading: false,
      customer_name: "",
      total_amount: "",
      received_amount: "",
      jobcard_payments: {},
      edit_payment: false,
      payment: {
        id: "",
        jobcard_id: "",
        customer_id: "",

        pay_type: "",
        amount: "",
      },
      company_id: 1,
      companyData: {},
      jPaymentsReceipt: {},
      printLoading: false,
      errors: {},
    };
  },
  created() {
    console.log(this.companyData);
    this.getCompany();
    var _this = this;
    bus.$on("add-jobcard-payment", function (jobcard) {
      _this.payment.jobcard_id = jobcard.id;

      _this.payment.customer_id = jobcard.customer_id;
      _this.customer_name = jobcard.customer.name;
      _this.total_amount = jobcard.pay_amount;
      _this.received_amount = jobcard.received_amount;
      _this.getJobcardPayments();
    });
  },
  methods: {
    addPayment() {
      this.loading = true;
      axios
        .post("jobcard_payments/add", this.payment)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            bus.$emit("jobcard-payment-added");
            this.clear_form_data();
            this.$refs.jobcardPayment.click();
            this.getJobcardPayments();
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });
            bus.$emit("jobcard-payment-added");
            this.clear_form_data();
            this.$refs.jobcardPayment.click();
            this.getJobcardPayments();
          }

          if (response.data == "error") {
            Toast.fire({
              icon: "error",
              title: "Check balance Status !",
            });
          }

          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;

          this.loading = false;
        });
    },

    getCompany() {
      axios
        .post("get-company", { company_id: this.company_id })
        .then((response) => {
          this.companyData = response.data;
        })
        .catch((err) => {});
    },

    clickPdf(jPayments) {
      this.jPaymentsReceipt = jPayments;
      var _this = this;
      _this.printLoading = true;
      var element = document.getElementById("j_payment_receipt_pdf");

      var opt = {
        margin: 1,
        // filename: "Receipt_" + this.receipt_num,
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

    getJobcardPayments() {
      axios
        .post("jobcard_payments/get", {
          jobcard_id: this.payment.jobcard_id,
        })
        .then((response) => {
          this.jobcard_payments = response.data;
        })
        .catch((err) => {});
    },

    deletePayment(jPayments) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Delete!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/jobcard_payments/delete", {
              id: jPayments.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                bus.$emit("jobcard-payment-added");
                this.clear_form_data();
                this.$refs.jobcardPayment.click();
                this.getJobcardPayments();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                bus.$emit("jobcard-payment-added");
                this.clear_form_data();
                this.$refs.jobcardPayment.click();
                this.getJobcardPayments();
              }
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Request failed ",
              });
            });
        }
      });
    },

    editPayment(jPayments) {
      this.$refs.paymentSection.scrollIntoView();
      this.button_title = "update";
      this.title = "Edit Payment";
      this.toastTitle = "Payment Updated Successfully";
      this.edit_payment = true;
      this.payment.id = jPayments.id;
      this.payment.pay_type = jPayments.pay_type;
      this.payment.amount = jPayments.amount;
    },

    clear_form_data() {
      this.payment.pay_type = "";
      this.payment.amount = "";
      this.payment.id = "";
      this.edit_payment = false;
      this.button_title = "pay";
      this.title = "Add New Payment";
      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },

  watch: {
    jobcard_payments(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.jobcardPaymentsTable).DataTable({
          pageLength: 10,
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
          ],
          bSort: false,
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.jobcardPaymentsTable).DataTable({
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
      bSort: false,
    });
  },
};
</script>

<style scoped>
.btn-group-xs > .btn,
.btn-xs {
  padding: 0.25rem 0.4rem;
  font-size: 0.875rem;
  line-height: 0.5;
  border-radius: 0.2rem;
}

.table-font {
  font-size: 14px;
}

#j_payment_receipt_pdf {
  color: rgb(20, 19, 19);
}
#j_payment_receipt_pdf table {
  color: rgb(20, 19, 19);
}
</style>
