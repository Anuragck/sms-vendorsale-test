<template>
  <!-- DataTable with Hover -->
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="col-lg-12" v-else>
    <div class="card mb-4">
      <div
        class="
          card-header
          py-7
          d-flex
          flex-row
          align-items-center
          justify-content-between
        "
      >
        <h5 class="m-0 font-weight-bold text-dark">Sales Return Report</h5>
        <!--FilterButton-->

        <div class="col-md-2 ml-auto">
          <button
            class="btn btn-primary btn-sm float-right rounded-pill"
            @click="showFilter()"
            v-if="search == false"
          >
            Add Filters <i class="fas fa-filter fa-fw"></i>
          </button>
          <button
            class="btn btn-outline-warning btn-sm float-right rounded-pill"
            @click="clearSearch()"
            v-if="search"
          >
            Clear Filters<i class="far fa-times-circle fa-fw"></i>
          </button>
        </div>

        <!--FilterButton end-->
      </div>

      <!-------------------------->
      <div class="" v-if="search">
        <div class="row justify-content-center">
          <div class="" style="">
            <div class="input-group">
              <span class="input-group-text">From</span>
              <input type="Date" class="form-control" v-model="from_date" />
              <span
                class="input-group-text ml-4 px-7"
                style="border-left: 0; border-right: 0"
              >
                To
              </span>
              <input type="Date" class="form-control" v-model="to_date" />
              <button
                class="btn btn-primary btn-sm ml-4 px-7"
                @click="getSalesReturn()"
              >
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
          id="salesReturnTable"
          ref="salesReturnTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Name</th>
              <th>Invoice No</th>
              <th>Bill Type</th>
              <th>Return Date</th>
              <th>Total Amount</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(sale, index) in sales_list" :key="sale.id">
              <td>{{ index + 1 }}</td>
              <td>{{ sale.customer["name"] }}</td>
              <td>{{ sale.invoice_no }}</td>
              <td v-if="sale.bill_type == 2">CASH</td>
              <td v-if="sale.bill_type == 1">CREDIT</td>
              <td>{{ sale.return_date | myDate }}</td>
              <td>{{ sale.total_amount }}</td>
              <td class="text-center" style="white-space: nowrap">
                <button
                  class="btn btn-outline-primary btn-sm"
                  data-toggle="modal"
                  data-target="#viewSaleReturns"
                  @click="viewSaleReport(sale)"
                >
                  <i class="fa fa-eye fa-fw" title="view"></i>
                </button>
<!--
                <button
                  class="btn btn-success btn-sm"
                  data-toggle="modal"
                  data-target="#printInvoice"
                  @click="salesReturnReportInvoice(sale)"
                >
                  <i class="fas fa-file-invoice" title="invoice"></i>
                </button>
-->
                <button
                  v-if="role_admin == 1"
                  class="btn btn-danger btn-sm"
                  @click="deleteSaleReport(sale)"
                >
                  <i class="fas fa-trash fafw" title="Move to trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- invoice modal  -->
    <div
      class="modal fade"
      id="printInvoice"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
      </div>
    </div>
    <!-- invoice modal -->
    <!-- viewOnly modal  -->
    <div
      class="modal fade"
      id="viewSaleReturns"
      tabindex="-1"
      data-backdrop="static"
      role="dialog"
      aria-labelledby="viewSaleReturnsLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5
              class="modal-title modal-title-custom"
              id="viewSaleReturnsLabel"
            >
              View Item Details
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
            <!-- content  -->

            <div class="row p-3">
              <div class="table-responsive">
                <table class="table table-sm table-striped text-center">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Type</th>
                      <th scope="col">Name</th>
                      <th scope="col">Rate</th>

                      <th scope="col">Return Quantity</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr
                      v-for="(item, index) in sale_return_items"
                      :key="item.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.product.category.name }}</td>
                      <td>{{ item.product.name }}</td>
                      <td>{{ item.rate }}</td>
                      <td>{{ parseInt(item.return_qty) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- content ends -->
          </div>
        </div>
      </div>
    </div>
    <!-- viewOnly modal -->
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      role_admin: 0,
      sales_list: {},
      sale_return_items: [],
      from_date: "",
      to_date: "",
      search: false,
    };
  },

  created() {
    this.getSalesReturn();
    this.role_admin = this.user.role;
    
  },
  methods: {
    getSalesReturn() {
      this.preLoader = true;
      axios
        .get("/admin/get-sale-returns", {
          params: {
            from_date: this.from_date,
            to_date: this.to_date,
          },
        })
        .then((response) => {
          this.sales_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    viewSaleReport(sale) {
      this.sale_return_items = sale.items;
    },
    clearSearch() {
      this.from_date = "";
      this.to_date = "";
      this.search = false;
      this.getSalesReturn();
    },

    showFilter() {
      this.search = true;
    },

    salesReturnReportInvoice(sale) {
      bus.$emit("sale-invoice", sale);
    },

    deleteSaleReport(sale) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/delete-sale-return", {
              id: sale.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getSalesReturn();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getSalesReturn();
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
  },

  watch: {
    sales_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.salesReturnTable).DataTable({
          pageLength: 5,
          dom: "lBfrtip",
          buttons: [
            {
              extend: "copy",
              title: "Sales Return Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "excel",
              title: "Sales Return Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "pdf",
              title: "Sales Return Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "print",
              title: "Sales Return Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
          ],

          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
          bSort: false,
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.salesReturnTable).DataTable({
      pageLength: 5,

      dom: "lBfrtip",
      buttons: [
        {
          extend: "copy",
          title: "Sales Return Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "excel",
          title: "Sales Return Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "pdf",
          title: "Sales Return Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "print",
          title: "Sales Return Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
      ],

      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
      bSort: false,
    });
  },
};
</script>

<style>
</style>
