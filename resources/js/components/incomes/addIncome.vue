<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addIncomeLabel">
        {{ "Income" }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_income_modal"
        @click="clearIncomeData()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="incomecategory" class="required"
                >Select Income Category</label
              >

              <!-- saleitem search component-->

              <model-list-select
                :list="this.incomeCategories"
                v-model="income.income_category_id"
                option-value="id"
                :custom-text="incomeCategoryName"
                placeholder="select Income Category"
                name="income_category_id"
              >
              </model-list-select>

              <!-- saleitem search component end-->
              <small class="text-danger" v-if="errors.income_category_id">
                {{ "The Income Category name field is required" }}</small
              >
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="date" class="required">Select Date</label>
              <input
                type="date"
                class="form-control"
                name="date"
                v-model="income.date"
              />
              <small class="text-danger" v-if="errors.date">
                {{ errors.date[0] }}</small
              >
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="paytype" class="required">Select Pay Type</label>
              <select
                class="form-control"
                id="incomePaytype"
                name="income_pay_type"
                v-model="income.pay_type"
              >
                <option value="">Select Pay Type</option>
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
              </select>
              <small class="text-danger" v-if="errors.pay_type">
                {{ "The Income Pay Type field is required" }}</small
              >
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="amount" class="required">Enter Amount</label>
              <input
                type="number"
                class="form-control"
                id="incomeAmount"
                placeholder="Enter Amount"
                name="income_amount"
                v-model="income.amount"
              />
              <small class="text-danger" v-if="errors.amount">
                {{ "The Income Amount field is required" }}</small
              >
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="remarks">Enter Remarks</label>
              <input
                type="text"
                class="form-control"
                id="incomeRemarks"
                placeholder="Enter Remarks"
                name="income_remarks"
                v-model="income.remarks"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="submit"
        class="btn btn-primary rounded-pill"
        @click="addIncome"
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
import moment from "moment";

export default {
  props: ["edit"],

  components: {
    ModelListSelect,
  },

  data() {
    return {
      loading: false,

      incomeCategories: [],
      toastTitle: "Income added successfully",

      income: {
        id: "",
        income_category_id: "",
        date: moment().format("YYYY-MM-DD"),
        amount: "",
        pay_type: "",
        remarks: "",
      },
      errors: {},
    };
  },

  created() {
    if (this.edit) {
      var _this = this;

      _this.toastTitle = "Income updated successfully";
      bus.$on("edit-income", function (income) {
        _this.clearIncomeData();
        _this.income.id = income.id;
        _this.income.income_category_id = income.income_category_id;

        _this.income.date = moment(income.created_at).format("YYYY-MM-DD");

        _this.income.amount = income.amount;
        _this.income.pay_type = income.pay_type;
        _this.income.remarks = income.remarks;
      });
    }
    this.getIncomeCategories();
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
    getIncomeCategories() {
      axios
        .get("get-income-categories")
        .then((response) => {
          this.incomeCategories = response.data;
        })
        .catch((err) => {
          console.log(" errors in incomecategories".err);
        });
    },

    incomeCategoryName(item) {
      return `${item.name}`;
    },

    addIncome() {
      (this.loading = true),
        axios
          .post("add-income", this.income)
          .then((response) => {
            if (response.data == "success") {
              Toast.fire({
                icon: "success",
                title: this.toastTitle,
              });
              bus.$emit("income-added");
              this.$refs.close_income_modal.click();
              this.clearIncomeData();
            }
            if (response.data == "failed") {
              Toast.fire({
                icon: "error",
                title: "Some Error Occurred,Please Try Again Later",
              });
              bus.$emit("income-added");
              this.$refs.close_income_modal.click();
              this.clearIncomeData();
            }
            this.loading = false;
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
            this.loading = false;
            console.log("errors");
          });
    },

    clearIncomeData() {
      this.income.income_category_id = "";
      this.income.remarks = "";
      this.income.pay_type = "";
      this.income.amount = "";
      this.errors = {};
    },
  },

  watch: {},
};
</script>
