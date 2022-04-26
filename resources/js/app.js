/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");



window.Vue = require("vue").default;
window.bus = new Vue();
export const bus = new Vue();

import VueRouter from 'vue-router'

import 'datatables.net-dt'


import jsZip from 'jszip';

import 'pdfmake'
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;


import 'datatables.net-buttons-dt'
import 'datatables.net-bs4'
import 'datatables.net-buttons-bs4'

import 'datatables.net-buttons/js/buttons.html5.js'
import 'datatables.net-buttons/js/buttons.print.js'
import 'datatables.net-buttons/js/buttons.colVis.js'

//for-excel
window.JSZip = jsZip;





// This line was the one missing

import Multiselect from 'vue-multiselect'
// register globally
Vue.component('multiselect', Multiselect)




import vSelect from 'vue-select'

Vue.component('v-select', vSelect);
import 'vue-select/dist/vue-select.css';

import moment from "moment";
//for show date in correct format on vue component
Vue.filter("myDate", function (dateFormatted) {
    return moment(dateFormatted).format("DD - MM - YYYY");
});



import 'vue-search-select/dist/VueSearchSelect.css'


//vue js social sharing
import VueSocialSharing from "vue-social-sharing";

Vue.use(VueSocialSharing);



Vue.use(VueRouter);

import Swal from "sweetalert2";
window.Swal = Swal;



const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});
window.Toast = Toast;

const routes = [
    { path: "/", component: require("./components/dashboard.vue").default },

    {
        path: "/products",
        component: require("./components/product/productsTable.vue").default,
    },
    {
        path: "/customers",
        component: require("./components/customer/customersTable.vue").default,

    },
    {
        path: "/sales",
        component: require("./components/sale/salesTable.vue").default,

    },

    {
        path: "/vendor-sales",
        component: require("./components/vendorsales/vendorSalesTable.vue").default,

    },


    {
        path: "/quotations",
        component: require("./components/quotation/quotationsTable.vue").default,
    },
    {
        path: "/sales-return",
        component: require("./components/sale/addSalesReturn.vue").default,
    },


    {
        path: "/purchase",
        component: require("./components/purchase/purchaseTable.vue").default,
    },
    {
        path: "/expense-categories",
        component: require("./components/expenses/expenseCategoriesTable.vue").default,

    },
    {
        path: "/income-categories",
        component: require("./components/incomes/incomeCategoriesTable.vue").default,

    },

    {
        path: "/expenses",
        component: require("./components/expenses/expenses.vue").default,

    },

    {
        path: "/incomes",
        component: require("./components/incomes/incomes.vue").default,

    },

    {
        path: "/sales-report",
        component: require("./components/sale/salesTable.vue").default,
       props:{report:true}
    },
    {
        path: "/quotation-report",
        component: require("./components/quotation/quotationReport.vue").default,
    },
    {
        path: "/sales-return-report",
        component: require("./components/sale/salesReturnReport.vue").default,
    },
    {
        path: "/purchase-report",
        component: require("./components/purchase/purchaseReport.vue").default,
    },
    {
        path: "/customer-credit-report",
        component: require("./components/customer/customerCreditReport.vue").default,
    },
    {
        path: "/stock-report",
        component: require("./components/stock/stockReport.vue").default,
    },
    {
        path: "/payment-report",
        component: require("./components/payments/paymentReport.vue").default,
    },
    {
        path: "/expense-report",
        component: require("./components/expenses/expenseReport.vue").default,
    },
    {
        path: "/income-report",
        component: require("./components/incomes/incomeReport.vue").default,
    },
    {
        path: "/item-report",
        component: require("./components/itemReport.vue").default,
    },
    {
        path: "/stock-adjustments",
        component: require("./components/stock/stockAdjustmentsTable.vue").default,
    },
    {
        path: "/reward-sale",
        component: require("./components/rewardsale/rewardSaleTable.vue").default,
    },

    {
        path: "/employees",
        component: require("./components/employee/employeeTable.vue").default,
    },


    {
        path: "/jobcard",
        component: require("./components/jobcard/jobcardTable.vue").default,
    },
    {
        path: "/jobcard-report",
        component: require("./components/jobcard/jobcardReport.vue").default,
    },
    {
        path: "/vendors",
        component: require("./components/vendor/vendorsTable.vue").default,

    },
    {
        path: "/purchase-credit-report",
        component: require("./components/purchase/purchaseCreditReport.vue").default,

    },

    {
        path: "/add-expense",
        component: require("./components/expenses/addExpense.vue").default,

    },
    {
        path: "/users",
        component: require("./components/user/usersTable.vue").default,

    },

    {
        path: "/closings",
        component: require("./components/closings/closing.vue").default,

    },
    {
        path: "/closing-report",
        component: require("./components/closings/closingReport.vue").default,

    },

    {
        path: "/purchase-orders",
        component: require("./components/purchase/purchaseOrders.vue").default,

    },
];

