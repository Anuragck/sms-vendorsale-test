<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addvendorLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_vendor_modal"
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
              <label for="vendorName" class="required">Name</label>
              <input
                type="text"
                class="form-control"
                id="vendorName"
                placeholder="Enter Name"
                name="name"
                v-model="vendor.name"
              />
            </div>

            <small class="text-danger" v-if="errors.name">
              {{ errors.name[0] }}</small
            >
          </div>

          <div class="col">
            <div class="form-group">
              <label for="vendorMobile" class="required">Mobile</label>
              <input
                type="number"
                class="form-control"
                id="vendorMobile"
                placeholder="Enter Mobile"
                name="mobile"
                v-model="vendor.mobile"
                :max="10"
              />
            </div>

            <small class="text-danger" v-if="errors.mobile">
              {{ errors.mobile[0] }}</small
            >
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="gst number">GST Number</label>
              <input
                type="text"
                class="form-control"
                id="gstNo"
                placeholder="Enter GST Number"
                name="gst_no"
                v-model="vendor.gst_no"
              />
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="vendorLocation">Location</label>
              <input
                type="text"
                class="form-control"
                id="vendorLocation"
                placeholder="Enter Location"
                name="location"
                v-model="vendor.location"
              />
            </div>
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label for="vendorAddress 1">Address 1</label>
            <input
              type="text"
              class="form-control"
              id="vendorAddress 1"
              placeholder="Enter Address 1"
              name="address_1"
              v-model="vendor.address_1"
            />
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label for="vendorAddress 2">Address 2</label>
            <input
              type="text"
              class="form-control"
              id="vendorAddress 2"
              placeholder="Enter Address 2"
              name="address_2"
              v-model="vendor.address_2"
            />
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="vendorState">State</label>
              <select name="state" class="form-control" v-model="vendor.state">
                <option value="">Choose...</option>
                <option value="KERALA">Kerala</option>
                <option value="KARNATAKA">Karnataka</option>
                <option value="TAMILNADU">Tamil Nadu</option>
                <option value="GOA">Goa</option>
              </select>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="vendorPincode">Pincode</label>
              <input
                type="number"
                class="form-control"
                id="vendorPincode"
                placeholder="Enter Pincode"
                name="pincode"
                v-model="vendor.pincode"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button
        type="button"
        @click="addVendor()"
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
      title: "Add Vendor",
      toastTitle: "Vendor added successfully",
      vendor: {
        id: "",
        name: "",
        mobile: "",
        gst_no: "",
        location: "",
        address_1: "",
        address_2: "",
        state: "",
        pincode: "",
      },

      errors: [],
    };
  },

  created() {
    if (this.edit) {
      var _this = this;

      _this.title = "Edit Vendor";
      _this.toastTitle = "Vendor updated successfully";
      bus.$on("edit-vendor", function (vendor) {
        _this.clear_form_data();
        _this.vendor.id = vendor.id;
        _this.vendor.name = vendor.name;
        _this.vendor.mobile = vendor.mobile;
        _this.vendor.location = vendor.location;
        _this.vendor.gst_no = vendor.gst_no;
        _this.vendor.address_1 = vendor.address_1;
        _this.vendor.address_2 = vendor.address_2;
        _this.vendor.state = vendor.state;
        _this.vendor.pincode = vendor.pincode;
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
    addVendor() {
      this.loading = true;
      axios
        .post("add-vendor", this.vendor)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_vendor_modal.click();
            this.clear_form_data();
            bus.$emit("vendor-added");
          }

  if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

             this.$refs.close_vendor_modal.click();
            this.clear_form_data();
            bus.$emit("vendor-added");
          
          }



          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err.response.data.errors;
        });
    },

    clear_form_data() {
      for (let data in this.vendor) {
        this.vendor[data] = "";
      }
      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },
};
</script>
