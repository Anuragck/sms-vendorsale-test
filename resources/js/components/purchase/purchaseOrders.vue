<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="main" v-else>
    <div class="col-lg-12">
      <div class="card mb-4 x">
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
          <h5 class="m-0 font-weight-bold text-dark">Purchase Order</h5>
        </div>

        <div class="text-center mr-5">
          <label for="" class="">Select Vendor</label>
        </div>

        <div class="input-group mb-3 justify-content-center mt-1">
          <div class="w-50" style="">
            <div class="input-group justify-content-center">
              <!-- search vendor -->

              <multiselect
                v-model="vendor"
                tag-placeholder="Add this as new Vendor"
                placeholder="Search for a Vendor"
                label="name"
                :options="vendorsList"
                track-by="id"
                :options-limit="4"
                :disabled="this.edit"
              ></multiselect>

              <!-- search vendor end  -->
            </div>
          </div>
          <div class="input-group-append ml-4">
            <button
              class="btn btn-sm btn-primary rounded-pill"
              @click="getProductsByVendor()"
            >
              Search <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>

        <div
          class="text-center mr-5 pr-5 mt-5 pt-1"
          v-if="
            products_by_vendor.length == 0 && no_previous_transaction == true
          "
        >
          <h6>{{ welcomeTitle }}</h6>
        </div>

        <div
          class="text-center mr-5 pr-5 mt-5 pt-1 "
          v-if="
            products_by_vendor.length == 0 && no_previous_transaction == true
          "
        >
          <button
            type="button"
            class="btn btn-primary rounded-pill"
            @click="addMoreProduct()"
          >
            {{ "Add New Products" }}
          </button>
        </div>

        <!-------------------------->

        <div class="tableSection">
          <!-- item detals  -->

          <div
            class="table-responsive p-3"
            v-if="this.products_by_vendor.length != 0"
          >
            <table
              class="table table-sm align-items-center table-flush table-hover"
              id="salesReturnTable"
              ref="salesReturnTable"
            >
              <thead class="thead-light">
                <tr>
                  <th class="w-25">#</th>
                  <th>Item Name</th>

                  <th>Part No</th>
                  <th>Current Quantity</th>

                  <th>Purchase Quantiy</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(product, index) in vendor_products"
                  :key="product.id"
                >
                  <td>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        v-model="product.checkbox"
                        @change="product.purchase_qty = ''"
                      />
                      <label class="form-check-label">
                        {{ index + 1 }}
                      </label>
                    </div>
                  </td>
                  <td>
                    {{ product.name }}
                  </td>

                  <td>
                    {{ product.part_no }}
                  </td>
                  <td>
                    {{ parseInt(product.quantity) }}
                  </td>

                  <td>
                    <div class="form-group" style="width: 80px">
                      <input
                        type="number"
                        class="form-control"
                        name="sale_return"
                        min="1"
                        
                        oninput="validity.valid||(value='');"
                        v-model="product.purchase_qty"
                        :disabled="product.checkbox == false"
                      />
                    </div>
                  </td>
                </tr>
              </tbody>

              <tfoot></tfoot>
            </table>

            <div class="row justify-content-end mr-2 mt-3">
              <button
                type="button"
                class="btn btn-primary rounded-pill px-5"
                id="add-payment-button"
                @click="addToList()"
                :disabled="loading"
              >
                Add To List
                <i
                  class="fas fa-spinner fa-spin fa-fw"
                  v-if="loading == true"
                ></i>
              </button>
            </div>

            <!-- add  product -->

            <div
              class="row justify-content-end mr-2 mt-3 pb-2"
              v-if="add_more_products == false"
            >
              <button
                type="button"
                class="btn btn-primary rounded-pill"
                @click="addMoreProduct()"
              >
                {{ "Add New Products" }}
              </button>
            </div>
            <!-- add  product -->
          </div>

          <div
            class="row d-flex justify-content-end mr-5"
            v-if="add_more_products == true"
          >
            <a type="button" class="close" @click="clearMoreProducts()">
              <span aria-hidden="true" class="modal-title-custom text-danger"
                >&times;</span
              >
            </a>
          </div>
          <div
            class="row justify-content-center mt-3"
            v-if="add_more_products == true"
          >
            <div class="col-12">
              <div class="input-group mb-3 justify-content-center mt-1">
                <div class="w-50" style="">
                  <div class="input-group justify-content-center">
                    <!-- search vendor -->

                    <multiselect
                      v-model="product"
                      tag-placeholder="Add this as new Product"
                      placeholder="Search or add a Product"
                      :custom-label="({ name, quantity }) => `${name} - (${parseInt(quantity)})`"
                      :options="productsData"
                      :taggable="true"
                      @tag="addNewProduct"
                      track-by="id"
                      :options-limit="4"
                      :disabled="this.edit"
                    ></multiselect>

                    <!-- search vendor end  -->
                  </div>
                </div>
                <div class="w-25 ml-2" style="">
                  <div class="input-group justify-content-center">
                    <!-- search vendor -->

                    <input
                      type="number"
                      class="form-control"
                      placeholder="Enter Quantity"
                      name="quantity"
                      min="1"
                      v-model="purchase_qty"
                      
                      oninput="validity.valid||(value='');"
                    />

                    <!-- search vendor end  -->
                  </div>
                </div>
                <div class="input-group-append ml-4">
                  <button
                    class="btn btn-sm btn-primary rounded-pill"
                    @click="addNewToList()"
                  >
                    Add to Order List
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- add  product -->
        </div>
      </div>
    </div>

    <div class="col-lg-12" v-if="this.orderList.items.length != 0">
      <div class="card mb-2 x">
        <div class="text-center pt-4 pb-4">
          <h5>Purchase Order List</h5>
        </div>
        <div class="table-responsive p-3">
          <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col" class="text-center">Quantity</th>
                <th scope="col" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(product, index) in orderList.items"
                :key="product.product_id"
              >
                <th scope="row">{{ index + 1 }}</th>
                <td>{{ product.product_name }}</td>
                <td class="text-center">{{ product.order_qty }}</td>
                <td scope="row" class="trashIconContainer">
                  <i
                    class="far fa-trash-alt"
                    @click="removeFromArray(index)"
                  ></i>
                </td>
              </tr>
            </tbody>

            <tfoot></tfoot>
          </table>
        </div>

        <div class="form-group p-4">
          <label for="exampleFormControlTextarea1">Purchase Remarks</label>
          <textarea class="form-control" rows="2" v-model="remarks"></textarea>
        </div>
        <div class="modal-footer pt-4">
          <button
            type="button"
            class="btn btn-primary rounded-pill"
            @click="addPurchaseOrder()"
            id="add-sale-button"
            :disabled="loading"
          >
            {{ button_title }}
            <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
          </button>
        </div>
      </div>
    </div>

    <div
      id="addProduct"
      class="modal fade"
      tabindex="-1"
      data-backdrop="static"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <add-product :usergst="this.user.gst"></add-product>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["edit", "user"],
  data() {
    return {
      preLoader: false,
      loading: false,
      gst_user: this.usergst,
      title: "Add Purchase Order",
      toastTitle: "Purchase Order added successfully",
      welcomeTitle: "",
      no_previous_transaction: false,
      user_gst: this.user.gst,

      vendor_id: "",

      add_more_products: false,

      vendorsList: [],
      vendor: [],

      products_by_vendor: [],
      vendor_products: [],
      product: [],

      productsData: [],

      errors: [],
      remarks: "",
      orderList: {
        id: "",
        vendor_id: "",
        vendor_name: "",
        items: [],
        remarks: "",
      },
      purchase_qty: "",
    };
  },

  created() {
    this.getVendors();
    this.getProducts();
    var _this = this;
    bus.$on("product-added", function () {
      _this.getProducts();
    });
  },
  computed: {
    checkProduct: function () {
      if (this.orderList.items != []) {
        var sum = 0;
        this.orderList.items.forEach((e) => {
          sum = e.id;
        });
        return sum;
      }
    },

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
            this.vendor = this.vendorsList.find(
              (option) => option.id == this.edit_vendor_id
            );
          } else {
            this.vendor = [];
          }
        })
        .catch((err) => {
          console.log("vendor errs" + err);
        });
    },

    getProductsByVendor() {
      if (this.vendor.id == null) {
        Toast.fire({
          icon: "warning",
          title: "Select Vendor",
        });
        return false;
      }

      this.preLoader = true;
      this.clear_form_data();
      axios
        .get("get-items-by-vendor", {
          params: {
            id: this.vendor.id,
          },
        })
        .then((response) => {
          this.products_by_vendor = response.data;

          for (let item in this.products_by_vendor) {
            this.vendor_products.push({
              checkbox: false,

              id: this.products_by_vendor[item].id,
              name: this.products_by_vendor[item].name,
              part_no: this.products_by_vendor[item].part_no,
              quantity: this.products_by_vendor[item].quantity,
              purchase_qty: "",
            });
          }

          if (this.products_by_vendor.length == 0) {
            this.welcomeTitle = "No record found !";
            this.no_previous_transaction = true;
            this.preLoader = false;
            return false;
          } else {
            this.welcomeTitle = "";
            this.no_previous_transaction = false;
          }

          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
        });
    },
    getProducts() {
      axios
        .get("get-sale-product")
        .then((response) => {
          this.productsData = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    addMoreProduct() {
      this.add_more_products = true;
    },
    clearMoreProducts() {
      this.add_more_products = false;
      this.product = [];
    },

    addNewProduct() {
      $("#addProduct").modal("show");
    },

    addToList() {
      if (this.vendor_products.length != 0) {
        for (let item in this.vendor_products) {
          if (this.vendor_products[item].purchase_qty != "") {
            if (
              !this.orderList.items.some(
                (data) => data.product_id === this.vendor_products[item].id
              )
            ) {
              this.orderList.items.push({
                product_id: this.vendor_products[item].id,
                product_name: this.vendor_products[item].name,
                order_qty: this.vendor_products[item].purchase_qty,
                part_no: this.vendor_products[item].part_no,
                quantity: this.vendor_products[item].quantity,
              });

              this.vendor_products[item].checkbox = false;
              this.vendor_products[item].purchase_qty = "";
            } else {
              Toast.fire({
                icon: "warning",
                title: this.vendor_products[item].name + "already added",
              });
              return false;
            }
          }else{

            Toast.fire({
          icon: "warning",
          title: "Quantity field is requred",
        });
          }
        }

        // Toast.fire({
        //   icon: "success",
        //   title: "successfully added",
        // });
      }
    },
    removeFromArray(index) {
      this.orderList.items.splice(index, 1);
    },

    addNewToList() {
      if (this.product!= null && this.purchase_qty != "") {

        if (
          !this.orderList.items.some(
            (item) => item.product_id === this.product.id
          )
        ) {
          this.orderList.items.push({
            product_id: this.product.id,
            product_name: this.product.name,
            order_qty: this.purchase_qty,
            part_no: this.product.part_no,
            quantity: this.product.quantity,
          });
          this.product = [];
          this.purchase_qty = "";
        } else {
          Toast.fire({
            icon: "warning",
            title: this.product.name + "  already added",
          });
          this.product = [];
          this.purchase_qty = "";
          return false;
        }
      }else{

            Toast.fire({
          icon: "warning",
          title: "Product or Quantity field is missing",
        });
          }
    },

    addPurchaseOrder() {
      if (this.vendor != []) {
        this.orderList.vendor_id = this.vendor.id;

        this.orderList.remarks = this.remarks;
      } else {
        Toast.fire({
          icon: "warning",
          title: "Vendor details not available",
        });

        return false;
      }

      this.loading = true;
      axios
        .post("/admin/add-purchase-order", this.orderList)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
            this.clearMoreProducts();
            this.clear_form_data();
            this.vendor = [];
            this.products_by_vendor = [];
            bus.$emit("purchase-order-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });
            this.vendor = [];
            this.products_by_vendor = [];
            this.clear_form_data();
            this.clearMoreProducts();
          }
          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          if (err.response.data.errors) {
            Toast.fire({
              icon: "error",
              title: "Try Again Later",
            });
          }
          this.loading = false;
        });
    },

    clear_form_data() {
      for (let er in this.errors) {
        this.errors[er] = "";
      }

      this.vendor_id = "";
      this.vendor_products = [];

      this.orderList.vendor_id = "";
      this.orderList.id = "";
      this.orderList.vendor_name = "";
      this.orderList.remarks = "";
      this.orderList.items = [];
      this.remarks = "";
    },
  },

  watch: {
    vendor: function () {
      if (this.vendor != null) {
        this.vendor_id = this.vendor.id;
      }
      this.products_by_vendor = [];
      this.no_previous_transaction = false;
    },



  },
};
</script>
<style scoped>
.trashIconContainer {
  vertical-align: middle !important;
  padding-top: 10px;
  text-align: center;
}

.x {
  min-height: 70vh;
}
</style>
