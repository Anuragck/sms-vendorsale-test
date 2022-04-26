<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addIncomeLabel">
        {{ "Closing Summary- "}}{{ closing_date|myDate }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_closing_modal"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-6 col-lg-3">
            <div
              class="
                border-primary
                shadow-sm
                border
                rounded
                text-center
                py-2
                mb-3
              "
            >
              <p class="text-primary mb-0 font-weight-bold">Opening Cash</p>
              <h6 class="card-title text-primary mb-1">{{ opening_cash }}</h6>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div
              class="
                border-primary border
                shadow-sm
                rounded
                text-center
                py-2
                mb-3
              "
            >
              <p class="text-primary mb-0 font-weight-bold">Opening Bank</p>
              <h6 class="card-title text-primary mb-1">{{ opening_bank }}</h6>
            </div>
          </div>

          <div class="col-6 col-lg-3">
            <div
              class="
                border-success border
                shadow-sm
                rounded
                text-center
                py-2
                mb-3
              "
            >
              <p class="text-success mb-0 font-weight-bold">Closing Bank</p>
              <h6 class="card-title text-success mb-1">{{ closing_bank }}</h6>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div
              class="
                border-success border
                shadow-sm
                rounded
                text-center
                py-2
                mb-3
              "
            >
              <p class="text-success mb-0 font-weight-bold">Clsosing Cash</p>
              <h6 class="card-title text-success mb-1">{{ closing_cash }}</h6>
            </div>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-lg-6 rounded">
            <div class="box effect1 p-4">
              <h6>
                <span class="font-weight-bold"> Total Opening Balance: </span
                ><span
                  >{{ parseFloat(total_opening_balance).toFixed(2) }}
                </span>
              </h6>
              <h6>
                <span class="font-weight-bold"> Total Closing Balance: </span
                ><span>{{ parseFloat(total_closing_balance).toFixed(2) }}</span>
              </h6>
            </div>
          </div>

          <div class="col-lg-6 rounded">
            <div class="box effect1 p-4">
              <h6>
                <span class="font-weight-bold"> Cash Receipt :</span
                ><span>{{ today_cash_receipt }}</span>
              </h6>
              <h6>
                <span class="font-weight-bold"> Bank Receipt :</span
                ><span>{{ today_bank_receipt }}</span>
              </h6>
            </div>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-lg-6 rounded">
            <div class="box effect1 p-4">
              <h6>
                <span class="font-weight-bold"> Cash Expenses :</span
                ><span>{{ today_cash_expense }}</span>
              </h6>
              <h6>
                <span class="font-weight-bold"> Bank Expenses :</span
                ><span>{{ today_bank_expense }}</span>
              </h6>
            </div>
          </div>
          <div class="col-lg-6 rounded">
            <div class="box effect1 p-4">
              <h6>
                <span class="font-weight-bold"> Cash Incomes :</span
                ><span>{{ today_cash_income }}</span>
              </h6>
              <h6>
                <span class="font-weight-bold"> Bank Incomes :</span
                ><span>{{ today_bank_income }}</span>
              </h6>
            </div>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-lg-6 rounded">
            <div class="box effect1 p-4">
              <h6>
                <span class="font-weight-bold"> Cash Purchase :</span
                ><span>{{ today_cash_purchase }}</span>
              </h6>
              <h6>
                <span class="font-weight-bold"> Bank Purchase :</span
                ><span>{{ today_bank_purchase }}</span>
              </h6>
            </div>
          </div>
          <div class="col-lg-6 rounded">
            <div class="box effect1 p-4">
              <h6>
                <span class="font-weight-bold"> Cash Sales :</span
                ><span>{{ today_cash_sale }}</span>
              </h6>
              <h6>
                <span class="font-weight-bold"> Bank Sales :</span
                ><span>{{ today_bank_sale }}</span>
              </h6>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="submit"
        class="btn btn-primary rounded-pill"
        data-dismiss="modal"
        aria-label="Close"
      >
        Close
      </button>
    </div>
    <!-- </form>-->
  </div>
</template>
<script>
export default {
  data() {
    return {
      closing_bank: "",
      closing_cash: "",
      closing_date: "",

      opening_bank: "",
      opening_cash: "",

      today_bank_expense: "",
      today_cash_expense: "",

      today_bank_income: "",
      today_cash_income: "",

      today_bank_purchase: "",
      today_cash_purchase: "",

      today_bank_receipt: "",
      today_cash_receipt: "",

      today_bank_sale: "",
      today_cash_sale: "",
      total_opening_balance: "",
      total_closing_balance: "",
    };
  },

  created() {
    var _this = this;
    bus.$on("view-closings", function (close) {
      _this.total_opening_balance =
        parseFloat(close.opening_bank) + parseFloat(close.opening_cash);
      _this.total_closing_balance =
        parseFloat(close.closing_cash) + parseFloat(close.closing_bank);
      _this.opening_cash = close.opening_cash;
      _this.opening_bank = close.opening_bank;
      _this.closing_cash = close.closing_cash;
      _this.closing_bank = close.closing_bank;

_this.closing_date=close.closing_date;
      _this.today_bank_expense = close.today_bank_expense;
      _this.today_cash_expense = close.today_cash_expense;

      _this.today_bank_income = close.today_bank_income;
      _this.today_cash_income = close.today_cash_income;

      _this.today_bank_purchase = close.today_bank_purchase;
      _this.today_cash_purchase = close.today_cash_purchase;

      _this.today_bank_receipt = close.today_bank_receipt;
      _this.today_cash_receipt = close.today_cash_receipt;

      _this.today_bank_sale = close.today_bank_sale;
      _this.today_cash_sale = close.today_bank_sale;
    });
  },
  computed: {},
  methods: {},

  watch: {},
};
</script>
<style scoped>
.box {
  background: #fff;
}
/*==================================================
 * Effect 1
 * ===============================================*/
.effect1 {
  box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
    rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
}
</style>
