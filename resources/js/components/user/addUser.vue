<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addcustomerLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_user_modal"
        @click="clear_form_data()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="name" class="required">Name</label>
              <input
                type="text"
                class="form-control"
                placeholder="Enter Name"
                name="name"
                v-model="user.name"
              />
            </div>

            <small class="text-danger" v-if="errors.name">
              {{ errors.name[0] }}</small
            >
          </div>

          <div class="col">
            <div class="form-group">
              <label for="customerMobile" class="required">Username</label>
              <input
                type="text"
                class="form-control"
                placeholder="Enter Username"
                name="username"
                v-model="user.username"
              />
            </div>
            <small class="text-danger" v-if="errors.username">
              {{ errors.username[0] }}</small
            >
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="gst number">Email</label>
              <input
                type="email"
                class="form-control"
                placeholder="Enter Email Number"
                name="email"
                v-model="user.email"
              />
            </div>

            <small class="text-danger" v-if="errors.email">
              {{ errors.email[0] }}</small
            >
          </div>

          <div class="col">
            <div class="form-group">
              <label for="mobile">Mobile</label>
              <div class="input-group">
                <input
                  type="number"
                  max="10"
                  name="mobile"
                  class="form-control"
                  v-model="user.mobile"
                />
              </div>
              <small class="text-danger" v-if="errors.mobile">
                {{ errors.mobile[0] }}</small
              >
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="gst number">Select Company </label>
              <select
                class="form-control"
                aria-label="select company"
                v-model="user.company"
                name="company"
              >
                <option value="">Select Company</option>
                <option value="1">Company 1</option>
                <option value="2">Company 2</option>
                <option value="3">Company 3</option>
              </select>
            </div>

                <small class="text-danger" v-if="errors.company">
              {{ errors.company[0] }}</small
            >
          </div>

          <div class="col">
            <div class="form-group">
              <label for="gst number">Select Role </label>
              <select class="form-control" v-model="user.role" name="role">
                <option value="">Select Role</option>
                <option value="1">Admin</option>
                <option value="2">User</option>
              </select>
            </div>

            <small class="text-danger" v-if="errors.role">
              {{ errors.role[0] }}</small
            >
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <input
                type="checkbox"
                value="1"
                id="gst"
                v-model="gst_permission"
              />
              <label for="gst">GST</label>
              <input
                type="checkbox"
                value="1"
                id="vehicle"
                v-model="vehicle_permission"
              />
              <label for="Vehicle">Vehicle</label>
              <input
                type="checkbox"
                value="1"
                id="jobcard"
                v-model="jobcard_permission"
              />
              <label for="Jobcard">Jobcard</label>
              <input
                type="checkbox"
                value="1"
                id="reward"
                v-model="reward_permission"
              />
              <label for="Reward">Reward</label>
            </div>

            <small class="text-danger"> </small>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="button"
        @click="addCustomer()"
        class="btn btn-primary rounded-pill"
        :disabled="loading"
      >
        {{ button_title
        }}<i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
      </button>
    </div>
  </div>
</template>
<script>
export default {
  props: ["edit", "usergst"],
  data() {
    return {
      loading: false,
      gst_user: this.usergst,
      title: "Add User",
      toastTitle: "User added successfully",
      gst_permission: false,
      vehicle_permission: false,
      jobcard_permission: false,
      reward_permission: false,
      user: {
        id: "",
        name: "",
        username: "",
        email: "",
        mobile: "",
        company: "",
        role: "",

        gst_permission: "",
        vehicle_permission: "",
        jobcard_permission: "",
        reward_permission: "",
      },

      errors: [],
    };
  },

  created() {
    if (this.edit) {
      var _this = this;
      _this.title = "Edit User";
      _this.toastTitle = "User details updated successfully";
      bus.$on("edit-user", function (user) {
        _this.user.id = user.id;
        _this.user.name = user.name;
        _this.user.username = user.username;
        _this.user.email = user.email;
        _this.user.mobile = user.mobile;
        _this.user.company = user.company_id;
        _this.user.role = user.role;

        _this.gst_permission = user.gst;
        _this.vehicle_permission = user.vehicle;
        _this.jobcard_permission = user.jobcard;
        _this.reward_permission = user.reward;
      });
    }
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

  methods: {
    addCustomer() {
      if (this.gst_permission == true) {
        this.user.gst_permission = 1;
      } else {
        this.user.gst_permission = 0;
      }

      if (this.vehicle_permission == true) {
        this.user.vehicle_permission = 1;
      } else {
        this.user.vehicle_permission = 0;
      }

      if (this.jobcard_permission == true) {
        this.user.jobcard_permission = 1;
      } else {
        this.user.jobcard_permission = 0;
      }

      if (this.reward_permission == true) {
        this.user.reward_permission = 1;
      } else {
        this.user.reward_permission = 0;
      }
      this.loading = true;
      axios
        .post("user-register", this.user)
        .then((response) => {
     this.loading = false;
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });
          }

     
          bus.$emit("user-added");
          this.$refs.close_user_modal.click();
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err.response.data.errors;
        });
    },

    clear_form_data() {
      for (let item in this.user) {
        this.user[item] = "";
      }
      for (let er in this.errors) {
        this.errors[er] = "";
      }

      this.gst_permission = false;
      this.vehicle_permission = false;
      this.jobcard_permission = false;
      this.reward_permission = false;
    },
  },
};
</script>