const router = new VueRouter({

    routes, // short for `routes: routes`
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("add-product", require("./components/product/addProduct.vue").default);
Vue.component("add-customer", require("./components/customer/addCustomer.vue").default);
Vue.component("add-purchase", require("./components/purchase/addPurchase.vue").default);
Vue.component("add-sale", require("./components/sale/addSale.vue").default);

Vue.component("add-vendorsale", require("./components/vendorsales/addVendorSale.vue").default);
Vue.component("vendor-sale-invoice", require("./components/vendorsales/vendorSalesInvoice.vue").default);

Vue.component("add-quotation", require("./components/quotation/addQuotation.vue").default);
Vue.component("quotation-print", require("./components/quotation/quotationPrint.vue").default);

Vue.component("add-adjustment", require("./components/stock/addAdjustment.vue").default);
Vue.component("add-vendor", require("./components/vendor/addVendor.vue").default);

Vue.component("add-expense-category", require("./components/expenses/addExpenseCategory.vue").default);
Vue.component("add-income-category", require("./components/incomes/addIncomeCategory.vue").default);
Vue.component("add-expense", require("./components/expenses/addExpense.vue").default);
Vue.component("add-income", require("./components/incomes/addIncome.vue").default);


Vue.component("purchase-payment-transactions", require("./components/purchase/purchasePayTransactions.vue").default);
Vue.component("add-purchase-credit-payment", require("./components/purchase/purchaseCreditPayment.vue").default);


Vue.component("view-transactions", require("./components/customer/viewTransactionsTable.vue").default);
Vue.component("add-payment", require("./components/payments/addPayment.vue").default);
Vue.component("add-reward-sale", require("./components/rewardsale/addRewardSale.vue").default);

Vue.component("sales-invoice", require("./components/sale/salesInvoice.vue").default);
Vue.component("payment-receipt", require("./components/payments/paymentReceipt.vue").default);


Vue.component("add-employee", require("./components/employee/addEmployee.vue").default);

Vue.component("add-jobcard", require("./components/jobcard/addJobcard.vue").default);
Vue.component("add-jobcard-payment", require("./components/jobcard/addJobcardPayment.vue").default);
Vue.component("jobcard-print", require("./components/jobcard/jobcardPrint.vue").default);



Vue.component("add-user", require("./components/user/addUser.vue").default);
Vue.component("navbar-search", require("./components/search/navbarSearch.vue").default);
Vue.component("search-card", require("./components/search/searchCard.vue").default);


Vue.component("view-closing", require("./components/closings/viewClosing.vue").default);


Vue.component("add-purchase-order", require("./components/purchase/addPurchaseOrder.vue").default);

// Vue.component("z-test", require("./components/ztest.vue").default);







// Vue.component("add-purchase-test", require("./components/addPurchaseTest.vue").default);



Vue.component("add-test", require("./components/test.vue").default);








Vue.component("change-password", require("./components/changePassword.vue").default);

Vue.component("pre-loader", require("./components/Loading.vue").default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
});
