<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="main" v-else>
    <div class="col-lg-12">
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
          <h5 class="m-0 font-weight-bold text-dark">
            Purchase <small>(Today's Purchase Details)</small>
          </h5>

          <button
            type="button"
            class="btn btn-primary rounded-pill"
            data-toggle="modal"
            data-target="#addPurchase"
          >
            Add Purchase <i class="fas fa-plus-circle fa-fw"></i>
          </button>
        </div>
        <div class="table-responsive p-3">
          <table
            class="table align-items-center table-flush table-hover display"
            id="purchaseTable"
            ref="purchaseTable"
            data-order=""
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Invoice Number</th>
                <th>Purchase Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Index</th>
                <th>Invoice Number</th>
                <th>Purchase Date</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              <tr v-for="(purchase, index) in purchaseData" :key="purchase.id">
                <td>{{ index + 1 }}</td>
                <td>{{ purchase.purchase_invoice_no }}</td>
                <td>{{ purchase.purchase_date | myDate }}</td>

                <td class="">
                  <button
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#editPurchase"
                    @click="editPurchase(purchase)"
                  >
                    <i class="fas fa-edit fafw" title="Edit"></i>
                  </button>
                  <button
                    class="btn btn-danger btn-sm"
                    @click="deletePurchase(purchase)"
                  >
                    <i class="fas fa-trash fafw" title="Delete"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- add purchase modal -->

      <div
        id="addPurchase"
        class="modal fade"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-purchase></add-purchase>
          </div>
        </div>
      </div>

      <!-- modal end -->

      <!-- edit purchase modal -->

      <div
        id="editPurchase"
        class="modal fade"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-purchase :edit="true"></add-purchase>
          </div>
        </div>
      </div>

      <!-- modal end -->

      <!-- add purchase modal -->
    </div>
  </div>

  <!-- add purchase modal -->

  <!-- modal end -->
</template>
<script>
export default {
  data() {
    return {
      preLoader: false,
      purchaseData: {},
    };
  },

  methods: {
    getPurchases() {
      this.preLoader = true; //the loading begin
      axios
        .get("get-today-purchases")
        .then((response) => {
          this.purchaseData = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
        });
    },

    editPurchase(purchase) {
      bus.$emit("edit-purchase", purchase);
    },

    deletePurchase(purchase) {
      Swal.fire({
        title: "Are you sure?",
        // text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("delete-purchase", purchase).then((response) => {
            if (response.data == "success") {
              this.getPurchases();
              Toast.fire({
                icon: "error",
                title: "Record Deleted successfully",
              });
            }

            if (response.data == "failed") {
              Toast.fire({
                icon: "error",
                title: "Some Error Occurred,Please Try Again Later",
              });
              this.getPurchases();
            }
          });
        }
      });
    },
  },

  created() {
    this.getPurchases();

    var _this = this;
    bus.$on("purchase-added", function () {
      _this.getPurchases();
    });
  },

  watch: {
    purchaseData(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.purchaseTable).DataTable({
          pageLength: 5,
          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.purchaseTable).DataTable({
      pageLength: 5,
      lengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "All"],
      ],
    });
  },
};
</script>
