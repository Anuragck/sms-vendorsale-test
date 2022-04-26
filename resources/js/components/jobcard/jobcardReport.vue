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
        <h5 class="m-0 font-weight-bold text-dark">jobcard Report</h5>
        <!--FilterButton-->
        <div class="ml-3">
          <button
            class="btn rounded btn-sm shadow-sm"
            @click="completedPayments()"
            :class="completed_payments ? 'btn-primary' : ''"
          >
            Delivery Completed
            <i class="fas fa-hourglass-half fa-fw"></i>
          </button>
          <button
            class="btn rounded btn-sm shadow-sm"
            @click="pendingPayments()"
            :class="pending_payments ? 'btn-primary' : ''"
          >
            Payments Pending <i class="fas fa-truck-loading fa-fw"></i>
          </button>
        </div>
        <div class="col-md-2 ml-auto">
          <button
            class="btn btn-primary btn-sm float-right rounded-pill"
            @click="showFilter()"
            v-if="search == false"
            :disabled="pending_payments == true"
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
      <div class="" v-if="search && pending_payments == false">
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
                @click="getJobcard()"
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
          id="jobcardTable"
          ref="jobcardTable"
        >
          <thead class="thead-light">
            <tr>
              <th style="width: px">#</th>
              <th style="width: 120px">Date</th>
              <th style="width: px">Jobcard No</th>
              <th style="width: px">Name</th>
              <th style="width: px">Vehicle</th>
              <th style="width: px">Reg No</th>
              <th style="width: 244px" class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(jobcard, index) in jobcard_list" :key="jobcard.id">
              <td>{{ index + 1 }}</td>
              <td>{{ jobcard.created_date | myDate }}</td>
              <td>{{ jobcard.id }}</td>
              <td>{{ jobcard.customer.name }}</td>
              <td>{{ jobcard.vehicle }}</td>
              <td>{{ jobcard.register_no }}</td>

              <td class="text-center" style="white-space: nowrap">
                <button
                  class="btn btn-warning btn-xs"
                  data-toggle="modal"
                  data-target="#editJobcard"
                  @click="viewJobcard(jobcard)"
                >
                  <i class="fa fa-eye fa-fw" title="view"></i>
                </button>
                <button
                  class="btn btn-primary btn-xs"
                  data-toggle="modal"
                  data-target="#addjobcardpayment"
                  @click="addJobcardPayment(jobcard)"
                  v-if="pending_payments == true"
                >
                  <i class="fas fa-hand-holding-usd" title="add payment"></i>
                </button>

                <button
                  class="btn btn-info btn-xs"
                  data-toggle="modal"
                  data-target="#printJobcard"
                  @click="printJobcard(jobcard)"
                >
                  <i class="fas fa-print" title="print jobcard"></i>
                </button>

                <button
                  v-if="role_admin == 1"
                  class="btn btn-danger btn-xs"
                  @click="deleteJobcard(jobcard)"
                >
                  <i class="fas fa-trash" title="Move to trash"></i>
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
      id="printJobcard"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <jobcard-print></jobcard-print>
        </div>
      </div>
    </div>
    <!-- invoice modal -->
    <!-- viewOnly modal  -->
    <div
      id="editJobcard"
      class="modal fade"
      tabindex="-1"
      data-backdrop="static"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <add-jobcard
            :usergst="this.user.gst"
            :edit="true"
            :viewOnly="this.viewonly"
          ></add-jobcard>
        </div>
      </div>
    </div>
    <!-- viewOnly modal -->

    <!-- payment  -->

    <div
      id="addjobcardpayment"
      class="modal fade"
      tabindex="-1"
      data-backdrop="static"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <add-jobcard-payment></add-jobcard-payment>
        </div>
      </div>
    </div>
    <!-- payment end -->
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      jobcard_list: {},
      from_date: "",
      to_date: "",
      role_admin: "",
      search: false,
      viewonly: false,

      completed_payments: true,
      pending_payments: false,
    };
  },

  created() {
    this.getJobcard();
    this.role_admin = this.user.role;
    var _this = this;
    bus.$on("jobcard-added", function () {
      _this.getJobcard();
    });
  },
  methods: {
    getJobcard() {
      var url = "";
      if (this.completed_payments == true) {
        url = "/admin/get-jobcards";
      } else if (this.pending_payments == true) {
        url = "get-payment-balance-jobcards";
      }
      this.preLoader = true;
      axios
        .get(url, {
          params: {
            jobcard_status: "2",
            report: "1",
            from_date: this.from_date,
            to_date: this.to_date,
          },
        })
        .then((response) => {
          this.jobcard_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },
    printJobcard(jobcard) {
      bus.$emit("print-jobcard", jobcard);
    },
    viewJobcard(jobcard) {
      this.viewonly = true;
      bus.$emit("edit-jobcard", jobcard);
    },
    clearSearch() {
      this.from_date = "";
      this.to_date = "";
      this.search = false;
      this.getJobcard();
    },

    showFilter() {
      this.search = true;
    },

    jobcardReportInvoice(jobcard) {
      bus.$emit("jobcard-invoice", jobcard);
    },
    addJobcardPayment(jobcard) {
      bus.$emit("add-jobcard-payment", jobcard);
    },

    deleteJobcard(jobcard) {
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
            .post("/admin/delete-jobcard", {
              id: jobcard.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getJobcard();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getJobcard();
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

    completedPayments() {
      this.pending_payments = false;
      this.completed_payments = true;
      this.getJobcard();
    },

    pendingPayments() {
      this.pending_payments = true;
      this.completed_payments = false;
      this.clearSearch();
    },
  },

  watch: {
    jobcard_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.jobcardTable).DataTable({
          pageLength: 5,
          dom: "lBfrtip",
          buttons: [
            {
              extend: "copy",
              title: "jobcard Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "excel",
              title: "jobcard Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "pdf",
              title: "jobcard Report",
              exportOptions: {
                columns: ":visible:not(:eq(7))",
              },
            },
            {
              extend: "print",
              title: "jobcard Report",
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
    this.dt = $(this.$refs.jobcardTable).DataTable({
      pageLength: 5,
      dom: "lBfrtip",
      buttons: [
        {
          extend: "copy",
          title: "jobcard Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "excel",
          title: "jobcard Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "pdf",
          title: "jobcard Report",
          exportOptions: {
            columns: ":visible:not(:eq(7))",
          },
        },
        {
          extend: "print",
          title: "jobcard Report",
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
</style>
