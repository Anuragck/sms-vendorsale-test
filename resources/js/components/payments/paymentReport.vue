<template>
  <!-- DataTable with Hover -->
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="col-lg-12" v-else>
    <div class="card mb-4">
      <div
        class="
          card-header
          pt-4
          d-flex
          flex-row
          align-items-center
          justify-content-between
        "
      >


        <h5 class="m-0 font-weight-bold text-dark"  style="display:inline-block;">Receipts Report</h5>
 <button class="btn rounded btn-sm shadow-sm  ml-3" @click="creditSalePayments()" :class="credit_sale_payments ? 'btn-primary' : ''" >
                        Credit Receipts
                        <i class="fas fa-file-invoice-dollar fa-fw"></i>
                    </button>

  <button v-if="user.jobcard" class="btn rounded btn-sm shadow-sm ml-1"  @click="jobCardPayments()" :class="jobcard_payments ? 'btn-primary' : ''" >
                        Jobcard Receipts <i class="fas fa-wallet fa-fw"></i>
                    </button>

        <!--FilterButton-->

        <div class="col-md-2 ml-auto">
          <button
            class="btn btn-primary btn-sm float-right rounded-pill"
            @click="viewFilters()"
            v-if="search == false"
          >
            Add Filters<i class="fas fa-filter fa-fw"></i>
          </button>
          <button
            class="btn btn-outline-warning btn-sm float-right rounded-pill"
            @click="hideFilter()"
            v-if="search"
          >
            Clear Filter <i class="far fa-times-circle fa-fw"></i>
          </button>
        </div>
        <!--FilterButton end-->
      </div>
      <div class="" v-if="search">
        <div class="row justify-content-center">
          <div class="" style="">
            <div class="input-group">
              <span class="input-group-text">From</span>
              <input type="Date" class="form-control " v-model="from_date" />
              <span
                class="input-group-text ml-4 px-3"
                style="border-left: 0; border-right: 0"
              >
                To
              </span>
              <input type="Date" class="form-control " v-model="to_date" />
              <button class="btn btn-sm btn-primary ml-4" @click="addFilters()">
                Search <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>
      </div>



      <!-------------------------->

      <div class="table-responsive p-3">
        <table
          class="table table-sm align-items-center table-flush table-hover"
          id="paymentTable"
          ref="paymentTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Name</th>
            <th v-if="jobcard_payments == true">Jobcard No</th>
              <th>Payment Date</th>
              <th>Payment Type</th>
              <th>Payment Amount</th>
              <th v-if="credit_sale_payments == true">Payment Remarks</th>

              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody v-if="credit_sale_payments == true">
            <tr v-for="(payment, index) in payments_list" :key="payment.id">
              <td>{{ index + 1 }}</td>
              <td>{{ payment.customer["name"] }}</td>
              <td>{{ payment.payment_date | myDate }}</td>
              <td>{{ payment.pay_type }}</td>
              <td>{{ payment.amount }}</td>
              <td>{{ payment.remarks }}</td>

              <td class="pl-4 text-center" style="white-space: nowrap">
                <button
                  v-if="role_admin == 1"
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#editPayments"
                  @click="editPayment(payment)"
                >
                  <i class="fas fa-edit fafw" title="Edit"></i>
                </button>

                <button
                  v-if="role_admin == 1"
                  class="btn btn-danger btn-sm"
                  @click="deletePayment(payment)"
                >
                  <i class="fas fa-trash fafw" title="Move to trash"></i>
                </button>

                <button
                  class="btn btn-success btn-sm"
                  data-toggle="modal"
                  data-target="#paymentReceipt"
                  @click="receiptDownload(payment)"
                >
                  <i class="fas fa-file-invoice" title="Bill"></i>
                </button>
              </td>
            </tr>
          </tbody>

 <tbody v-if="jobcard_payments == true">
            <tr v-for="(jPayment, index) in payments_list" :key="jPayment.id">
              <td>{{ index + 1 }}</td>
              <td>{{ jPayment.jobcard.customer.name }}</td>
 <td>{{ jPayment.jobcard.id }}</td>
              <td>{{ jPayment.payment_date | myDate }}</td>
              <td>{{ jPayment.pay_type }}</td>
              <td>{{ jPayment.amount }}</td>

              <td class="pl-4 text-center" style="white-space: nowrap">

                <button
                  class="btn btn-success btn-sm"
                  data-toggle="modal"
                  data-target="#jobcardPaymentReceipt"
                  @click="jobcardReceiptDownload(jPayment)"
                >
                  <i class="fas fa-file-invoice" title="Bill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- payment invoice modal  -->
    <div
      class="modal fade"
      id="paymentReceipt"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <payment-receipt></payment-receipt>
        </div>
      </div>
    </div>
    <!-- invoice modal -->

 <!-- Jobcard payment receipt modal  -->
    <div
      class="modal fade"
      id="jobcardPaymentReceipt"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <payment-receipt :jobcard_payment="true"></payment-receipt>
        </div>
      </div>
    </div>
    <!-- Jobcard payment Receipt modal -->




    <!-- editPayments modal  -->
    <div
      class="modal fade"
      id="editPayments"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <add-payment :edit="true"></add-payment>
        </div>
      </div>
    </div>
    <!-- editPayments modal -->
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      role_admin: 0,
      payments_list: {},
      from_date: "",
      to_date: "",
      search: false,

