
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
        ref="close_purchase_order_modal"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body">
<!--
<div class="text-center mr-5">
       <label for="" class="">Select Vendor</label>
</div>
-->
        <div class="input-group mb-3 justify-content-center">
 <div class="w-75" style="">
         <div class="input-group justify-content-center">

              <!-- search vendor -->

              <multiselect
                v-model="vendors"
                tag-placeholder="Add this as new Vendor"
                placeholder="Search or add a Vendor"
                label="name"
                :options="vendorsList"

                track-by="id"
                :options-limit="4"
                :disabled="this.edit || viewOnly"
              ></multiselect>

              <small class="text-danger" v-if="errors.vendor_id">
                {{ errors.vendor_id[0] }}</small
              >
              <!-- search vendor end  -->
         </div>
 </div>
            <div class="input-group-append ml-4">
              <button class="btn btn-sm btn-primary rounded-pill" @click="getProductsByVendor()">
                Search <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>


        <div class="text-center mr-5 pr-5 mt-5 pt-5">
          <h6>{{ welcomeTitle }}</h6>
        </div>

   <multiselect
                v-model="product"
                tag-placeholder="Add this as new Product"
                placeholder="Search or add a Product"
                label="name"

                :options="products_by_vendor"

                track-by="id"
                :options-limit="4"
                :disabled="this.edit || viewOnly"
              ></multiselect>
{{ products_by_vendor }}

      </div>
    </div>
    <div class="modal-footer">
      <button
        type="button"
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
  props: ["edit", "usergst", "viewOnly"],
  data() {
    return {
      loading: false,
      gst_user: this.usergst,
      title: "Add Purchase Order",
      toastTitle: "Purchase Order added successfully",
      welcomeTitle: "Select Vendor To Continue . . .",

      vendor_id: "",

      vendorsList: [],
      vendors: [],

products_by_vendor:[],

productsWithQty:'',
product:[],

      errors: [],
    };
  },

  created() {
    this.getVendors();
    if (this.edit) {
      var _this = this;

      _this.title = "Edit Purchase Order";
      _this.toastTitle = "Purchase Order updated successfully";
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
    getVendors() {
      axios
        .get("get-vendors")
        .then((response) => {
          this.vendorsList = response.data;

          if (this.edit || this.viewOnly) {
            this.vendors = this.vendorsList.find(
              (option) => option.id == this.edit_vendor_id
            );
          } else {
            this.vendors = [];
          }
        })
        .catch((err) => {
          console.log("vendors errs".err);
        });
    },

 getProductsByVendor() {
      this.preLoader = true;
      axios
        .get("get-items-by-vendor", {
          params: {
            id: this.vendors.id,
          },
        })
        .then((response) => {
          this.products_by_vendor = response.data;


          if (this.products_by_vendor == 0) {
            this.welcomeTitle = "No record found !";

            this.preLoader = false;
            return false;
          }

          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
        });
    },

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

            this.$refs.close_purchase_order_modal.click();
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_purchase_order_modal.click();
          }

          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err.response.data.errors;
        });
    },

    clear_form_data() {
      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },

  watch: {
    vendors: function () {
      if (this.vendors != null) {
        this.vendor_id = this.vendors.id;
      }
    },
  },
};
</script>
