<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="col-lg-12" v-else>
    <div class="card" v-if="close == false && closed == false">
      <div class="card-header">
        <h5 class="font-weight-bold float-left">Today Account Details</h5>
        <h5 class="font-weight-bold float-right">{{ today | myDate }}</h5>
      </div>
      <div class="card-body">
        {{ closing_details }}
        <div class="form-group">
          <label for="exampleFormControlInput1">CASH</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Opening Cash"
            v-model="opening.opening_cash"
          />
        </div>

        <div class="form-group">
          <label for="exampleFormControlInput1">BANK</label>
          <input
            type="number"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Opening Bank"
            v-model="opening.opening_bank"
          />

        </div>

        <button
          type="button"
          class="btn btn-primary float-right"
          @click="addOpening()"
        >
          Save Changes
        </button>
      </div>
    </div>

    <div class="card" v-if="close == true && closed == false">
      <div class="card-header">
        <h5 class="font-weight-bold float-left">Today Account Details</h5>
        <h5 class="font-weight-bold float-right">{{ today | myDate }}</h5>
      </div>
      <div class="card-body">
        <h5>
          <span class="font-weight-bold">Opening Cash : </span
          >{{ closing_details.opening_cash }}
        </h5>
        <h5>
          <span class="font-weight-bold">Opening Bank : </span>
          {{ closing_details.opening_bank }}
        </h5>
        <hr />

        <button
          type="button"
          class="btn btn-primary float-right"
          @click="closeAccount()"
        >
          Close Account
        </button>
      </div>
    </div>

    <div class="card" v-if="closed == true">
      <div class="card-header">
        <h5 class="font-weight-bold float-left">Today Account Details</h5>
        <h5 class="font-weight-bold float-right">{{ today | myDate }}</h5>
      </div>
      <div class="card-body ">
<div class="row px-3">


<div class="col-lg-6 col-md-6 mt-1 ">
<div class="card py-3 px-2 shadow  rounded">
 <h4 class="text-info font-weight-bold">Previous Openings</h4>
 <h6>
          <span class="font-weight-bold">Opening Cash : </span
          >{{ closing_details.opening_cash }}
        </h6>
        <h6>
          <span class="font-weight-bold">Opening Bank : </span>
          {{ closing_details.opening_bank }}
        </h6>
</div>
</div>


<div class="col-lg-6 col-md-6 mt-1">
<div class="card py-3 px-2 shadow  rounded">
 <h4 class="text-danger font-weight-bold">Today Account Closed</h4>
        <h6>
          <span class="font-weight-bold">Closing Cash : </span
          >{{ closing_details.closing_cash }}
        </h6>
        <h6>
          <span class="font-weight-bold">Closing Bank : </span>
          {{ closing_details.closing_bank }}
        </h6>
</div>
</div>
</div>
<hr>

<div class="row px-3">


<div class="col-lg-6 col-md-6 mt-2 ">
<div class="card py-3 px-2 shadow  rounded">
   <h5 class="font-weight-bold">Today Incomes</h5>
        <h6>
          <span class="font-weight-bold">Cash : </span
          >{{ closing_details.today_cash_income }}
        </h6>
        <h6>
          <span class="font-weight-bold">Bank : </span>
          {{ closing_details.today_bank_income }}
        </h6>
</div>
</div>
<div class="col-lg-6 col-md-6 mt-2">
<div class="card py-3 px-2 shadow  rounded">
   <h5 class="font-weight-bold">Today Expenses</h5>
        <h6>
          <span class="font-weight-bold">Cash : </span
          >{{ closing_details.today_cash_expense }}
        </h6>
        <h6>
          <span class="font-weight-bold">Bank : </span>
          {{ closing_details.today_bank_expense }}
        </h6>
</div>
</div>
</div>
        <hr />

<div class="row px-3">


<div class="col-lg-6 col-md-6 mt-2 ">
<div class="card py-3 px-2 shadow  rounded">
     <h6>
          <span class="font-weight-bold">Today Cash Sale : </span
          >{{ closing_details.today_cash_sale }}
        </h6>
        <h6>
          <span class="font-weight-bold">Today Bank Sale : </span>
          {{ closing_details.today_bank_sale }}
        </h6>
</div>
</div>
<div class="col-lg-6 col-md-6 mt-2">
<div class="card py-3 px-2 shadow  rounded">
    <h6>
          <span class="font-weight-bold">Today Cash Purchase : </span
          >{{ closing_details.today_cash_purchase }}
        </h6>
        <h6>
          <span class="font-weight-bold">Today Bank Purchase : </span>
          {{ closing_details.today_bank_purchase }}
        </h6>
</div>
</div>
</div>


        <hr />


      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  data() {
    return {
      today: moment().format("YYYY-MM-DD"),
      opening: {
        opening_cash: "",
        opening_bank: "",
      },
      closed: false,
      preLoader: false,

      closing_details: {},
      id: "",

      close: false,

      errors: {},
    };
  },

  methods: {
    addOpening() {
      axios
        .post("add-opening", this.opening)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: "Opening Balance Successfully Added",
            });
            this.getClosing();
            this.clearFieldData();
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });
          }
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
        });
    },

    getClosing() {
      this.preLoader = true; //the loading begin
      axios
        .get("get-closings")
        .then((res) => {
          this.closing_details = res.data;
          if (res.data.id) {
            this.id = res.data.id;
          }
          if (res.data.closing_status == 2) {
            this.closed = true;
          }
          if (res.data.closing_status == 1) {
            this.close = true;
          } else {
            this.close = false;
          }
          this.preLoader = false; //the loading end
        })
        .catch((err) => {
          console.log(err);
          this.preLoader = false; //the loading end
        });
    },

    closeAccount() {
      Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes !",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("add-closing", { id: this.id }).then((response) => {
            if (response.data == "success") {
              Toast.fire({
                icon: "success",
                title: "Account Closed successfully",
              });
              this.getClosing();
            }

            if (response.data == "failed") {
              Toast.fire({
                icon: "error",
                title: "Some Error Occurred,Please Try Again Later",
              });
            }
          });
        }
      });
    },

    clearFieldData() {
      this.opening.opening_cash = "";
      this.opening.opening_bank = "";
    },
  },

  created() {
    this.getClosing();
    console.log(this.id);
  },
};
</script>
