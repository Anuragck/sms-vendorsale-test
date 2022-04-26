<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addpaymentLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_reward_sale_modal"
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
              <label for="customer" class="required">Select Customer</label>

              <!-- search component -->

              <model-list-select
                :list="this.customer_list"
                name="customer_id"
                v-model="rewardSale.customer_id"
                option-value="customer_id"
                :custom-text="customerNameAndMobile"
                placeholder="select customer"
                :isDisabled="this.edit"
              >
              </model-list-select>

              <!-- search component end -->
            </div>

            <small class="text-danger" v-if="errors.customer_id">
              {{ errors.customer_id[0] }}</small
            ><small class="text-danger"> {{ customerErrorMessage }}</small>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="product" class="required">Select Product</label>
              <model-list-select
                :list="this.item_list"
                name="item_id"
                v-model="rewardSale.item_id"
                option-value="id"
                :custom-text="productName"
                placeholder="select item"
              >
              </model-list-select>

              <small class="text-danger" v-if="errors.item_id">
                {{ errors.item_id[0] }}</small
              ><small class="text-danger"> {{ productErrorMessage }}</small>
            </div>
          </div>
        </div>

        <!-- customer detals  -->

        <div class="row p-3">
          <div class="col" v-if="this.customer_reward_point != ''">
            <div
              class="alert information-card-warning ml-auto w-100 text-center"
              role="alert"
            >
              <h4 class="font-weight-bold text-center">
                {{ customer_name.toUpperCase() }}
              </h4>
              <div class="text-gray-light">
                <i class="fas fa-gift fa-fw"></i> Reward Points:
                {{ customer_reward_point }}
              </div>
            </div>
            <div class="text-danger">
              {{ customer_reward_pointErrorMessage }}
            </div>
          </div>

          <div class="col" v-if="this.product_reward_point != ''">
            <div
              class="alert alert-success ml-auto w-100 text-center"
              role="alert"
            >
              <h4 class="font-weight-bold text-center">
                {{ product_name.toUpperCase() }}
              </h4>
              <div class="text-gray-light">
                <i class="fas fa-gift fa-fw"></i> Reward Points:
                {{ product_reward_point }}
              </div>
            </div>
          </div>
        </div>

        <!-- customer detals ends  -->

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="quantity"> Quantity</label>
              <input
                type="number"
                class="form-control"
                name="quantity"
                min="1"
                oninput="validity.valid||(value='');"
                v-model="rewardSale.quantity"
                @keydown="quantityErrorMessage = ''"
              />
            </div>

            <small class="text-danger"> {{ quantityErrorMessage }}</small>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          @click="addRewardSale()"
          class="btn btn-primary rounded-pill"
          :disabled="loading"
        >
          {{ button_title
          }}<i class="fas fa-check-double fa-fw" v-if="loading == false"></i>
          <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
        </button>
      </div>
    </div>
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
      loading: false,

      title: "Reward Sale",
      toastTitle: "Reward sale added successfully",
      customer_list: [],
      item_list: [],
      product_list: [],
      customer_name: "",
      product_name: "",
      customer_reward_point: "",
      product_reward_point: "",
      quantityErrorMessage: "",
      customerErrorMessage: "",
      productErrorMessage: "",
      customer_reward_pointErrorMessage: "",
      rewardSale: {
        id: "",
        customer_id: "",
        item_id: "",
        quantity: "1",
      },

      errors: [],
    };
  },

  created() {
    this.getcustomer();
    this.getProduct();
    var vm = this;
    bus.$on("reward-sale-deleted", function () {
      vm.getcustomer();
    });

    if (this.edit) {
      var _this = this;
      _this.customer_reward_point = "";
      _this.title = "Edit Reward Sale";
      _this.toastTitle = "Reward Sale Updated Successfully";
      bus.$on("edit-reward-sale", function (reward) {
        _this.rewardSale.id = reward.id;
        _this.rewardSale.customer_id = reward.customer["id"];

        _this.rewardSale.item_id = reward.product["id"];
        _this.rewardSale.quantity = reward.quantity;
        _this.getcustomer();
      });
    }
  },

  methods: {
    addRewardSale() {
      if (
        this.rewardSale.customer_id == "" &&
        this.rewardSale.item_id == "" &&
        this.rewardSale.quantity == ""
      ) {
        this.customerErrorMessage = "This field required";
        this.productErrorMessage = "This field Rrquired";
        this.quantityErrorMessage = "This field required";
        return false;
      }
      if (this.rewardSale.customer_id == "") {
        this.customerErrorMessage = "This field required";
        return false;
      }

      if (this.rewardSale.item_id == "") {
        this.productErrorMessage = "This field required";
        return false;
      }

      if (this.rewardSale.quantity == "") {
        this.quantityErrorMessage = "This field required";
        return false;
      }

      if (
        this.rewardSale.quantity * this.product_reward_point >
        this.customer_reward_point
      ) {
        this.quantityErrorMessage = "Insufficient Reward Points";
        return false;
      }
      this.loading = true;
      axios
        .post("/admin/add-rewardsale", this.rewardSale)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_reward_sale_modal.click();

            this.clear_form_data();
            this.getcustomer();
            this.customer_list = [];
            this.customer_reward_point = "";
            bus.$emit("reward-sale-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_reward_sale_modal.click();

            this.clear_form_data();
            this.getcustomer();
            this.customer_list = [];
            this.customer_reward_point = "";
            bus.$emit("reward-sale-added");
          }

          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    getProduct() {
      axios
        .get("/admin/get-products")
        .then((response) => {
          this.product_list = response.data;

          if (this.product_list.length != 0) {
            for (let item in this.product_list) {
              if (this.product_list[item].category_id == "2") {
                this.item_list.push({
                  id: this.product_list[item].id,
                  name: this.product_list[item].name,
                  reward_points: this.product_list[item].reward_points,
                });
              }
            }
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getcustomer() {
      axios
        .get("/admin/get-rewards")
        .then((response) => {
          this.customer_list = response.data;

          this.customerDetails();
        })
        .catch((err) => {
          console.log(err);
        });
    },

    // search component functions

    customerNameAndMobile(item) {
      return `${item.customer_name} - ${item.customer_mobile} `;
    },
    productName(item) {
      return `${item.name}`;
    },

    customerDetails() {
      if (this.customer_list.length != 0 && this.rewardSale.customer_id != "") {
        for (let item in this.customer_list) {
          if (
            this.customer_list[item].customer_id == this.rewardSale.customer_id
          ) {
            this.customer_name = this.customer_list[item].customer_name;

            if (this.edit) {
              this.customer_reward_point =
                this.customer_list[item].points +
                this.rewardSale.quantity * this.product_reward_point;
            } else {
              this.customer_reward_point = this.customer_list[item].points;
            }

            if (
              this.customer_reward_point != "" &&
              this.customer_reward_point < 500
            ) {
              this.customer_reward_pointErrorMessage =
                " Insufficient Reward Points !";
            } else {
              this.customer_reward_pointErrorMessage = " ";
            }
          }
        }
      }
    },

    productDetails() {
      if (this.product_list.length != 0 && this.rewardSale.item_id != "") {
        for (let item in this.product_list) {
          if (this.product_list[item].id == this.rewardSale.item_id) {
            this.product_name = this.product_list[item].name;
            this.product_reward_point = this.product_list[item].reward_points;
          }
        }
      }
    },

    clear_form_data() {
      this.customer_name = "";
      this.product_name = "";
      this.customer_reward_point = "";
      this.product_reward_point = "";
      this.quantityErrorMessage = "";
      this.customerErrorMessage = "";
      this.productErrorMessage = "";

      this.rewardSale.id = "";
      this.rewardSale.customer_id = "";
      this.rewardSale.item_id = "";
      this.rewardSale.quantity = "1";

      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },

  watch: {
    "rewardSale.customer_id": function () {
      this.customerDetails();
      this.customerErrorMessage = "";
    },
    "rewardSale.item_id": function () {
      this.productDetails();
      this.productErrorMessage = "";
    },
  },
  computed: {
    button_title() {
      if (this.loading == true) {
        return "Redeeming  ";
      } else {
        return "Redeem ";
      }
    },
  },
};
</script>
