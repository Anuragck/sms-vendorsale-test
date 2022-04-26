<template>
  <div>
    <div class="modal-header bg-primary">
      <h5 class="modal-title modal-title-custom" id="addcustomerLabel">
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_sale_modal"
        @click="clear_form_data()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="card-body" id="salesForm">
        <div class="row" v-if="newCustomer == false">
          <div class="col">
            <div class="form-group">
              <label for="customer" class="required">Select Customer</label>

              <!-- search component -->
              <multiselect
                v-model="customer"
                tag-placeholder="Add this as new Customer"
                placeholder="Search or add a customer"
                label="name"
                :options="customer_list"
                :custom-label="customLabel"
                :taggable="true"
                @tag="addTag"
                track-by="mobile"
                :options-limit="4"
                :disabled="this.edit || viewOnly"
              ></multiselect>
              <small class="text-danger" v-if="errors.customer_id">
                {{ errors.customer_id[0] }}</small
              >
            </div>
          </div>
        </div>

        <div class="row d-flex justify-content-end" v-if="newCustomer == true">
          <a type="button" class="close" @click="clearNewCustomer()">
            <span aria-hidden="true" class="modal-title-custom text-danger"
              >&times;</span
            >
          </a>
        </div>
        <div class="row" v-if="newCustomer == true">
          <div class="col">
            <div class="form-group">
              <label class="">Customer Name</label>
              <input
                type="text"
                name="name"
                id=""
                placeholder="Enter Name"
                v-model="sale.name"
                class="form-control"
              />
              <small class="text-danger" v-if="errors.name">
                {{ errors.name[0] }}</small
              >
              <small class="text-danger"> {{ newCustomerNameErrMsg }}</small>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label class="">Enter Phone Number</label>
              <input
                type="phone"
                name="mobile"
                id=""
                placeholder="Enter Mobile Number"
                v-model="sale.mobile"
                class="form-control"
              />
              <small class="text-danger" v-if="errors.mobile">
                {{ errors.mobile[0] }}</small
              >
              <small class="text-danger"> {{ newCustomerMobileErrMsg }}</small>
            </div>
          </div>
          <div class="col" v-if="user == 1">
            <div class="form-group">
              <label class="">Enter GST Number</label>

              <input
                type="text"
                name=""
                id=""
                placeholder="Enter GST Number"
                v-model="sale.gst_no"
                class="form-control"
              />
            </div>
          </div>
        </div>

        <!--row-start-->
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="form-group">
              <label for="category" class="required">Select Bill Type</label>
              <select
                class="form-control"
                id="bill_type"
                v-model="sale.bill_type"
                name="bill_type"
                :disabled="reportView"
              >
                <option value="">Select Bill Type</option>

                <option value="2">Cash</option>
                <option value="1">Credit</option>
              </select>
            </div>
            <small class="text-danger" v-if="errors.bill_type">
              {{ errors.bill_type[0] }}</small
            >
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="form-group">
              <label for="invoice_date">Date</label>
              <input
                type="date"
                class="form-control"
                id="invoice_date"
                name="invoice_date"
                v-model="sale.date"
                :disabled="reportView"
              />
            </div>

            <small class="text-danger" v-if="errors.invoice_date">
              {{ errors.invoice_date[0] }}</small
            >
          </div>

          <div class="col-lg-3 col-md-6" v-if="vehiclecheck">
            <div class="form-group">
              <label for="vehicle">Vehicle</label>
              <input
                type="text"
                class="form-control"
                placeholder="Enter Name"
                name="vehicle"
                v-model="sale.vehicle"
                :disabled="reportView"
              />
            </div>

            <small class="text-danger" v-if="errors.vehicle">
              {{ errors.vehicle[0] }}</small
            >
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="form-group">
              <label for="register_no">Register No</label>
              <input
                type="text"
                class="form-control"
                placeholder="Enter Number"
                name="register_no"
                v-model="sale.register_no"
                :disabled="reportView"
              />
            </div>

            <small class="text-danger" v-if="errors.register_no">
              {{ errors.register_no[0] }}</small
            >
          </div>
        </div>
        <!--row-end--->
        <hr />

        <div class="row" v-if="reportView == false">
          <div class="col-lg-8 col-md-8 d-flex justify-content-start mb-1">
            <button
              type="button"
              class="btn btn-sm rounded"
              @click="showServiceCard()"
              :class="
                serviceShowCard ? 'btn-primary active' : 'btn-outline-primary '
              "
            >
              Add Service
              <i
                class="fas fa-plus fa-fw fa-sm"
                v-if="serviceShowCard == false"
              ></i>
              <i
                class="fas fa-arrow-down fa-fw fa-sm"
                v-if="serviceShowCard"
              ></i>
            </button>

            <button
              type="button"
              class="btn rounded btn-sm ml-1"
              @click="showSaleCard()"
              :class="
                saleShowCard ? 'btn-primary active' : 'btn-outline-primary'
              "
            >
              Add Sale
              <i
                class="fas fa-plus fa-fw fa-sm"
                v-if="saleShowCard == false"
              ></i>
              <i class="fas fa-arrow-down fa-fw fa-sm" v-if="saleShowCard"></i>
            </button>
          </div>

          <div
            class="col-lg-2 col-md-2 mt-1 d-flex justify-content-end"
            v-if="user == 1 && reportView == false"
          >
            <span
              class="form-check-label font-weight-bold mr-1"
              for="flexCheckDefault"
            >
              GST Bill
            </span>
            <label class="switch">
              <input
                type="checkbox"
                checked
                v-model="gst_bill"
                @click="checkBox"
              />
              <span class="slider round"></span>
            </label>
          </div>

          <!-- whole sale  -->

          <div
            class="col-lg-2 col-md-2 mt-1 d-flex justify-content-end"
            v-if="user == 1 && reportView == false"
          >
            <span
              class="form-check-label font-weight-bold mr-1"
              for="flexCheckDefault"
            >
              Wholesale
            </span>
            <label class="switch">
              <input
                type="checkbox"
                checked
                v-model="wholesale"
                @click="wholesaleCheckBox"
              />
              <span class="slider round"></span>
            </label>
          </div>
          <!-- whole sale checkBox end -->
        </div>

        <!-- service row -->

        <div class="card pb-1" v-if="serviceShowCard">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="Service" class="required">Select Service</label>

                  <!-- service search component -->

                  <model-list-select
                    :list="this.serviceList"
                    v-model="saleItem"
                    name="service"
                    option-value="id"
                    :custom-text="service"
                    placeholder="select item"
                  >
                  </model-list-select>

                  <!-- service search component end-->
                </div>
                <small class="text-danger"> {{ saleItemErrorMessage }}</small>
              </div>
            </div>

            <div class="row">
              <!-- gst slab  -->

              <div class="col-lg-4" v-if="this.user == 1 && this.gst_bill == 1">
                <div class="form-group">
                  <label class="required">Select GST Slab</label>
                  <select class="form-control" name="gst" v-model="gst">
                    <option value="">Select GST Slab</option>
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="12">12%</option>
                    <option value="18">18%</option>
                    <option value="28">28%</option>
                  </select>
                  <small class="text-danger">{{ gstErrorMessage }}</small>
                </div>
              </div>
              <!-- gst slab end -->
              <div class="col-lg-4 col-md-6">
                <div class="form-group">
                  <label for="service_rate" class="required">Rate</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    name="service_rate"
                    min="0"
                    oninput="validity.valid||(value='');"
                    v-model="sale_rate_per_quantity"
                  />
                </div>

                <small class="text-danger"> {{ rateErrorMessage }}</small>
              </div>
              <div
                class="col-lg-2 col-md-6"
                v-if="this.user == 1 && this.gst_bill == 1"
              >
                <div class="form-group">
                  <label for="discount">Discount</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    name="discount"
                    min="0"
                    oninput="validity.valid||(value='');"
                    v-model="discount"
                  />
                </div>

                <small class="text-danger"> {{ discountErrorMessage }}</small>
              </div>
              <div class="col-lg-2 col-md-12 mt-4 pt-2">
                <button
                  type="button"
                  class="btn rounded btn-primary float-right"
                  @click="addToArray()"
                >
                  Add To List <i class="fas fa-clipboard-list fa-fw"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- service row  end-->

        <!-- sale row -->

        <div class="card pb-1" v-if="saleShowCard">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="saleitem" class="required">Select Item</label>

                  <!-- saleitem search component-->

                  <model-list-select
                    :list="this.saleList"
                    v-model="saleItem"
                    option-value="id"
                    :custom-text="saleItemName"
                    placeholder="select item"
                  >
                  </model-list-select>

                  <!-- saleitem search component end-->
                </div>
                <small class="text-danger"> {{ saleItemErrorMessage }}</small>
              </div>
            </div>

            <div class="row">
              <!-- gst slab  -->

              <div
                class="col-lg-3 col-md-6"
                v-if="this.user == 1 && this.gst_bill == 1"
              >
                <div class="form-group">
                  <label class="required">Select GST Slab</label>
                  <select class="form-control" name="gst" v-model="gst">
                    <option value="">Select GST Slab</option>
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="12">12%</option>
                    <option value="18">18%</option>
                    <option value="28">28%</option>
                  </select>
                  <small class="text-danger">{{ gstErrorMessage }}</small>
                </div>
              </div>
              <!-- gst slab end -->

              <div class="col-lg-2 col-md-6">
                <div class="form-group">
                  <label for="item_quantity">Quantity</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    name="item_quantity"
                    min="1"
                    oninput="validity.valid||(value='');"
                    v-model="saleQuantity"
                    @input="saleQuantityErrorMessage = ''"
                  />
                </div>

                <small class="text-danger" v-if="saleQuantityErrorMessage">
                  {{ saleQuantityErrorMessage }}</small
                >
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <label for="rate_per_quantity" class="required"
                    >Rate per quantity</label
                  >
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    name="rate_per_quantity"
                    min="0"
                    oninput="validity.valid||(value='');"
                    v-model="sale_rate_per_quantity"
                  />
                </div>

                <small class="text-danger"> {{ rateErrorMessage }}</small>
              </div>

              <!-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa -->
              <div class="col-lg-2 col-md-6">
                <div class="form-group">
                  <label for="discount">Total Rate</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    v-model="total_item_rate"
                    disabled
                  />
                </div>
              </div>

              <div class="col-lg-2 col-md-6">
                <div class="form-group">
                  <label for="discount">Discount</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    name="discount"
                    min="0"
                    oninput="validity.valid||(value='');"
                    v-model="discount"
                  />
                </div>

                <small class="text-danger"> {{ discountErrorMessage }}</small>
              </div>

              <div class="col-lg-2 col-md-6">
                <div class="form-group">
                  <label for="discount">Pay Amount</label>
                  <input
                    type="number"
                    step="any"
                    class="form-control"
                    v-model="total_item_pay_amount"
                    disabled
                  />
                </div>

                <small class="text-danger"> {{ discountErrorMessage }}</small>
              </div>

              <!-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa -->
              <div class="col-lg-2 col-md-12 mt-4 pt-2">
                <button
                  type="button"
                  class="btn rounded btn-primary float-right"
                  @click="addToArray()"
                >
                  Add To List <i class="fas fa-clipboard-list fa-fw"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- sale row  end-->

        <div class="row mt-4">
          <div class="col-md-12">
            <h5>Item Details</h5>
            <div class="table-responsive mt-3 mb-1">
              <!--  -->
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Index</th>
                          <th scope="col">Type</th>
                          <th scope="col">Item</th>
                          <th scope="col text-right">Quantity</th>
                          <th scope="col text-right">Rate</th>
                          <th
                            scope="col text-right"
                            v-if="this.user == 1 && this.gst_bill == 1"
                          >
                            GST
                          </th>
                          <th scope="col text-right">Total Rate</th>
                          <th scope="col text-right">Discount</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, index) in sale.items" :key="item.id">
                          <td scope="row" class="trashIconContainer">
                            {{ index + 1 }}
                          </td>
                          <td>
                            <input
                              type="text"
                              class="form-control"
                              v-model="item.type"
                              disabled
                            />
                          </td>
                          <td>
                            <input
                              type="text"
                              class="form-control"
                              v-model="item.item_name"
                              disabled
                            />
                          </td>
                          <td>
                            <input
                              type="number"
                              step="any"
                              min="1"
                              class="form-control text-right"
                              v-model="item.quantity"
                              disabled
                            />
                          </td>
                          <td>
                            <input
                              type="number"
                              step="any"
                              min="1"
                              class="form-control text-right"
                              v-model="item.rate"
                              disabled
                            />
                          </td>

                          <td v-if="item.gst != ''">
                            <input
                              readonly="readonly"
                              type="number"
                              step="any"
                              class="form-control text-right"
                              :value="item.gst"
                              disabled
                            />
                          </td>

                          <td>
                            <input
                              readonly="readonly"
                              type="number"
                              step="any"
                              class="form-control text-right"
                              :value="item.rate * item.quantity"
                              disabled
                            />
                          </td>

                          <td>
                            <input
                              readonly="readonly"
                              type="number"
                              step="any"
                              class="form-control text-right"
                              :value="item.discount"
                              disabled
                            />
                          </td>

                          <td scope="row" class="trashIconContainer">
                            <i
                              class="far fa-trash-alt"
                              @click="removeFromArray(index)"
                            ></i>
                          </td>
                        </tr>

                        <tr v-if="this.sale.items.length == 0">
                          <td
                            colspan="9"
                            v-if="this.user == 1 && this.gst_bill == 1"
                          >
                            <p class="text-center light-danger p-2">
                              No products added/available.
                            </p>
                          </td>
                          <td colspan="8" v-else>
                            <p class="text-center light-danger p-2">
                              No products added/available.
                            </p>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr
                          v-if="this.sale.items.length != 0"
                          class="table-borderless"
                        >
                          <td
                            colspan="7"
                            class="text-right pt-3"
                            v-if="this.user == 1 && this.gst_bill == 1"
                          >
                            Total Discount &#x20b9;:
                          </td>

                          <td colspan="6" class="text-right pt-3" v-else>
                            Total Discount &#x20b9;:
                          </td>

                          <td class="text-right" style="width: 250px">
                            <span> {{ netdiscount }}/- </span>
                          </td>
                        </tr>

                        <!-- qqqqqqqqqqqqqqqqqqqqqqqqqqq  -->

                        <tr
                          v-if="this.sale.items.length != 0"
                          class="table-borderless"
                        >
                          <td
                            colspan="7"
                            class="text-right pt-3"
                            v-if="this.user == 1 && this.gst_bill == 1"
                          >
                            Round of Discount &#x20b9;:
                          </td>

                          <td colspan="6" class="text-right pt-3" v-else>
                            Round of Discount &#x20b9;:
                          </td>

                          <td class="text-right" style="width: 250px">
                            <input
                              type="number"
                              step="any"
                              class="form-control text-right"
                              min="0"
                              oninput="validity.valid||(value='');"
                              v-model="sale.roundoff"
                              :disabled="reportView"
                            />
                          </td>
                        </tr>

                        <!-- qqqqqqqqqqqqqqqqqqqqqqqqqqq  -->
                        <tr
                          v-if="this.sale.items.length != 0"
                          class="table-borderless"
                        >
                          <td
                            colspan="7"
                            class="text-right py-2"
                            v-if="this.user == 1 && this.gst_bill == 1"
                          >
                            Net Pay &#x20b9;:
                          </td>
                          <td colspan="6" class="text-right py-2" v-else>
                            Net Pay &#x20b9;:
                          </td>
                          <td class="text-right py-2 paymentclass">
                            {{ netPayAmout - netdiscount - sale.roundoff }}/ -
                          </td>
                        </tr>
                        <tr
                          v-if="
                            this.sale.bill_type == 2 &&
                            this.sale.items.length != 0
                          "
                          class="table-borderless"
                        >
                          <td
                            colspan="7"
                            class="text-right pt-3"
                            v-if="this.user == 1 && this.gst_bill == 1"
                          >
                            Select Payment Type:
                          </td>
                          <td colspan="6" class="text-right pt-3" v-else>
                            Select Payment Type:
                          </td>

                          <td class="text-right">
                            <div class="form-group">
                              <select
                                class="form-control"
                                v-model="sale.pay_type"
                                name="pay_type"
                                :disabled="reportView"
                                @change="payTypeErrorMessage = ''"
                              >
                                <option value="">Select Type</option>

                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                              </select>
                            </div>
                            <small
                              class="text-danger"
                              v-if="payTypeErrorMessage != ''"
                            >
                              {{ payTypeErrorMessage }}</small
                            >
                          </td>
                        </tr>
                        <!-- <tr>
                          <td colspan="5" class="text-right">Total</td>
                          <td class="text-right">0.00</td>
                        </tr> -->
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!--  -->
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer" v-if="reportView == false">
      <button
        type="button"
        class="btn btn-primary rounded-pill"
        @click="addSale()"
        id="add-sale-button"
        :disabled="loading"
      >
        {{ button_title }}
        <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
      </button>
    </div>
  </div>
