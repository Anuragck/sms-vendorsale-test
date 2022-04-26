<template>
<div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addexpensecategoryLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_expensecategory_modal"
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
              <label for="expensecategoryName" class="required">Name</label>
              <input
                type="text"
                class="form-control"
                id="expensecategoryName"
                placeholder="Enter Name"
                name="name"
                v-model="expensecategory.name"
              />
            </div>

            <small class="text-danger" v-if="errors.name">
              {{ errors.name[0] }}</small
            >
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="button"
        @click="addExpenseCategory()"
        class="btn btn-primary rounded-pill"
        :disabled="loading"
      >
        {{ button_title }}
        <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
      </button>
    </div>
</div>
</template>
<script>
export default {
  props: ["edit"],
  data() {
    return {
      loading: false,

      title: "Add Expensecategory",
      toastTitle: "Expensecategory added successfully",
      expensecategory: {
        id: "",
        name: "",
      },

      errors: [],
    };
  },

  created() {
    if (this.edit) {
      var _this = this;

      _this.title = "Edit Expensecategory";
      _this.toastTitle = "Expensecategory updated successfully";
      bus.$on("edit-expensecategory", function (expensecategory) {
        _this.clear_form_data();
        _this.expensecategory.id = expensecategory.id;
        _this.expensecategory.name = expensecategory.name;
      });

    }
 var _this=this;
  bus.$on("new-expense-category-data", function (newExpense) {
        _this.expensecategory.name=newExpense;
      });
  },

  methods: {
    addExpenseCategory() {
      this.loading = true;
      axios
        .post("/admin/add-expense-category", this.expensecategory)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_expensecategory_modal.click();
            this.clear_form_data();
            bus.$emit("expensecategory-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_expensecategory_modal.click();
            this.clear_form_data();
            bus.$emit("expensecategory-added");
          }

          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    clear_form_data() {
      for (let data in this.expensecategory) {
        this.expensecategory[data] = "";
      }
      for (let er in this.errors) {
        this.errors[er] = "";
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
