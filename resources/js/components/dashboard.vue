<template>
    <pre-loader v-if="preLoader"></pre-loader>
    <div class="container-fluid" id="container-wrapper" v-else>
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h4 mb-0 pb-1 text-gray-800">Dashboard</h4>
            <div class="pb-1">
                <select
                    id="inputState"
                    class="btn btn-sm btn-primary arrow active px-0"
                    @change="dateFilter($event)"
                >
                    <option
                        class="bg-light text-dark"
                        value="0"
                        :selected="today == true"
                    >
                        Today
                    </option>
                    <option
                        class="bg-light text-dark"
                        value="1"
                        :selected="week == true"
                    >
                        This Week
                    </option>
                    <option
                        class="bg-light text-dark"
                        value="4"
                        :selected="month == true"
                    >
                        This Month
                    </option>
                    <option
                        class="bg-light text-dark"
                        value="3"
                        :selected="year == true"
                    >
                        This Year
                    </option>
                </select>

                <button
                    class="btn btn-sm btn-primary active px-2 ml-1"
                    @click="viewFilters()"
                    v-if="dateCustom == false"
                >
                    Custom <i class="fas fa-calendar-alt fa-fw"></i>
                </button>
                <button
                    class="btn btn-sm btn-outline-warning ml-1 px-3"
                    @click="hideFilter()"
                    v-if="dateCustom"
                >
                    Clear <i class="far fa-times-circle fa-fw"></i>
                </button>
                <button
                    @click.prevent="clickPdf()"
                    class="btn btn-primary active btn-sm"
                    :disabled="printLoading"
                >
                    {{ print_btn_title }}
                    <i
                        class="fas fa-print fa-fw"
                        v-if="printLoading == false"
                    ></i
                    ><i
                        class="fas fa-spinner fa-spin fa-fw"
                        v-if="printLoading == true"
                    ></i>
                </button>
            </div>
        </div>

        <div class="mb-4" v-if="dateCustom">
            <div class="row justify-content-center">
                <div class="" style="">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span
                                class="input-group-text"
                                id="inputGroup-sizing-sm"
                                >From</span
                            >
                        </div>
                        <input
                            type="Date"
                            class="form-control"
                            v-model="from_date"
                        />
                        <div class="input-group-prepend ml-3">
                            <span
                                class="input-group-text"
                                id="inputGroup-sizing-sm"
                                >To</span
                            >
                        </div>
                        <input
                            type="Date"
                            class="form-control"
                            v-model="to_date"
                        />
                        <button
                            class="btn btn-sm btn-primary ml-4"
                            @click="addFilters()"
                        >
                            Search <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!---------------------------------DASHBOARD PRINT SECTION START--------------------------------------------->
            <div style="display: none">
                <div class="container-fluid" id="dashboard_print">
                    <h6 class="font-weight-bold">DATE: {{ print_date }}</h6>
                    <table
                        id="receipt_table"
                        class="table table-bordered"
                        style="width: 100%; color: black"
                        cellspacing="0"
                        cellpadding="1"
                    >
                        <thead>
                            <tr class="">
                                <th colspan="3">SUMMARY</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="40%">Total Amount Received</td>
                                <td width="40%">
                                    {{ categoryChartlabel[0] }} :
                                    {{
                                        parseFloat(
                                            categoryChartData[0]
                                        ).toFixed(2)
                                    }}
                                    <br />
                                    {{ categoryChartlabel[1] }} :
                                    {{
                                        parseFloat(
                                            categoryChartData[1]
                                        ).toFixed(2)
                                    }}
                                    <br />
                                    {{ categoryChartlabel[2] }} :
                                    {{
                                        parseFloat(
                                            categoryChartData[2]
                                        ).toFixed(2)
                                    }}
                                </td>
                                <td width="20%">
                                    <b>{{
                                        parseFloat(
                                            total_payments_received
                                        ).toFixed(2)
                                    }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td width="40%">Total Expense Amount</td>
                                <td width="40%">
                                    {{ "Bank :"
                                    }}{{
                                        parseFloat(
                                            total_bank_expenses_received
                                        ).toFixed(2)
                                    }}
                                    <br />
                                    {{ "Cash :"
                                    }}{{
                                        parseFloat(
                                            total_cash_expenses_received
                                        ).toFixed(2)
                                    }}
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    <b>{{
                                        parseFloat(
                                            total_expenses_received_amnt
                                        ).toFixed(2)
                                    }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td width="40%">Total Profit</td>
                                <td width="40%">
                                    {{ "Bank : " }}
                                    {{
                                        parseFloat(proftChartValues[0]).toFixed(
                                            2
                                        )
                                    }}
                                    <br />
                                    {{ "Cash : " }}
                                    {{
                                        parseFloat(proftChartValues[1]).toFixed(
                                            2
                                        )
                                    }}
                                </td>
                                <td width="20%">
                                    <b>{{
                                        parseFloat(total_profit).toFixed(2)
                                    }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- sale-details
          <table
            id="receipt_table"
            class="table table-bordered"
            style="width: 100%; color: black"
            cellspacing="0"
            cellpadding="1"
          >
            <thead>
              <tr class="">
                <th colspan="2">SALE DETAILS</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td width="80%">Number Of Sales</td>
                <td width="20%">
                  <b>{{ total_sales_count }}</b>
                </td>
              </tr>

              <tr>
                <td width="80%">Total Sale Amount</td>
                <td width="20%">
                  <b>{{ parseFloat(total_sale_amount).toFixed(2) }}</b>
                </td>
              </tr>

              <tr>
                <td width="">Credit Sale Amount</td>
                <td width="">
                  <b>{{ parseFloat(credit_sales).toFixed(2) }}</b>
                </td>
              </tr>
            </tbody>
          </table> -->
                    <!--transaction-details-->
                    <table
                        id="receipt_table"
                        class="table table-bordered"
                        style="width: 100%; color: black"
                        cellspacing="0"
                        cellpadding="1"
                    >
                        <thead>
                            <tr class="">
                                <th colspan="3">Balance Sheet</th>
                            </tr>
                            <tr class="">
                                <th width="15%">Date</th>
                                <th width="40%">Description</th>
                                <th width="15%">Credit</th>
                                <th width="15%">Debit</th>
                                <th width="15%">Payment Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="transaction in transaction_list"
                                :key="transaction.id"
                            >
                                <td width="15%">{{ transaction.date }}</td>
                                <td width="40%">
                                    {{ transaction.description }}
                                </td>
                                <td width="15%">
                                    <b>{{ transaction.credit }}</b>
                                </td>
                                <td width="15%">
                                    <b>{{ transaction.debit }}</b>
                                </td>
                                <td width="15%">
                                    <b>{{ transaction.paytype }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <b>Total Payments Received</b>
                                </td>
                                <td colspan="1">
                                    <b>{{
                                        parseFloat(
                                            total_payments_received
                                        ).toFixed(2)
                                    }}</b>
                                    <br />
                                    <br />Cash -
                                    {{
                                        parseFloat(
                                            total_cash_payments_received
                                        ).toFixed(2)
                                    }}
                                    <br />
                                    Bank -
                                    {{
                                        parseFloat(
                                            total_bank_payments_received
                                        ).toFixed(2)
                                    }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Total Expenses</b></td>
                                <td>
                                    <b>{{
                                        parseFloat(
                                            total_expenses_received_amnt
                                        ).toFixed(2)
                                    }}</b>
                                    <br />
                                    <br />Cash -
                                    {{
                                        parseFloat(
                                            total_cash_expenses_received
                                        ).toFixed(2)
                                    }}
                                    <br />
                                    Bank -
                                    {{
                                        parseFloat(
                                            total_bank_expenses_received
                                        ).toFixed(2)
                                    }}
                                </td>
                                <td colspan="1"></td>
                            </tr>
                            <tr>
                                <td colspan="4"><b>Total Balance</b></td>
                                <td>
                                    <b>{{
                                        parseFloat(total_profit).toFixed(2)
                                    }}</b>
                                    <br />
                                    <br />Cash -
                                    {{
                                        parseFloat(proftChartValues[1]).toFixed(
                                            2
                                        )
                                    }}
                                    <br />
                                    Bank -
                                    {{
                                        parseFloat(proftChartValues[0]).toFixed(
                                            2
                                        )
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Credit Sale List-->
                    <table
                        id="receipt_table"
                        class="table table-bordered"
                        style="width: 100%; color: black"
                        cellspacing="0"
                        cellpadding="1"
                    >
                        <thead>
                            <tr class="">
                                <th colspan="3">CREDIT SALE DETAILS</th>
                            </tr>
                            <tr>
                                <th width="20%">Date</th>
                                <th width="40%">Customer Name</th>
                                <th width="20%">Invoice No</th>
                                <th width="20%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="credit_sale in credit_sale_list"
                                :key="credit_sale.id"
                            >
                                <td width="20%">
                                    {{
                                        getformatteddate(
                                            credit_sale.invoice_date
                                        )
                                    }}
                                </td>
                                <td width="40%">
                                    {{ credit_sale.customer.name }}
                                </td>
                                <td width="20%">
                                    {{ credit_sale.invoice_no }}
                                </td>
                                <td width="20%">
                                    <b>{{ credit_sale.pay_amount }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Total Credit Given</b></td>
                                <td>
                                    <b>{{
                                        parseFloat(credit_sales).toFixed(2)
                                    }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- sale-details-->
                    <!-- <table
                        id="receipt_table"
                        class="table table-bordered"
                        style="width: 100%; color: black"
                        cellspacing="0"
                        cellpadding="1"
                    >
                        <thead>
                            <tr class="">
                                <th colspan="3">SALE DETAILS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in item_group" :key="item.id">
                                <td width="60%">{{ item.product_name }}</td>
                                <td width="20%">
                                    {{ item.quantity }}
                                </td>
                                <td width="20%">
                                    <b>{{ item.amount }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Total Sale Amount</b></td>
                                <td>
                                    <b>{{
                                        parseFloat(total_sale_amount).toFixed(2)
                                    }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->

                    <!-- expense-details
          <table
            id="receipt_table"
            class="table table-bordered"
            style="width: 100%; color: black"
            cellspacing="0"
            cellpadding="1"
          >
            <thead>
              <tr class="">
                <th colspan="3">EXPENSE DETAILS</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="expense in expense_list" :key="expense.id">
                <td width="40%">{{ expense.expensecategory.name }}</td>
                <td width="40%">
                  {{ expense.pay_type }}
                </td>
                <td width="20%">
                  <b>{{ expense.amount }}</b>
                </td>
              </tr>
              <tr>
                <td colspan="2"><b>Total Expenses</b></td>
                <td>
                  <b>{{
                    parseFloat(total_expenses_received_amnt).toFixed(2)
                  }}</b>
                </td>
              </tr>
            </tbody>
          </table> -->

                    <!--sale item -details-->
                    <!-- <table
            id="receipt_table"
            class="table table-bordered"
            style="width: 100%; color: black"
            cellspacing="0"
            cellpadding="1"
          >
            <thead>
              <tr class="">
                <th colspan="4">SALE ITEM DETAILS</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Product Name</th>
                <th>Rate</th>
                <th>Quantity</th>
                <th>Amount</th>
              </tr>
              <tr v-for="saleItem in sale_item_details" :key="saleItem.id">
                <td width="40%">{{ saleItem.product.name }}</td>
                <td width="20%">
                  {{ saleItem.rate }}
                </td>

                <td width="20%">
                  {{ parseFloat(saleItem.quantity) }}
                </td>

                <td width="20%">
                  {{ saleItem.total_rate }}
                </td>
              </tr>
              <tr>
                <td colspan="3"><b>Total</b></td>
                <td>
                  <b>{{ parseFloat(total_itemsale_amount).toFixed(2) }}</b>
                </td>
              </tr>
            </tbody>
          </table> -->

                    <!--service item -details-->
                    <!-- <table
            id="receipt_table"
            class="table table-bordered"
            style="width: 100%; color: black"
            cellspacing="0"
            cellpadding="1"
          >
            <thead>
              <tr class="">
                <th colspan="4">SERVICE ITEM DETAILS</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Service Name</th>
                <th>Rate</th>
                <th>Quantity</th>
                <th>Gross Amount</th>
              </tr>
              <tr
                v-for="serviceItem in service_item_details"
                :key="serviceItem.id"
              >
                <td width="40%">{{ serviceItem.product.name }}</td>
                <td width="20%">
                  {{ serviceItem.rate }}
                </td>

                <td width="20%">
                  {{ parseFloat(serviceItem.quantity) }}
                </td>

                <td width="20%">
                  {{ serviceItem.total_rate }}
                </td>
              </tr>
              <tr>
                <td colspan="3"><b>Total</b></td>
                <td>
                  <b>{{ parseFloat(total_servicesale_amount).toFixed(2) }}</b>
                </td>
              </tr>
            </tbody>
          </table>-->
                </div>
            </div>

            <!---------------------------------DASHBOARD PRINT SECTION END----------------------------------------------->

            <div class="container-fluid px-0">
                <!----------DASHBOARD-NEW-STYLE-TEST-START-------------------------------------------------------------------->

                <!----3diffrent cards section-start------------------------->
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="card mb-3 pb-4">
                            <div
                                class="card-header py-0 d-flex flex-row align-items-center justify-content-between"
                            ></div>

                            <div class="card-body">
                                <div class="row mb-2">
                                    <!-- New User Card Example -->
                                    <div class="col-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="row no-gutters align-items-center"
                                                >
                                                    <div class="col mr-2">
                                                        <div
                                                            class="text-xs font-weight-bold text-uppercase mb-1"
                                                        >
                                                            Total Amount
                                                            Received
                                                        </div>
                                                        <div
                                                            class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                        >
                                                            <i
                                                                class="fas fa-rupee-sign fa-fw"
                                                            ></i>
                                                            {{
                                                                parseFloat(
                                                                    total_payments_received
                                                                ).toFixed(2)
                                                            }}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i
                                                            class="fas fa-coins fa-2x text-primary"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-12">
                                        <pie-chart
                                            :inputlabel="
                                                this.categoryChartlabel
                                            "
                                            :inputdata="this.categoryChartData"
                                            :width="250"
                                            :height="250"
                                        ></pie-chart>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 values text-center row">
                                <div class="col">
                                    <h6 class="text-xs">
                                        {{ categoryChartlabel[0] }} :
                                    </h6>
                                    <h6 class="text-xs ml-1">
                                        {{ categoryChartlabel[1] }} :
                                    </h6>
                                    <h6 class="text-xs ml-1">
                                        {{ categoryChartlabel[2] }} :
                                    </h6>
                                </div>

                                <div class="col">
                                    <h6 class="font-weight-bold text-xs">
                                        <i class="fas fa-rupee-sign fa-fw"></i
                                        >{{
                                            parseFloat(
                                                categoryChartData[0]
                                            ).toFixed(2)
                                        }}
                                    </h6>
                                    <h6 class="font-weight-bold text-xs">
                                        <i class="fas fa-rupee-sign fa-fw"></i
                                        >{{
                                            parseFloat(
                                                categoryChartData[1]
                                            ).toFixed(2)
                                        }}
                                    </h6>
                                    <h6 class="font-weight-bold text-xs">
                                        <i class="fas fa-rupee-sign fa-fw"></i
                                        >{{
                                            parseFloat(
                                                categoryChartData[2]
                                            ).toFixed(2)
                                        }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!------------------------>

                    <!-----2-rd------------------->
                    <div class="col-md-4 col-lg-4">
                        <div class="card mb-3 pb-3">
                            <div
                                class="card-header py-0 d-flex flex-row align-items-center justify-content-between"
                            ></div>
                            <div class="card-body">
                                <div class="row mb-0">
                                    <!-- New User Card Example -->
                                    <div class="col-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="row no-gutters align-items-center"
                                                >
                                                    <div class="col mr-2">
                                                        <div
                                                            class="text-xs font-weight-bold text-uppercase mb-1"
                                                        >
                                                            Total Expense Amount
                                                        </div>
                                                        <div
                                                            class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                        >
                                                            <i
                                                                class="fas fa-rupee-sign fa-fw"
                                                            ></i>
                                                            {{
                                                                parseFloat(
                                                                    total_expenses_received_amnt
                                                                ).toFixed(2)
                                                            }}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i
                                                            class="fas fa-search-dollar fa-2x text-danger"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-2 pb-3">
                                    <div
                                        class="col-12 pb-4"
                                        v-if="expenseCategoryData.length == ''"
                                        style="height: 232px; max-height: 232px"
                                    >
                                        <h6 class="text-center pt-5 mt-3">
                                            No Expenses Found
                                        </h6>
                                        <div class="div text-center">
                                            <i
                                                class="far fa-smile fa-4x"
                                                style="color: #376e37"
                                            ></i>
                                        </div>
                                    </div>

                                    <div
                                        v-if="expenseCategoryData.length > 0"
                                        class="col-12"
                                    >
                                        <bar-chart
                                            :inputlabel="
                                                this.expenseCategoryLabel
                                            "
                                            :inputdata="
                                                parseFloat(
                                                    this.expenseCategoryData
                                                ).toFixed(2)
                                            "
                                            :height="234"
                                            :width="234"
                                        ></bar-chart>
                                    </div>
                                </div>
                                <div class="row barchart-exp mt-4">
                                    <div class="col">
                                        <div
                                            class="px-2 text-center"
                                            v-for="ex in expenseCategoryLabel"
                                            :key="ex"
                                        >
                                            <h6 class="text-xs">{{ ex }} :</h6>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div
                                            class="px-2 text-center"
                                            v-for="ed in expenseCategoryData"
                                            :key="ed"
                                        >
                                            <h6
                                                class="font-weight-bold text-xs"
                                            >
                                                <i
                                                    class="fas fa-rupee-sign fa-fw"
                                                ></i
                                                >{{ ed }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----2-end------------------>
                    <!-----3rd-------------->
                    <div class="col-md-4 col-lg-4">
                        <div class="card mb-3 pb-4">
                            <div
                                class="card-header py-0 d-flex flex-row align-items-center justify-content-between"
                            ></div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <!-- New User Card Example -->
                                    <div class="col-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="row no-gutters align-items-center"
                                                >
                                                    <div class="col mr-2">
                                                        <div
                                                            class="text-xs font-weight-bold text-uppercase mb-1"
                                                        >
                                                            Total Profit
                                                        </div>
                                                        <div
                                                            class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                        >
                                                            <i
                                                                class="fas fa-rupee-sign fa-fw"
                                                            ></i>
                                                            {{
                                                                parseFloat(
                                                                    total_profit
                                                                ).toFixed(2)
                                                            }}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i
                                                            class="fas fa-hand-holding-usd fa-2x text-success"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-2 pb-3">
                                    <div class="col-12">
                                        <pie-chart
                                            :inputlabel="this.proftChartLabel"
                                            :inputdata="this.proftChartValues"
                                            :height="230"
                                            :width="230"
                                        ></pie-chart>
                                    </div>
                                </div>
                            </div>

                            <div class="px-2 values text-center mb-4">
                                <h6 class="text-xs">
                                    {{ "Bank Profit" }} :<span
                                        class="font-weight-bold"
                                        ><i class="fas fa-rupee-sign fa-fw"></i
                                        >{{
                                            parseFloat(
                                                total_bank_profit
                                            ).toFixed(2)
                                        }}</span
                                    >
                                    <span class="text-xs ml-1"></span>
                                </h6>
                                <h6 class="text-xs">
                                    {{ "Cash Profit" }} :<span
                                        class="font-weight-bold text-xs"
                                        ><i class="fas fa-rupee-sign fa-fw"></i
                                        >{{
                                            parseFloat(
                                                total_cash_profit
                                            ).toFixed(2)
                                        }}
                                    </span>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <!-----3rd end--->
                </div>
                <!---3diffrentcards sectionend------------------------------>

                <!----------DASHBOARD-NEW-STYLE-TEST-END--------------------------------------------------------------->

                <!----------SALES CARD START--------------------------------------------------------------->
                <div class="card mb-3">
                    <div
                        class="card-header pt-3 d-flex flex-row align-items-center justify-content-between"
                    >
                        <h6 class="m-0 font-weight-bold text-primary">
                            Sales Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-md-4 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Number Of Sales
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{ total_sales_count }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="far fa-handshake fa-2x text-success"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New User Card Example -->
                            <div class="col-md-4 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Total Sale Amount
                                                </div>
                                                <div
                                                    class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                >
                                                    <i
                                                        class="fas fa-rupee-sign fa-fw"
                                                    ></i>
                                                    {{
                                                        parseFloat(
                                                            total_sale_amount
                                                        ).toFixed(2)
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-sort-amount-up fa-2x text-info"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Earnings (Annual) Card Example -->
                            <div class="col-md-4 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Credit Sales Amount
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    <i
                                                        class="fas fa-rupee-sign fa-fw"
                                                    ></i>
                                                    {{
                                                        parseFloat(
                                                            credit_sales
                                                        ).toFixed(2)
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-wallet fa-2x text-warning"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!----------SALES CARD END--------------------------------------------------------------->

                <!----------JOBCARDS CARD START--------------------------------------------------------------->
                <div class="card mb-3">
                    <div
                        class="card-header pt-3 d-flex flex-row align-items-center justify-content-between"
                    >
                        <h6 class="m-0 font-weight-bold text-primary">
                            Jobcard Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-md-2 col-lg-2 mb-2">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Number Of Jobcards
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{ total_jobcards }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 mb-2">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Pending <br />Completion
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        total_jobcards_pending_completion
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2 mb-2">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Pending Delivery
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        total_jobcards_pending_delivery
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- New User Card Example -->

                            <!-- Earnings (Annual) Card Example -->
                            <div class="col-md-3 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col mr-2">
                                                <div
                                                    class="text-xs font-weight-bold text-uppercase mb-1"
                                                >
                                                    Total received
                                                </div>
                                                <div
                                                    class="h5 mb-0 font-weight-bold text-gray-800"
                                                >
                                                    <i
                                                        class="fas fa-rupee-sign fa-fw"
                                                    ></i>
                                                    {{
                                                        parseFloat(
                                                            total_jobcard_received_amount
                                                        ).toFixed(2)
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i
                                                    class="fas fa-rupee-sign fa-2x text-success"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!----------JOBCARDS CARD END--------------------------------------------------------------->

                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="card mb-4" style="min-height: 95%">
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                            >
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Low Stock Items
                                </h6>
                            </div>
                            <div class="table-responsive p-3">
                                <table
                                    class="table table-sm align-items-center table-flush table-hover display"
                                    id="low_stock_items_table"
                                    ref="low_stock_items_table"
                                    data-order=""
                                >
                                    <thead class="bg-low-stock text-light">
                                        <tr>
                                            <th>sl.no</th>
                                            <th>Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                lowstock, index
                                            ) in lowStockItems"
                                            :key="lowstock.id"
                                        >
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ lowstock.name }}</td>
                                            <td>
                                                {{
                                                    parseInt(lowstock.quantity)
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--col-end-->
                    <!-- <div class="col-md-6 col-lg-6">
            <div class="card mb-4">
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
                <h6 class="m-0 font-weight-bold text-primary">
                  Low Stock Items
                </h6>
              </div>

              <bar-chart
                :inputlabel="this.chartlabel"
                :inputdata="this.chartData"
              ></bar-chart>
            </div>
          </div> -->

                    <div class="col-md-6 col-lg-6">
                        <div class="card mb-4" style="min-height: 95%">
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                            >
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Customer Credit Report
                                </h6>
                            </div>
                            <div class="table-responsive p-3">
                                <table
                                    class="table table-sm align-items-center table-flush table-hover display"
                                    id="creditTableDash"
                                    ref="creditTableDash"
                                    data-order=""
                                >
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th>sl.no</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                credit, index
                                            ) in customerCredits"
                                            :key="credit.id"
                                        >
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ credit.customer_name }}</td>
                                            <td>
                                                <i
                                                    class="fas fa-rupee-sign"
                                                ></i>
                                                {{ credit.amount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--col-end-->
                </div>
                <!--end-row-->
            </div>

            <!----->
        </div>
    </div>
</template>

<script>
import html2pdf from "html2pdf.js";

import barChart from "./charts/barChart.vue";
import pieChart from "./charts/pieChart.vue";

import moment from "moment";
export default {
    components: {
        barChart,
        pieChart,
    },
    data() {
        return {
            preLoader: false,
            printLoading: false,
            dashboard_print_area: false,
            week: "",
            today: "",
            month: "",
            year: "",
            dateCustom: false,
            chartlabel: [],
            chartData: [],
            paymentChartlabel: [],
            paymentChartData: [],

            categoryChartlabel: [],
            categoryChartData: [],

            expenseCategoryLabel: [],
            expenseCategoryData: [],

            proftChartLabel: [],
            proftChartValues: [],

            sale_item_details: [],
            service_item_details: [],

            from_date: "",
            to_date: "",
            print_date: "",

            total_sales_count: "",
            credit_sales: "",
            total_sale_amount: "",
            total_payments_received: "",
            total_bank_payments_received: "",
            total_cash_payments_received: "",
            total_expenses_received_amnt: "",
            total_profit: "",

            total_bank_expenses_received: "",
            total_cash_expenses_received: "",

            total_itemsale_amount: "",
            total_servicesale_amount: "",

            total_cash_profit: "",
            total_bank_profit: "",

            expense_list: [],
            transaction_list: [],
            item_group: [],
            credit_sale_list: [],
            customerCredits: [],

            lowStockItems: [],

            lowStock: [],

            dashBoardData: {},

            total_jobcards: "",
            total_jobcards_pending_completion: "",
            total_jobcards_pending_delivery: "",
            total_jobcards_delivered: "",
            total_jobcard_amount: "",
            total_jobcard_received_amount: "",
            jobcard_item_details: [],
        };
    },
    mounted() {
        this.dt = $(this.$refs.creditTableDash).DataTable({
            bInfo: false,
            bLengthChange: false,
            pageLength: 7,
        });

        this.dt = $(this.$refs.low_stock_items_table).DataTable({
            bInfo: false,
            bLengthChange: false,
            pageLength: 7,
        });
    },

    watch: {
        customerCredits(val) {
            this.dt.destroy();
            this.$nextTick(() => {
                this.dt = $(this.$refs.creditTableDash).DataTable({
                    bInfo: false,
                    bLengthChange: false,
                    pageLength: 7,
                });
            });
        },

        lowStockItems(val) {
            this.dt.destroy();
            this.$nextTick(() => {
                this.dt = $(this.$refs.low_stock_items_table).DataTable({
                    bInfo: false,
                    bLengthChange: false,
                    pageLength: 7,
                });
            });
        },
    },

    methods: {
        getformatteddate(date) {
            return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
        },

        getDashboard() {
            this.preLoader = true; //the loading begin
            axios
                .post("get-dashboard", {
                    from_date: this.from_date,
                    to_date: this.to_date,
                })
                .then((res) => {
                    this.total_sales_count = res.data[0].total_sales;
                    this.credit_sales = res.data[0].total_sale_credit_amount;
                    this.total_sale_amount = res.data[0].total_sale_amount;
                    this.total_payments_received =
                        res.data[0].total_payments_received;
                    this.total_bank_payments_received =
                        res.data[0].total_bank_payments_received;
                    this.total_cash_payments_received =
                        res.data[0].total_cash_payments_received;

                    this.total_expenses_received_amnt =
                        res.data[0].total_expenses_received;
                    this.total_profit = res.data[0].total_profit;

                    this.total_bank_expenses_received =
                        res.data[0].total_bank_expenses_received;
                    this.total_cash_expenses_received =
                        res.data[0].total_cash_expenses_received;

                    this.expense_list = res.data[0].expense_list;
                    this.transaction_list = res.data[0].transactions;
                    this.credit_sale_list = res.data[0].credit_sale_list;
                    this.item_group = res.data[0].item_group;
                    this.print_date = res.data[0].print_date;

                    this.lowStockItems = res.data[0].low_stock_items;

                    this.total_itemsale_amount =
                        res.data[0].total_itemsale_amount;
                    this.total_servicesale_amount =
                        res.data[0].total_servicesale_amount;

                    this.proftChartValues = [];
                    this.proftChartLabel = [];

                    if (this.total_profit < 0) {
                        this.total_bank_profit = res.data[0].total_bank_profit;
                        this.total_cash_profit = res.data[0].total_cash_profit;
                        this.proftChartValues.push(0, 0);
                        this.proftChartLabel.push("Bank", "Cash");
                    }
                    if (this.total_profit >= 0) {
                        this.total_bank_profit = res.data[0].total_bank_profit;
                        this.total_cash_profit = res.data[0].total_cash_profit;

                        this.proftChartValues.push(
                            this.total_bank_profit,
                            this.total_cash_profit
                            //   res.data[0].total_bank_profit,
                            //   res.data[0].total_cash_profit
                        );
                        this.proftChartLabel.push("Bank", "Cash");
                    }

                    //inside-main-custumer-with-credits-array
                    this.customerCredits = res.data[0].customer_with_credits;
                    this.lowStock = res.data[0].low_stock_items;

                    this.dashBoardData = res.data;

                    if (res.data) {
                        this.chartlabel = res.data[0].low_stock_item_names;
                        this.chartData = res.data[0].low_stock_item_quantities;

                        this.paymentChartData =
                            res.data[0].piechart_income_payment_values;

                        this.paymentChartlabel =
                            res.data[0].piechart_income_payment_names;

                        this.expenseCategoryLabel =
                            res.data[0].piechart_expense_category_name;
                        this.expenseCategoryData =
                            res.data[0].piechart_expense_category_amount;

                        this.categoryChartData =
                            res.data[0].piechart_income_category_values;

                        this.categoryChartlabel =
                            res.data[0].piechart_income_category_names;
                        this.sale_item_details = res.data[0].sale_item_details;
                        this.service_item_details =
                            res.data[0].service_item_details;

                        this.total_jobcards = res.data[0].total_jobcards;
                        this.total_jobcards_pending_completion =
                            res.data[0].total_jobcards_pending_completion;
                        this.total_jobcards_pending_delivery =
                            res.data[0].total_jobcards_pending_delivery;
                        this.total_jobcards_delivered =
                            res.data[0].total_jobcards_delivered;
                        this.total_jobcard_amount =
                            res.data[0].total_jobcard_amount;
                        this.total_jobcard_received_amount =
                            res.data[0].total_jobcard_received_amount;
                        this.jobcard_item_details =
                            res.data[0].jobcard_item_details;
                    }
                    this.preLoader = false;
                })
                .catch((err) => {
                    this.preLoader = false;
                    console.log(err);
                });
        },

        dateFilter(event) {
            //value-0 =Today
            if (event.target.value == 0) {
                this.from_date = "";
                this.to_date = "";
                this.getDashboard();
                this.today = true;
                this.week = false;
                this.month = false;
                this.year = false;
            }
            //value-1 =this-week
            if (event.target.value == 1) {
                this.from_date = moment()
                    .startOf("isoWeek")
                    .format("YYYY-MM-DD");
                this.to_date = moment().endOf("isoWeek").format("YYYY-MM-DD");
                this.getDashboard();
                this.today = false;
                this.week = true;
                this.month = false;
                this.year = false;
            }

            //value-5 =This month
            if (event.target.value == 4) {
                this.from_date = moment().startOf("month").format("YYYY-MM-DD");
                this.to_date = moment().endOf("month").format("YYYY-MM-DD");
                this.getDashboard();
                this.today = false;
                this.week = false;
                this.month = true;
                this.year = false;
            }

            //value-3=this-year
            if (event.target.value == 3) {
                this.from_date = moment().startOf("year").format("YYYY-MM-DD");
                this.to_date = moment().endOf("year").format("YYYY-MM-DD");
                this.getDashboard();
                this.today = false;
                this.week = false;
                this.month = false;
                this.year = true;
            }
        },

        viewFilters() {
            this.dateCustom = true;
        },
        hideFilter() {
            this.getDashboard();
            this.dateCustom = false;
            this.from_date = "";
            this.to_date = "";

            this.today = true;
            this.week = false;
            this.month = false;
            this.year = false;
        },
        addFilters() {
            this.getDashboard();
            this.from_date = "";
            this.to_date = "";
        },

        clickPdf() {
            var _this = this;
            _this.printLoading = true;
            var element = document.getElementById("dashboard_print");

            var opt = {
                margin: 0.25,
                filename: "dash",
                // image: { type: "jpeg", quality: 1 },
                html2canvas: { scale: 2, letterRendering: true },
                // html2canvas: { scale: 6, logging: true },
                jsPDF: { unit: "in", format: "a4", orientation: "portrait" },

                pagebreak: { avoid: "tr" },
            };

            //

            var result = html2pdf()
                .set(opt)
                .from(element)
                .toPdf()
                .get("pdf")
                .then(function (pdf) {
                    //  pdf.autoPrint();
                    window.open(pdf.output("bloburl"), "_blank");
                    _this.printLoading = false;
                });

            //  var result = html2pdf().from(element).set(opt).save();
        },
    },

    computed: {
        print_btn_title() {
            if (this.printLoading == true) {
                return "Processing  ";
            } else {
                return "Print";
            }
        },
    },

    created() {
        this.getDashboard();
    },
};
</script>

<style scoped>
.bottom-wrapper {
    margin-top: 0.5em;
}
.stats strong {
    display: block;
    color: #35393bd0;
    font-size: 16px;
    font-weight: 700;
}
.dates span {
    width: 1px;
    height: 40px;
    position: absolute;
    right: 0;
    top: 0;
    background: #ebeff2;
}
.stats {
    height: 320px;
    border-top: 1px solid #ebeff2;
    border-bottom: 8px solid #328bcfd3;
    background: #6777ef1e;

    overflow: auto;
    padding: 15px 0;
    font-size: 16px;
    color: rgba(255, 0, 0, 0.562);
    font-weight: 600;
    border-radius: 0 0 5px 5px;
}
.stats div {
    border-right: 1px solid #eaedf0;
    width: 33.33333%;
    float: left;
    text-align: center;
}

.stats div:nth-of-type(3) {
    border: none;
}

.barchart-exp {
    height: 66px;
    max-height: 66px;
    overflow-y: auto;
}

.barchart-exp::-webkit-scrollbar {
    width: 12px;
}

.barchart-exp::-webkit-scrollbar-track {
    box-shadow: #35393b3b;
    -webkit-box-shadow: inset 0 0 6px rgba(48, 115, 170, 0.699);
    border-radius: 10px;
}

.barchart-exp::-webkit-scrollbar-thumb {
    border-radius: 10px;
    box-shadow: #35393b34;
    -webkit-box-shadow: inset 0 0 6px rgba(112, 127, 216, 0.5);
}

#receipt_table td,
#receipt_table th {
    border: 1px solid #ddd;
    padding-top: 8px;
    padding-left: 8px;
    padding-bottom: 8px;
    word-break: break-all;
    text-align: left;
    word-wrap: break-word;
    font-size: 11px;
    font-family: Arial, Helvetica, sans-serif;
}
#receipt_table thead {
    background-color: #b1c3de;
    font-family: Arial, Helvetica, sans-serif;
}

.bg-low-stock {
    background-color: rgba(255, 0, 0, 0.514);
}
</style>
