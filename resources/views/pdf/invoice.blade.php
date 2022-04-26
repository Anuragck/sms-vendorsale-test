<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=0.1">
    <meta name="description" content="Bodyshop">
    <meta name="keywords" content="Bodyshop">
    <meta name="XEECODE" content="SandOne">
    <title>INVOICE PRINT</title>

    {{-- <style>
        /*header { position: fixed; top: -30px; left: 0px; right: 0px; height: 20px; }*/

        html body {
            font-family: arial, sans-serif;
            font-size: 13px;
            color: #000000;
            margin: 0px;
            /* background: #fff; */
        }

        table {
            background-color: transparent;

        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 10px;
        }

        .table-bordered {
            border: 1px solid #000;
        }

        .table-test {
            border: 1px solid #000;

        }

        .border {
            border: 1px solid #000;
            padding: 4px;
        }

        .m-8 {
            margin-left: 8px;
            margin-right: 8px;
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th {
            border: 1px solid #000;
        }

        td,
        th {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .col-2 {
            float: left;
            width: 16.66666667%;
        }

        .col-3 {
            float: left;
            width: 25%;
        }

        .col-4 {
            float: left;
            width: 33.33333333%;
        }

        .col-6 {
            float: left;
            width: 50%;
        }

        .col-8 {
            float: left;
            width: 66.66666667%;
        }

        .col-10 {
            float: left;
            width: 83.33333333%;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-6 {
            margin-bottom: 6px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .dotted {
            border: none;
            border-bottom: 1px dotted #040404;
            color: #fff;
            background-color: #fff;
            margin: 0 13px 0 11px;
        }

        .clearfix {

            clear: both;
        }

        .ptb-0 {
            padding-top: 0;
            padding-bottom: 0;
        }

        .text-right {
            text-align: right;
        }

    </style> --}}

    <style>
@page { margin: 5px 8px; }
body { margin: 5px 8px; }
        td {
            font-size: 12px;
        }

        .container {
            color: black;
        }

        table.table-bordered {
            color: black;
            border: 1px solid black;
            margin-top: 20px;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid black;
        }

        td {
            padding: 5px;
        }

        th {
            padding: 5px;
        }

        table {
            background-color: transparent;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .items-tr-h {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            font-size: 11px;
        }

        .items-tr {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }
        .items-tr-signature {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            font-weight: bold;
            font-size: 12px;
        }

        .items-ftr {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            font-weight: bold;
            font-size: 12px;
        }

        .border-bottom-only {
            border-bottom: 1px solid black;
            border-right: 0px;
            border-left: 0px;
            border-top: 0px;
        }

        #thermal-bill {
            font-size: 5px;

            color: black;
        }

        #thermal-table td {
            color: black;
            font-size: 5px;
        }

        /**********NEW THERMAL PAPER  */
        #thermal_print_new * {
            font-size: 12px;
            font-family: "Times New Roman";
            color: black;
        }

        #thermal_print_new .table-center {
            margin-left: auto;
            margin-right: auto;
        }

        #thermal_print_new td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        #thermal_print_new table.test {
            border-collapse: collapse;
        }

        #thermal_print_new td.description,
        th.description {
            width: 75px;
            max-width: 75px;
        }

        #thermal_print_new td.quantity,
        th.quantity {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        #thermal_print_new td.price,
        th.price {
            width: 50px;
            max-width: 50px;
            word-break: break-all;
        }

        #thermal_print_new .centered {
            text-align: center;
            align-content: center;
        }

        #thermal_print_new .right {
            text-align: right;
            align-content: right;
        }

        #thermal_print_new .ticket {
            width: 200px;
            max-width: 200px;
        }

        /********NEW THERMAL PAPER */

    </style>

</head>

