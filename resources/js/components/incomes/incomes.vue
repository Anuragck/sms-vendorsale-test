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
          <h5 class="m-0 font-weight-bold text-dark">Incomes</h5>

          <button
            type="button"
            class="btn btn-primary rounded-pill"
            data-toggle="modal"
            data-target="#addIncome"
          >
            Add Incomes <i class="fas fa-plus-circle fa-fw"></i>
          </button>
        </div>
        <div class="table-responsive p-3">
          <table
            class="table align-items-center table-flush table-hover display"
            id="incomesTable"
            ref="incomesTable"
            data-order=""
          >
            <thead class="thead-light">
              <tr>
                <th>S/N</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Pay Type</th>
                <th>Remarks</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>S/N</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Pay Type</th>
                <th>Remarks</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              <tr v-for="(income, index) in incomes" :key="income.id">
                <td>{{ index + 1 }}</td>

                <td>{{ income.incomecategory.name }}</td>
                <td>{{ income.amount }}</td>
                <td>{{ income.pay_type }}</td>
                <td>{{ income.remarks }}</td>

                <td class="pl-3">
                  <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    name=""
                    data-toggle="modal"
                    data-target="#editIncome"
                    @click="editIncome(income)"
                  >
                    <i class="fas fa-edit fa-fw"></i>
                  </button>
                  <button
                    class="btn btn-danger btn-sm ml-1"
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

      <!-- add product modal -->

      <div
        id="addIncome"
        class="modal fade"
        tabindex="-1"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <add-income></add-income>
          </div>
        </div>
      </div>
    </div>
    <!-- edit modal  -->
    <div
      class="modal fade"
      id="editIncome"
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
    <!-- end modal -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      preLoader: false,
      incomes: {},
    };
  },

  methods: {
    getIncomes() {
      this.preLoader = true; //the loading begin
      axios
        .get("get-today-incomes")
        .then((response) => {
          this.incomes = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
        });
    },

    editIncome(income) {
      bus.$emit("edit-income", income);
    },

    deleteIncome(income) {
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
          axios
            .post("delete-income", {
              id: income.id,
            })
            .then((response) => {
              if (response.data == "Success") {
                this.getIncomes();
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

                this.getIncomes();
              }
            });
        }
      });
    },
  },

  created() {
    this.getIncomes();

    var _this = this;
    bus.$on("income-added", function () {
      _this.getIncomes();
    });
  },

  watch: {
    incomes(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.incomesTable).DataTable({
          pageLength: 10,
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.incomesTable).DataTable({
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>
