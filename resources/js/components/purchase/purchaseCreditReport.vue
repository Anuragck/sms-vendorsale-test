<template>
  <!-- DataTable with Hover -->
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="col-lg-12" v-else>
    <div class="card mb-4">
      <div
        class="
          card-header
          py-3
          d-flex
          flex-row
          align-items-center
          justify-content-between
        "
      >
        <h5 class="m-0 font-weight-bold text-dark">Purchase Credits Report</h5>
      </div>

      <div class="table-responsive p-3">
        <table
          class="table table-sm align-items-center table-flush table-hover"
          id="purchaseCreditTable"
          ref="purchaseCreditTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Vendor Name</th>
              <th>Transactions</th>
              <th>Credit Amount</th>

              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(pcredit, index) in purchase_credits_list"
              :key="pcredit.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ pcredit.vendor_name }}</td>
              <td>{{ pcredit.transactions_count }}</td>
              <td>{{ pcredit.amount }}</td>

              <td class="pl-4 text-center" style="white-space: nowrap">
                <button
                  class="btn btn-outline-primary btn-sm"
                  data-toggle="modal"
                  data-target="#viewPurchasePaymentTransactions"
                  @click="viewTransactions(pcredit)"
                >
                  <i class="fa fa-eye fa-fw" title="view"></i>
                </button>

                <button
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#addPurhasepayment"
                  @click="puchasePayment(pcredit)"
                >
                  <i class="fas fa-hand-holding-usd" title="Add Payment"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- view purchase payment Transactions modal  -->
    <div
      class="modal fade"
      id="viewPurchasePaymentTransactions"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <purchase-payment-transactions></purchase-payment-transactions>
        </div>
      </div>
    </div>
    <!-- view purchase payment Transactions modal -->

    <!-- add  purchase payment  modal  -->
    <div
      class="modal fade"
      id="addPurhasepayment"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-purchase-credit-payment></add-purchase-credit-payment>
        </div>
      </div>
    </div>
    <!-- add purchase payment   -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      preLoader: false,
      purchase_credits_list: {},
    };
  },

  created() {
    this.getPurchaseCredit();
    var _this = this;
    bus.$on("purchase-payment-added", function () {
      _this.getPurchaseCredit();
    });
  },
  methods: {
    getPurchaseCredit() {
      this.preLoader = true;
      axios
        .get("get-purchase-credits")
        .then((response) => {
          this.purchase_credits_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    viewTransactions(pcredit) {
      bus.$emit("purchase-credit-transactions", pcredit);
    },

    puchasePayment(pcredit) {
      bus.$emit("add-purchase-credit-payment", pcredit);
    },
  },

  watch: {
    purchase_credits_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.purchaseCreditTable).DataTable({
          pageLength: 5,
          dom: "lBfrtip",
          buttons: [
            {
              extend: "copy",
              title: "Purchase Credit Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
            {
              extend: "excel",
              title: "Purchase Credit Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
            {
              extend: "pdf",
              title: "Purchase Credit Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
            {
              extend: "print",
              title: "Purchase Credit Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
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
    this.dt = $(this.$refs.purchaseCreditTable).DataTable({
      pageLength: 5,

      dom: "lBfrtip",
      buttons: [
        {
          extend: "copy",
          title: "Purchase Credit Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
        {
          extend: "excel",
          title: "Purchase Credit Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
        {
          extend: "pdf",
          title: "Purchase Credit Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
        {
          extend: "print",
          title: "Purchase Credit Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
      ],

      lengthMenu: [
        [5,10, 25, 50, -1],
        [5,10, 25, 50, "All"],
      ],
    });
  },
};
</script>

<style scoped>
</style>