<body>
    @if ($gst == 0)
        <div>
            <div class="container">
                <div id="canvas_div_pdf">
                    <div class="text-center">
                        <h6 style="text-align: center;">Estimate Invoice</h6>
                    </div>
                    @if($companyDetails[0]->logo)
                    <img class="d-block img-fluid" src="{{ URL::asset('/images/' . $companyDetails[0]->logo) }}" alt="logo"
                        style="width: 80px" />
                    @endif
                    <table class="table table-bordered mb-5" width="100%" style="margin-bottom: 0">
                        <tbody>
                            <tr>
                                <td colspan="11">
                                    <b>{{ $companyDetails[0]->name }}</b> <br />
                                    {{ $companyDetails[0]->address_1 }}<br />
                                    {{ $companyDetails[0]->address_2 }}<br />
                                    {{ $companyDetails[0]->district }} - {{ -$companyDetails[0]->pincode }}<br />
                                    {{ $companyDetails[0]->state }}<br />

                                    Ph: {{ $companyDetails[0]->mobile_1 }} /
                                    {{ $companyDetails[0]->mobile_2 }}<br />
                                </td>
                                <td colspan="2">
                                    Invoice No. :
                                    <b>{{ $invoice->invoice_no }}</b><br />
                                    Bill Type. :
                                    @if ($invoice->bill_type == '1')
                                        <span><b>{{ 'Credit' }}</b></span>
                                    @endif

                                    @if ($invoice->bill_type == '2')
                                        <span><b>{{ 'Cash' }}</b></span>
                                    @endif

                                    <br />
                                    <br />
                                </td>
                                <td colspan="2">
                                    Dated :
                                    <b>{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</b>
                                    <br />
                                    <br />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="15">
                                    Customer Details: <br />
                                    <b>{{ $invoice->customer->name }}</b> <br />
                                    {{ $invoice->customer->mobile }} <br />
                                </td>
                            </tr>
                        </tbody>

                        <tr>
                            <td class="items-tr-h" colspan="1">Sl.No</td>

                            <td class="items-tr-h" colspan="10" style="width: 300px">
                                Item Details
                            </td>
                            <td class="items-tr-h" colspan="1">Qty</td>
                            <td class="items-tr-h" colspan="1">Value</td>
                            <td class="items-tr-h" colspan="2">Total Amount</td>
                        </tr>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="items-tr" colspan="1">
                                    {{ $loop->index + 1 }}
                                </td>

                                <td class="items-tr" colspan="10">
                                    {{ $item->product->name }}
                                </td>
                                <td class="items-tr" colspan="1">
                                    {{ $item->quantity }}
                                </td>
                                <td class="items-tr" colspan="1">
                                    {{ $item->rate }}
                                </td>
                                <td class="items-tr" colspan="2">
                                    {{ $item->quantity * $item->rate }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="12" align="right" class="items-ftr">
                                <b>Total Amount (Rs.)</b>
                            </td>

                            <td colspan="3" align="right" class="items-ftr">
                                <b>{{ $invoice->total_amount }} </b>
                            </td>
                        </tr>
                        @php
                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                            $amount_in_words = $f->format($invoice->pay_amount);
                        @endphp
                        <tr>
                            <td colspan="15" align="center" style="border: 0">
                                <b>Grand Total:&nbsp;&nbsp; {{ ucwords($amount_in_words) }} Rupees Only</b>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="12" align="left" style="border: 0">
                                Total Amount
                            </td>
                            <td colspan="3" align="right" style="border: 0">
                                <span>{{ $invoice->total_amount }}</span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="12" style="border: 0">Discount Amount</td>
                            <td colspan="3" align="right" style="border: 0">
                                <span>{{ $invoice->total_discount }} </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" style="border: 0">Round Off</td>
                            <td colspan="3" align="right" style="border: 0">
                                <span>{{ $invoice->roundoff }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" style="border: 0">
                                <b>Net Amount</b>
                            </td>
                            <td colspan="3" align="right" style="border: 0">
                                <b> <span> {{ $invoice->pay_amount }} /-</span></b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- gst bill --}}
    @if ($gst == 1)
        <div class="container">
            <div id="canvas_div_pdf">
                <div class="text-center">
                    <h6 style="text-align: center;">GST Invoice</h6>
                </div>

                <img class="d-block img-fluid" src="{{ URL::asset('/images/' . $companyDetails[0]->logo) }}" alt="logo"
                    style="width: 80px" />


                <table class="table table-bordered mb-5" width="100%" style="margin-bottom: 0">
                    <tbody>
                        <tr>
                            <td colspan="9">
                                <b>{{ $companyDetails[0]->name }}</b> <br />
                                {{ $companyDetails[0]->address_1 }}<br />
                                {{ $companyDetails[0]->address_2 }}<br />
                                {{ $companyDetails[0]->district }} - {{ -$companyDetails[0]->pincode }}<br />
                                {{ $companyDetails[0]->state }}<br />

                                Ph: {{ $companyDetails[0]->mobile_1 }} /
                                {{ $companyDetails[0]->mobile_2 }}<br />

                                <span v-if="companyDetails.gst_no !== null">GSTIN/UIN
                                    :{{ $companyDetails[0]->gst_no }}</span>
                            </td>
                            <td colspan="2" class="w-25">
                                Invoice No. :
                                <b>{{ $invoice->invoice_no }}</b><br />
                                Bill Type. :
                                @if ($invoice->bill_type == 1)
                                    <span><b>{{ 'Credit' }}</b></span>
                                @endif
                                @if ($invoice->bill_type == 2)
                                    <span><b>{{ 'Cash' }}</b></span>
                                @endif
                                <br />
                                <br />
                            </td>
                            <td colspan="3">
                                Dated:
                                <b>{{ $invoice->invoice_date }}</b>
                                <br />
                                <br />
                            </td>
                        </tr>

                        <tr>
                            <td colspan="14">
                                Customer Details: <br />
                                <b>{{ $invoice->customer->name }}</b> <br />
                                {{ $invoice->customer->mobile }} <br />
                                @if ($invoice->customer->gst_no != null)
                                    <span>GSTIN/UIN :{{ $invoice->customer->gst_no }}</span>
                                @endif
                                <br />
                            </td>
                        </tr>
                    </tbody>

                    <tr class="">
                        <td colspan="" class="items-tr-h">SL No.</td>
                        <td colspan="3" class="items-tr-h" style="min-width:300px;">Item Details</td>
                        <td class="items-tr-h">QTY</td>
                        <td class="items-tr-h">Taxable Value</td>
                        <td class="items-tr-h">SGST (%)</td>
                        <td class="items-tr-h">SGST Amount</td>
                        <td class="items-tr-h">CGST (%)</td>
                        <td class="items-tr-h">CGST Amount</td>
                        <td class="items-tr-h">CESS (%)</td>
                        <td class="items-tr-h">CESS Amount</td>
                        <td class="items-tr-h">Total Tax Amount</td>
                        <td class="items-tr-h">Total Amount</td>
                    </tr>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ $loop->index + 1 }}</b>
                            </td>
                            <td colspan="3" align="center" class="items-tr">
                                <b>{{ $item->product->name }}</b>
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ $item->quantity }}</b>
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ $item->taxable_amount }}</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ $item->gst / 2 }}</b>
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                {{ $item->sgst }}
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                {{ $item->gst / 2 }}
                            </td>
                            <td colspan="1" align="center" class="items-tr">
                                {{ $item->cgst }}
                            </td>

                            <td colspan="1" align="center" class="items-tr">0</td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>0.00</b>
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ $item->sgst + $item->cgst }}</b>
                            </td>

                            <td colspan="1" align="center" class="items-tr">
                                <b>{{ $item->total_rate }}</b>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="items-tr"></td>
                        <td colspan="3" class="items-tr"></td>

                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                        <td class="items-tr"></td>
                    </tr>

                    <tr>
                        <td colspan="5" align="right" class="items-tr">
                            <b>Total Amount (Rs.)</b>
                        </td>
                        <td colspan="1" align="right" class="items-tr">
                            <b>{{ $invoice->total_taxable_amount }}</b>
                        </td>
                        <td colspan="2" align="center" class="items-tr">
                            <b>{{ $invoice->total_sgst }}</b>
                        </td>
                        <td colspan="2" align="center" class="items-tr">
                            <b>{{ $invoice->total_cgst }}</b>
                        </td>
                        <td colspan="2" align="center" class="items-tr">
                            <b>{{ $invoice->total_cess }}</b>
                        </td>
                        <td colspan="1" align="right" class="items-tr">
                            <b>{{ $invoice->total_cgst + $invoice->total_sgst }} </b>
                        </td>
                        <td colspan="1" align="right" class="items-tr">
                            <b>{{ $invoice->total_amount }}</b>
                        </td>
                    </tr>
                    @php
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $amount_in_words = $f->format($invoice->pay_amount);
                    @endphp
                    <tr>
                        <td colspan="9" rowspan="8" align="center" class="align-middle items-tr">
                            <b>Grand Total:&nbsp;&nbsp; {{ ucwords($amount_in_words) }} Rupees Only</b>
                        </td>
                        <td colspan="3" style="border: 0">Total Taxable Amount</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->total_taxable_amount }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 0">Total SGST</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->total_sgst }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 0">Total CGST</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->total_cgst }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 0">Total Cess</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->total_cess }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" align="left" style="border: 0">Total Amount</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->total_amount }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 0">Discount Amount</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->total_discount }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 0">Round Off</td>
                        <td colspan="2" align="right" style="border: 0">
                            {{ $invoice->roundoff }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-only">
                            <b>Net Amount</b>
                        </td>
                        <td colspan="2" align="right" class="border-bottom-only">
                            <b>{{ $invoice->pay_amount }}</b>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="10" class="items-tr"></td>
                        <td colspan="4" class="items-tr text-center">
                            <b>FOR {{ $companyDetails[0]->name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="items-tr"></td>
                        <td colspan="4" class="items-tr-signature">
                            <b>Signature:</b><br /><br />
                            <b>Name:</b>
                        </td>
                    </tr>
                </table>



            </div>
        </div>
    @endif
    <!----GST INVOICE END------>

</body>

</html>
