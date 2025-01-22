<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Invoice</title>
    <style>
        .status{
            font-weight: 900;
            text-align: center;
            white-space: nowrap;
        }
        .text-info {
            color: #17a2b8; /* Light blue for "Draft" status */
        }

        .text-primary {
            color: #007bff; /* Blue for "Submitted" status */
        }

        .text-success {
            color: #28a745; /* Green for "Approval Not Required" and "Approved" statuses */
        }

        .text-warning {
            color: #ffc107; /* Yellow for "Partially Approved" status */
        }

        .text-danger {
            color: #dc3545; /* Red for "Rejected" status */
        }
    </style>
</head>
<body>
    <div style="width:700px; font-size: 11px; font-family:Arial;">

        <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="vertical-align: top;">
                    @if (isset($orgLogo))
                        <img src="{!! $orgLogo !!}" alt="" height="20px" />
                    @else
                        <img src="{{$imagePath}}" height="20px" alt="">
                    @endif
                </td>
                <td style="text-align: right; vertical-align: bottom; font-weight: bold; font-size: 18px;">
                    Supplier Invoice
                    <br>
                    {{ Str::ucfirst(@$organization->name) }}
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
            <tr>
                <td rowspan="2" style="border: 1px solid #000; padding: 3px; width: 40%; vertical-align: top;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="3" style="font-weight: 900; font-size: 13px; padding-bottom: 3px;">
                                Buyer Name &
                                Address:
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span style="font-weight: 700; font-size: 13px;">
                                    {{ Str::ucfirst(@$organizationAddress->line_1) }} {{ Str::ucfirst(@$organizationAddress->line_2) }}
                                </span> <br>
                                {{ @$organizationAddress->landmark }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;">Pin Code : </td>
                            <td style="padding-top: 15px; font-weight: 700;">{{ @$organizationAddress->postal_code }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">City:</td>
                            <td style="padding-top: 3px;">
                                {{ @$organizationAddress->city->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">State:</td>
                            <td style="padding-top: 3px;">
                                {{ @$organizationAddress->state->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">GSTIN NO</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">State Code:</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">Country:</td>
                            <td style="padding-top: 3px;">
                                {{ @$organizationAddress->country->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">GSTIN NO:</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">CIN NO:</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">PHONE:</td>
                            <td style="padding-top: 3px;">
                                {{ @$organizationAddress->mobile }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">EMAIL ID:</td>
                            <td style="padding-top: 3px;">
                                {{ @$organizationAddress->email }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">PAN NO. :</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                    </table>
                </td>
                <td rowspan="2"
                    style="border: 1px solid #000; padding: 3px; border-left: none; vertical-align: top; width: 40%;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2" style="font-weight: 900; vertical-align: top;">Supplier's Name & Address:
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 3px;">
                                JPS PLASTICS PVT LTD <br>
                                SARAI ROAD
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">City: </td>
                            <td style="padding-top: 10px;">
                                {{ @$shippingAddress->city->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">State:</td>
                            <td style="padding-top: 3px;">
                                {{ @$shippingAddress->state->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">Country</td>
                            <td style="padding-top: 3px;">
                                {{ @$shippingAddress->country->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">Pin code:</td>
                            <td style="padding-top: 3px;">
                                {{ @$shippingAddress->pincode }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">GSTIN NO:</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">PHONE:</td>
                            <td style="padding-top: 3px;">
                                {{ @$po->vendor->mobile }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">EMAIL ID:</td>
                            <td style="padding-top: 3px;">
                                {{ @$po->vendor->email }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 3px;">PAN NO. :</td>
                            <td style="padding-top: 3px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid #000; padding: 3px;float: right; border-left: none; vertical-align: top; width: 20%;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><b>SI No:</b></td>
                            <td style="font-weight: 900;">
                                {{ @$po->document_number }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>SI Date:</b></td>
                            @if($po->document_date)
                                <td style="font-weight: 900;">
                                    {{ date('d-M-y', strtotime($po->document_date)) }}
                                </td>
                            @endif
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td
                    style="border: 1px solid #000; padding: 3px; border-left: none; vertical-align: top; width: 35%; border-top: none;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <b style="font-weight: 900;">Status :-</b>
                                    <span class="{{$docStatusClass}}">
                                        {{ $po->display_status }}
                                    </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
            <tr>
                <td
                    style="padding: 2px; border: 1px solid #000; border-top: none; background: #80808070; text-align: center; font-weight: bold;">
                    #
                </td>
                <td
                    style="font-weight: bold; width: 150px; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    <div style="">Item</div>
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    HSN
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    Quantity
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    UOM
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    Rate
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    Total
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center;">
                    Discount
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center; word-wrap: break-word; word-break: break-word; width: 50px;">
                    Taxable Value
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center; word-wrap: break-word; word-break: break-word; width: 50px;">
                    Tax Amnt
                </td>
                <td
                    style="font-weight: bold; padding: 2px; border: 1px solid #000; border-top: none; border-left: none; background: #80808070; text-align: center; word-wrap: break-word; word-break: break-word; width: 50px;">
                    Tax Group
                </td>
            </tr>
            @php 
                $totalCGSTValue = 0.00;
                $totalSGSTValue = 0.00;
                $totalIGSTValue = 0.00;
            @endphp
            @foreach($po->po_items as $key => $val)
                <tr>

                    <td
                        style=" vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none;  text-align: center;">
                        {{ $key + 1 }}</td>
                    <td
                        style="vertical-align: top; padding:10px 3px; text-align:left; border: 1px solid #000; border-top: none; border-left: none;">
                        <b> {{ @$val?->item?->item_name }}</b><br/>
                        
                        @if($val?->attributes->count())
                        @php 
                            $html = '';
                            foreach($val?->attributes as $attribute) {
                            $attr = \App\Models\AttributeGroup::where('id', @$attribute->attribute_name)->first();
                            $attrValue = \App\Models\Attribute::where('id', @$attribute->attribute_value)->first();
                                
                                if ($attr && $attrValue) { 
                                        if($html) {
                                            $html.= ', ';    
                                        }
                                        $html .= "$attr->name : $attrValue->value";
                                } else {
                                        $html .= ":";
                                }
                            }
                        @endphp
                            {{$html}}
                        @endif
                        <br/>
                        @if(@$val?->item?->specifications->count())
                            {{-- @foreach(@$val?->item?->specifications as $specification)
                            @endforeach --}}
                            {{ $val->item->specifications->pluck('value')->implode(', ') }}

                            <br/>
                        @endif
                        Code : {{ @$val->item_code }}<br/>
                        @if(@$val->remarks)Remarks : {{@$val->remarks}}@endif
                    </td>
                    <td
                        style=" vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none; text-align: center;">
                        {{ @$val?->hsn?->code }}
                    </td>
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none; text-align: right;">
                        {{@$val->order_qty}}
                    </td>
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none; text-align: center;">
                       {{ucfirst(@$val?->item?->uom?->name)}}
                    </td>
                    
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none; text-align: right;">
                        {{@$val->rate}}
                    </td>
                    @php
                    $total = number_format(($val->order_qty * $val->rate), 2, '.', '');
                    @endphp
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none; text-align: right;">
                        {{ $total }}
                    </td>
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none;  text-align: center; text-align: right;">
                        {{$val->item_discount_amount + $val->header_discount_amount}}
                    </td>
                    @php
                        $total = $val->order_qty * $val->rate;
                        $netValue = $total- $val->item_discount_amount - $val->header_discount_amount;
                        $netValue = number_format($netValue, 2, '.', '');
                    @endphp
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none;  text-align: center; text-align: right;">
                        {{{$netValue}}}
                    </td>
                    @php 
                        $totalCGSTValue += $val->cgst_value['value'];
                        $totalSGSTValue += $val->sgst_value['value'];
                        $totalIGSTValue += $val->igst_value['value'];
                    @endphp
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none;  text-align: center; text-align: right;">
                        {{ number_format($val->cgst_value['value'] + $val->sgst_value['value'] + $val->igst_value['value'], 2) }}
                    </td>
                    <td
                        style="vertical-align: top; padding:10px 3px; border: 1px solid #000; border-top: none; border-left: none;  text-align: center; text-align: right;">
                        {{ $val?->ted_tax?->taxDetail?->erpTax?->tax_group ?? 'NA' }}
                    </td>
                </tr>
            @endforeach
        </table>

        <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="padding: 3px; border: 1px solid #000; width: 50%; border-top: none; vertical-align: top;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td> <b>Total SI Value (In Words)</b> <br>
                                {{ @$amountInWords }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;"><b>Currency:</b> {{@$po->currency->name}} </td>
                        </tr>
                        <tr>
                            <td style="color: red; padding-top: 20px;">Please attach Quality Assurance Certificate with
                                each Invoice duly filled, signed
                                and stamped on your company letter head.</td>
                        </tr>
                    </table>

                </td>
                <td
                    style="padding: 3px; border: 1px solid #000; border-top: none; border-left: none; vertical-align: top;">
                    <table style="width: 100%; margin-bottom: 0px; margin-top: 10px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align: right;">
                                <b>Total Value:</b>
                            </td>
                            <td style="text-align: right;">
                                {{ number_format($totalItemValue,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding-top: 3px;">
                                <b>Total Discount:</b>
                            </td>
                            <td style="text-align: right; padding-top: 3px;">
                                {{ number_format($totalItemDiscount,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding-top: 3px;">
                                <b>Taxable Value:</b>
                            </td>
                            <td style="text-align: right; padding-top: 3px;">
                                {{ number_format($totalTaxableValue,2) }}
                            </td>
                        </tr>
                        @foreach($taxes as $tax)
                        <tr>
                            <td style="text-align: right; padding-top: 3px;">
                                <b>{{$tax->ted_name}} {{$tax->ted_perc}}% @ {{$tax->total_assessment_amount}}:</b>
                            </td>
                            <td style="text-align: right; padding-top: 3px;">
                                {{ number_format(@$tax->total_amount, 2) }}
                            </td>
                        </tr>
                        @endforeach
                        @if($po?->headerExpenses?->count())
                        <tr>
                            <td style="text-align: right; padding-top: 3px;">
                                <b>Total After Tax:</b>
                            </td>
                            <td style="text-align: right; padding-top: 3px;">
                                {{ number_format($totalAfterTax,2)}}
                            </td>
                        </tr>
                        @endif
                        @foreach($po->headerExpenses as $key => $expense)

                            <tr>
                                <td style="text-align: right; padding-top: 3px;">
                                    <b>
                                        {{ucFirst($expense->ted_name ?? 'NA') ?? 'NA'}}
                                    </b>
                                </td>
                                <td style="text-align: right; padding-top: 3px;">
                                    {{ number_format(@$expense->ted_amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="text-align: right; padding-top: 3px;">
                                <b>Total SI Value:</b>
                            </td>
                            <td style="text-align: right; padding-top: 3px;">
                                {{ number_format($totalAmount,2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2"
                    style="padding: 3px; border: 1px solid #000; width: 50%; border-top: none; vertical-align: top;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="font-weight: bold; font-size: 13px;"> <b>Remark :</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="min-height: 80px;">
                                    {{$po->remarks}}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{-- <tr>
            <td colspan="2"
                style="padding: 3px; border: 1px solid #000; width: 50%; border-top: none; vertical-align: top;">
                <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="font-weight: bold; font-size: 13px;"> <b>Attachment :</b></td>
                    </tr>
                    <tr>
                        <td>
                            <div style="min-height: 80px;">
                                @if($po->getDocuments() && $po->getDocuments()->count())
                                    @foreach($po->getDocuments() as $attachment)
                                    @php
                                        $imageExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
                                    @endphp
                                    @if(in_array(pathinfo($attachment->file_name, PATHINFO_EXTENSION), $imageExtensions))
                                    @php
                                    @endphp
                                    <a href="{{ url($po->getDocumentUrl($attachment)) }}" target="_blank">
                                        <img src="{{$po->getDocumentUrl($attachment)}}" alt="Image : {{$attachment->name}}" style="max-width: 100%; max-height: 150px; margin-top: 10px;">
                                    </a>
                                    @else
                                    <p>
                                        <a href="{{ url($po->getDocumentUrl($attachment)) }}" target="_blank">
                                        {{ $attachment->name }}
                                        </a>
                                    </p>
                                    @endif
                                    @endforeach
                                @else
                                    <p>No attachments available.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr> --}}
            <tr>
                <td
                    style="padding: 3px; border: 1px solid #000; width: 55%; border-top: none; border-right: none; vertical-align: top;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>Price Basis : </td>
                            <td>FOR GREATER NOIDA</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;">Delivery on or before :</td>
                            <td style="padding-top: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 5px;">Mode of Transport :</td>
                            <td style="padding-top: 5px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 5px;">Payment Terms :</td>
                            <td style="padding-top: 5px;">
                                {{@$po->paymentTerm->name}}
                            </td>
                        </tr>
                    </table>

                </td>
                <td
                    style="padding: 3px; border: 1px solid #000; border-top: none; border-left: none; vertical-align: top; padding-left: 80px;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>Insurance :</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-top: 5px; width: 80px; ">Pack Charges :
                            </td>
                            <td style="padding-top: 5px;"> INCLUDED
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!--  -->

            <tr>
                <td
                    style="padding: 3px; border: 1px solid #000; width: 50%; border-top: none; border-right: none; vertical-align: top;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2">Instructions to suppliers :</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 5px;">Please turn over for detailed Supplier invoice Terms and
                                Conditions. </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 5px;">Created By :</td>
                            <td style="padding-top: 5px;">
                                {{@$po->createdBy->name}}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 5px;">Printed By :</td>
                            <td style="padding-top: 5px;">
                                {{@$po->createdBy->name}}
                            </td>
                        </tr>
                    </table>

                </td>
                <td
                    style="padding: 3px; border: 1px solid #000; border-top: none; border-left: none; vertical-align: bottom;">
                    <table style="width: 100%; margin-bottom: 0px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align: center; padding-bottom: 20px;">FOR SHEELA FOAM LTD </td>
                        </tr>
                        <tr>
                            <td>This is a computer generated document hence not require any signature. </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2"
                    style=" border: 1px solid #000; padding: 5px; text-align: center; font-size: 12px; border-top: none; text-align: center;">
                    Regd. Office:604 Ashadeep,9 Hailey Road,New Delhi 110001 <br>
                    Principal office: Plot No 14, Sector 135 Noida Expressway, Noida -201305
                </td>
            </tr>

        </table>

        <div style="page-break-before:always"></div>


        <!-- Third page Forth page -->

        <table style="width: 100%; margin-bottom: 0px; margin-top: 10px; font-size: 13px;" cellspacing="0"
            cellpadding="0">
            <tr>
                <td colspan="2"
                    style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 14px; padding: 5px; padding-bottom: 10px;">
                    TERMS AND CONDITIONS FOR "Supplier invoice-GOODS"</td>
            </tr>
            <tr>
                <td colspan="2"
                    style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 14px; padding: 5px; padding-bottom: 10px;">
                    @foreach($po->termsConditions as $poTerm) 
                        {!! $poTerm->termAndCondition?->term_detail !!}
                    @endforeach
                </td>
            </tr>
        </table>

        <div style="page-break-before:always"></div>
        <!-- Fifth page -->

        <table style="width: 100%; margin-bottom: 0px; margin-top: 15px; font-size: 13px;" cellspacing="0"
            cellpadding="0">
            <tr>
                <td colspan="2" style="padding: 8px 5px;">Date:</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 5px; padding-top: 40px;">To</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 0px 5px; line-height: 18px;">SHEELA FOAM LTD UNIT-VI (GNA) <br>
                    PLOT NO 51-A, UDYOG VIHAR , GREATER NOIDA, G.B NAGAR
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 2px 5px; padding-top: 20px;">UTTAR PRADESH</td>
            </tr>
            <tr>
                <td style="width: 45px; padding: 2px 5px;">Phone : </td>
                <td style="padding: 2px 5px;">0120-2569291-93</td>
            </tr>
            <tr>
                <td style="width: 45px; padding: 2px 5px;">E-Mail :</td>
                <td style="padding: 2px 5px;"></td>
            </tr>

            <tr>
                <td colspan="2"
                    style="padding: 8px 5px; padding-top: 50px; font-weight: bold; font-size: 15px; text-decoration: underline;">
                    Sub.: Quality Assurance Certificate. </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 8px 5px; line-height: 25px;">
                    We hereby certify that the goods manufactured / supplied by us against Supplier invoice No. 13
                    dated : 05-04-2024 do conform to specifications / standard mention in the Supplier invoice.
                    of M/s. Sheela Foam Ltd and our Invoice No. <span
                        style="display: inline-block; min-width: 150px; border-bottom: 1px dotted #000;"> </span>

                    dated : <span style="display: inline-block; min-width: 100px; border-bottom: 1px dotted #000;">
                    </span>, do conform to specifications / standard mention in the Supplier invoice.
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 8px 5px;">We assure for the quality of goods supplied as above.</td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 8px 5px; padding-top: 30px;">For JPS PLASTICS PVT LTD</td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 8px 5px; padding-top: 30px;">AUTHORISED SIGNATORY</td>
            </tr>
        </table>

        <table style="width: 100%; margin-bottom: 0px; margin-top: 15px; font-size: 13px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="padding: 8px 5px; width: 182px; font-weight: bold; font-size: 13px; vertical-align: top;">IMPORTANT INSTRUCTION-: </td>
                <td style="padding: 8px 5px; font-weight: bold; font-size: 13px;" >Please esnure that latest PDIR format with latest revision number is being filled
                    at your end and sent along with each invoice sent to The Company. For any query
                    regarding same, contact Purchase department.</td>
            </tr>
        </table>

        <div style="page-break-before:always"></div>
        <!-- Six page -->

        <table style="width: 100%; margin-bottom: 0px; margin-top: 15px; font-size: 13px;" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2" style="padding: 5px 0px; font-weight: bold; text-decoration: underline; font-size: 16px; text-align: center;">SAFETY INSTRUCTIONS</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px; padding-top: 15px;">Following po_items are strictly prohibited to be brought / used in our factory premises:</td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">1).</td>
                <td style="padding: 5px 0px;">BIDDI, Cigarette. </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">2).</td>
                <td style="padding: 5px 0px;">Tobacco or any other intoxicant in any form. </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">3).</td>
                <td style="padding: 5px 0px;">Gutka, Pan Masala.</td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">4).</td>
                <td style="padding: 5px 0px;">Match Sticks or Match Box (Filled or Empty).</td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">5).</td>
                <td style="padding: 5px 0px;">Lighters.</td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">6).</td>
                <td style="padding: 5px 0px;">Alcohal in any form.</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;">Upon not following above instructions, The Company shall be at liberty to impose the penalties which may please be noted as under:
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">A).</td>
                <td style="padding: 5px 0px;">
                    Penalty of Rs. 250/- (Rupees Two Hundred and Fifty Only) shall be imposed if a pack / bundle or a part of
                    BIDDI or Cigarette is found. This penalty shall multiply with additional packs / bundle.
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">B).</td>
                <td style="padding: 5px 0px;">
                    Penalty of Rs. 250/- (Rupees Two Hundred and Fifty Only) per pouch (Open or Sealed) shall be imposed if a
                    Chewing Tobacco or any other intoxicant in any form is found.
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">C).</td>
                <td style="padding: 5px 0px;">
                    Penalty of Rs. 250/- (Rupees Two Hundred and Fifty Only) per pouch (Open or Sealed) of Gutka, Pan
                    Masala shall be imposed.
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">D).</td>
                <td style="padding: 5px 0px;">
                    Penalty of Rs. 1500/- (Rupees One Thousand Five Hundred Only) shall be imposed if a Match Box (Empty or
                    ttract a penalty of Rs. 1500/- (Rupees One Thousand Five Hundred Only).
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">E).</td>
                <td style="padding: 5px 0px;">
                    Penalty of Rs. 1000/- (Rupees One Thousand Only) shall be imposed on bringing Alcohal inside our factory.

                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">F).</td>
                <td style="padding: 5px 0px;">
                    Damage caused to any property inside The Company shall be fully recoverable and the cost of damage shall
                    be acertained by The Company.
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px; padding-left: 20px; width: 25px;">G).</td>
                <td style="padding: 5px 0px;">Any other instructions which are given by our Security at entry has to be followed to avoid penalty which is
                    not specifically mentioned above.</td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 5px 0px; padding-top: 20px;"> Repeat offenders shall be considered for black-listing.</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px; padding-top: 20px;">
                    It shall be your responsibility to instruct and make sure that the driver / representative of your vehicle deposit
                    above po_items if he / she is carrying at our Security. These po_items shall be returned on vehicle exit from our
                    premises. We shall directly hold you responsible for any lapse on transporters' end on account of above Safety
                    Instructions.
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px; padding-top: 20px;">We request your co-operation in the matter.</td>
            </tr>

        </table>

    </div>
</body>

</html>
