<template>
    <div>
        <!-- Modal -->
        <div class="modal-header bg-primary">
            <h5 class="modal-title modal-title-custom" id="addPurchaseLabel">
                {{ title }}
            </h5>
            <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
                ref="close_purchase_modal"
                @click="clearData()"
            >
                <span aria-hidden="true" class="modal-title-custom"
                    >&times;</span
                >
            </button>
        </div>

        <form class="form-horizontal">
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="inv no" class="required"
                                    >Invoice Number</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    name="purchase_invoice_no"
                                    placeholder="Enter Invoice Number"
                                    v-model="purchaseData.purchase_invoice_no"
                                    :disabled="reportView"
                                />
                                <small
                                    class="text-danger"
                                    v-if="errors.purchase_invoice_no"
                                >
                                    {{ errors.purchase_invoice_no[0] }}</small
                                >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="inv no" class="required"
                                    >Purchase Date</label
                                >
                                <input
                                    type="date"
                                    class="form-control"
                                    name="purchase_date"
                                    v-model="purchaseData.purchase_date"
                                    :disabled="reportView"
                                />
                                <small
                                    class="text-danger"
                                    v-if="errors.purchase_date"
                                >
                                    {{ errors.purchase_date[0] }}</small
                                >
                            </div>
                        </div>
                    </div>

                    <!--row end-->

                    <div class="row">
                        <div class="col" v-if="new_vendor == false">
                            <label for="productName" class="required"
                                >Select Vendor
                            </label>
                            <div class="form-group">
                                <!-- search vendor -->

                                <multiselect
                                    v-model="vendors"
                                    tag-placeholder="Add this as new Vendor"
                                    placeholder="Search or add a Vendor"
                                    label="name"
                                    :options="vendorsList"
                                    :taggable="true"
                                    @tag="newVendor"
                                    track-by="id"
                                    :options-limit="4"
                                    :disabled="this.edit || viewOnly"
                                ></multiselect>

                                <small
                                    class="text-danger"
                                    v-if="errors.vendor_id"
                                >
                                    {{ errors.vendor_id[0] }}</small
                                >
                                <!-- search vendor end  -->
                            </div>
                        </div>
                        <!-- new customer -->

                        <div class="col" v-if="new_vendor == true">
                            <div class="form-group">
                                <label class="required">Vendor Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter Name"
                                    name="vendor_name"
                                    v-model="purchaseData.vendor_name"
                                    @change="vendorNameError = ''"
                                />
                            </div>

                            <small class="text-danger">
                                {{ vendorNameError }}</small
                            >
                        </div>

                        <div class="col" v-if="new_vendor == true">
                            <div class="form-group">
                                <label class="required">Mobile</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="Enter Mobile"
                                    name="vendor_mobile"
                                    v-model="purchaseData.vendor_mobile"
                                    :max="10"
                                    @change="vendorMobileError = ''"
                                />
                            </div>

                            <small class="text-danger">
                                {{ vendorMobileError }}</small
                            >
                        </div>

                        <!-- new customer end -->

                        <div class="col">
                            <div class="form-group">
                                <label for="category" class="required"
                                    >Select Bill Type</label
                                >
                                <select
                                    class="form-control"
                                    id="bill_type"
                                    name="bill_type"
                                    v-model="purchaseData.bill_type"
                                    :disabled="reportView"
                                >
                                    <option value="">Select Bill Type</option>

                                    <option value="2">Cash</option>
                                    <option value="1">Credit</option>
                                </select>
                                <small
                                    class="text-danger"
                                    v-if="errors.bill_type"
                                >
                                    {{ errors.bill_type[0] }}</small
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="reportView == false">
                        <div class="col" v-if="new_product == false">
                            <label for="productName" class="required"
                                >Select Product
                            </label>
                            <div class="form-group">
                                <!-- product list  -->

                                <multiselect
                                    v-model="products"
                                    tag-placeholder="Add this as new product"
                                    placeholder="Search or add a product"
                                    label="name"
                                    :options="productsList"
                                    :taggable="true"
                                    @tag="newProduct"
                                    track-by="id"
                                    :options-limit="4"
                                ></multiselect>

                                <small class="text-danger">
                                    {{ productErrorMessage }}</small
                                >

                                <!-- product list  ends -->
                            </div>
                        </div>

                        <!-- new product  -->

                        <div class="col" v-if="new_product == true">
                            <div class="form-group">
                                <label class="required">Product Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter Name"
                                    name="product_name"
                                    v-model="product_name"
                                />
                            </div>

                            <small class="text-danger">
                                {{ productNameError }}</small
                            >
                        </div>

                        <!-- new product  ends-->
                    </div>

                    <div class="row" v-if="reportView == false">
                        <div class="col">
                            <div class="form-group">
                                <label for="purchaseQty" class="required"
                                    >Product Quantity</label
                                >
                                <div class="input-group">
                                    <input
                                        type="number"
                                        step="any"
                                        class="form-control width100 xyz"
                                        v-model="purchase_qty"
                                        name="purchase_qty"
                                        placeholder="Enter product quantity"
                                    />
                                </div>
                                <small class="text-danger">
                                    {{ purchase_qtyErrorMessage }}</small
                                >
                            </div>
                        </div>

                        <div class="col">
                            <label for="productName" class="required"
                                >Rate Per Quantity
                            </label>
                            <div class="form-group">
                                <input
                                    type="number"
                                    step="any"
                                    class="form-control width100 xyz"
                                    v-model="rate_per_qty"
                                    name="rate_per_qty"
                                    placeholder="Rate per quantity"
                                />
                                <small class="text-danger">
                                    {{ rate_per_qty_error_msg }}</small
                                >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="required">Select GST Slab</label>
                                <select
                                    class="form-control"
                                    name="gst_no"
                                    v-model="gst_percentage"
                                >
                                    <option value="">Select GST Slab</option>
                                    <option value="0">0%</option>
                                    <option value="5">5%</option>
                                    <option value="12">12%</option>
                                    <option value="18">18%</option>
                                    <option value="28">28%</option>
                                </select>
                                <small class="text-danger">{{
                                    gst_percentage_err_msg
                                }}</small>
                            </div>

                            <span class="input-group-btn float-right">
                                <button
                                    class="btn btn-dark btn-sm"
                                    @click.prevent="addNewRow()"
                                >
                                    Add <i class="fas fa-plus-circle fa-fw"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Items</h5>
                            <div class="table-responsive mt-3">
                                <table
                                    id="myTable"
                                    class="table align-items-center table-flush table-hover display"
                                >
                                    <thead>
                                        <tr>
                                            <th>Sl.no</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Gst</th>
                                            <th>Taxable Value</th>
                                            <th>Total Tax Amount</th>
                                            <th>Total Amount</th>
                                            <th v-if="reportView == false">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tr
                                        v-if="
                                            this.purchaseData.items.length == 0
                                        "
                                    >
                                        <td colspan="9">
                                            <p
                                                class="text-center light-danger p-2"
                                            >
                                                No products added.
                                            </p>
                                        </td>
                                    </tr>
                                    <tbody
                                        v-for="(
                                            data, index
                                        ) in purchaseData.items"
                                        :key="index"
                                    >
                                        <tr
                                            v-if="
                                                purchaseData.items.length != 0
                                            "
                                        >
                                            <td>
                                                {{ index + 1 }}
                                            </td>
                                            <td class="">
                                                {{ data.product_name }}
                                            </td>
                                            <td>
                                                {{ data.purchase_qty }}
                                            </td>
                                            <td>
                                                {{ data.rate_per_qty }}
                                            </td>
                                            <td>
                                                {{ data.gst_percentage }}
                                            </td>
                                            <td>
                                                {{ data.taxable_amount }}
                                            </td>
                                            <td>
                                                {{ data.tax }}
                                            </td>
                                            <td>
                                                {{ data.amount }}
                                            </td>
                                            <td
                                                class="pl-4"
                                                v-if="reportView == false"
                                            >
                                                <button
                                                    class="btn btn-danger btn-sm"
                                                    @click.prevent="
                                                        deleteRow(index)
                                                    "
                                                >
                                                    <i
                                                        title="Delete"
                                                        class="fas fa-trash fafw"
                                                    ></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="7">
                                                Total Purchase Amount
                                            </td>
                                            <td colspan="2">
                                                {{
                                                    parseFloat(
                                                        totalPurchaseAmount
                                                    ).toFixed(2)
                                                }}/-
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1"
                                    >Purchase Remarks</label
                                >
                                <textarea
                                    class="form-control"
                                    rows="2"
                                    v-model="purchaseData.remarks"
                                    :disabled="reportView"
                                ></textarea>
                            </div>

                            <div
                                class="row"
                                v-if="this.purchaseData.bill_type == 2"
                            >
                                <div class="col">
                                    <div class="form-group">
                                        <label for="category" class="required"
                                            >Select Pay Type</label
                                        >
                                        <select
                                            class="form-control"
                                            id="pay_type"
                                            name="pay_type"
                                            v-model="purchaseData.pay_type"
                                            :disabled="reportView"
                                            @change="pay_type_err_msg = ''"
                                        >
                                            <option value="">
                                                Select Pay Type
                                            </option>

                                            <option value="Cash">Cash</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                        <small class="text-danger">
                                            {{ pay_type_err_msg }}</small
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" v-if="reportView == false">
                <button
                    type="submit"
                    class="btn btn-primary rounded-pill"
                    @click.prevent="submitPurchase()"
                    :disabled="loading"
                >
                    {{ button_title }}
                    <i
                        class="fas fa-spinner fa-spin fa-fw"
                        v-if="loading == true"
                    ></i>
                </button>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props: ["edit", "viewOnly"],
    data() {
        return {
            loading: false,
            product_id: "",
            purchase_qty: "",
            title: "Add Purchase",
            toastTitle: "Purchase added successfully",
            reportView: false,
            purchase_id: "",
            purchase_qtyErrorMessage: "",
            productErrorMessage: "",
            rate_per_qty_error_msg: "",
            gst_percentage_err_msg: "",

            pay_type_err_msg: "",

            productsList: [],
            products: [],
            vendorsList: [],
            vendors: [],
            edit_vendor_id: "",
            new_vendor: false,
            vendorNameError: "",
            vendorMobileError: "",
            new_product: false,
            productNameError: "",
            errors: {},
            rate_per_qty: "",
            gst_percentage: "",
            product_name: "",
            purchaseData: {
                vendor_id: "",
                vendor_name: "",
                vendor_mobile: "",

                bill_type: "",
                pay_type: "",
                id: "",
                purchase_invoice_no: "",
                purchase_date: "",
                remarks: "",
                items: [],
            },
        };
    },

    computed: {
        totalPurchaseAmount: function () {
            if (this.purchaseData.items != []) {
                var sum = 0;
                this.purchaseData.items.forEach((e) => {
                    sum += parseFloat(e.amount);
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
    },

    methods: {
        submitPurchase() {
            if (
                this.purchaseData.bill_type == 2 &&
                this.purchaseData.pay_type == ""
            ) {
                this.pay_type_err_msg = "This field required";
                return false;
            }
            if (this.edit) {
                this.purchaseData.id = this.purchase_id;
            }

            if (
                this.new_vendor == true &&
                this.purchaseData.vendor_name == "" &&
                this.purchaseData.vendor_mobile == ""
            ) {
                if (this.purchaseData.vendor_name == "") {
                    this.vendorNameError = "This field required";
                }

                if (this.purchaseData.vendor_mobile == "") {
                    this.vendorMobileError = "This field required";
                }

                return false;
            }
            if (
                (this.new_vendor == true &&
                    this.purchaseData.vendor_mobile.length < 10) ||
                this.purchaseData.vendor_mobile.length > 10
            ) {
                this.vendorMobileError = "Invalid mobile number";

                return false;
            }

            this.loading = true;
            axios
                .post("add-purchase", this.purchaseData)
                .then((response) => {
                    if (
                        response.data == "items_error" ||
                        response.data == "items_array_error"
                    ) {
                        Toast.fire({
                            icon: "warning",
                            title: "Please add purchase items to the list",
                        });
                    }

                    if (response.data == "success") {
                        Toast.fire({
                            icon: "success",
                            title: this.toastTitle,
                        });

                        bus.$emit("purchase-added");
                        this.$refs.close_purchase_modal.click();
                    }
                    if (response.data == "failed") {
                        Toast.fire({
                            icon: "error",
                            title: "Some Error Occurred,Please Try Again Later",
                        });

                        bus.$emit("purchase-added");
                        this.$refs.close_purchase_modal.click();
                    }

                    this.loading = false;
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });
        },
        addNewRow() {
            if (this.products == [] || this.purchase_qty == "") {
                if (this.products == []) {
                    this.productErrorMessage = "Please select product";
                }

                if (this.purchase_qty == "") {
                    this.purchase_qtyErrorMessage =
                        "Purchase quantity is required";
                }

                if (this.rate_per_qty == "") {
                    this.rate_per_qty_error_msg =
                        "Rate per quantity is required";
                }

                if (this.gst_percentage == "") {
                    this.gst_percentage_err_msg = "Gst percentage is required";
                }

                return false;
            }

            if (this.new_product == true && this.product_name == "") {
                this.productNameError = "This field required";
                return false;
            }

            var id = "";
            var product_name;

            if (this.new_product == true) {
                product_name = this.product_name;
            } else {
                id = this.products.id;
                for (let product in this.productsList) {
                    if (this.productsList[product].id == id) {
                        product_name = this.productsList[product].name;
                    }
                }
            }

            //for-calculating-amount
            var _tax_amount = this.rate_per_qty * this.purchase_qty;
            var _tax =
                (this.purchase_qty * this.rate_per_qty * this.gst_percentage) /
                100;

            this.purchaseData.items.push({
                product_id: id,
                purchase_qty: this.purchase_qty,
                product_name: product_name,

                rate_per_qty: this.rate_per_qty,
                gst_percentage: this.gst_percentage,

                taxable_amount: this.rate_per_qty * this.purchase_qty,
                tax:
                    (this.purchase_qty *
                        this.rate_per_qty *
                        this.gst_percentage) /
                    100,

                amount: parseFloat(_tax_amount + _tax).toFixed(2),
            });

            this.new_product = false;

            this.product_name = "";
            this.products = [];
            this.purchase_qty = "";

            this.rate_per_qty = "";
            this.gst_percentage = "";
            this.productErrorMessage = "";
            this.productNameError = "";
            this.purchase_qtyErrorMessage = "";
        },

        deleteRow(index) {
            this.purchaseData.items.splice(index, 1);
        },
        getProducts() {
            axios
                .get("get-sale-product")
                .then((response) => {
                    this.productsList = response.data;
                })
                .catch((err) => {
                    console.log("products errs".err);
                });
        },
        getVendors() {
            axios
                .get("get-vendors")
                .then((response) => {
                    this.vendorsList = response.data;

                    if (this.edit || this.viewOnly) {
                        this.vendors = this.vendorsList.find(
                            (option) => option.id == this.edit_vendor_id
                        );
                    } else {
                        this.vendors = [];
                    }
                })
                .catch((err) => {
                    console.log("vendors errs".err);
                });
        },

        clearData() {
            this.purchaseData.purchase_invoice_no = "";
            this.purchaseData.purchase_date = "";
            this.purchaseData.remarks = "";
            this.purchaseData.items = [];
            this.errors = {};

            this.product_id = "";
            this.purchase_qty = "";
            this.productErrorMessage = "";
            this.vendorMobileError = "";
            this.vendorVendorError = "";

            this.purchase_qtyErrorMessage = "";
            this.new_vendor = false;
            this.purchaseData.vendor_name = "";
            this.vendors = [];
        },

        newVendor(vendorName) {
            this.new_vendor = true;
            this.purchaseData.vendor_name = vendorName;
            this.purchaseData.vendor_id = "";
            this.vendors = [];
        },

        newProduct(productName) {
            this.new_product = true;

            this.product_name = productName;

            this.products = [];
        },
    },

    created() {
        this.getProducts();
        this.getVendors();

        if (this.edit) {
            var _this = this;
            _this.title = "Edit Purchase";
            _this.toastTitle = "Purchase updated successfully";
            bus.$on("edit-purchase", function (purchase) {
                _this.clearData();

                _this.edit_vendor_id = purchase.vendor_id;
                _this.getVendors();
                _this.purchaseData.purchase_invoice_no =
                    purchase.purchase_invoice_no;
                _this.purchaseData.purchase_date = purchase.purchase_date;
                _this.purchaseData.remarks = purchase.remarks;
                _this.purchase_id = purchase.id;

                _this.purchaseData.vendor_id = purchase.vendor_id;
                _this.purchaseData.bill_type = purchase.bill_type;

                _this.purchaseData.pay_type = purchase.pay_type;

                // console.log(purchase.purchase_items)
                for (let item in purchase.purchase_items) {
                    var productId = purchase.purchase_items[item].product_id;
                    var purchaseQty =
                        purchase.purchase_items[item].purchase_qty;

                    var ratePerQty = purchase.purchase_items[item].rate_per_qty;
                    var gstPercentage =
                        purchase.purchase_items[item].gst_percentage;
                    var taxableAmount =
                        purchase.purchase_items[item].taxable_amount;
                    var tax = purchase.purchase_items[item].tax;
                    var totalAmount = purchase.purchase_items[item].amount;

                    for (let product in _this.productsList) {
                        if (_this.productsList[product].id == productId) {
                            var product_name = _this.productsList[product].name;

                            _this.purchaseData.items.push({
                                product_id: productId,
                                purchase_qty: purchaseQty,
                                product_name: product_name,

                                rate_per_qty: ratePerQty,
                                gst_percentage: gstPercentage,
                                taxable_amount: taxableAmount,
                                tax: tax,
                                amount: totalAmount,
                            });
                            //   console.log(_this.productsList[product].id);
                        }
                    }
                }
            });
        }

        if (this.viewOnly) {
            var _this = this;
            _this.reportView = true;

            _this.title = "Purchase Details";
        }
    },

    watch: {
        product_id: function () {
            if (this.product_id != "") {
                this.productErrorMessage = "";
            }
        },

        purchase_qty: function () {
            if (this.purchase_qty != "") {
                this.purchase_qtyErrorMessage = "";
            }
        },

        rate_per_qty: function () {
            if (this.rate_per_qty != "") {
                this.rate_per_qty_error_msg = "";
            }
        },

        gst_percentage: function () {
            if (this.gst_percentage != "") {
                this.gst_percentage_err_msg = "";
            }
        },

        vendors: function () {
            if (this.vendors != null) {
                this.purchaseData.vendor_id = this.vendors.id;
            }
        },
    },
};
</script>
<style scoped>
.xyz {
    max-height: 38px;
}

/*.light-danger{
background-color: rgba(247, 25, 25, 0.247);
 color: rgba(0, 0, 0, 0.705);
}*/
</style>
