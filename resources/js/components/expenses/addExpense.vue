<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addExpenseLabel">
        {{ "Expense" }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_expense_modal"
        @click="clearExpenseData()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body">
        <div class="row">
          <div class="col" v-if="newCategoryName == false">
            <div class="form-group">
              <label for="expensecategory" class="required"
                >Select Expense Category</label
              >

              <!-- saleitem search component-->

              <multiselect
                v-model="newExpense"
                tag-placeholder="Add this as new Expense Category"
                placeholder="Search or add a Expense Category"
                label="name"
                :options="expenseCategories"
                :taggable="true"
                @tag="newExpenseCategory"
                track-by="id"
                :options-limit="4"
                :disabled="this.edit"
              ></multiselect>

              <!-- saleitem search component end-->
              <small class="text-danger" v-if="errors.expense_category_id">
                {{ "The Expense Category name field is required" }}</small
              >
            </div>
          </div>

          <div class="col" v-if="newCategoryName == true">
            <div class="form-group">
              <label for="expensecategory" class="required"
                >Expense Category Name</label
              >
              <input
                type="text"
                class="form-control"
                name="category_name"
                v-model="expense.category_name"
              />
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="date" class="required">Select Date</label>
              <input
                type="date"
                class="form-control"
                name="date"
                v-model="expense.date"
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
                id="expensePaytype"
                name="expense_pay_type"
                v-model="expense.pay_type"
              >
                <option value="">Select Pay Type</option>
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
              </select>
              <small class="text-danger" v-if="errors.pay_type">
                {{ "The Payment Type field is required" }}</small
              >
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="amount" class="required">Enter Amount</label>
              <input
                type="number"
                class="form-control"
                id="expenseAmount"
                placeholder="Enter Amount"
                name="expense_amount"
                v-model="expense.amount"
              />
              <small class="text-danger" v-if="errors.amount">
                {{ "The Expense Amount field is required" }}</small
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
                id="expenseRemarks"
                placeholder="Enter Remarks"
                name="expense_remarks"
                v-model="expense.remarks"
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
        @click="addExpense"
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

      newExpense: [],
      newCategoryName: false,
      edit_expense_category_id: "",

      expenseCategories: [],

      toastTitle: "Expense added successfully",

      expense: {
        id: "",
        expense_category_id: "",
        date: moment().format("YYYY-MM-DD"),
        amount: "",
        pay_type: "",
        remarks: "",
        category_name: "",
      },

      errors: {},
    };
  },

  created() {
    if (this.edit) {
      var _this = this;

      _this.toastTitle = "Expense updated successfully";
      bus.$on("edit-expense", function (expense) {


        _this.edit_expense_category_id = expense.expense_category_id;
        _this.expense.id = expense.id;
 _this.getExpenseCategories();
_this.clearExpenseData();
        _this.expense.date = moment(expense.created_at).format("YYYY-MM-DD");
        _this.expense.amount = expense.amount;
        _this.expense.pay_type = expense.pay_type;
        _this.expense.remarks = expense.remarks;
      });
    }
    this.getExpenseCategories();
  },

  methods: {
    getExpenseCategories() {
      axios
        .get("get-expense-categories")
        .then((response) => {
          this.expenseCategories = response.data;

          if (this.edit) {
            this.newExpense = this.expenseCategories.find(
              (option) => option.id == this.edit_expense_category_id
            );
          } else {
            this.newExpense = "";
          }

        })
        .catch((err) => {
          console.log(" errors in expensecategories".err);
        });
    },

    newExpenseCategory(newExpense) {
      this.newCategoryName = true;
      this.expense.category_name = newExpense;
      this.newExpense = [];
    },

    addExpense() {
      this.loading = true;
      axios
        .post("add-expense", this.expense)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
            bus.$emit("expense-added");
            this.$refs.close_expense_modal.click();
            this.clearExpenseData();
          }
          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            bus.$emit("expense-added");
            this.$refs.close_expense_modal.click();
            this.clearExpenseData();
          }

          this.loading = false;
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
          this.loading = false;
          console.log("errors");
        });
    },

    clearExpenseData() {
      this.expense.expense_category_id = "";
      this.expense.remarks = "";
      this.expense.pay_type = "";
      this.expense.amount = "";
      this.errors = {};

      this.newCategoryName = false;
      this.newExpense = [];
    },
  },

  watch: {
    newExpense: function () {
      if (this.newExpense != null) {
        this.expense.expense_category_id = this.newExpense.id;
      }
    },
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
};
</script>
