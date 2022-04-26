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
          <h6 class="m-0 font-weight-bold text-dark">Sales Return</h6>
        </div>

        <label for="" class="text-center mr-5 pr-5">Enter Invoice Number</label>
        <div class="input-group mb-3 justify-content-center">
          <div class="w-25">
            <!-- dropDown -->
            <div class="input-group justify-content-center">
              <input
                type="text"
                name="invoice_no"
                id=""
                placeholder="Enter invoice no"
                v-model="invoice_no"
                class="form-control"
                @change="invoiceErrMsg = ''"
              />
              <small class="text-danger" v-if="errors.invoice_no">
                {{ errors.invoice_no[0] }}</small
              >
              <small class="text-danger"> {{ invoiceErrMsg }}</small>
            </div>

            <!-- dropDown end -->
          </div>
          <div class="input-group-append ml-4">
            <button
              class="btn btn-sm btn-primary rounded-pill"
              @click="getSales()"
            >
              Search <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>

        <div class="text-center mr-5 pr-5 mt-5 pt-5" v-if="invoice == ''">
          <h6>{{ welcomeTitle }}</h6>
        </div>

        <!-------------------------->

        <div class="tableSection" v-if="invoice != ''">
          <!-- item detals  -->

          <!-- Earnings (Annual) Card Example -->
          <div class="col-12 mb-4 justify-content-center">
            <div class="card h-100">
              <div class="card-body">
                <!-- customer details -->
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>Customer Name</label>
                      <h6>
                        {{ sale[0].sale_details.customer.name }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Customer mobile</label>
                      <h6>
                        {{ sale[0].sale_details.customer.mobile }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label>Invoice No</label>
                      <h6>
                        {{ sale[0].sale_details.id }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label>Invoice date</label>
                      <h6>
                        {{ sale[0].sale_details.invoice_date }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Vehicle</label>
                      <h6>
                        {{ sale[0].sale_details.vehicle }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Register No</label>
                      <h6>
                        {{ sale[0].sale_details.register_no }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>
                </div>
                <!-- second row  -->

                <hr />

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>GST Bill</label>

                      <h6 v-if="sale[0].sale_details.gst_bill == 1">Yes</h6>
                      <h6 v-if="sale[0].sale_details.gst_bill == 0">No</h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Bill Type</label>
                      <h6 v-if="sale[0].sale_details.bill_type == 1">Credit</h6>
                      <h6 v-if="sale[0].sale_details.bill_type == 2">Cash</h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Total Amount</label>
                      <h6>
                        {{ sale[0].sale_details.total_amount }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Total Discount</label>
                      <h6>
                        {{ sale[0].sale_details.total_discount }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Net Amount</label>
                      <h6>
                        {{
                          sale[0].sale_details.total_amount -
                          sale[0].sale_details.total_discount
                        }}
                      </h6>
                      <small class="text-danger"> </small>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="return_date">Date</label>
                      <input
                        type="date"
                        class="form-control inputWidth"
                        id="return_date"
                        name="return_date"
                        v-model="sales_return.return_date"
                      />
                    </div>

                    <small class="text-danger" v-if="errors.return_date">
                      {{ errors.return_date[0] }}</small
                    >
                  </div>
                </div>

                <!-- customer details ends-->
              </div>
            </div>
          </div>
          <div
            class="row justify-content-center"
            v-if="sale[0].sale_return_count != 0"
          >
            <!-- <button
             
              type="button"
              @click="showReturns()"
              class="btn btn-primary rounded-pill px-5"
              :disabled="loading"
            >
              Sales return history
            </button> -->

            <h6 class="text-danger">
              This invoice is already updated, please select another record !
            </h6>
          </div>
          <!-- sales return table -->
          <!-- <div class="table-responsive p-3" v-if="this.show_returns">
            <table
              class="table table-sm align-items-center table-flush table-hover"
            >
              <thead class="thead-light">
                <tr>
                  <th class="">#</th>
                  <th>Item Name</th>

                  <th>Rate</th>
                  <th>Quantity</th>
                  <th>Total Amount</th>
                  <th>Total Discount</th>
                  <th>Gross Amount</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(returnItem, index) in sale_return_details.items"
                  :key="returnItem.id"
                >
                  <td>
                    {{ index + 1 }}
                  </td>
                  <td>
                    {{ returnItem.product_name }}
                  </td>

                  <td>
                    {{ returnItem.rate }}
                  </td>
                  <td>
                    {{ parseInt(returnItem.quantity) }}
                  </td>
                  <td>
                    {{ returnItem.total }}
                  </td>
                  <td>
                    {{ returnItem.discount }}
                  </td>
                  <td>
                    {{ returnItem.gross_amount }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div> -->
          <!-- sales return table -->

          <div
            class="table-responsive p-3"
            v-if="this.sales_return.items.length != 0"
          >
            <table
              class="table table-sm align-items-center table-flush table-hover"
              id="salesReturnTable"
              ref="salesReturnTable"
            >
              <thead class="thead-light">
                <tr>
                  <th class="">#</th>
                  <th>Item Name</th>

                  <th>Rate</th>
                  <th>Quantity</th>
                  <th>Total Amount</th>
                  <th>Total Discount</th>
                  <th>Gross Amount</th>
                  <th>Return Quantiy</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="(sale, index) in sales_return.items" :key="sale.id">
                  <td>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        v-model="sale.checkbox"
                        :disabled="sale.category_id != 1"
                      />
                      <label class="form-check-label">
                        {{ index + 1 }}
                      </label>
                    </div>
                  </td>
                  <td>
                    {{ sale.product_name }}
                  </td>

                  <td>
                    {{ sale.rate }}
                  </td>
                  <td>
                    {{ parseInt(sale.quantity) }}
                  </td>
                  <td>
                    {{ sale.total }}
                  </td>
                  <td>
                    {{ sale.discount }}
                  </td>
                  <td>
                    {{ sale.gross_amount }}
                  </td>

                  <td>
                    <div class="form-group" style="width: 80px">
                      <input
                        type="number"
                        class="form-control"
                        name="sale_return"
                        min="1"
                        :max="sale.quantity"
                        oninput="validity.valid||(value='');"
                        v-model="sale.return_qty"
                        :disabled="
                          sale.checkbox == false || sale.category_id != 1
                        "
                      />
                    </div>
                  </td>
                </tr>
              </tbody>

              <tfoot>
                <tr>
                  <td colspan="8">
                    <div class="row justify-content-end mr-2 mt-3">
                      <button
                        type="button"
                        @click="addSalesReturn()"
                        class="btn btn-primary rounded-pill px-5"
                        id="add-payment-button"
                        :disabled="loading || sale[0].sale_return_count != 0"
                      >
                        {{ button_title }}
                        <i
                          class="fas fa-spinner fa-spin fa-fw"
                          v-if="loading == true"
                        ></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div v-else>
            <h6>No Track Record Found !</h6>
          </div>
        </div>
      </div>
    </div>

    <!--<div v-else class="Height"></div>-->
  </div>
</template>

<script>
export default {
  data() {
    return {
      preLoader: false,
      button_title: "Add Returns",
      toastTitle: "added successfully !",
      welcomeTitle: "Select Sale To Continue . . .",
      loading: false,
      show_returns: false,
      sale: [],
      sale_return_details: [],
      sales_invoice: "",
      invoice_no: "",
      invoiceErrMsg: "",
      search: false,
      errors: {},

      invoice: "",

      sales_return: {
        id: "",
        customer_id: "",
        gst_bill: "",
        bill_type: "",
        sale_invoice_no: "",
        return_date: "",

        items: [],

        total_discount: "",
      },
    };
  },

  created() {},
  methods: {
    getSales() {
      if (this.invoice_no == "") {
        this.invoiceErrMsg = "This field required";
        return false;
      }
      this.clear_field();
      this.preLoader = true;
      axios
        .get("/admin/get-sale-by-invoice", {
          params: {
            invoice_no: this.invoice_no,
          },
        })
        .then((response) => {
          this.sale = response.data;

          if (this.sale[0].sale_details == null) {
            this.welcomeTitle = "No record found !";

            this.preLoader = false;
            return false;
          }
          var sale_items = this.sale[0].sale_details.items;
          this.sale_return_details = this.sale[0].sale_return_details;
          this.invoice = this.sale[0].sale_details.id;

          this.sales_return.customer_id = this.sale[0].sale_details.customer_id;
          this.sales_return.gst_bill = this.sale[0].sale_details.gst_bill;
          this.sales_return.bill_type = this.sale[0].sale_details.bill_type;
          this.sales_return.sale_invoice_no = this.sale[0].sale_details.id;
          this.sales_return.total_discount =
            this.sale[0].sale_details.total_discount;

          if (sale_items.length != 0) {
            for (let item in sale_items) {
              this.sales_return.items.push({
                checkbox: false,
                return_qty: "",
                category_id: sale_items[item].category_id,
                product_id: sale_items[item].product.id,
                product_name: sale_items[item].product.name,
                quantity: sale_items[item].quantity,

                rate: sale_items[item].rate,
                discount: sale_items[item].discount,
                gst: sale_items[item].gst,
                discount: sale_items[item].discount,
                gross_amount: sale_items[item].gross_amount,
                total: sale_items[item].total,
              });
            }
          }

          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
        });
    },

    addSalesReturn() {
      if (this.isReturnQuantityNull == false) {
        Toast.fire({
          icon: "error",
          title: "Return quantity field is empty !",
        });

        return false;
      }

      this.loading = true;
      axios
        .post("/admin/add-sale-return", this.sales_return)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });

            this.clear_field();
            this.invoice_no = "";
            this.welcomeTitle = "Select Sale To Continue . . .";
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.clear_field();
            this.invoice_no = "";
            this.welcomeTitle = "Select Sale To Continue . . .";
          }

          if (response.data == "error") {
            Toast.fire({
              icon: "error",
              title: "Check quantity Status !",
            });
          }

          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    clear_field() {
      this.invoice = "";

      this.sale = [];
      this.sales_return.customer_id = "";
      this.sales_return.gst_bill = "";
      this.sales_return.bill_type = "";
      this.sales_return.sale_invoice_no = "";
      this.sales_return.total_discount = "";
      this.sales_return.return_date = "";
      this.sales_return.items = [];
      for (let err in this.errors) {
        this.errors[err] = "";
      }
    },

    showReturns() {
      this.show_returns = !this.show_returns;
    },
  },

  computed: {
    isReturnQuantityNull: function () {
      if (this.sales_return.items != []) {
        var return_qty = "";
        this.sales_return.items.forEach((e) => {
          return_qty = e.return_qty;
        });

        if (return_qty != "") {
          return true;
        } else return false;
      }
    },
  },

  watch: {
    "sales_return.items"(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.salesReturnTable).DataTable({
          pageLength: 5,
          paging: false,
          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
          bSort: false,
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.salesReturnTable).DataTable({
      pageLength: 5,
      paging: false,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],

      bSort: false,
    });
  },
};
</script>

<style scoped>
.Height {
  min-height: 360px;
}

.x {
  min-height: 80vh;
}
.inputWidth {
  max-width: 155px;
}
</style>
