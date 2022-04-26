<template>
  <!-- DataTable with Hover -->
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
          <h5 class="m-0 font-weight-bold text-dark">Employees</h5>

          <button
            type="button"
            class="btn rounded-pill btn-primary"
            data-toggle="modal"
            data-target="#addEmployee"
          >
            Add Employee <i class="fas fa-plus-circle fa-fw"></i>
          </button>

        </div>
        <div class="table-responsive p-3">
          <table
            class="table align-items-center table-flush table-hover display"
            id="employeesTable"
            ref="employeesTable"
            data-order=""
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Designation</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Index</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Designation</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              <tr
                v-for="(employee, index) in employee_details"
                :key="employee.id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ employee.name }}</td>
                <td>{{ employee.mobile }}</td>
                <td>{{ employee.designation.name }}</td>

                <td class="pl-3">
                  <button
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#editEmployee"
                    @click="editEmployee(employee)"
                  >
                    <i class="fas fa-edit fafw" title="Edit"></i>
                  </button>

                  <button
                    v-if="role_admin == 1"
                    class="btn btn-danger btn-sm ml-1"
                    @click="deleteEmployee(employee)"
                  >
                    <i class="fas fa-trash fafw" title="Move to trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!--------------Add-Employee-Modal---------------->
      <div
        id="addEmployee"
        class="modal fade"
        tabindex="-1"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <add-employee></add-employee>
          </div>
        </div>
      </div>

      <!--------------Add-Employee-Modal---------------->

      <!--------------Edit-Employee-Modal---------------->
      <div
        id="editEmployee"
        class="modal fade"
        tabindex="-1"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <add-employee :edit="true"></add-employee>
          </div>
        </div>
      </div>

      <!--------------Add-Employee-Modal---------------->
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      role_admin: 0,
      preLoader: false,
      employee_details: [],
    };
  },
  created() {
    this.role_admin = this.user.role;
    this.getEmployees();
    var _this = this;
    bus.$on("employee-added", function () {
      _this.getEmployees();
    });
  },

  methods: {


    getEmployees() {
      this.preLoader = true; //the loading begin
      axios
        .get("get-employees")
        .then((res) => {
          this.employee_details = res.data;
          this.preLoader = false; //the loading end
        })
        .catch((err) => {
          this.preLoader = false; //the loading end
          console.log(err);
        });
    },



    deleteEmployee(employee) {
      Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("delete-employee", employee).then((response) => {
            if (response.data == "success") {
              this.getEmployees();
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

              this.getEmployees();
            }
          });
        }
      });
    },

    editEmployee(employee) {
      bus.$emit("edit-employee", employee);
    },
  },

  watch: {
    employee_details(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.employeesTable).DataTable({
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
    this.dt = $(this.$refs.employeesTable).DataTable({
      pageLength: 5,
      lengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "All"],
      ],
    });
  },
};
</script>