</template>
<script>
import html2pdf from "html2pdf.js";
import { ModelListSelect } from "vue-search-select";
export default {
  components: {
    ModelListSelect,
  },
  props: ["edit", "viewOnly", "usergst", "vehicle"],
  data() {
    return {
      loading: false,
      user: this.usergst,
      vehiclecheck: this.vehicle,
      title: "Add Sale",
      toastTitle: "sale added successfully",
      saleShowCard: false,
      serviceShowCard: false,
      sale_rate_per_quantity: "",

      customer: [],
      newCustomer: false,
      edit_customer_id: "",

      gst: "",
      gst_bill: "",
      wholesale: "",
      gstErrorMessage: "",
      discount: "0",
      item_discount_percentage: "",
      total_item_rate: "0",
      total_item_pay_amount: "0",
      discountErrorMessage: "",
      saleItem: "",
      saleQuantity: "1",
      sale_item_category: "",
      saleItemErrorMessage: "",
      rateErrorMessage: "",
      saleQuantityErrorMessage: "",
      payTypeErrorMessage: "",

      newCustomerNameErrMsg: "",
      newCustomerMobileErrMsg: "",

      saleList: [],
      serviceList: [],
      product_list: [],
      customer_list: [],
      sale: {
        id: "",
        customer_id: "",
        bill_type: "",
        vehicle: "",
        register_no: "",
        date: "",
        total_discount: "0",
        roundoff: "0",
        gst_bill: "",
        wholesale: "",
        pay_type: "",

        name: "",
        mobile: "",
        gst_no: "",

        items: [],
      },
      reportView: false,

      errors: {},
    };
  },
  created() {
    this.getProduct();
    this.getcustomer();

    if (this.edit) {
      var _this = this;

      _this.title = "Edit Sale";
      _this.toastTitle = "Sale updated successfully";
      bus.$on("edit-sale", function (sale) {
        _this.edit_customer_id = sale.customer_id;

        _this.getcustomer();

        _this.clearField();
        _this.showItemList(sale);
      });
    }

    if (this.viewOnly) {
      var _this = this;
      this.user = 1;

      _this.reportView = true;
      _this.title = "Sale Card";
      _this.toastTitle = "Sale updated successfully";
      bus.$on("view-sale", function (sale) {
        _this.clearField();
        _this.showItemList(sale);

        _this.edit_customer_id = sale.customer_id;
        _this.getcustomer();
        //
      });
    }
  },
  methods: {
    addSale() {
      if (this.sale.items.length == 0) {
        Toast.fire({
          icon: "error",
          title: " No products added",
        });

        return false;
      }

      if (this.user == 1 && this.gst_bill == 1) {
        this.sale.gst_bill = " 1";
      } else {
        this.sale.gst_bill = "0";
      }
      if (this.wholesale == true) {
        this.sale.wholesale = " 1";
      } else {
        this.sale.wholesale = "0";
      }

      if (this.sale.bill_type == 2 && this.sale.pay_type == "") {
        this.payTypeErrorMessage = "This field required";
        return false;
      }
      if (
        this.newCustomer == true &&
        (this.sale.name == "" || this.sale.mobile == "")
      ) {
        if (this.sale.name == "") {
          this.newCustomerNameErrMsg = "This field is required";
        }
        if (this.sale.mobile == "") {
          this.newCustomerMobileErrMsg = "This field is required";
        }
        return false;
      }
      this.sale.total_discount = parseFloat(this.netdiscount);
      this.loading = true;

      axios
        .post("/admin/add-sale", this.sale)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
            this.getcustomer();
            this.$refs.close_sale_modal.click();
            this.clear_form_data();
            bus.$emit("sale-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.getcustomer();
            this.$refs.close_sale_modal.click();
            this.clear_form_data();
            bus.$emit("sale-added");
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
              if (this.product_list[item].category_id == "1") {
                this.saleList.push({
                  category: this.product_list[item].category,
                  category_id: this.product_list[item].category_id,
                  created_at: this.product_list[item].created_at,
                  deleted_at: this.product_list[item].deleted_at,
                  id: this.product_list[item].id,
                  name: this.product_list[item].name,
                  opening_quantity: this.product_list[item].opening_quantity,
                  quantity: this.product_list[item].quantity,
                  reward_points: this.product_list[item].reward_points,
                  updated_at: this.product_list[item].updated_at,
                  user_id: this.product_list[item].user_id,
                });
              }

              if (this.product_list[item].category_id == "3") {
                this.serviceList.push({
                  category: this.product_list[item].category,
                  category_id: this.product_list[item].category_id,
                  created_at: this.product_list[item].created_at,
                  deleted_at: this.product_list[item].deleted_at,
                  id: this.product_list[item].id,
                  name: this.product_list[item].name,
                  opening_quantity: this.product_list[item].opening_quantity,
                  quantity: this.product_list[item].quantity,
                  reward_points: this.product_list[item].reward_points,
                  updated_at: this.product_list[item].updated_at,
                  user_id: this.product_list[item].user_id,
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
        .get("/admin/get-customers")
        .then((response) => {
          this.customer_list = response.data;

          if (this.edit || this.viewOnly) {
            this.customer = this.customer_list.find(
              (option) => option.id == this.edit_customer_id
            );
          } else {
            this.customer = "";
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },

    addToArray() {
      if (this.user == 1 && this.gst_bill == 1) {
        if (
          this.saleItem == "" ||
          this.sale_rate_per_quantity == "" ||
          this.gst == "" ||
          this.discount == ""
        ) {
          if (this.saleItem == "") {
            this.saleItemErrorMessage = "This field required";
          }

          if (this.sale_rate_per_quantity == "") {
            this.rateErrorMessage = "This field required";
          }

          if (this.gst == "") {
            this.gstErrorMessage = "This field required";
          }

          if (this.discount == "") {
            this.discountErrorMessage = "This field required";
          }

          return false;
        }
      } else if (this.user == 1 && this.gst_bill == 0) {
        if (this.saleItem == "" || this.sale_rate_per_quantity == "") {
          if (this.saleItem == "") {
            this.saleItemErrorMessage = "This field required";
          }

          if (this.sale_rate_per_quantity == "") {
            this.rateErrorMessage = "This field required";
          }

          return false;
        }
      } else if (this.user == 0) {
        if (this.saleItem == "" || this.sale_rate_per_quantity == "") {
          if (this.saleItem == "") {
            this.saleItemErrorMessage = "This field required";
          }

          if (this.sale_rate_per_quantity == "") {
            this.rateErrorMessage = "This field required";
          }

          return false;
        }
      }

      var id = this.saleItem;

      var type;
      var category_id;
      var product_id;
      var item_name;

      for (let product in this.product_list) {
        if (this.product_list[product].id == id) {
          if (
            this.saleQuantity > parseFloat(this.product_list[product].quantity)
          ) {
            this.saleQuantityErrorMessage =
              "The requested quantity is not available";
            return false;
          }

          type = this.product_list[product].category.name;
          category_id = this.product_list[product].category.id;
          product_id = this.product_list[product].id;
          item_name = this.product_list[product].name;

          if (this.user == 1 && this.gst_bill == 1) {
            this.sale.items.push({
              type: type,
              item_name: item_name,

              category_id: category_id,
              product_id: product_id,
              quantity: this.saleQuantity,
              rate: this.sale_rate_per_quantity,
              gst: this.gst,
              discount: this.discount,
            });
          } else {
            this.sale.items.push({
              gst: "",
              type: type,
              item_name: item_name,

              category_id: category_id,
              product_id: product_id,
              quantity: this.saleQuantity,
              rate: this.sale_rate_per_quantity,
              discount: this.discount,
            });
          }

          this.gst = "";
          this.discount = "0";
          (this.total_item_rate = ""),
            (this.total_item_pay_amount = ""),
            (this.saleQuantity = "1");
          this.sale_rate_per_quantity = "";
          this.saleItem = "";
          this.rateErrorMessage = "";
          this.saleItemErrorMessage = "";
          this.saleQuantityErrorMessage = "";
          this.gstErrorMessage = "";
          this.discountErrorMessage = "";
          this.payTypeErrorMessage = "";
        }
      }
    },

    removeFromArray(index) {
      if (this.reportView) {
        return false;
      }

      this.sale.items.splice(index, 1);
    },

    showItemList(sale) {
      this.sale.id = sale.id;
      this.gst_bill = sale.gst_bill;
      this.sale.customer_id = sale.customer_id;
      this.sale.bill_type = sale.bill_type;
      this.sale.vehicle = sale.vehicle;
      this.sale.register_no = sale.register_no;
      this.sale.date = sale.invoice_date;
      this.sale.pay_type = sale.pay_type;
      this.wholesale = sale.wholesale;
      this.sale.total_discount = parseFloat(sale.total_discount);
      this.sale.roundoff = parseFloat(sale.roundoff);

      var salesItem = sale.items;

      for (let item in salesItem) {
        var id = salesItem[item].product_id;
        var rate = salesItem[item].rate;
        var quantity = salesItem[item].quantity;

        var gst = salesItem[item].gst;
        var discount = salesItem[item].discount;

        var type;
        var category_id;
        var product_id;
        var item_name;

        for (let product in this.product_list) {
          if (this.product_list[product].id == id) {
            type = this.product_list[product].category.name;
            category_id = this.product_list[product].category.id;
            product_id = this.product_list[product].id;
            item_name = this.product_list[product].name;

            if (this.user == 1 && sale.gst_bill == 1) {
              this.sale.items.push({
                type: type,
                item_name: item_name,

                category_id: category_id,
                product_id: product_id,
                quantity: quantity,
                rate: rate,

                gst: gst,
                discount: discount,
              });
            } else {
              this.sale.items.push({
                gst: "",
                type: type,
                item_name: item_name,

                category_id: category_id,
                product_id: product_id,
                quantity: quantity,
                rate: rate,
                discount: discount,
              });
            }

            // ##################################################
          }
        }
      }
    },

    showSaleCard() {
      this.saleShowCard = !this.saleShowCard;

      this.serviceShowCard = false;

      this.rateErrorMessage = "";
      this.saleItemErrorMessage = "";
      this.saleQuantityErrorMessage = "";
      this.saleQuantity = "1";
      this.sale_rate_per_quantity = "";
      this.saleItem = "";
      this.discount = "0";
      this.gst = "";
      this.total_item_pay_amount = "0";
      this.total_item_rate = "0";
      this.gstErrorMessage = "";
      this.discountErrorMessage = "";
      this.payTypeErrorMessage = "";
    },

    showServiceCard() {
      this.serviceShowCard = !this.serviceShowCard;
      this.saleShowCard = false;
      this.rateErrorMessage = "";
      this.saleItemErrorMessage = "";
      this.saleQuantityErrorMessage = "";
      this.sale_rate_per_quantity = "";
      this.total_item_pay_amount = "0";
      this.total_item_rate = "0";
      this.saleItem = "";
      this.discount = "0";
      this.gst = "";
      this.gstErrorMessage = "";
      this.discountErrorMessage = "";
      this.payTypeErrorMessage = "";
    },

    clear_form_data() {
      if (this.viewOnly == false) {
        document.getElementById("add-sale-button").style.display = "block";
        // document.getElementById("clear-and-close-button").style.display = "none";
      }

      this.clearNewCustomer();

      this.saleShowCard = false;
      this.serviceShowCard = false;
      this.sale_rate_per_quantity = "";

      this.saleItem = "";
      this.saleQuantity = "1";
      this.clearField();

      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },

    clearField() {
      this.sale.id = "";
      this.sale.customer_id = "";
      this.sale.bill_type = "";
      this.sale.vehicle = "";
      this.sale.register_no = "";
      this.sale.date = "";
      this.sale.total_discount = "0";
      this.sale.gst_bill = "";
      this.sale.wholesale = "";
      this.sale.items = [];
    },

    checkBox() {
      this.sale.items = [];
    },

    wholesaleCheckBox() {
      this.sale.items = [];
      this.saleItem = "";
      this.total_item_pay_amount = "0";
      this.discount = "0";
      this.total_item_rate = "0";
      this.sale_rate_per_quantity = "0";
    },

    // search component functions

    customerNameAndMobile(item) {
      return `${item.name} - ${item.mobile} `;
    },

    service(item) {
      return `${item.name}`;
    },

    saleItemName(item) {
      return `${item.name} - (${parseInt(item.quantity)} )`;
    },

    customLabel(item){
       return `${item.name} - ${item.mobile}`;
    },

    // search component functions ends

    addTag(newTag) {
      this.newCustomer = true;

      this.sale.name = newTag;
      this.sale.customer_id = "";
      this.customer = [];
    },

    clearNewCustomer() {
      this.newCustomer = false;
      this.sale.name = "";
      this.sale.mobile = "";
      this.sale.gst_no = "";
    },

    getItemTotal() {
      if (this.saleItem != "") {
        this.total_item_rate = parseFloat(
          this.saleQuantity * this.sale_rate_per_quantity
        ).toFixed(2);

        this.discount = parseFloat(
          (this.total_item_rate * this.item_discount_percentage) / 100
        ).toFixed(2);

        this.total_item_pay_amount = parseFloat(
          this.total_item_rate - this.discount
        ).toFixed(2);
      }
    },

    checkDiscountPercentage() {
      if (this.wholesale == 1 && this.sale_item_category == 1) {
        if (this.customer.discount > this.item_discount_percentage) {
          this.item_discount_percentage = this.customer.discount;
        }
      }
    },
  },

  computed: {
    netPayAmout: function () {
      if (this.sale.items != []) {
        var sum = 0;
        this.sale.items.forEach((e) => {
          sum += parseFloat(e.rate) * parseFloat(e.quantity);
        });
        return sum;
      }
    },

    button_title() {
      if (this.loading == true) {
        return "Saving  ";
      } else {
        return "Save Changes";
      }
    },

    netdiscount: function () {
      if (this.sale.items != []) {
        var sum = 0;
        this.sale.items.forEach((e) => {
          sum += parseFloat(e.discount);
        });
        return sum;
      }
    },
  },

  watch: {
    saleItem: function () {
      if (this.saleItem != "") {
        this.saleItemErrorMessage = "";

        for (let item in this.product_list) {
          if (this.product_list[item].id == this.saleItem) {
            if (this.product_list[item].mrp != null) {
              this.sale_rate_per_quantity = this.product_list[item].mrp;
              this.sale_item_category = this.product_list[item].category_id;

              this.item_discount_percentage = this.product_list[item].discount;
            }

            if (this.product_list[item].gst != null) {
              this.gst = parseFloat(this.product_list[item].gst);
            }
          }

          //aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
        }
        this.checkDiscountPercentage();
        this.getItemTotal();
      }
    },
    saleQuantity: function () {
      if (this.saleQuantity != "0") {
        this.getItemTotal();
      }
    },
    sale_rate_per_quantity: function () {
      if (this.sale_rate_per_quantity != "") {
        this.getItemTotal();
        this.rateErrorMessage = "";
      }
    },

    discount: function () {
      if (this.discount != "") {
        this.total_item_pay_amount = parseFloat(
          this.total_item_rate - this.discount
        ).toFixed(2);
      }
    },

    "sale.name": function () {
      if (this.sale.name != "") {
        this.newCustomerNameErrMsg = "";
      }
    },

    "sale.mobile": function () {
      if (this.sale.mobile != "") {
        this.newCustomerMobileErrMsg = "";
      }
    },

    customer: function () {
      if (this.customer != null) {
        this.checkDiscountPercentage();
        this.getItemTotal();
        this.sale.customer_id = this.customer.id;
      }
    },
  },
};
</script>


<style scoped>
.trashIconContainer {
  vertical-align: middle !important;
  padding-top: 10px;
  text-align: center;
}

.paymentclass {
  font-weight: bold;
  color: black;
}

/********Checkbox Slider start */
.switch {
  position: relative;
  display: inline-block;
  width: 35px;
  height: 18px;
  margin-top: 2px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 0px;
  bottom: 0px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #6777ef;
}

input:focus + .slider {
  box-shadow: 0 0 1px #6777ef;
}

input:checked + .slider:before {
  -webkit-transform: translateX(17px);
  -ms-transform: translateX(17px);
  transform: translateX(17px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
/********Checkbox Slider end */
</style>

