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
        ref="close_customer_modal"
        @click="clear_form_data()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <label for="customerName" class="required">Name</label>
              <input
                type="text"
                class="form-control"
                id="customerName"
                placeholder="Enter Name"
                name="name"
                v-model="customer.name"
              />
            </div>

            <small class="text-danger" v-if="errors.name">
              {{ errors.name[0] }}</small
            >
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 col-md-6">
            <div class="form-group">
              <label for="customerMobile" class="required">Mobile</label>
              <input
                type="number"
                class="form-control"
                id="customerMobile"
                placeholder="Enter Mobile"
                name="mobile"
                v-model="customer.mobile"
                :max="10"
              />
            </div>

            <small class="text-danger" v-if="errors.mobile">
              {{ errors.mobile[0] }}</small
            >
          </div>

          <div class="col-lg-6 col-md-6">
            <div class="form-group">
              <label for="customerEmail" class="required">Email</label>
              <input
                type="email"
                class="form-control"
                id="customerEmail"
                placeholder="Enter Email ID"
                name="email"
                v-model="customer.email"
              />
            </div>

            <small class="text-danger" v-if="errors.email">
              {{ errors.email[0] }}</small
            >
          </div>
        </div>

        <div class="row" v-if="gst_user == 1">
          <div class="col-lg-6 col-md-6">
            <div class="form-group">
              <label for="gst number">GST Number</label>
              <input
                type="text"
                class="form-control"
                id="gstNo"
                placeholder="Enter GST Number"
                name="gst_no"
                v-model="customer.gst_no"
              />
            </div>

            <small class="text-danger" v-if="errors.gst_no">
              {{ errors.gst_no[0] }}</small
            >
          </div>

          <div class="col-lg-6 col-md-6">
            <div class="form-group">
              <label for="discount">Discount</label>
              <div class="input-group">
                <input
                  id="numberbox2"
                  type="number"
                  max="100"
                  min="0"
                  class="form-control"
                  v-model="customer.discount"
                />
                <div class="input-group-append">
                  <span class="input-group-text"> % </span>
                </div>
              </div>
              <small class="text-danger"></small>
            </div>
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
      title: "Add Customer",
      toastTitle: "Customer added successfully",
      customer: {
        id: "",
        name: "",
        email: "",
        mobile: "",
        gst_no: "",
        discount: "",
      },

      errors: [],
    };
  },

  created() {
    if (this.edit) {
      var _this = this;

      _this.title = "Edit Customer";
      _this.toastTitle = "Customer updated successfully";
      bus.$on("edit-customer", function (customer) {
        _this.clear_form_data();
        _this.customer.id = customer.id;
        _this.customer.name = customer.name;
        _this.customer.email = customer.email;
        _this.customer.mobile = customer.mobile;
        _this.customer.gst_no = customer.gst_no;
        _this.customer.discount = customer.discount;
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
      this.loading = true;
      axios
        .post("/admin/add-customer", this.customer)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_customer_modal.click();
            this.clear_form_data();
            bus.$emit("customer-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_customer_modal.click();
            this.clear_form_data();
            bus.$emit("customer-added");
          }

          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err.response.data.errors;
        });
    },

    clear_form_data() {
      for (let data in this.customer) {
        this.customer[data] = "";
      }
      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },
};
</script>
