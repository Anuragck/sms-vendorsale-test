<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addAdjustmentLabel">
        {{ "Stock Adjustment" }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_adjustment_modal"
        @click="clearAdjustData()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <!-- <form
          class="form-horizontal"
          method="POST"
          enctype="multipart/form-data"
        @submit.prevent="addAdjustment()"
        >-->
    <div class="modal-body">
      <div class="card-body">
        <div class="form-group">
          <label for="adjustitem" class="required">Select Product</label>

          <!-- saleitem search component-->

          <model-list-select
            :list="this.adjustProductList"
            v-model="adjustments.product_id"
            option-value="id"
            :custom-text="adjustmentItemName"
            placeholder="select product"
            name="product_id"
          >
          </model-list-select>

          <!-- saleitem search component end-->
          <small class="text-danger" v-if="errors.product_id">
            {{ "The product name field is required" }}</small
          >
        </div>

        <div class="form-group">
          <label for="category" class="required"
            >Select Adjustment Action</label
          >
          <select
            class="form-control"
            id="adjustmentAction"
            name="adjustment_action"
            v-model="adjustments.adjustment_action"
          >
            <option value="">Select Adjustment Action</option>
            <option value="1">Increase Quantity</option>
            <option value="2">Decrease Quantity</option>
          </select>
          <small class="text-danger" v-if="errors.adjustment_action">
            {{ errors.adjustment_action[0] }}</small
          >
        </div>

        <div class="form-group">
          <label for="qty" class="required">Enter Quantity</label>
          <input
            type="number"
            class="form-control"
            id="adjustmentQuantity"
            placeholder="Enter Quantity"
            name="adjustment_quantity"
            v-model="adjustments.adjustment_quantity"
          />
          <small class="text-danger" v-if="errors.adjustment_quantity">
            {{ errors.adjustment_quantity[0] }}</small
          >
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="submit"
        class="btn btn-primary rounded-pill"
        @click="addAdjustment"
        :disabled="loading"
      >
        {{ button_title }}
        <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
      </button>
    </div>
    <!-- </form>-->
  </div>
</template>
<script>
import { ModelListSelect } from "vue-search-select";

export default {
  components: {
    ModelListSelect,
  },

  data() {
    return {
      loading: false,
      product_id: "",
      productsForAdjustment: [],
      adjustProductList: [],

      adjustments: {
        product_id: "",
        adjustment_action: "",
        adjustment_quantity: "",
      },
      errors: {},
    };
  },

  created() {
    this.getProductsAdjustments();
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
    getProductsAdjustments() {
      axios
        .get("get-products")
        .then((response) => {
          this.productsForAdjustment = response.data;

          if (this.productsForAdjustment.length != 0) {
            for (let item in this.productsForAdjustment) {
              if (
                this.productsForAdjustment[item].category_id == "1" ||
                this.productsForAdjustment[item].category_id == "2"
              ) {
                this.adjustProductList.push({
                  category: this.productsForAdjustment[item].category,
                  category_id: this.productsForAdjustment[item].category_id,
                  created_at: this.productsForAdjustment[item].created_at,
                  deleted_at: this.productsForAdjustment[item].deleted_at,
                  id: this.productsForAdjustment[item].id,
                  name: this.productsForAdjustment[item].name,
                  opening_quantity:
                    this.productsForAdjustment[item].opening_quantity,
                  quantity: this.productsForAdjustment[item].quantity,
                  reward_points: this.productsForAdjustment[item].reward_points,
                  updated_at: this.productsForAdjustment[item].updated_at,
                  user_id: this.productsForAdjustment[item].user_id,
                });
              }
            }
          }
        })
        .catch((err) => {
          console.log("products errs for adjustments".err);
        });
    },

    adjustmentItemName(item) {
      return `${item.name}`;
    },

    addAdjustment() {
      this.loading = true;
      axios
        .post("add-adjustment", this.adjustments)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: "Product Adjusted Successfully",
            });
            bus.$emit("adjustment-added");
            this.$refs.close_adjustment_modal.click();
            this.clearAdjustData();
          }

     if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

                  bus.$emit("adjustment-added");
            this.$refs.close_adjustment_modal.click();
            this.clearAdjustData();
          
          }



          
          this.loading = false;
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
          this.loading = false;
          console.log("errors");
        });
    },

    clearAdjustData() {
      this.adjustments.product_id = "";
      this.adjustments.adjustment_action = "";
      this.adjustments.adjustment_quantity = "";

      this.errors = {};
    },
  },

  watch: {},
};
</script>
