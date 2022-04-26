<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="main" v-else>
    <!-- DataTable with Hover -->
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
          <h5 class="m-0 font-weight-bold text-dark">Stock Adjustments</h5>

          <button
            type="button"
            class="btn btn-primary rounded-pill"
            data-toggle="modal"
            data-target="#addAdjustments"
          >
            Add Adjustments <i class="fas fa-plus-circle fa-fw"></i>
          </button>
        </div>
        <div class="table-responsive p-3">
          <table
            class="table align-items-center table-flush table-hover display"
            id="adjustmentsTable"
            ref="adjustmentsTable"
            data-order=""
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Category</th>
                <th>Product Name</th>
                <th>Increased Qty</th>
                <th>Decreased Qty</th>
                <th>Current Qty</th>
                <th v-if="role_admin == 1">Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Index</th>
                <th>Category</th>
                <th>Product Name</th>
                <th>Increased Qty</th>
                <th>Decreased Qty</th>
                <th>Current Qty</th>

                <th v-if="role_admin == 1">Actions</th>
              </tr>
            </tfoot>
            <tbody>
              <tr v-for="(stock, index) in stockAdjustments" :key="stock.index">
                <td>{{ index + 1 }}</td>
                <td v-if="stock.product.category_id == 1">{{ "Sale" }}</td>
                <td v-if="stock.product.category_id == 2">{{ "Reward" }}</td>
                <td v-if="stock.product.category_id == 3">{{ "Service" }}</td>
                <td>{{ stock.product.name }}</td>
                <td>{{ stock.increase_quantity }}</td>
                <td>{{ stock.decrease_quantity }}</td>
                <td>{{ stock.product.quantity }}</td>

                <td class="pl-3" v-if="role_admin == 1">
                  <button
                    class="btn btn-danger btn-sm ml-1"
                    @click="deleteAdjustment(stock)"
                  >
                    <i class="fas fa-trash fafw" title="Move to trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- add product modal -->

      <div
        id="addAdjustments"
        class="modal fade"
        tabindex="-1"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <add-adjustment></add-adjustment>
          </div>
        </div>
      </div>

      <!-- modal end -->

      <!-- add product modal end -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      role_admin: 0,
      stockAdjustments: {},
    };
  },

  methods: {
    getAdjustments() {
      this.preLoader = true; //the loading begin
      axios
        .get("get-adjustments")
        .then((response) => {
          this.stockAdjustments = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
        });
    },

    deleteAdjustment(stock) {
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
          axios.post("delete-adjustment", stock).then((response) => {
            if (response.data == "success") {
              this.getAdjustments();
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

              this.getAdjustments();
            }
          });
        }
      });
    },
  },

  created() {
    this.role_admin = this.user.role;
    this.getAdjustments();

    var _this = this;
    bus.$on("adjustment-added", function () {
      _this.getAdjustments();
    });
  },

  watch: {
    stockAdjustments(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.adjustmentsTable).DataTable({
          pageLength: 6,
          dom: "lBfrtip",
          buttons: [
            {
              extend: "copy",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(6))",
              },
            },
            {
              extend: "excel",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(6))",
              },
            },
            {
              extend: "pdf",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(6))",
              },
            },
            {
              extend: "print",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(6))",
              },
            },
          ],
          lengthMenu: [
            [6, 10, 25, 50, -1],
            [6, 10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.adjustmentsTable).DataTable({
      pageLength: 6,
      dom: "lBfrtip",
      buttons: [
        {
          extend: "copy",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(6))",
          },
        },
        {
          extend: "excel",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(6))",
          },
        },
        {
          extend: "pdf",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(6))",
          },
        },
        {
          extend: "print",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(6))",
          },
        },
      ],
      lengthMenu: [
        [6, 10, 25, 50, -1],
        [6, 10, 25, 50, "All"],
      ],
    });
  },
};
</script>
