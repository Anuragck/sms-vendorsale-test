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
        <h5 class="m-0 font-weight-bold text-dark">Income Report</h5>
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
              <input type="Date" class="form-control" v-model="from_date" />
              <span
                class="input-group-text ml-4 px-3"
                style="border-left: 0; border-right: 0"
              >
                To
              </span>
              <input type="Date" class="form-control" v-model="to_date" />
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
          id="incomeTable"
          ref="incomeTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Income Date</th>
              <th>Income Category</th>
              <th>Income Remarks</th>
              <th>Payment Amount</th>
              <th>Payment Type</th>

              <!--  <th class="text-center">Actions</th>
                        </tr>
                    </thead>-->
              <th v-if="role_admin == 1" class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(income, index) in income_list" :key="income.id">
              <td>{{ index + 1 }}</td>
              <td>{{ income.created_at | myDate }}</td>
              <td>{{ income.incomecategory["name"] }}</td>
              <td>{{ income.remarks }}</td>
              <td>{{ income.amount }}</td>
              <td>{{ income.pay_type }}</td>

              <td class="pl-4 text-center" style="white-space: nowrap">
                <button
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#editIncomes"
                  @click="editIncome(income)"
                >
                  <i class="fas fa-edit fafw" title="Edit"></i>
                </button>
              </td>

              <td
                v-if="role_admin == 1"
                class="pl-4 text-center"
                style="white-space: nowrap"
              >
                <button
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#editIncomes"
                  @click="editIncome(income)"
                >
                  <i class="fas fa-edit fafw" title="Edit"></i>
                </button>

                <button
                  v-if="role_admin == 1"
                  class="btn btn-danger btn-sm"
                  @click="deleteIncome(income)"
                >
                  <i class="fas fa-trash fafw" title="Move to trash"></i>
                </button>
                <button
                  class="btn btn-danger btn-sm"
                  @click="deleteIncome(income)"
                >
                  <i class="fas fa-trash fafw" title="Move to trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- editIncomes modal -->
    <div
      class="modal fade"
      id="editIncomes"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-income :edit="true"></add-income>
        </div>
      </div>
    </div>
    <!-- editIncomes modal -->
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      role_admin: 0,
      income_list: {},
      from_date: "",
      to_date: "",
      search: false,
    };
  },

  created() {
    this.getIncomes();
    this.role_admin = this.user.role;
  },
  methods: {
    getIncomes() {
      this.preLoader = true;
      axios
        .get("/admin/get-incomes", {
          params: {
            from_date: this.from_date,
            to_date: this.to_date,
          },
        })
        .then((response) => {
          this.income_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    editIncome(income) {
      bus.$emit("edit-income", income);
    },

    hideFilter() {
      this.getIncomes();
      this.search = false;
      this.from_date = "";
      this.to_date = "";
    },

    viewFilters() {
      this.search = true;
    },
    addFilters() {
      this.getIncomes();
      this.from_date = "";
      this.to_date = "";
    },

    deleteIncome(income) {
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
            .post("/admin/delete-income", {
              id: income.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getIncomes();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getIncomes();
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
    income_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.incomeTable).DataTable({
          pageLength: 5,

          dom: "Bfrtip",

          buttons: [
            {
              extend: "copy",
              title: "Income Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
            {
              extend: "excel",
              title: "Income Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
            {
              extend: "pdf",
              title: "Income Report",
              exportOptions: {
                columns: ":visible:not(:eq(5))",
              },
            },
            {
              extend: "print",
              title: "Income Report",
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
    this.dt = $(this.$refs.incomeTable).DataTable({
      pageLength: 5,

      dom: "Bfrtip",

      buttons: [
        {
          extend: "copy",
          title: "Income Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
        {
          extend: "excel",
          title: "Income Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
        {
          extend: "pdf",
          title: "Income Report",
          exportOptions: {
            columns: ":visible:not(:eq(5))",
          },
        },
        {
          extend: "print",
          title: "Income Report",
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

<style scoped></style>
