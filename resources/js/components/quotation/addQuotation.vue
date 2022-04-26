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
        ref="close_quotation_modal"
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
                v-model="quotation.name"
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
                v-model="quotation.mobile"
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
                v-model="quotation.gst_no"
                class="form-control"
              />
            </div>
          </div>
        </div>

        <!--row-start-->
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="form-group">
              <label for="quotation_date">Date</label>
              <input
                type="date"
                class="form-control"
                id="quotation_date"
                name="quotation_date"
                v-model="quotation.quotation_date"
                :disabled="reportView"
              />
            </div>

            <small class="text-danger" v-if="errors.quotation_date">
              {{ errors.quotation_date[0] }}</small
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
                  <multiselect
                    v-model="saleItem"
                    tag-placeholder="Add this as new Product"
                    placeholder="Search or add a Product"
                    label="name"
                    :options="serviceList"
                    :taggable="true"
                    @tag="addNewService"
                    track-by="id"
                    :options-limit="4"
                    :disabled="this.edit || viewOnly"
                  ></multiselect>

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
                  <multiselect
                    v-model="saleItem"
                    tag-placeholder="Add this as new Product"
                    placeholder="Search or add a Product"
                    label="name"
                    :options="saleList"
                    :taggable="true"
                    @tag="addNewProduct"
                    track-by="id"
                    :options-limit="4"
                    :disabled="this.edit || viewOnly"
                  ></multiselect>

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
                        <tr
                          v-for="(item, index) in quotation.items"
                          :key="item.id"
                        >
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

                        <tr v-if="this.quotation.items.length == 0">
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
                          v-if="this.quotation.items.length != 0"
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
                          v-if="this.quotation.items.length != 0"
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
                              v-model="quotation.roundoff"
                              :disabled="reportView"
                            />
                          </td>
                        </tr>

                        <!-- qqqqqqqqqqqqqqqqqqqqqqqqqqq  -->
                        <tr
                          v-if="this.quotation.items.length != 0"
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
                            {{
                              netPayAmout - netdiscount - quotation.roundoff
                            }}/ -
                          </td>
                        </tr>
                        <tr
                          v-if="
                            this.quotation.bill_type == 2 &&
                            this.quotation.items.length != 0
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
                                v-model="quotation.pay_type"
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
        @click="addQuotation()"
        id="add-quotation-button"
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
      title: "Add Quotation",
      toastTitle: "Quotation added successfully",
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

      saleItem: [],

      new_product: "",

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
      quotation: {
        id: "",
        customer_id: "",
        bill_type: "",
        vehicle: "",
        register_no: "",
        quotation_date: "",
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

      _this.title = "Edit Quotation";
      _this.toastTitle = "Quotation updated successfully";
      bus.$on("edit-quotation", function (quotation) {
        _this.edit_customer_id = quotation.customer_id;

        _this.getcustomer();

        _this.clearField();
        _this.showItemList(quotation);
      });
    }

    if (this.viewOnly) {
      var _this = this;
      this.user = 1;

      _this.reportView = true;
      _this.title = "Quotation Card";
      _this.toastTitle = "Quotation updated successfully";
      bus.$on("view-quotation", function (quotation) {
        _this.clearField();
        _this.showItemList(quotation);

        _this.edit_customer_id = quotation.customer_id;
        _this.getcustomer();
        //
      });
    }
  },
  methods: {
    addQuotation() {
      if (this.quotation.items.length == 0) {
        Toast.fire({
          icon: "error",
          title: " No products added",
        });

        return false;
      }

      if (this.user == 1 && this.gst_bill == 1) {
        this.quotation.gst_bill = " 1";
      } else {
        this.quotation.gst_bill = "0";
      }
      if (this.wholesale == true) {
        this.quotation.wholesale = " 1";
      } else {
        this.quotation.wholesale = "0";
      }

      if (this.quotation.bill_type == 2 && this.quotation.pay_type == "") {
        this.payTypeErrorMessage = "This field required";
        return false;
      }
      if (
        this.newCustomer == true &&
        (this.quotation.name == "" || this.quotation.mobile == "")
      ) {
        if (this.quotation.name == "") {
          this.newCustomerNameErrMsg = "This field is required";
        }
        if (this.quotation.mobile == "") {
          this.newCustomerMobileErrMsg = "This field is required";
        }
        return false;
      }
      this.quotation.total_discount = parseFloat(this.netdiscount);
      this.loading = true;

      axios
        .post("/admin/add-quotation", this.quotation)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
            this.getcustomer();
            this.$refs.close_quotation_modal.click();
            this.clear_form_data();
            bus.$emit("quotation-added");
          }

          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.getcustomer();
            this.$refs.close_quotation_modal.click();
            this.clear_form_data();
            bus.$emit("quotation-added");
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
          this.saleList = [];
          this.serviceList = [];
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
          this.saleItem == [] ||
          this.sale_rate_per_quantity == "" ||
          this.gst == "" ||
          this.discount == ""
        ) {
          if (this.saleItem == []) {
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
        if (this.saleItem == [] || this.sale_rate_per_quantity == "") {
          if (this.saleItem == []) {
            this.saleItemErrorMessage = "This field required";
          }

          if (this.sale_rate_per_quantity == "") {
            this.rateErrorMessage = "This field required";
          }

          return false;
        }
      } else if (this.user == 0) {
        if (this.saleItem == [] || this.sale_rate_per_quantity == "") {
          if (this.saleItem == []) {
            this.saleItemErrorMessage = "This field required";
          }

          if (this.sale_rate_per_quantity == "") {
            this.rateErrorMessage = "This field required";
          }

          return false;
        }
      }

      var id = this.saleItem.id;

      var type;
      var category_id;
      var product_id;
      var item_name;

      for (let product in this.product_list) {
        if (this.product_list[product].id == id) {
          if (
            this.saleQuantity >
              parseFloat(this.product_list[product].quantity) &&
            this.product_list[product].category.id == 1
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
            this.quotation.items.push({
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
            this.quotation.items.push({
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
          this.total_item_rate = "";
          this.total_item_pay_amount = "";
          this.saleQuantity = "1";
          this.sale_rate_per_quantity = "";
          this.saleItem = [];
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

      this.quotation.items.splice(index, 1);
    },

    showItemList(quotation) {
      this.quotation.id = quotation.id;
      this.gst_bill = quotation.gst_bill;
      this.quotation.customer_id = quotation.customer_id;
      this.quotation.bill_type = quotation.bill_type;
      this.quotation.vehicle = quotation.vehicle;
      this.quotation.register_no = quotation.register_no;
      this.quotation.quotation_date = quotation.quotation_date;
      this.quotation.pay_type = quotation.pay_type;
      this.wholesale = quotation.wholesale;
      this.quotation.total_discount = parseFloat(quotation.total_discount);
      this.quotation.roundoff = parseFloat(quotation.roundoff);

      var quotationItems = quotation.items;

      for (let item in quotationItems) {
        var id = quotationItems[item].product_id;
        var rate = quotationItems[item].rate;
        var quantity = quotationItems[item].quantity;

        var gst = quotationItems[item].gst;
        var discount = quotationItems[item].discount;

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

            if (this.user == 1 && quotation.gst_bill == 1) {
              this.quotation.items.push({
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
              this.quotation.items.push({
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
      this.saleItem = [];
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
      this.saleItem = [];
      this.discount = "0";
      this.gst = "";
      this.gstErrorMessage = "";
      this.discountErrorMessage = "";
      this.payTypeErrorMessage = "";

      this.saleQuantity = "1";
    },

    clear_form_data() {
      if (this.viewOnly == false) {
        document.getElementById("add-quotation-button").style.display = "block";
        // document.getElementById("clear-and-close-button").style.display = "none";
      }

      this.clearNewCustomer();

      this.saleShowCard = false;
      this.serviceShowCard = false;
      this.sale_rate_per_quantity = "";

      this.saleItem = [];
      this.saleQuantity = "1";
      this.clearField();

      for (let er in this.errors) {
        this.errors[er] = "";
      }
    },

    clearField() {
      this.quotation.id = "";
      this.quotation.customer_id = "";
      this.quotation.bill_type = "";
      this.quotation.vehicle = "";
      this.quotation.register_no = "";
      this.quotation.quotation_date = "";
      this.quotation.total_discount = "0";
      this.quotation.gst_bill = "";
      this.quotation.wholesale = "";
      this.quotation.items = [];
    },

    checkBox() {
      this.quotation.items = [];
    },

    wholesaleCheckBox() {
      this.quotation.items = [];
      this.saleItem = [];
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

    // search component functions ends

    addTag(newTag) {
      this.newCustomer = true;

      this.quotation.name = newTag;
      this.quotation.customer_id = "";
      this.customer = [];
    },

    clearNewCustomer() {
      this.newCustomer = false;
      this.quotation.name = "";
      this.quotation.mobile = "";
      this.quotation.gst_no = "";
    },

    getItemTotal() {
      if (this.saleItem != []) {
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

    addNewProduct(newProduct) {
      Swal.fire({
        title: "Are you sure?",
        html: "You want to add <b>" + newProduct + "</b> as a new Product ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("add-product", { name: newProduct, product_category: 1 })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: "Product Added successfully",
                });
                this.getProduct();
                this.saleItem = [];

                bus.$emit("product-added");
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });
              }
            })
            .catch((error) => {
              console.log("errors");
            });
        }
      });
    },

    addNewService(newService) {
      Swal.fire({
        title: "Are you sure?",
        html: "You want to add <b>" + newService + "</b> as a new service ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("add-product", { name: newService, product_category: 3 })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: "Service Product Added successfully",
                });
                this.getProduct();
                this.saleItem = [];

                bus.$emit("product-added");
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });
              }
            })
            .catch((error) => {
              console.log("errors");
            });
        }
      });
    },
  },

  computed: {
    netPayAmout: function () {
      if (this.quotation.items != []) {
        var sum = 0;
        this.quotation.items.forEach((e) => {
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
      if (this.quotation.items != []) {
        var sum = 0;
        this.quotation.items.forEach((e) => {
          sum += parseFloat(e.discount);
        });
        return sum;
      }
    },
  },

  watch: {
    saleItem: function () {
      this.sale_rate_per_quantity = "";

      if (this.saleItem != [] && this.saleItem != null) {
        this.saleItemErrorMessage = "";

        for (let item in this.product_list) {
          if (this.product_list[item].id == this.saleItem.id) {
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

    "quotation.name": function () {
      if (this.quotation.name != "") {
        this.newCustomerNameErrMsg = "";
      }
    },

    "quotation.mobile": function () {
      if (this.quotation.mobile != "") {
        this.newCustomerMobileErrMsg = "";
      }
    },

    customer: function () {
      if (this.customer != null) {
        this.checkDiscountPercentage();
        this.getItemTotal();
        this.quotation.customer_id = this.customer.id;
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

