<template>
  <div>
    <div class="modal-header bg-primary">
      <h5
        class="modal-title modal-title-custom"
        id="addJobcardLabel"
        v-if="viewOnly"
      >
        Jobcard
      </h5>
      <h5 class="modal-title modal-title-custom" id="addJobcardLabel" v-else>
        {{ title }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
        ref="close_Jobcard_modal"
        @click="clear_form_data()"
      >
        <span aria-hidden="true" class="modal-title-custom">&times;</span>
      </button>
    </div>
    <form class="form-horizontal" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <div class="card-body">
          <div class="row">
            <div class="col" v-if="newCustomer == false">
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

            <div class="col-lg-4 col-md-4" v-if="newCustomer == true">
              <div class="form-group">
                <label class="">Customer Name</label>
                <input
                  type="text"
                  name="name"
                  placeholder="Enter Name"
                  v-model="jobCard.name"
                  class="form-control"
                />
                <small class="text-danger" v-if="errors.name">
                  {{ errors.name[0] }}</small
                >
                <small class="text-danger"> {{ newCustomerNameErrMsg }}</small>
              </div>
            </div>
            <div class="col-lg-4 col-md-4" v-if="newCustomer == true">
              <div class="form-group">
                <label class="">Phone Number</label>
                <input
                  type="phone"
                  name="mobile"
                  placeholder="Enter Mobile Number"
                  v-model="jobCard.mobile"
                  class="form-control"
                  min:10
                  max:10
                />
                <small class="text-danger" v-if="errors.mobile">
                  {{ errors.mobile[0] }}</small
                >
                <small class="text-danger">
                  {{ newCustomerMobileErrMsg }}</small
                >
              </div>
            </div>

            <div
              class="col-lg-4 col-md-4"
              v-if="user == 1 && newCustomer == true"
            >
              <div class="form-group">
                <label class="">GST Number</label>

                <input
                  type="text"
                  name=""
                  placeholder="Enter GST Number"
                  v-model="jobCard.gst_no"
                  class="form-control"
                />
              </div>
            </div>
            <!-- customer location -->

            <!-- <div class="col">
              <div class="form-group">
                <label>Location</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter Location"
                />
                <small class="text-danger"> </small>
              </div>
            </div> -->
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="form-group">
                <label>Vehicle</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter Vehicle Name"
                  v-model="jobCard.vehicle"
                  :disabled="viewOnly"
                />
                <small class="text-danger"> </small>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="form-group">
                <label class="required">Register Number</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter Register Number"
                  name="register_no"
                  v-model="jobCard.register_no"
                  :disabled="viewOnly"
                />
                <small class="text-danger" v-if="errors.register_no">
                  {{ errors.register_no[0] }}</small
                >
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="form-group">
                <label>Chasis Number</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter Chasis Number"
                  v-model="jobCard.chasis_no"
                  :disabled="viewOnly"
                />
                <small class="text-danger"> </small>
              </div>
            </div>

            <div class="col-lg-4 col-md-6">
              <div class="form-group">
                <label>Odometer Reading</label>
                <input
                  type="number"
                  class="form-control"
                  placeholder="Enter Odometer Reading"
                  v-model="jobCard.odometer"
                  :disabled="viewOnly"
                />
                <small class="text-danger"> </small>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="form-group">
                <label class="required">Select Job Advisor</label>
                <select
                  class="form-control"
                  name="jobadvisor_id"
                  v-model="jobCard.jobadvisor_id"
                  :disabled="viewOnly"
                >
                  <option value="">Select Job Advisor</option>
                  <option
                    v-for="advisor in advisor_list"
                    :key="advisor.id"
                    :value="advisor.id"
                  >
                    {{ advisor.name }}
                  </option>
                </select>
                <small class="text-danger" v-if="errors.jobadvisor_id">
                  {{ errors.jobadvisor_id[0] }}</small
                >
              </div>
            </div>

            <div class="col-lg-4 col-md-6">
              <div class="form-group">
                <label>Expected Delivery Date</label>
                <input
                  type="date"
                  class="form-control"
                  v-model="jobCard.expected_date"
                  :disabled="viewOnly"
                />
                <small class="text-danger"> </small>
              </div>
            </div>
          </div>
          <!--row end-->

          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label>Customer Voice</label>
                <textarea
                  class="form-control"
                  rows="3"
                  v-model="jobCard.customer_voice"
                  :disabled="viewOnly"
                ></textarea>
                <small class="text-danger"> </small>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label>Technician Voice</label>
                <textarea
                  class="form-control"
                  rows="3"
                  v-model="jobCard.technician_voice"
                  :disabled="viewOnly"
                ></textarea>
                <small class="text-danger"> </small>
              </div>
            </div>
          </div>
          <!--row end-->

          <div class="row mt-1 mb-1" v-if="!viewOnly">
            <div class="col-lg-8 col-md-8 d-flex justify-content-start mb-1">
              <button
                type="button"
                class="btn btn-sm rounded"
                @click="showService()"
                :class="
                  service_card ? 'btn-primary active' : 'btn-outline-primary '
                "
              >
                Add Service
                <i
                  class="fas fa-plus fa-fw fa-sm"
                  v-if="service_card == false"
                ></i>
                <i
                  class="fas fa-arrow-down fa-fw fa-sm"
                  v-if="service_card"
                ></i>
              </button>

              <button
                type="button"
                class="btn btn-sm rounded ml-1"
                @click="showItem()"
                :class="
                  item_card ? 'btn-primary active' : 'btn-outline-primary'
                "
              >
                Add Items
                <i
                  class="fas fa-plus fa-fw fa-sm"
                  v-if="item_card == false"
                ></i>
                <i class="fas fa-arrow-down fa-fw fa-sm" v-if="item_card"></i>
              </button>
            </div>
            <div
              class="col-lg-4 col-md-4 mt-1 d-flex justify-content-end"
              v-if="user == 1"
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
          </div>

          <!-- add service /item  row -->

          <div class="card" v-if="service_card == true || item_card == true">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4 " v-if="service_card == true">
                  <div class="form-group">
                    <label for="customer" class="required"
                      >Select Service</label
                    >

                    <!-- search component -->
                    <multiselect
                      v-model="select_item"
                      placeholder="Search service"
                      label="name"
                      :options="service_list"
                      track-by="mobile"
                      :options-limit="4"
                      :disabled="viewOnly"
                    ></multiselect>
                    <small class="text-danger" v-if="errors.select_item">
                      {{ errors.select_item[0] }}</small
                    >
                  </div>
                </div>

                <div class="col-lg-4 " v-if="item_card == true">
                  <div class="form-group">
                    <label class="required">Select Item</label>

                    <!-- search component -->
                    <multiselect
                      v-model="select_item"
                      placeholder="Search item"
                      label="name"
                      :options="item_list"
                      track-by="mobile"
                      :options-limit="4"
                      :disabled="viewOnly"
                    ></multiselect>
                    <small class="text-danger"> {{ select_item_error }}</small>
                  </div>
                </div>

                <div class="col-lg-4 ">
                  <div class="form-group">
                    <label class="">Select Technician</label>

                    <!-- search component -->
                    <multiselect
                      v-model="technician"
                      placeholder="Search Technician"
                      label="name"
                      :options="technician_list"
                      track-by="name"
                      :options-limit="4"
                      :disabled="viewOnly"
                    ></multiselect>

                    <small class="text-danger"></small>
                  </div>
                </div>
                <div class="col-lg-4 " v-if="this.user == 1 && this.gst_bill == 1">
                  <div class="form-group">
                    <label class="required">Select GST Slab</label>
                    <select class="form-control" name="gst_no" v-model="gst">
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
              </div>

              <div class="row">
                <!-- gst slab  -->

                <div class="col-lg-4 ">
                  <div class="form-group">
                    <label for="item_quantity">Quantity</label>
                    <input
                      type="number"
                      class="form-control"
                      name="item_quantity"
                      min="0"
                      oninput="validity.valid||(value='');"
                      v-model="quantity"
                      @input="quantityErrorMessage = ''"
                    />
                  </div>

                  <small class="text-danger" v-if="quantityErrorMessage">
                    {{ quantityErrorMessage }}</small
                  >
                </div>

                <div class="col-lg-4 ">
                  <div class="form-group">
                    <label for="rate" class="required">Rate</label>
                    <input
                      type="number"
                      class="form-control"
                      name="service_rate"
                      min="0"
                      oninput="validity.valid||(value='');"
                      v-model="rate"
                    />
                  </div>

                  <small class="text-danger"> {{ rateErrorMessage }}</small>
                </div>

                <!-- gst slab end -->

                <div class="col-lg-4 " v-if="this.user == 1 && this.gst_bill == 1">
                  <div class="form-group">
                    <label for="discount">Discount</label>
                    <input
                      type="number"
                      class="form-control"
                      name="discount"
                      min="0"
                      oninput="validity.valid||(value='');"
                      v-model="discount"
                    />
                  </div>

                  <small class="text-danger"> {{ discountErrorMessage }}</small>
                </div>

              </div>

<div class="mt-1 float-right ">
              <button
                type="button"
                class="btn rounded btn-primary "
                @click="addToArray()"
              >
                Add To List <i class="fas fa-clipboard-list fa-fw"></i>
              </button>
</div>
            </div>


          </div>
          <!--add service/item row  end-->
          <!-- table -->

          <div class="row">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Type</th>
                    <th scope="col">Item</th>
                    <th scope="col">Technician</th>
                    <th scope="col">Quantity</th>

                    <th scope="col">Rate</th>
                    <th
                      scope="col text-right"
                      v-if="this.user == 1 && this.gst_bill == 1"
                    >
                      GST
                    </th>
                    <th scope="col">Total Rate</th>
                    <th
                      scope="col text-right"
                      v-if="this.user == 1 && this.gst_bill == 1"
                    >
                      Discount
                    </th>
                    <th scope="col text-right">Delete</th>
                  </tr>

                  <tr v-if="this.jobCard.items.length == 0">
                    <td
                      colspan="10"
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
                </thead>
                <tbody>
                  <tr v-for="(item, index) in jobCard.items" :key="item.id">
                    <td scope="row" class="trashIconContainer">
                      {{ index + 1 }}
                    </td>

                    <td>{{ item.category_name }}</td>
                    <td>{{ item.product_name }}</td>
                    <td>{{ item.technician_name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.rate }}</td>

                    <td v-if="item.gst != ''">
                      {{ item.gst }}
                    </td>
                    <td>{{ item.quantity * item.rate }}</td>

                    <td v-if="item.gst != ''">
                      {{ item.discount }}
                    </td>
                    <td scope="row" class="trashIconContainer">
                      <i
                        class="far fa-trash-alt"
                        @click="removeFromArray(index)"
                      ></i>
                    </td>
                  </tr>
                </tbody>

                <tfoot>
                  <tr v-if="this.jobCard.items.length != 0">
                    <td
                      colspan="8"
                      class="text-right"
                      v-if="this.user == 1 && this.gst_bill == 1"
                    >
                      Total Discount &#x20b9;:
                    </td>
                    <td colspan="6" class="text-right" v-else>
                      Total Discount &#x20b9;:
                    </td>

                    <td class="text-right" style="width: 250px">
                      <input
                        v-if="
                          this.user == 1 && this.gst_bill == 1
                            ? (jobCard.total_discount = netdiscount)
                            : ''
                        "
                        type="number"
                        class="form-control text-right"
                        min="0"
                        oninput="validity.valid||(value='');"
                        v-model="jobCard.total_discount"
                        :disabled="viewOnly"
                      />

                      <input
                        v-else
                        type="number"
                        class="form-control text-right"
                        min="0"
                        oninput="validity.valid||(value='');"
                        v-model="jobCard.total_discount"
                        :disabled="viewOnly"
                      />
                    </td>
                  </tr>
                  <tr v-if="this.jobCard.items.length != 0">
                    <td
                      colspan="8"
                      class="text-right"
                      v-if="this.user == 1 && this.gst_bill == 1"
                    >
                      Net Pay &#x20b9;:
                    </td>
                    <td colspan="6" class="text-right" v-else>
                      Net Pay &#x20b9;:
                    </td>

                    <td class="text-right">
                      <span class="paymentclass">
                        {{ netPayAmout - jobCard.total_discount }}/-</span
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

          <!-- table end-->
        </div>
      </div>
      <div class="modal-footer" v-if="!viewOnly">
        <button
          type="button"
          class="btn btn-primary rounded-pill"
          @click="addJobcard()"
          :disabled="loading"
        >
          {{ button_title }}
          <i class="fas fa-spinner fa-spin fa-fw" v-if="loading == true"></i>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { ModelListSelect } from "vue-search-select";
export default {
  components: {
    ModelListSelect,
  },
  props: ["viewOnly", "edit", "usergst"],
  data() {
    return {
      title: "Add Jobcard",
      toastTitle: "Jobcard added successfully",
      user: this.usergst,
      loading: false,
      customer: [],
      customer_list: [],
      advisor_list: [],
      technician: [],
      technician_list: [],
      service_list: [],
      item_list: [],
      select_item: [],
      select_item_error: "",
      gst_bill: "",
      edit_customer_id: "",

      service_card: false,
      item_card: false,
      newCustomer: false,
      newCustomerNameErrMsg: "",
      newCustomerMobileErrMsg: "",
      rate: "",
      rateErrorMessage: "",
      item: "",
      ItemErrorMessage: "",
      gst: "",
      gstErrorMessage: "",
      discount: "0",
      discountErrorMessage: "",
      quantity: "1",
      quantityErrorMessage: "",
      jobCard: {
        items: [],
        id: "",
        customer_id: "",
        name: "",
        mobile: "",
        gst_no: "",

        gst_bill: "",
        vehicle: "",
        register_no: "",
        chasis_no: "",
        odometer: "",
        expected_date: "",
        customer_voice: "",
        technician_voice: "",
        jobadvisor_id: "",

        total_discount: "0",
      },
      errors: {},
    };
  },

  created() {
    this.getcustomer();
    this.getJobAdvisor();
    this.getTechnician();
    this.getService();
    this.getItem();

    if (this.edit) {
      var _this = this;

      _this.title = "Edit Jobcard";
      _this.toastTitle = "Jobcard updated successfully";
      bus.$on("edit-jobcard", function (jobcard) {
        _this.edit_customer_id = jobcard.customer_id;

        _this.getcustomer();

        _this.showItemList(jobcard);
      });
    }
    if (this.viewOnly) {
      var _this = this;
      this.user = 1;

      _this.title = "Jobcard";

      bus.$on("view-jobcard", function (jobcard) {
        _this.edit_customer_id = jobcard.customer_id;

        _this.getcustomer();

        _this.showItemList(jobcard);
      });
    }
  },

  methods: {
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

    getJobAdvisor() {
      axios
        .get("/admin/get-job-adviser")
        .then((response) => {
          this.advisor_list = response.data;

          if (this.edit || this.viewOnly) {
            this.customer = this.advisor_list.find(
              (option) => option.id == this.edit_jobadvisor_id
            );
          } else {
            this.customer = "";
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getTechnician() {
      axios
        .get("/admin/get-technician")
        .then((response) => {
          this.technician_list = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getService() {
      axios
        .get("/admin/get-service-product")
        .then((response) => {
          this.service_list = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getItem() {
      axios
        .get("/admin/get-sale-product")
        .then((response) => {
          this.item_list = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    addTag(newTag) {
      this.newCustomer = true;

      this.jobCard.name = newTag;
      this.jobCard.customer_id = "";
      this.customer = [];
    },

    checkBox() {
      this.jobCard.items = [];
      this.clear_add_items();
    },
    showService() {
      this.service_card = !this.service_card;
      this.item_card = false;
      this.clear_add_items();
    },
    showItem() {
      this.item_card = !this.item_card;
      this.service_card = false;
      this.clear_add_items();
    },

    addJobcard() {
      if (this.user == 1 && this.gst_bill == 1) {
        this.jobCard.gst_bill = " 1";
      } else {
        this.jobCard.gst_bill = "0";
      }

      if (
        this.newCustomer == true &&
        (this.jobCard.name == "" || this.jobCard.mobile == "")
      ) {
        if (this.jobCard.name == "") {
          this.newCustomerNameErrMsg = "This field is required";
        }
        if (this.jobCard.mobile == "") {
          this.newCustomerMobileErrMsg = "This field is required";
        }
        return false;
      }
      if (
        (this.newCustomer == true && this.jobCard.mobile.length < 10) ||
        this.jobCard.mobile.length > 10
      ) {
        this.newCustomerMobileErrMsg = "Invalid mobile number";
        return false;
      }

      this.loading = true;
      axios
        .post("add-jobcard", this.jobCard)
        .then((response) => {
          if (response.data == "success") {
            Toast.fire({
              icon: "success",
              title: this.toastTitle,
            });
            this.getcustomer();
            this.$refs.close_Jobcard_modal.click();
            this.clear_form_data();
            bus.$emit("jobcard-added");
          }
          if (response.data == "failed") {
            Toast.fire({
              icon: "error",
              title: "Some Error Occurred,Please Try Again Later",
            });

            this.getcustomer();
            this.$refs.close_Jobcard_modal.click();
            this.clear_form_data();
            bus.$emit("jobcard-added");
          }

          this.loading = false;
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.loading = false;
        });
    },

    clear_form_data() {
      for (let card in this.jobCard) {
        this.jobCard[card] = "";
      }
      this.jobCard.items = [];
      this.jobCard.total_discount = "0";
      for (let er in this.errors) {
        this.errors[er] = "";
      }

      this.gst_bill = "";
      this.edit_customer_id = "";
      this.service_card = false;
      this.item_card = false;
      this.newCustomer = false;
      this.newCustomerNameErrMsg = "";
      this.newCustomerMobileErrMsg = "";
      this.clear_add_items();
    },

    clear_add_items() {
      this.technician = [];
      this.select_item = [];
      this.select_item_error = "";
      this.rate = "";
      this.rateErrorMessage = "";
      this.item = "";
      this.ItemErrorMessage = "";
      this.gst = "";
      this.gstErrorMessage = "";
      this.discount = "0";
      this.discountErrorMessage = "";
      this.quantity = "1";
      this.quantityErrorMessage = "";
    },

    addToArray() {
      if (this.user == 1 && this.gst_bill == 1) {
        if (
          this.select_item.length == 0 ||
          this.rate == "" ||
          this.quantity == "" ||
          this.gst == "" ||
          this.discount == ""
        ) {
          if (this.select_item.length == 0) {
            this.select_item_error = "This field required";
          }

          if (this.rate == "") {
            this.rateErrorMessage = "This field required";
          }

          if (this.quantity == "") {
            this.quantityErrorMessage = "This field required";
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
        if (
          this.select_item.length == 0 ||
          this.rate == "" ||
          (this.quantity == "") | (this.discount == "")
        ) {
          if (this.select_item.length == 0) {
            this.select_item_error = "This field required";
          }

          if (this.rate == "") {
            this.rateErrorMessage = "This field required";
          }
          if (this.quantity == "") {
            this.quantityErrorMessage = "This field required";
          }

          if (this.discount == "") {
            this.discountErrorMessage = "This field required";
          }
          return false;
        }
      } else if (this.user == 0) {
        if (
          this.select_item.length == 0 ||
          this.rate == "" ||
          (this.quantity == "") | (this.discount == "")
        ) {
          if (this.select_item.length == 0) {
            this.select_item_error = "This field required";
          }

          if (this.rate == "") {
            this.rateErrorMessage = "This field required";
          }
          if (this.quantity == "") {
            this.quantityErrorMessage = "This field required";
          }

          if (this.discount == "") {
            this.discountErrorMessage = "This field required";
          }
          return false;
        }
      }

      this.jobCard.items.push({
        technician_id: this.technician.id,
        technician_name: this.technician.name,
        category_id: this.select_item.category.id,
        category_name: this.select_item.category.name,
        product_id: this.select_item.id,
        product_name: this.select_item.name,
        quantity: this.quantity,
        rate: this.rate,
        discount: this.discount,
        gst: this.gst,
      });

      this.clear_add_items();
    },

    removeFromArray(index) {
      if (this.viewOnly) {
        return false;
      }

      this.jobCard.items.splice(index, 1);
    },

    showItemList(jobcard) {
      var itemList = jobcard.items;
      this.jobCard.id = jobcard.id;
      this.gst_bill = jobcard.gst_bill;

      this.jobCard.gst_bill = jobcard.gst_bill;

      this.jobCard.vehicle = jobcard.vehicle;
      this.jobCard.register_no = jobcard.register_no;
      this.jobCard.chasis_no = jobcard.chasis_no;
      this.jobCard.odometer = jobcard.odometer;
      this.jobCard.expected_date = jobcard.expected_date;
      this.jobCard.customer_voice = jobcard.customer_voice;
      this.jobCard.technician_voice = jobcard.technician_voice;
      this.jobCard.jobadvisor_id = jobcard.jobadvisor_id;

      this.jobCard.total_discount = jobcard.total_discount;

      for (let item in itemList) {
        var technician_id = itemList[item].technician_id;
        var category_id = itemList[item].category_id;
        var product_id = itemList[item].product_id;
        var quantity = itemList[item].quantity;
        var rate = itemList[item].rate;
        var discount = itemList[item].discount;
        var gst = itemList[item].gst;

        for (let item in this.item_list) {
          if (this.item_list[item].id == product_id) {
            var product_name = this.item_list[item].name;
            var category_name = this.item_list[item].category.name;
          }
        }
        for (let item in this.technician_list) {
          if (this.technician_list[item].id == technician_id) {
            var technician_name = this.technician_list[item].name;
          }
        }
        if (this.user == 1 && jobcard.gst_bill == 1) {
          this.jobCard.items.push({
            technician_id: technician_id,

            technician_name: technician_name,

            category_id: category_id,

            category_name: category_name,

            product_id: product_id,

            product_name: product_name,

            quantity: quantity,

            rate: rate,

            discount: discount,

            gst: gst,
          });
        } else {
          this.jobCard.items.push({
            technician_id: technician_id,

            technician_name: technician_name,

            category_id: category_id,

            category_name: category_name,

            product_id: product_id,

            product_name: product_name,

            quantity: quantity,

            rate: rate,

            discount: discount,

            gst: "",
          });
        }
      }
    },
  },

  computed: {
    netPayAmout: function () {
      if (this.jobCard.items != []) {
        var sum = 0;
        this.jobCard.items.forEach((e) => {
          sum += parseFloat(e.rate) * parseInt(e.quantity);
        });
        return sum;
      }
    },

    button_title() {
      if (this.loading == true) {
        return "Saving  ";
      } else {
        return "Save Jobcard";
      }
    },

    netdiscount: function () {
      if (this.user == 1 && this.gst_bill == 1) {
        if (this.jobCard.items != []) {
          var sum = 0;
          this.jobCard.items.forEach((e) => {
            sum += parseFloat(e.discount);
          });
          return sum;
        }
      }
    },
  },

  watch: {
    customer: function () {
      if (this.customer != null) {
        this.jobCard.customer_id = this.customer.id;
      }
    },
  },
};
</script>

<style scoped>
.trashIconContainer {
  vertical-align: middle !important;
  padding-top: 4px;
  text-align: center;
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
