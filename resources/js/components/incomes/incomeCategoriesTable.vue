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
        <h5 class="m-0 font-weight-bold text-dark">Income Categories</h5>

        <button
          type="button"
          class="btn btn-primary rounded-pill"
          data-toggle="modal"
          data-target="#addincomecategory"
          id="#myBtn"
        >
          Add Income Category<i class="fas fa-plus-circle fa-fw"></i>
        </button>
      </div>
      <div class="table-responsive p-3">
        <table
          class="table align-items-center table-flush table-hover"
          id="incomecategoryTable"
          ref="incomecategoryTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>

              <th>Name</th>

              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(incomecategory, index) in incomecategories"
              :key="incomecategory.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ incomecategory.name }}</td>

              <td>
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  name=""
                  data-toggle="modal"
                  data-target="#editincomecategory"
                  @click="editIncomeCategory(incomecategory)"
                >
                  <i class="fas fa-edit fa-fw"></i>
                </button>

                <button
                  type="button"
                  class="btn btn-sm btn-danger"
                  name=""
                  @click="deleteIncomeCategory(incomecategory)"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- modal -->
    <div
      class="modal fade"
      id="addincomecategory"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-income-category></add-income-category>
        </div>
      </div>
    </div>

    <!-- end modal -->

    <!-- edit modal  -->
    <div
      class="modal fade"
      id="editincomecategory"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-income-category :edit="true"></add-income-category>
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
      incomecategories: {},
    };
  },
  created() {
    this.getIncomeCategories();
    var _this = this;
    bus.$on("incomecategory-added", function () {
      _this.getIncomeCategories();
    });
  },

  methods: {
    getIncomeCategories() {
      this.preLoader = true; //the loading begin
      axios
        .get("/admin/get-income-categories")
        .then((res) => {
          this.incomecategories = res.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    deleteIncomeCategory(incomecategory) {
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
            .post("/admin/delete-income-category", {
              id: incomecategory.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getIncomeCategories();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getIncomeCategories();
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

    editIncomeCategory(incomecategory) {
      bus.$emit("edit-incomecategory", incomecategory);
    },
  },

  watch: {
    incomecategories(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.incomecategoryTable).DataTable({
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
    this.dt = $(this.$refs.incomecategoryTable).DataTable({
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>
