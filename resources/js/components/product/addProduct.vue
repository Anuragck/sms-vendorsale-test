<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addProductLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_product_modal"
        @click="clearData()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <form
      class="form-horizontal"
      method="POST"
      enctype="multipart/form-data"
      @submit.prevent="addProduct()"
    >
      <div class="modal-body">
        <div class="card-body">
          <div class="">
            <div class="form-group">
              <label for="category" class="required">Select Category</label>
              <select
                class="form-control"
                name="product_category"
                v-model="products.product_category"
                :disabled="edit"
              >
                <option value="">Select Category</option>
                <option
                  v-for="category in productCategories"
                  :key="category.id"
                  v-bind:value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
              <small class="text-danger" v-if="errors.product_category">{{
                errors.product_category[0]
              }}</small>
            </div>
            <div class="row">
              <div
                :class="
                  products.product_category === 3
                    ? 'col-lg-12 '
                    : 'col-lg-6 col-md-6'
                "
              >
                <div class="form-group" v-if="products.product_category">
                  <label for="productName" class="required">Product Name</label>
                  <input
                    type="text"
                    class="form-control"
                    id="productName"
                    placeholder="Enter Product Name"
                    v-model="products.name"
                    required
                  />
                  <small class="text-danger" v-if="errors.name">
                    {{ errors.name[0] }}</small
                  >
                </div>
              </div>
              <div class="col-lg-6 col-md-6">
                <div
                  class="form-group"
                  v-if="
                    products.product_category === 1 ||
                    products.product_category === 2
                  "
                >
                  <label for="openingQuantity">Opening Quantity</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="openingQuantity"
                    placeholder="Enter Opening Quantity"
                    v-model="products.opening_quantity"
                    :disabled="edit"
                  />
                </div>
              </div>
            </div>
            <!--row01 end-->
            <div class="row">
              <div class="col-lg-3 col-md-6">
                <div
                  class="form-group"
                  v-if="products.product_category === 1 && usergst == 1"
                >
                  <label for="purchaseQty" class="required"
                    >Select Gst Slab</label
                  >
                  <model-list-select
                    :list="this.gstList"
                    v-model="products.gst"
                    option-value="val"
                    :custom-text="gstSlab"
                    placeholder="select Gst"
                  >
                  </model-list-select>

                  <small class="text-danger"> </small>
                </div>
              </div>
              <div :class="products.product_category === 3 ? 'col-lg-12' : 'col-lg-3 col-md-6'">
                <div class="form-group" v-if="products.product_category === 1 || products.product_category === 3">
                  <label for="openingQuantity">Mrp</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    id="mrp"
                    min="0"
                    oninput="validity.valid||(value='');"
                    placeholder="Enter Mrp"
                    v-model="products.mrp"
                  />
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group" v-if="products.product_category === 1">
                  <label for="openingQuantity">Discount</label>
                  <div class="input-group">
                    <input
                      id="numberbox"
                      type="number"
                      step="any"
                      max="100"
                      min="0"
                      oninput="validity.valid||(value='');"
                      class="form-control"
                      v-model="products.discount"
                    />
                    <div class="input-group-append">
                      <span class="input-group-text"> % </span>
                    </div>
                  </div>
                  <small class="text-danger"></small>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="form-group" v-if="products.product_category === 1">
                  <label for="minimumQty">Minimum Quantity</label>
                  <input
                    type="number"
                    step="any"
                    min="0"
                    oninput="validity.valid||(value='');"
                    class="form-control"
                    id="minimumquantity"
                    placeholder="Enter Minimum Quantity"
                    v-model="products.minimum_qty"
                  />
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="form-group" v-if="products.product_category === 1">
                  <label for="minimumQty">Brand Name</label>
                  <input
                    type="text"
                    step="any"
                    class="form-control"
                    id="minimumquantity"
                    placeholder="Enter Brand Name"
                    v-model="products.brand"
                  />
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="form-group" v-if="products.product_category === 1">
                  <label for="minimumQty">Part No</label>
                  <input
                    type="text"
                    step="any"
                    class="form-control"
                    id="minimumquantity"
                    placeholder="Enter Part Number"
                    v-model="products.part_no"
                  />
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="form-group" v-if="products.product_category === 1">
                  <label for="minimumQty">HSN Code</label>
                  <input
                    type="text"
                    step="any"
                    class="form-control"
                    id="minimumquantity"
                    placeholder="Enter HSN Code"
                    v-model="products.hsn_code"
                  />
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="form-group" v-if="products.product_category === 1">
                  <label for="minimumQty">Location</label>
                  <input
                    type="text"
                    step="any"
                    class="form-control"
                    id="minimumquantity"
                    placeholder="Enter Location"
                    v-model="products.location"
                  />
                </div>
              </div>
            </div>
            <div class="form-group" v-if="products.product_category === 2">
              <label for="rewardPoints">Reward Points</label>
              <input
                type="number"
                class="form-control"
                id="rewardPoints"
                placeholder="Enter Reward Points"
                v-model="products.reward_points"
                :disabled="edit"
              />
            </div>
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
  props: ["edit", "usergst"],
  data() {
    return {
      title: "Add Product",
      productCategories: {},

      loading: false,

      products: {
        id: "",
        product_category: "",
        name: "",
        opening_quantity: "",
        reward_points: "",
        mrp: "",
        gst: "",
        discount: "",
        minimum_qty: "",

        brand: "",
        part_no: "",
        hsn_code: "",
        location: "",
      },
      errors: {},

      gstList: [
        {
          val: "0",
          name: "0%",
        },
        {
          val: 5,
          name: "5%",
        },

        {
          val: 12,
          name: "12%",
        },
        {
          val: 18,
          name: "18%",
        },
        {
          val: 28,
          name: "28%",
        },
      ],
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
    this.getCategories();

    if (this.edit) {
      var _this = this;
      _this.title = "Edit Product";
      bus.$on("edit-product", function (product) {
        _this.clearData();
        _this.products.product_category = product.category_id;
        _this.products.id = product.id;
        _this.products.name = product.name;

        _this.products.discount = product.discount;
        _this.products.minimum_qty = product.minimum_qty;

        _this.products.opening_quantity = product.opening_quantity;
        _this.products.reward_points = product.reward_points;

        _this.products.gst = parseFloat(product.gst);
        _this.products.mrp = product.mrp;

        _this.products.brand = product.brand;
        _this.products.part_no = product.part_no;
        _this.products.hsn_code = product.hsn_code;
        _this.products.location = product.location;
      });
    }
  },

  methods: {
    getCategories() {
      axios
        .get("product-categories")
        .then((response) => {
          this.productCategories = response.data;
        })
        .catch((err) => {});
    },

    addProduct() {
      if (this.edit) {
        this.products.id = this.products.id;
      }
      this.loading = true;
      axios
        .post("add-product", this.products)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: "Product Added successfully",
            });
            bus.$emit("product-added");
            this.$refs.close_product_modal.click();
            this.clearData();
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            bus.$emit("product-added");
            this.$refs.close_product_modal.click();
            this.clearData();
          }

          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          this.errors = error.response.data.errors;
          console.log("errors");
        });
    },

    clearData() {
      this.products.product_category = "";
      this.products.name = "";
      this.products.opening_quantity = "";
      this.products.reward_points = "";
      this.errors = {};
      this.mrp = "";
      this.gst = "";
      this.products.minimum_qty = "";
      this.products.discount = "";
    },

    gstSlab(item) {
      return `${item.name}`;
    },
  },
};
</script>
