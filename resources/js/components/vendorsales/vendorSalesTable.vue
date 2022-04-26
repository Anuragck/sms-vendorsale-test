<template>
  <!-- DataTable with Hover -->
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="salePage" v-else>
    <!-- <div class="col-lg-12" v-if="salePage">

  <add-sale
            :usergst="this.user.gst"
            :vehicle="this.user.vehicle"
          ></add-sale>

</div> -->

    <div class="col-lg-12" v-if="salePage == false">
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
          <h5 class="m-0 font-weight-bold text-dark">{{ title }}</h5>

          <button
            v-if="report != true"
            type="button"
            class="btn btn-primary rounded-pill"
            data-toggle="modal"
            data-target="#addsale"
            id="#myBtn"
            @click="addSale = true"
          >
            Add Sale<i class="fas fa-plus-circle fa-fw"></i>
          </button>

          <!--FilterButton-->

          <div class="col-md-2 ml-auto" v-if="report == true">
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
                  @click="getSale()"
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
            ref="salesTable"
            id="salesTable"
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>

                <th>Name</th>
                <th>Bill Type</th>
                <th v-if="vehiclecheck">Vehicle</th>
                <th v-if="vehiclecheck">Register No</th>
                <th>Total Amount</th>
                <th v-if="rewardcheck">Reward Point</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(sale, index) in sales_list" :key="sale.id">
                <td>{{ index + 1 }}</td>
                <td>{{ sale.vendor.name }}</td>
                <td v-if="sale.bill_type == 2">CASH</td>
                <td v-if="sale.bill_type == 1">CREDIT</td>
                <td v-if="vehiclecheck">{{ sale.vehicle }}</td>
                <td v-if="vehiclecheck">{{ sale.register_no }}</td>
                <td>{{ sale.pay_amount }}</td>
                <td v-if="rewardcheck">{{ sale.reward_points }}</td>

                <td class="pl-3" style="white-space: nowrap">
                  <button
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#editsale"
                    @click="editSale(sale)"
                    :disabled="sale.gst_bill == 1 && authUser == false"
                  >
                    <i class="fas fa-edit fafw" title="Edit"></i>
                  </button>
                  <button
                    v-if="report"
                    class="btn btn-outline-primary btn-sm"
                    data-toggle="modal"
                    data-target="#viewOnly"
                    @click="viewSale(sale)"
                  >
                    <i class="fa fa-eye fa-fw" title="view"></i>
                  </button>
                  <button
                    class="btn btn-danger btn-sm"
                    @click="deleteSale(sale)"
                  >
                    <i class="fas fa-trash fafw" title="Move to trash"></i>
                  </button>

                  <button
                    class="btn btn-success btn-sm"
                    data-toggle="modal"
                    data-target="#printInvoice"
                    @click="saleInvoice(sale)"
                  >
                    <i class="fas fa-file-invoice" title="invoice"></i>
                  </button>
                  <!-- <button class="btn btn-warning btn-sm" @click="pdf(sale)">
                    <i class="fas fa-file-invoice" title="invoice"></i>
                  </button>-->
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- modal -->
      <div
        v-if="addSale"
        class="modal fade"
        id="addsale"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-vendorsale
              :usergst="this.user.gst"
              :vehicle="this.user.vehicle"
            ></add-vendorsale>
          </div>
        </div>
      </div>

      <!-- end modal -->

      <!-- edit modal  -->
      <div
        class="modal fade"
        id="editsale"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-vendorsale
              :edit="true"
              :usergst="this.user.gst"
              :vehicle="this.user.vehicle"
            ></add-vendorsale>
          </div>
        </div>
      </div>
      <!-- end modal -->

      <!-- viewOnly modal  -->
      <div
        class="modal fade"
        id="viewOnly"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-vendorsale :viewOnly="true"></add-vendorsale>
          </div>
        </div>
      </div>
      <!-- viewOnly modal -->

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
          <div class="modal-content">
            <vendor-sale-invoice></vendor-sale-invoice>
          </div>
        </div>
      </div>
      <!-- invoice modal -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      title: "Vendor Sale",
      salePage: false,
      preLoader: false,
      sales_list: {},
      vehiclecheck: false,
      rewardcheck: false,
      addSale: false,
      authUser: false,
      from_date: "",
      to_date: "",
      search: false,

      report: false,

      exportButtons: "",
    };
  },

  created() {
    if (this.user.gst == 1) {
      this.authUser = true;
    }
    if (this.user.vehicle == 1) {
      this.vehiclecheck = true;
    }
    if (this.user.reward == 1) {
      this.rewardcheck = true;
    }
    this.checkReport();

    var _this = this;
    bus.$on("sale-added", function () {
      _this.getSale();
    });
  },

  methods: {
    getSale() {
      this.preLoader = true; //the loading begin
      axios
        .get("/admin/get-vendor-sales", {
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

    editSale(sale) {
      bus.$emit("edit-sale", sale);
    },

    saleInvoice(sale) {
      bus.$emit("sale-invoice", sale);
    },

    deleteSale(sale) {
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
            .post("/admin/delete-sale", {
              id: sale.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getSale();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getSale();
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

    // dompdf

    pdf(sale) {
      axios({
        url: "get-invoice",

        method: "GET",

        responseType: "blob",
        params: {
          id: sale.id,
          gst_bill: sale.gst_bill,
        },
      })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");

          fileLink.href = fileURL;
          fileLink.setAttribute("download", "Invoice.pdf");
          document.body.appendChild(fileLink);

          fileLink.click();
        })
        .catch((err) => {});
    },

    showFilter() {
      this.search = true;
    },
    viewSale(sale) {
      bus.$emit("view-sale", sale);
    },
    clearSearch() {
      this.from_date = "";
      this.to_date = "";
      this.search = false;
      this.getSale();
    },

    checkReport() {
      this.clearSearch();
      if (this.$attrs.report == true) {
        this.report = true;
        this.exportButtons = "lBfrtip";
        this.title = "Sale Report";
        this.getSale();
      } else {
        this.report = false;
        this.exportButtons = "lfrtip";
        this.title = "Vendor Sale";
        this.getSale();
      }
    },
  },

  watch: {
    $attrs: function () {
      this.checkReport();
    },

    sales_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.salesTable).DataTable({
          pageLength: 5,
          dom: this.exportButtons,
          buttons: [
            {
              extend: "copy",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "excel",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "pdf",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "print",
              title: "Sales Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
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
    this.dt = $(this.$refs.salesTable).DataTable({
      pageLength: 5,

      dom: this.exportButtons,
      buttons: [
        {
          extend: "copy",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "excel",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "pdf",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "print",
          title: "Sales Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
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

<style>
</style>
