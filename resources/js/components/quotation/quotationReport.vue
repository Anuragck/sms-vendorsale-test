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
        <h5 class="m-0 font-weight-bold text-dark">Quotations Report</h5>
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
                @click="getQuotations()"
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
          id="customerTable"
          ref="customerTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Name</th>
             <th>Quotation No</th>
              <th>Total Amount</th>
            
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(quotation, index) in quotation_list" :key="quotation.id">
              <td>{{ index + 1 }}</td>
              <td>{{ quotation.customer["name"] }}</td>
             <td>{{ quotation.quotation_no }}</td>
              <td>{{ quotation.pay_amount }}</td>
              

              <td class="text-center" style="white-space: nowrap">
 <button
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#editquotation"
                  @click="editQuotation(quotation)"
                  :disabled="quotation.gst_bill == 1 && authUser == false"
                >
                  <i class="fas fa-edit fafw" title="Edit"></i>
                </button>
                <button
                  class="btn btn-outline-primary btn-sm"
                  data-toggle="modal"
                  data-target="#viewOnly"
                  @click="viewQuotation(quotation)"
                >
                  <i class="fa fa-eye fa-fw" title="view"></i>
                </button>

                <button
                  class="btn btn-success btn-sm"
                  data-toggle="modal"
                  data-target="#printQuotation"
                  @click="quotationPrint(quotation)"
                >
                  <i class="fas fa-file-invoice" title="invoice"></i>
                </button>

                <button
                  v-if="role_admin == 1"
                  class="btn btn-danger btn-sm"
                  @click="deleteQuotation(quotation)"
                >
                  <i class="fas fa-trash fafw" title="Move to trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>




 <!-- edit modal  -->
    <div
      class="modal fade"
      id="editquotation"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <add-quotation
            :edit="true"
            :usergst="this.user.gst"
            :vehicle="this.user.vehicle"
          ></add-quotation>
        </div>
      </div>
    </div>
    <!-- end modal -->


    <!-- invoice modal  -->
    <div
      class="modal fade"
      id="printQuotation"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <quotation-print></quotation-print>
        </div>
      </div>
    </div>
    <!-- invoice modal -->
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
          <add-quotation :viewOnly="true"></add-quotation>
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
      quotation_list: {},
      from_date: "",
      to_date: "",
      search: false,
      vehiclecheck: false,
      rewardcheck: false,
 authUser: false,
    };
  },

  created() {
  if (this.user.gst == 1) {
      this.authUser = true;
    }
    if (this.user.vehicle == 1) {
      this.vehiclecheck = true;
    }
    this.role_admin = this.user.role;
    if (this.user.reward == 1) {
      this.rewardcheck = true;
    }
    this.getQuotations();
    var _this = this;
    bus.$on("quotation-added", function () {
      _this.getQuotations();
    });
  },
  methods: {
    getQuotations() {
      this.preLoader = true;
      axios
        .get("/admin/get-quotations", {
          params: {
            from_date: this.from_date,
            to_date: this.to_date,
          },
        })
        .then((response) => {
          this.quotation_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

 editQuotation(quotation) {
      bus.$emit("edit-quotation", quotation);
    },
    viewQuotation(quotation) {
      bus.$emit("view-quotation", quotation);
    },
    clearSearch() {
      this.from_date = "";
      this.to_date = "";
      this.search = false;
      this.getQuotations();
    },

    showFilter() {
      this.search = true;
    },

    quotationPrint(quotation) {
      bus.$emit("quotation-print", quotation);
    },

    deleteQuotation(quotation) {
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
            .post("/admin/delete-quotation", {
              id: quotation.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getQuotations();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getQuotations();
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
    quotation_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.customerTable).DataTable({
          pageLength: 5,
          dom: "lBfrtip",
          buttons: [
            {
              extend: "copy",
              title: "Quotations Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "excel",
              title: "Quotations Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "pdf",
              title: "Quotations Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "print",
              title: "Quotations Report",
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
    this.dt = $(this.$refs.customerTable).DataTable({
      pageLength: 5,

      dom: "lBfrtip",
      buttons: [
        {
          extend: "copy",
          title: "Quotations Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "excel",
          title: "Quotations Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "pdf",
          title: "Quotations Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "print",
          title: "Quotations Report",
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
