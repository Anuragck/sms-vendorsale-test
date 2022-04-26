<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addcustomerLabel">
        Transaction History
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <pre-loader v-if="preLoader"></pre-loader>
      <div class="container" v-else>
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
        <!-- customer detals  -->

        <!-- customer detals ends  -->
        <div class="row">
          <div class="col-3 d-flex justify-content-start"></div>
          <div class="col-6 d-flex justify-content-center">
            <div class="mb-0" v-if="dateCustom">
              <div class="">
                <div class="" style="">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span
                        class="input-group-text customDateFltr"
                        id="inputGroup-sizing-sm"
                        >From</span
                      >
                    </div>
                    <input
                      type="Date"
                      class="form-control"
                      v-model="from_date"
                    />
                    <div class="input-group-prepend ml-3">
                      <span
                        class="input-group-text customDateFltr"
                        id="inputGroup-sizing-sm"
                        >To</span
                      >
                    </div>
                    <input type="Date" class="form-control" v-model="to_date" />
                    <button
                      class="btn btn-xs btn-primary ml-4"
                      @click="addFilters()"
                    >
                      Search <i class="fas fa-search fa-fw"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-3 d-flex justify-content-end">
            <button
              class="btn btn-xs btn-secondary px-2 mr-2 ml-auto"
              @click="viewFilters()"
              v-if="dateCustom == false"
            >
              Add Filter <i class="fas fa-calendar-alt fa-fw"></i>
            </button>
            <button
              class="btn btn-xs btn-warning px-3 mr-2 ml-auto"
              @click="hideFilter()"
              v-if="dateCustom"
            >
              Clear <i class="far fa-times-circle fa-fw"></i>
            </button>
          </div>
        </div>

        <div class="table-responsive pt-1 px-3 pb-0">
          <table
            class="table table-sm align-items-center table-flush table-hover"
            id="purchasePaytransactionTable"
            ref="purchasePaytransactionTable"
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Payment ID</th>
                <th>Date</th>

                <th>Payment Type</th>
                <th>Amount</th>
                <th>Remarks</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(transaction, index) in p_payment_transactions_list"
                :key="transaction.id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ transaction.id }}</td>

                <td>{{ transaction.payment_date }}</td>

                <td>
                  {{ transaction.pay_type }}
                </td>

                <td>
                  {{ transaction.amount }}
                </td>

                <td>
                  {{ transaction.remarks }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="modal-footer"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      preLoader: false,
      dateCustom: false,
      p_payment_transactions_list: [],
      from_date: "",
      to_date: "",
      vendor_id: "",
      vendor_name: "",
      credit_amount: "",
    };
  },

  created() {
    var _this = this;
    bus.$on("purchase-credit-transactions", function (pcredit) {
      _this.vendor_id = pcredit.vendor_id;
      _this.vendor_name = pcredit.vendor_name;
      _this.credit_amount = pcredit.amount;

      _this.getPurchasePaymentTran();
    });
  },
  methods: {
    getPurchasePaymentTran() {
      this.preLoader = true;
      axios
        .post("get-purchase-payments", {
          vendor_id: this.vendor_id,
          from_date: this.from_date,
          to_date: this.to_date,
        })
        .then((response) => {
          this.p_payment_transactions_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    addFilters() {
      this.getPurchasePaymentTran();
      this.from_date = "";
      this.to_date = "";
    },

    viewFilters() {
      this.dateCustom = true;
    },
    hideFilter() {
      this.getPurchasePaymentTran();
      this.dateCustom = false;
      this.from_date = "";
      this.to_date = "";
    },
  },

  watch: {
    p_payment_transactions_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.purchasePaytransactionTable).DataTable({
          pageLength: 4,
          dom: "lBfrtip",

          buttons: [
            {
              extend: "excel",
              title: "Credit Purchase Payment History - " + this.vendor_name,
              messageTop: "Balance Credit Amount : " + this.credit_amount,
            },
            {
              extend: "pdf",
              title: "Credit Purchase Payment History - " + this.vendor_name,
              messageTop: "Balance Credit Amount : " + this.credit_amount,
            },
            {
              extend: "print",
              title: "Credit Purchase Payment History - " + this.vendor_name,
              messageTop: "Balance Credit Amount : " + this.credit_amount,
            },
          ],
          lengthMenu: [
            [4, 10, 25, 50, -1],
            [4, 10, 25, 50, "All"],
          ],
          bSort: false,
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.purchasePaytransactionTable).DataTable({
      pageLength: 4,
      dom: "lBfrtip",

      buttons: [
        {
          extend: "excel",
          title: "Credit Purchase Payment History - " + this.vendor_name,
          messageTop: "Balance Credit Amount : " + this.credit_amount,
        },
        {
          extend: "pdf",
          title: "Credit Purchase Payment History - " + this.vendor_name,
          messageTop: "Balance Credit Amount : " + this.credit_amount,
        },
        {
          extend: "print",
          title: "Credit Purchase Payment History - " + this.vendor_name,
          messageTop: "Balance Credit Amount : " + this.credit_amount,
        },
      ],
      lengthMenu: [
        [4, 10, 25, 50, -1],
        [4, 10, 25, 50, "All"],
      ],
      bSort: false,
    });
  },
};
</script>

<style scoped>
.btn-group-xs > .btn,
.btn-xs {
  padding: 0.3rem 0.5rem;
  font-size: 0.875rem;
  line-height: 0.5;
  border-radius: 0.2rem;
}
</style>

