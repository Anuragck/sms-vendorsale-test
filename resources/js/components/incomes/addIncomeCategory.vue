<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addincomecategoryLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_incomecategory_modal"
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
              <label for="incomecategoryName" class="required">Name</label>
              <input
                type="text"
                class="form-control"
                id="incomecategoryName"
                placeholder="Enter Name"
                name="name"
                v-model="incomecategory.name"
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
        @click="addIncomeCategory()"
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
      title: "Add Income Category ",
      toastTitle: "Income Category  added successfully",
      incomecategory: {
        id: "",
        name: "",
      },

      errors: [],
    };
  },

  created() {
    if (this.edit) {
      var _this = this;

      _this.title = "Edit Income Category";
      _this.toastTitle = "Income Category updated successfully";
      bus.$on("edit-incomecategory", function (incomecategory) {
        _this.clear_form_data();
        _this.incomecategory.id = incomecategory.id;
        _this.incomecategory.name = incomecategory.name;
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
    addIncomeCategory() {
      this.loading = true;
      axios
        .post("/admin/add-income-category", this.incomecategory)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.$refs.close_incomecategory_modal.click();
            this.clear_form_data();
            bus.$emit("incomecategory-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.$refs.close_incomecategory_modal.click();
            this.clear_form_data();
            bus.$emit("incomecategory-added");
          }
          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    clear_form_data() {
      for (let data in this.incomecategory) {
        this.incomecategory[data] = "";
      }
      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },
  },
};
</script>
