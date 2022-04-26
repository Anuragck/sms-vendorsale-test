<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addEmployeeLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_employee_modal"
        @click="clearFormData()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <form
      class="form-horizontal"
      method="POST"
      enctype="multipart/form-data"
      @submit.prevent="addEmployee()"
    >
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <label for="employeeName" class="required">Employee Name</label>
            <input
              type="text"
              class="form-control"
              name="name"
              placeholder="Enter Employee Name"
              v-model="employees.name"
              :disabled="edit"
            />
            <small class="text-danger" v-if="errors.name">{{
              errors.name[0]
            }}</small>
          </div>

          <div class="form-group">
            <label for="mobile" class="">Mobile</label>
            <input
              type="number"
              class="form-control"
              name="mobile"
              placeholder="Enter Mobile Number"
              v-model="employees.mobile"
            />
            <small class="text-danger" v-if="errors.mobile">{{
              errors.mobile[0]
            }}</small>
          </div>

          <div class="form-group">
            <label for="category" class="required">Select Designation</label>
            <select
              class="form-control"
              name="designation_id"
              v-model="employees.designation_id"
            >
              <option value="">Select Designation</option>
              <option
                v-for="designation in designations"
                :key="designation.id"
                v-bind:value="designation.id"
              >
                {{ designation.name }}
              </option>
            </select>
            <small class="text-danger" v-if="errors.designation_id">{{
              errors.designation_id[0]
            }}</small>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="submit"
          class="btn btn-primary rounded-pill"
          :disabled="loading"
        >
          {{ button_title }}
          <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
        </button>
      </div>
    </form>
  </div>
</template>
<script>
import { ModelListSelect } from "vue-search-select";
export default {
  components: {
    ModelListSelect,
  },
  props: ["edit"],

  data() {
    return {
      title: "Add Employee",
      toastTitle: "Employee added successfully",
      errors: {},

      loading: false,
      designations: {},

      employees: {
        id: "",
        name: "",
        mobile: "",
        designation_id: "",
      },
    };
  },

  computed: {
    button_title() {
      if (this.loading == true) {
        return "Saving  ";
      } else {
        return "Save Changes";
      }
    },
  },
  created() {
    this.getDesignation();

    if (this.edit) {
      var _this = this;
      _this.title = "Edit Employee";
      _this.toastTitle = "Employee data Updated successfully";
      bus.$on("edit-employee", function (employee) {
        _this.clearFormData();
        _this.employees.designation_id = employee.designation_id;
        _this.employees.id = employee.id;
        _this.employees.name = employee.name;
        _this.employees.mobile = employee.mobile;
      });
    }
  },

  methods: {
    getDesignation() {
      axios
        .get("get-designations")
        .then((response) => {
          this.designations = response.data;
        })
        .catch((err) => {});
    },

    addEmployee() {
      if (this.edit) {
        this.employees.id = this.employees.id;
      }
      this.loading = true;
      axios
        .post("add-employee", this.employees)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
            bus.$emit("employee-added");
            this.$refs.close_employee_modal.click();
            this.clearFormData();
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            bus.$emit("employee-added");
            this.$refs.close_employee_modal.click();
            this.clearFormData();
          }

          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          this.errors = error.response.data.errors;
          console.log("errors");
        });
    },

    clearFormData() {
      this.employees.name = "";
      this.employees.mobile = "";
      this.employees.designation_id = "";
      this.errors = {};
    },
  },
};
</script>