credit_sale_payments: true,
      jobcard_payments: false,
    };
  },

  created() {
    this.role_admin = this.user.role;
    this.getPayment();
    var _this = this;
    bus.$on("payment-added", function () {
      _this.getPayment();
    });
  },
  methods: {
    getPayment() {
    var url='';
        if(this.credit_sale_payments == true){
        url='get-payments';
        }else if(this.jobcard_payments == true){
        url='jobcard_payments/all';
        }
      this.preLoader = true;
      axios
        .get(url, {
          params: {
            from_date: this.from_date,
            to_date: this.to_date,
          },
        })
        .then((response) => {
          this.payments_list = response.data;
          this.preLoader = false;


        })
        .catch((err) => {
          this.preLoader = false;

          console.log(err);
        });
    },

    editPayment(payment) {
      bus.$emit("edit-payment", payment);
    },

    hideFilter() {
      this.getPayment();
      this.search = false;
      this.from_date = "";
      this.to_date = "";
    },

    viewFilters() {
      this.search = true;
    },
    addFilters() {
      this.getPayment();
      this.from_date = "";
      this.to_date = "";
    },

    receiptDownload(payment) {
      bus.$emit("payment-receipt", payment);
    },
 jobcardReceiptDownload(jPayment) {
      bus.$emit("jobcard-payment-receipt", jPayment);
    },

    deletePayment(payment) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#5085d6",
        cancelButtonColor: "#d5",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/delete-payment", {
              id: payment.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getPayment();
              }
              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getPayment();
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

creditSalePayments(){
this.credit_sale_payments=true;
this.jobcard_payments=false;

this.from_date="";
this.to_date="";
this.search=false;

this.getPayment();
},

jobCardPayments(){
this.credit_sale_payments=false;
this.jobcard_payments=true;

this.from_date="";
this.to_date="";
this.search=false;

this.getPayment();
}
  },

  watch: {
    payments_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.paymentTable).DataTable({
          pageLength: 5,

          dom: "lBfrtip",

          buttons: [
            {
              extend: "copy",
              title: "Payment Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
            {
              extend: "excel",
              title: "Payment Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
            {
              extend: "pdf",
              title: "Payment Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
            {
              extend: "print",
              title: "Payment Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
          ],
          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.paymentTable).DataTable({
      pageLength: 5,

      dom: "lBfrtip",

      buttons: [
        {
          extend: "copy",
          title: "Payment Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
        {
          extend: "excel",
          title: "Payment Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
        {
          extend: "pdf",
          title: "Payment Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
        {
          extend: "print",
          title: "Payment Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
      ],
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>

<style scoped>
</style>
