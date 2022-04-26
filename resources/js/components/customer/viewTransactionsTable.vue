<template>
    <div>
        <div class="modal-header bg-primary">
            <h5 class="modal-title modal-title-custom" id="addcustomerLabel">
                Transaction History
            </h5>
            <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
            >
                <span aria-hidden="true" class="modal-title-custom"
                    >&times;</span
                >
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div
                    class="alert information-card-warning ml-auto w-100 pl-5"
                    role="alert"
                >
                    <div class="float-left pl-4">
                        <p class="">Customer Name</p>
                        <h4 class="font-weight-bold text-center">
                            {{ customer_name }}
                        </h4>
                    </div>

                    <div class="float-right pr-5">
                        <p class="">Credit Amount</p>
                        <h4 class="font-weight-bold text-center">
                            {{ customer_credit }}/-
                        </h4>
                    </div>
                </div>
            </div>

            <!-- customer detals  -->

            <!-- customer detals ends  -->

            <div class="table-responsive p-3">
                <table
                    class="table table-sm align-items-center table-flush table-hover"
                    id="transactionTable"
                    ref="transactionTable"
                >
                    <thead class="thead-light">
                        <tr>
                            <th>Index</th>
                            <th>Date</th>
                            <th>Description</th>

                            <th>Debit</th>
                            <th>Credit</th>

                            <th>Balance</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(transaction, index) in transactions_list
                                .slice()
                                .reverse()"
                            :key="transaction.id"
                        >
                            <td>{{ index + 1 }}</td>
                            <td>{{ transaction.created_at | myDate }}</td>

                            <td v-if="transaction.invoice_no">
                                Invoiced with Invoice No:{{
                                    transaction.invoice_no
                                }}
                            </td>
                            <td
                                v-else-if="
                                    transaction.remarks == 'MARKED AS BAD DEBT'
                                "
                            >
                                MARKED AS BAD DEBT
                            </td>

                            <td v-else>
                                Payment Received from Receipt No:
                                {{ transaction.payment_id }}
                            </td>

                            <td v-if="transaction.invoice_no">
                                <span
                                    :class="
                                        transaction.remarks ==
                                        'PAYMENT AGAINST CREDIT'
                                            ? 'text-success'
                                            : 'text-danger'
                                    "
                                >
                                    {{ transaction.amount }}</span
                                >
                            </td>
                            <td v-else>0</td>
                            <td v-if="transaction.payment_id">
                                <span
                                    :class="
                                        transaction.remarks ==
                                        'PAYMENT AGAINST CREDIT'
                                            ? 'text-success'
                                            : 'text-danger'
                                    "
                                >
                                    {{ transaction.amount }}</span
                                >
                            </td>
                            <td v-else>0</td>

                            <td>{{ transaction.change }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            transactions_list: [],
            customer_id: "",
            customer_name: "",
            customer_credit: "",
            search: false,
        };
    },

    created() {
        var _this = this;
        bus.$on("transaction-history", function (customer) {
            _this.customer_id = customer.customer_id;
            _this.customer_name = customer.customer_name;
            _this.customer_credit = customer.amount;
            _this.getTransaction();
        });
    },
    methods: {
        getTransaction() {
            this.transactions_list = [];

            axios
                .post("/admin/get-credits-by-customer", {
                    customer_id: this.customer_id,
                })
                .then((response) => {
                    var list = response.data;

                    var created_at;
                    var invoice_no;
                    var payment_id;
                    var remarks;
                    var amount;
                    var change = 0;

                    for (let item in list) {
                        created_at = list[item].created_at;
                        invoice_no = list[item].invoice_no;
                        payment_id = list[item].payment_id;
                        remarks = list[item].remarks;
                        amount = list[item].amount;

                        if (list[item].remarks == "CREDIT SALE") {
                            change = change + parseFloat(list[item].amount);
                        }
                        if (list[item].remarks == "PAYMENT AGAINST CREDIT") {
                            change = change + parseFloat(list[item].amount);
                        }

                        this.transactions_list.push({
                            created_at: created_at,
                            invoice_no: invoice_no,
                            payment_id: payment_id,
                            remarks: remarks,
                            amount: amount,

                            change: change.toFixed(2),
                        });
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },

    watch: {
        transactions_list(val) {
            this.dt.destroy();
            this.$nextTick(() => {
                this.dt = $(this.$refs.transactionTable).DataTable({
                    pageLength: 10,
                    dom: "lBfrtip",
                    buttons: [
                        {
                            extend: "excel",
                            title:
                                "Transaction History - " + this.customer_name,
                        },
                        {
                            extend: "pdf",
                            title:
                                "Transaction History - " + this.customer_name,
                        },
                        {
                            extend: "print",
                            title:
                                "Transaction History - " + this.customer_name,
                        },
                    ],
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
        this.dt = $(this.$refs.transactionTable).DataTable({
            pageLength: 10,
            dom: "lBfrtip",
            buttons: [
                {
                    extend: "excel",
                    title: "Transaction History - " + this.customer_name,
                },
                {
                    extend: "pdf",
                    title: "Transaction History - " + this.customer_name,
                },
                {
                    extend: "print",
                    title: "Transaction History - " + this.customer_name,
                },
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"],
            ],
            bSort: false,
        });
    },
};
</script>

<style scoped></style>
