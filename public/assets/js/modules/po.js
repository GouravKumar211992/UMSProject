/*Tax Detail Display Start*/
$(document).on('click', '.summaryTaxBtn', (e) => {
    getTaxSummary();
});

/*Approve modal*/
$(document).on('click', '#approved-button', (e) => {
   let actionType = 'approve';
   $("#approveModal").find("#action_type").val(actionType);
   $("#approveModal #popupTitle").text("Approve Application");
   $("#approveModal").modal('show');
});

/*Reject modal*/
$(document).on('click', '#reject-button', (e) => {
   let actionType = 'reject';
   $("#approveModal #popupTitle").text("Reject Application");
   $("#approveModal").find("#action_type").val(actionType);
   $("#approveModal").modal('show');
});

/*Get tax Summary*/
function getTaxSummary()
{
    let taxSummary = {};
    $("#itemTable [id*='row_']").each(function(index, row) {
        row = $(row);        
        let qty = Number(row.find('[name*="[qty]"]').val());
        let rate = Number(row.find('[name*="[rate]"]').val());
        let itemDisc = Number(row.find('[name*="[discount_amount]"]').val());
        let itemHeaderDisc = Number(row.find('[name*="[discount_amount_header]"]').val());        
        let totalItemDisc = itemDisc + itemHeaderDisc;
        let totalItemValue = qty * rate;
        let totalItemValueAfterDisc = totalItemValue - totalItemDisc;
        let processedTaxTypes = {};
        if (totalItemValueAfterDisc) {
            row.find('[name*="[t_type]"]').each(function(taxIndex, TaxRow) {
                // Get tax type, percentage, and value for each tax row
                let tType = $(TaxRow).closest('td').find(`[name*="components[${index+1}][taxes][${taxIndex+1}][t_type]"]`).val();
                let tPerc = Number($(TaxRow).closest('td').find(`[name*="components[${index+1}][taxes][${taxIndex+1}][t_perc]"]`).val());
                let tValue = Number($(TaxRow).closest('td').find(`[name*="components[${index+1}][taxes][${taxIndex+1}][t_value]"]`).val());
                let dynamicKey = `${tType}_${tPerc}`;                
                if (taxSummary[dynamicKey]) {
                    taxSummary[dynamicKey].totalTaxValue += tValue;
                } else {
                    taxSummary[dynamicKey] = {
                        taxType: tType,
                        taxPerc: tPerc,
                        totalTaxValue: tValue,
                        totalTaxableAmount: 0
                    };
                }
                processedTaxTypes[dynamicKey] = true;
            });
            for (let key in processedTaxTypes) {
                taxSummary[key].totalTaxableAmount += totalItemValueAfterDisc;
            }
        }
    });
    let taxSummaryHtml = "";
    let rowCount = 1;
    for (let key in taxSummary) {
        let summary = taxSummary[key];
        let amount = Number(summary.totalTaxableAmount).toFixed(); 
        taxSummaryHtml += `<tr>
        <td>${rowCount}</td>
        <td>${summary.taxType}</td>
        <td>${Number(amount).toFixed(2)}</td>
        <td>${summary.taxPerc}%</td>
        <td>${summary.totalTaxValue.toFixed(2)}</td>
        </tr>`;
        rowCount++;
    }
    $('#po_tax_details').html(taxSummaryHtml);
    $("#poTaxDetailModal").modal('show');
}

/*Tbl row highlight*/
$(document).on('click', '.mrntableselectexcel tr', (e) => {
   $(e.target.closest('tr')).addClass('trselected').siblings().removeClass('trselected');
});

$(document).on('keydown', function(e) {
 if (e.which == 38) {
   /*bottom to top*/
   $('.trselected').prev('tr').addClass('trselected').siblings().removeClass('trselected');
 } else if (e.which == 40) {
   /*top to bottom*/
   $('.trselected').next('tr').addClass('trselected').siblings().removeClass('trselected');
 }
 // if($('.trselected').length) {
 //   $('html, body').scrollTop($('.trselected').offset().top - 200); 
 // }
});

/*Check box check and uncheck*/
$(document).on('change','#itemTable > thead .form-check-input',(e) => {
    if (e.target.checked) {
        $("#itemTable > tbody .form-check-input").each(function(){
            $(this).prop('checked',true);
        });
    } else {
        $("#itemTable > tbody .form-check-input").each(function(){
            $(this).prop('checked',false);
        });
    }
});

$(document).on('change','#itemTable > tbody .form-check-input',(e) => {
    if(!$("#itemTable > tbody .form-check-input:not(:checked)").length) {
        $('#itemTable > thead .form-check-input').prop('checked', true);
    } else {
        $('#itemTable > thead .form-check-input').prop('checked', false);
    }
});

/*Attribute on change*/
$(document).on('change', '[name*="comp_attribute"]', (e) => {
    let rowCount = e.target.closest('tr').querySelector('[name*="row_count"]').value;
    let attrGroupId = e.target.getAttribute('data-attr-group-id');
    $(`[name="components[${rowCount}][attr_group_id][${attrGroupId}][attr_name]"]`).val(e.target.value);
    qtyEnabledDisabled();
});

/*Each row addDiscountBtn*/
$(document).on('click', '.addDiscountBtn', (e) => {
    $("#new_item_dis_name_select").val('');
    $("#new_item_discount_id").val('');
    $("#new_item_dis_name").val('');
    $("#new_item_dis_perc").val('').prop('readonly',false);
    $("#new_item_dis_value").val('').prop('readonly',false);
    let rowCount = e.target.closest('button').getAttribute('data-row-count');
    let tr = '';
    let totalAmnt = 0;
    $(`[id="row_${rowCount}"]`).find("[name*='[dis_amount]']").each(function(index,item) {
        let key = index +1;
        let id = $(item).closest('tr').find(`[name*='[${key}][id]']`).val();
        let tedId = $(item).closest('tr').find(`[name*='[${key}][ted_id]']`).val();
        let name = $(item).closest('tr').find(`[name*='[${key}][dis_name]']`).val();
        let perc = $(item).closest('tr').find(`[name*='[${key}][dis_perc]']`).val();
        let amnt = Number($(item).val()).toFixed(2);
        totalAmnt+=Number(amnt);
        let tbl_row_count = index + 1;
         tr += `
        <tr class="display_discount_row">
            <td>${tbl_row_count}</td>
            <td>${name}
                <input type="hidden" value="${tedId}" name="disc_item[${tbl_row_count}][ted_id]">
                <input type="hidden" value="${id}" name="disc_item[${tbl_row_count}][item_d_id]">
                <input type="hidden" value="${name}" name="disc_item[${tbl_row_count}][item_d_name]" />
            </td>
            <td class="text-end">${perc}
                <input type="hidden" value="${perc}" name="disc_item[${tbl_row_count}][item_d_perc]" />
            </td>
            <td class="text-end">${amnt}
            <input type="hidden" value="${amnt}" name="disc_item[${tbl_row_count}][item_d_amnt]" />
            </td>
            <td>
                <a data-row-count="${rowCount}" data-id="${id}" href="javascript:;" class="text-danger deleteItemDiscountRow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </a>
            </td>
        </tr>`
    });
    $(".display_discount_row").remove();
    $("#eachRowDiscountTable #disItemFooter").before(tr);
    $("#disItemFooter #total").text(totalAmnt);
    $("#disItemFooter #row_count").val(rowCount);
    $('#itemRowDiscountModal').modal('show');
    initializeAutocompleteTED("new_item_dis_name_select", "new_item_discount_id", "new_item_dis_name", "po_module_discount", "new_item_dis_perc");
});

function setTableCalculation() {
    let totalItemValue = 0;
    let totalItemDiscount = 0;
    let totalItemCost = 0;
    let totalHeaderDiscount = 0;
    let totalAfterBothDisc = 0;
    let totalTax = 0;
    let totalAfterTax = 0;
    let totalHeaderExp = 0;
    let grandTotal = 0;
    $("#itemTable [id*='row_']").each(function (index, item) {
        let rowCount = Number($(item).attr('data-index'));
        let qty = $(item).find("[name*='[qty]']").val() || 0;
        let rate = $(item).find("[name*='[rate]']").val() || 0;
        let itemValue = (Number(qty) * Number(rate)) || 0;
        totalItemValue+=itemValue;
        $(item).find("[name*='[item_value]']").val(itemValue.toFixed(2));

        /*Bind Item Discount*/
        let itemDiscount = 0;
        if ($(item).find("[name*='[dis_perc]']").length && itemValue) {
            $(item).find("[name*='[dis_perc]']").each(function(index, eachItem) {
                let hiddenPerc = Number($(`[name="components[${rowCount}][discounts][${index + 1}][hidden_dis_perc]"]`).val()) || 0;
                let discPerc = hiddenPerc || Number($(eachItem).val());
                let eachDiscAmount = 0;
                if (discPerc) {
                    eachDiscAmount = (itemValue * discPerc) / 100; 
                } else {
                    eachDiscAmount = Number($(`[name="components[${rowCount}][discounts][${index + 1}][dis_amount]"]`).val()) || 0;
                }
                itemDiscount += eachDiscAmount;
                $(`[name="components[${rowCount}][discounts][${index + 1}][dis_amount]"]`).val(eachDiscAmount.toFixed(2));
            });
            $(item).find("[name*='[discount_amount]']").val(itemDiscount.toFixed(2));
        } else if (!itemValue) {
            $(item).find("[name*='[discount_amount]']").val("0.00");
        }
        totalItemDiscount+=itemDiscount;

        let itemCost = itemValue - itemDiscount;
        totalItemCost+=itemCost;
        $(item).find("[name*='[item_total_cost]']").val(itemCost.toFixed(2));
        /*Bind Item Discount*/

    });

    /*Bind table footer*/
    $("#totalItemValue").attr('amount',totalItemValue).text(totalItemValue.toFixed(2));
    $("#totalItemDiscount").attr('amount',totalItemDiscount).text(totalItemDiscount.toFixed(2));
    $("#TotalEachRowAmount").attr('amount',totalItemCost).text(totalItemCost.toFixed(2));
    /*Bind table footer*/ 

    $("#f_sub_total").attr('amount',totalItemValue.toFixed(2)).text(totalItemValue.toFixed(2));
    $("#f_total_discount").attr('amount',totalItemDiscount.toFixed(2)).text(totalItemDiscount.toFixed(2));

    /*Bind summary header Discount*/
    let totalAmountAfterItemDis = totalItemCost;
    let disHeaderAmnt = 0;
    if ($(".display_summary_discount_row").find("[name*='[d_perc]']").length && totalAmountAfterItemDis) {
        $(".display_summary_discount_row").find("[name*='[d_perc]']").each(function(index, eachItem) {
            let eachDiscTypePrice = 0;
            let hiddenPerc = Number($(`[name="disc_summary[${index + 1}][hidden_d_perc]"]`).val()) || 0;
            let itemDiscPerc = hiddenPerc || Number($(eachItem).val());
            if(itemDiscPerc) {
                eachDiscTypePrice = (totalAmountAfterItemDis * itemDiscPerc) / 100;
            } else {
                eachDiscTypePrice = Number($(`[name="disc_summary[${index + 1}][d_amnt]"]`).val()) || 0;
            }
            $(`[name="disc_summary[${index + 1}][d_amnt]"]`).closest('td').html(`${eachDiscTypePrice.toFixed(2)}
                <input type="hidden" value="${eachDiscTypePrice.toFixed(2)}" name="disc_summary[${index + 1}][d_amnt]">
            `);
            // $(`[name="disc_summary[${index + 1}][d_amnt]"]`).val(eachDiscTypePrice.toFixed(2));            
            disHeaderAmnt += eachDiscTypePrice;
        });
    } else {
        let eachDiscTypePrice = 0;
        $(".display_summary_discount_row").find("[name*='[d_perc]']").each(function(index) {
            let oldValue = $(`[name='disc_summary[${index + 1}][d_amnt]']`).val() || 0;
            oldValue = oldValue || eachDiscTypePrice;
            $(`[name="disc_summary[${index + 1}][d_amnt]"]`).closest('td').html(`${eachDiscTypePrice.toFixed(2)}
                <input type="hidden" value="${oldValue.toFixed(2)}" name="disc_summary[${index + 1}][d_amnt]">
            `);
            // $(`[name="disc_summary[${index + 1}][d_amnt]"]`).val(eachDiscTypePrice.toFixed(2));
        });
        disHeaderAmnt += eachDiscTypePrice;
    }
    $("#disSummaryFooter #total").attr('amount', disHeaderAmnt.toFixed(2)).text(disHeaderAmnt.toFixed(2));
    $("#f_header_discount").attr('amount',disHeaderAmnt.toFixed(2)).text(disHeaderAmnt.toFixed(2));
    /*Bind summary header Discount*/

    /*Bind header discount item level*/
    $("#itemTable [id*='row_']").each(function (index, item2) {
        let rowCount2 = Number($(item2).attr('data-index'));
        let qty2 = $(item2).find("[name*='[qty]']").val() || 0;
        let rate2 = $(item2).find("[name*='[rate]']").val() || 0;
        let itemValue2 =  (Number(qty2) * Number (rate2)) || 0;
        let itemDisc2 = Number($(item2).find("[name*='[discount_amount]']").val()) || 0;
        let itemHeaderDisc = (itemValue2 - itemDisc2) / (totalItemValue - totalItemDiscount) * disHeaderAmnt;
        if(itemHeaderDisc) {
            $(item2).find("[name*='[discount_amount_header]']").val(itemHeaderDisc.toFixed(2));
        } else {
            $(item2).find("[name*='[discount_amount_header]']").val("0.00");
        }
        totalHeaderDiscount+=itemHeaderDisc;
    })
    /*Bind header discount item level*/

    /*Bind Tax*/
    const taxPromises = [];
    let isTax = $("#tax_required").val().trim().toLowerCase() === 'yes';
    $("#itemTable [id*='row_']").each(function (index, item3) {
        let rowCount3 = Number($(item3).attr('data-index'));
        let qty3 = $(item3).find("[name*='[qty]']").val() || 0;
        let rate3 = $(item3).find("[name*='[rate]']").val() || 0;
        let itemValue3 = (Number(qty3) * Number(rate3)) || 0;
        let itemDisc3 = Number($(item3).find("[name*='[discount_amount]']").val()) || 0;
        let itemHeaderDisc = Number($(item3).find("[name*='[discount_amount_header]']").val()) || 0;
        let itemId = $(item3).find('[name*="[item_id]"]').val();

        let price = itemValue3 - itemDisc3 - itemHeaderDisc;
        if (price > 0 && itemId) {
            if(isTax) {
                let transactionType = 'collection';
                let partyCountryId = $("#hidden_country_id").val();
                let partyStateId = $("#hidden_state_id").val();
                // Construct the query parameters
                let queryParams = new URLSearchParams({
                    price: price,
                    item_id: itemId,
                    transaction_type: transactionType,
                    party_country_id: partyCountryId,
                    party_state_id: partyStateId,
                    rowCount: rowCount3
                }).toString();
                let urlWithParams = `${actionUrlTax}?${queryParams}`;
                let promise = fetch(urlWithParams)
                    .then(response => response.json())
                    .then(data => {
                        $(item3).find("[name*='t_d_id']").remove();
                        $(item3).find("[name*='t_code']").remove();
                        $(item3).find("[name*='applicability_type']").remove();
                        $(item3).find("[name*='t_type']").remove();
                        $(item3).find("[name*='t_perc']").remove();
                        $(item3).find("[name*='t_value']").remove();
                        if (data.status === 200) {
                            $(item3).find("[name*='item_total_cost']").after(data?.data?.html);
                        } else {
                            console.warn("Data status not 200 or HTML not found in response");
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
                taxPromises.push(promise);    
            }
        } else {
            $(item3).find("[name*='t_d_id']").remove();
            $(item3).find("[name*='t_code']").remove();
            $(item3).find("[name*='applicability_type']").remove();
            $(item3).find("[name*='t_type']").remove();
            $(item3).find("[name*='t_perc']").remove();
            $(item3).find("[name*='t_value']").remove();
        }
    });

    /*Bind Tax*/

    Promise.all(taxPromises).then(() => {

        $("#itemTable [id*='row_']").each(function (index, item4) {
            let rowCount4 = Number($(item4).attr('data-index'));
            let qty4 = $(item4).find("[name*='[qty]']").val() || 0;
            let rate4 = $(item4).find("[name*='[rate]']").val() || 0;
            let itemValue4 = (Number(qty4) * Number(rate4)) || 0;
            let itemDisc4 = Number($(item4).find("[name*='[discount_amount]']").val()) || 0;
            let itemHeaderDisc = Number($(item4).find("[name*='[discount_amount_header]']").val()) || 0;

            let totalAmountAfterItemDis = itemValue4 - itemDisc4;
            if (isTax) {
                if($(item4).find("[name*='[t_perc]']").length && totalAmountAfterItemDis) {
                    let taxAmountRow = 0.00;
                    $(item4).find("[name*='[t_perc]']").each(function(index,eachItem) {
                        let eachTaxTypePrice = 0;
                        let taxPercTax = Number($(eachItem).val());
                        if(taxPercTax) {
                            eachTaxTypePrice = ((totalAmountAfterItemDis - Number(itemHeaderDisc)) * taxPercTax) / 100; 
                            $(item4).find(`[name="components[${rowCount4}][taxes][${index+1}][t_value]"]`).val(eachTaxTypePrice.toFixed(2));
                        } else {
                            $(item4).find(`[name="components[${rowCount4}][taxes][${index+1}][t_value]"]`).val(eachTaxTypePrice.toFixed(2));
                        }
                        taxAmountRow += eachTaxTypePrice;
                    });
                    totalTax += taxAmountRow;
                }
            }
        });

        totalAfterBothDisc = Number(totalItemValue || 0)-Number(totalItemDiscount || 0)-Number(totalHeaderDiscount || 0);
        totalAfterTax = Number(totalItemValue || 0)-Number(totalItemDiscount || 0)-Number(totalHeaderDiscount || 0)+Number(totalTax || 0);

        $("#f_taxable_value").attr('amount',totalAfterBothDisc.toFixed(2)).text(totalAfterBothDisc.toFixed(2));
        if (totalAfterBothDisc < 0) {
            $("#f_taxable_value").attr('style', 'color: #dc3545 !important;');
        } else {
            $("#f_taxable_value").attr('style', 'color: inherit;');
        }        
        $("#f_tax").attr('amount',totalTax.toFixed(2)).text(totalTax.toFixed(2));
        
        if (totalTax < 0) {
            $("#f_tax").attr('style', 'color: #dc3545 !important;');
        } else {
            $("#f_tax").attr('style', 'color: inherit;');
        }  

        $("#f_total_after_tax").attr('amount',totalAfterTax.toFixed(2)).text(totalAfterTax.toFixed(2));

        if (totalAfterTax < 0) {
            $("#f_total_after_tax").attr('style', 'color: #dc3545 !important;');
        } else {
            $("#f_total_after_tax").attr('style', 'color: inherit;');
        } 

        /*Bind header Expenses*/
        if($(".display_summary_exp_row").find("[name*='[e_perc]']").length && totalAfterTax) {
            $(".display_summary_exp_row").find("[name*='[e_perc]']").each(function(index,eachItem) {
                let eachExpTypePrice = 0;
                let hiddenPerc = Number($(`[name="exp_summary[${index+1}][hidden_e_perc]"]`).val()) || 0; 
                let expDiscPerc = hiddenPerc || Number($(eachItem).val());
                if(expDiscPerc) {
                    eachExpTypePrice = (totalAfterTax * expDiscPerc) / 100; 
                    // $(`[name="exp_summary[${index+1}][e_amnt]"]`).val(eachExpTypePrice.toFixed(2));
                    $(`[name="exp_summary[${index+1}][e_amnt]"]`).closest('td').html(`
                    ${eachExpTypePrice.toFixed(2)}
                    <input type="hidden" value="${eachExpTypePrice.toFixed(2)}" name="exp_summary[${index+1}][e_amnt]">
                    `);
                } else {
                    eachExpTypePrice = Number($(`[name="exp_summary[${index+1}][e_amnt]"]`).val()) || 0; 
                }
                totalHeaderExp += eachExpTypePrice;
            });
        } else {
           $(".display_summary_exp_row").find("[name*='[e_perc]']").each(function(index,eachItem) {
                let eachExpTypePrice = 0;
                // let expDiscPerc = Number($(eachItem).val());
                // $(`[name="exp_summary[${index+1}][e_amnt]"]`).val(eachExpTypePrice.toFixed(2));
                $(`[name="exp_summary[${index+1}][e_amnt]"]`).closest('td').html(`
                    ${eachExpTypePrice.toFixed(2)}
                    <input type="hidden" value="${eachExpTypePrice.toFixed(2)}" name="exp_summary[${index+1}][e_amnt]">
                    `);
                totalHeaderExp += eachExpTypePrice;
            });
        }
        $("#expSummaryFooter #total").attr('amount',totalHeaderExp.toFixed(2)).text(totalHeaderExp.toFixed(2));
        $("#f_exp").text(totalHeaderExp.toFixed(2));

        if (totalHeaderExp < 0) {
            $("#f_exp").attr('style', 'color: #dc3545 !important;');
        } else {
            $("#f_exp").attr('style', 'color: inherit;');
        } 

        /*Bind header Expenses*/
        grandTotal = totalAfterTax + totalHeaderExp;
        $("#f_total_after_exp").attr('amount',grandTotal.toFixed(2)).text(grandTotal.toFixed(2));
        
        if (grandTotal < 0) {
            $("#f_total_after_exp").attr('style', 'color: #dc3545 !important;');
        } else {
            $("#f_total_after_exp").attr('style', 'color: inherit;');
        } 

        /*Bind header exp item level*/
        let total_net_total = 0;
        $("#itemTable [id*='row_']").each(function (index, item5) {
            let rowCount5 = Number($(item5).attr('data-index'));
            let qty5 = $(item5).find("[name*='[qty]']").val() || 0;
            let rate5 = $(item5).find("[name*='[rate]']").val() || 0;
            let itemValue5 =  (Number(qty5) * Number (rate5)) || 0;
            let itemDisc5 = Number($(item5).find("[name*='[discount_amount]']").val()) || 0;
            let itemHeaderDisc5 = Number($(item5).find("[name*='[discount_amount_header]']").val()) || 0;
            let itemTax5 = 0;
            if($(item5).find("[name*='[t_value]']").length) {
                $(item5).find("[name*='[t_value]']").each(function(indexing, iteming){
                    itemTax5+= Number($(iteming).val()) || 0;
                })
            }
            total_net_total += itemValue5 - itemDisc5 - itemHeaderDisc5 + itemTax5;
        });

        $("#itemTable [id*='row_']").each(function (index, item6) {
            let each_net_value = 0;
            let exp_header_amnt_item = 0;
            let rowCount6 = Number($(item6).attr('data-index'));
            let qty6 = $(item6).find("[name*='[qty]']").val() || 0;
            let rate6 = $(item6).find("[name*='[rate]']").val() || 0;
            let itemValue6 =  (Number(qty6) * Number (rate6)) || 0;
            let itemDisc6 = Number($(item6).find("[name*='[discount_amount]']").val()) || 0;
            let itemHeaderDisc6 = Number($(item6).find("[name*='[discount_amount_header]']").val()) || 0;
            let itemTax6 = 0;
            if($(item6).find("[name*='[t_value]']").length) {
                $(item6).find("[name*='[t_value]']").each(function(indexing, iteming){
                    itemTax6+= Number($(iteming).val()) || 0;
                })
            }
            if(totalHeaderExp) {
                each_net_value = itemValue6 - itemDisc6 - itemHeaderDisc6 + itemTax6;
                exp_header_amnt_item = each_net_value / total_net_total * totalHeaderExp;
                $(item6).find("[name*='[exp_amount_header]']").val(exp_header_amnt_item.toFixed(2));
            } else {
                $(item6).find("[name*='[exp_amount_header]']").val(exp_header_amnt_item.toFixed(2));
            }
        });

    });
}

/*Edit mode table calculation filled*/
if($("#itemTable .mrntableselectexcel tr").length) {
   setTimeout(()=> {
      $("[name*='component_item_name[1]']").trigger('focus');
      $("[name*='component_item_name[1]']").trigger('blur');
      setTableCalculation();
   },100);
}

/*itemDiscountSubmit*/
$(document).on('click', '.itemDiscountSubmit', (e) => {
    $("#itemRowDiscountModal").modal('hide');
});

/*Delete deleteItemDiscountRow*/
$(document).on('click', '.deleteItemDiscountRow', (e) => {
    let rowCount = e.target.closest('a').getAttribute('data-row-count') || 0;
    // let index = e.target.closest('a').getAttribute('data-index') || 0;
    let id = Number(e.target.closest('a').getAttribute('data-id')) || 0;
    if(!id) {
        e.target.closest('tr').remove();
        let hiddenDis = '';
        let totalAmnt = 0;
        $(".display_discount_row").each(function(index,item) {
            let id = $(item).find('[name*="[item_d_id]"]').val(); 
            let name = $(item).find('[name*="[item_d_name]"]').val();
            let perc = $(item).find('[name*="[item_d_perc]"]').val();
            let amnt = $(item).find('[name*="[item_d_amnt]"]').val();
            totalAmnt+=Number(amnt);
            hiddenDis+= `<input type="hidden" value="${id}" name="components[${rowCount}][discounts][${index+1}][id]">
            <input type="hidden" value="${name}" name="components[${rowCount}][discounts][${index+1}][dis_name]">
            <input type="hidden" value="${perc}" name="components[${rowCount}][discounts][${index+1}][dis_perc]">
            <input type="hidden" value="${amnt}" name="components[${rowCount}][discounts][${index+1}][dis_amount]">`;
        });
        $(`[name*="components[${rowCount}][discount_amount]"]`).val(totalAmnt);
        $(`[id*='row_${rowCount}']`).find("[name*='[discounts]'").remove();
        $(`[name*="components[${rowCount}][discount_amount]"]`).after(hiddenDis);
        $("#disItemFooter #total").attr('amount',totalAmnt).text(totalAmnt.toFixed(2));
    }
    setTableCalculation();
});

// addDeliveryScheduleBtn
$(document).on('click', '.addDeliveryScheduleBtn', (e) => {
    let rowCount = e.target.closest('div').getAttribute('data-row-count');
    let qty = Number($("#itemTable #row_"+rowCount).find("[name*='[qty]']").val());
    if(!qty) {
        Swal.fire({
            title: 'Error!',
            text: 'Please enter quanity then you can add delivery schedule.',
            icon: 'error',
        });
        return false;
    }
    $("#deliveryScheduleModal").find("#row_count").val(rowCount);
    let rowHtml = '';
    let curDate = new Date().toISOString().split('T')[0];
    let minDate = $("[name='document_date']").val() ?? curDate;
    if(!$("#itemTable #row_"+rowCount).find("[name*='[d_qty]']").length) {        
    let rowHtml = `<tr class="display_delivery_row">
                        <td>1</td>
                        <td>
                            <input type="hidden" name="row_count" value="${rowCount}" id="row_count">
                            <input type="number" name="components[${rowCount}][delivery][1][d_qty]" class="form-control mw-100" />
                        </td>
                        <td>
                            <input type="date" min="${minDate}" name="components[${rowCount}][delivery][1][d_date]" value="${minDate}" class="form-control mw-100" /></td>
                        <td>
                        <a data-row-count="${rowCount}" data-index="1" href="javascript:;" class="text-danger deleteItemDeliveryRow"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                       </td>
                    </tr>`;
    $("#deliveryScheduleModal").find('.display_delivery_row').remove();
    $("#deliveryScheduleModal").find('#deliveryFooter').before(rowHtml);
    } else {
        if($("#itemTable #row_"+rowCount).find("[name*=d_qty]").length) {
            $(".display_delivery_row").remove();
        } else {
            $('.display_delivery_row').not(':first').remove();
            $(".display_delivery_row").find("[name*=d_qty]").val('');
        }
        $("#itemTable #row_"+rowCount).find("[name*=d_qty]").each(function(index,item){
            let id =  $(item).closest('td').find(`[name='components[${rowCount}][delivery][${index+1}][id]']`).val();
            let dQty =  $(item).closest('td').find(`[name='components[${rowCount}][delivery][${index+1}][d_qty]']`).val();
            let dDate =  $(item).closest('td').find(`[name='components[${rowCount}][delivery][${index+1}][d_date]']`).val();

            rowHtml+= `<tr class="display_delivery_row">
                        <td>${index+1}</td>
                        <td>
                            <input type="hidden" name="row_count" value="${rowCount}" id="row_count">
                            <input type="number" value="${dQty}" name="components[${rowCount}][delivery][${index+1}][d_qty]" class="form-control mw-100" />
                        </td>
                        <td>
                            <input type="date" min="${minDate}" name="components[${rowCount}][delivery][${index+1}][d_date]" value="${dDate}" class="form-control mw-100" /></td>
                        <td>
                        <a data-id="${id}" data-row-count="${rowCount}" data-index="${index+1}" href="javascript:;" class="text-danger deleteItemDeliveryRow"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                       </td>
                    </tr>`;

        });
    }
    $("#deliveryScheduleTable").find('#deliveryFooter').before(rowHtml);
    $("#deliveryScheduleTable").find('#deliveryFooter #total').attr('qty',qty);
    $("#deliveryScheduleModal").modal('show');
    totalScheduleQty();
});

/*Total delivery schedule qty*/
function totalScheduleQty()
{
    let total = 0.00;
    $("#deliveryScheduleTable [name*='[d_qty]']").each(function(index, item) {
        total += Number($(item).val());
    });
    $("#deliveryFooter #total").text(total.toFixed(2));
}

// addTaxItemRow add row
$(document).on('click', '.addTaxItemRow', (e) => {
    let curDate = new Date().toISOString().split('T')[0];
    let minDate = $("[name='document_date']").val() ?? curDate;
    // let rowCount = $('#deliveryScheduleModal .display_delivery_row').find('#row_count').val();
    let rowCount = $("tr.trselected").attr('data-index');
    let qty = 0.00;
    $("#deliveryScheduleTable [name*='[d_qty]']").each(function(index, item) {
        qty = qty + Number($(item).val());
    });
    if(!qty && $("#deliveryScheduleTable [name*='[d_qty]']").length) {
        Swal.fire({
            title: 'Error!',
            text: 'Please enter quanity then you can add new row.',
            icon: 'error',
        });
        return false;
    }
    if(!$("#deliveryScheduleTable [name*='[d_qty]']:last").val() && $("#deliveryScheduleTable [name*='[d_qty]']").length) {
        Swal.fire({
            title: 'Error!',
            text: 'Please enter quanity then you can add new row.',
            icon: 'error',
        });
        return false;
    }
    let itemQty = Number($('#deliveryScheduleModal #deliveryFooter #total').attr('qty'));
    if (qty > itemQty) {
        Swal.fire({
            title: 'Error!',
            text: 'You cannot add more than the available item quantity.',
            icon: 'error',
        });
        return false;
    }
    if(qty != itemQty) {        
        let tblRowCount = $('#deliveryScheduleModal .display_delivery_row').length + 1;
        let rowHtml = `<tr class="display_delivery_row">
                            <td>${tblRowCount}</td>
                            <td>
                                <input type="hidden" name="row_count" value="${rowCount}" id="row_count">
                                <input type="number" name="components[${rowCount}][delivery][${tblRowCount}][d_qty]" class="form-control mw-100" />
                            </td>
                            <td>
                                <input type="date" min="${minDate}" name="components[${rowCount}][delivery][${tblRowCount}][d_date]" value="${minDate}" class="form-control mw-100" /></td>
                            <td>
                            <a data-row-count="${rowCount}" data-index="${tblRowCount}" href="javascript:;" class="text-danger deleteItemDeliveryRow"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                           </td>
                        </tr>`;
        if($("#deliveryScheduleModal").find('.display_delivery_row:last').length) {
            $("#deliveryScheduleModal").find('.display_delivery_row:last').after(rowHtml);
        } else {
            $("#deliveryScheduleModal").find('#deliveryFooter').before(rowHtml);
        }
    } else {
        Swal.fire({
            title: 'Error!',
            text: 'Qunatity not available.',
            icon: 'error',
        });
        return false;
    }
    totalScheduleQty();
});

/*itemDeliveryScheduleSubmit */
$(document).on('click', '.itemDeliveryScheduleSubmit', (e) => {
    let isValid = true;
    document.querySelectorAll('input[name*="[d_qty]"], input[name*="[d_date]"]').forEach(input => {
        if (!input.value) {
            isValid = false;
            input.classList.add('is-invalid');
            input.focus();
        } else {
            input.classList.remove('is-invalid');
        }
    });
    if (!isValid) {
        event.preventDefault();
        Swal.fire({
            title: 'Error!',
            text: 'Please fill out all required fields.',
            icon: 'error',
        });
        return false;
    }

    let rowCount = $('#deliveryScheduleModal .display_delivery_row').find('#row_count').val();    
    let hiddenHtml = '';
    $("#deliveryScheduleTable .display_delivery_row").each(function(index,item){
        let dQty =  $(item).find("[name*='d_qty']").val();
        let dDate = $(item).find("[name*='d_date']").val();
        hiddenHtml+=`<input type="hidden" value="${dQty}" name="components[${rowCount}][delivery][${index+1}][d_qty]"/>
                     <input type="hidden" value="${dDate}" name="components[${rowCount}][delivery][${index+1}][d_date]" />`;

    });
    $("#itemTable #row_"+rowCount).find("[name*='d_qty']").remove();
    $("#itemTable #row_"+rowCount).find("[name*='d_date']").remove();
    // $("#itemTable #row_"+rowCount).find("[name*='t_value']").remove();
   $("#itemTable #row_"+rowCount).find(".addDeliveryScheduleBtn").before(hiddenHtml);
   $("#deliveryScheduleModal").modal('hide');
});

/*Remove delivery row*/
$(document).on('click', '.deleteItemDeliveryRow', (e) => {
    let id = $(e.target).closest('a').attr('data-id') || 0;
    let rowIndex = e.target.getAttribute('data-row-index');
    let rowCount = e.target.getAttribute('data-row-count');
    if (!Number(id)) {        
        $(e.target).closest('tr').remove();
        setTimeout(() => {
            let rowCount = $(".display_delivery_row").find('#row_count').val();
            $('.display_delivery_row').each(function(index, item) {
                let a = `components[${rowCount}][delivery][${index+1}][d_qty]`;
                let b = `components[${rowCount}][delivery][${index+1}][d_date]`;
                $(item).find("[name*='[d_qty]']").prop('name', a);
                $(item).find("[name*='[d_date]']").prop('name', b);
                $(item).find("td:first").text(index+1);
            });
            $(`[name*='components[${rowCount}][delivery][${rowIndex}]']`).remove();
            $(".display_delivery_row").find('#row_count').val(rowCount);
            totalScheduleQty();
        },0);
    }
});

/*Delivery qty on input*/
$(document).on('change input', '.display_delivery_row [name*="d_qty"]', (e) => {
    let itemQty = Number($('#deliveryScheduleModal #deliveryFooter #total').attr('qty'));
    let inputQty = 0;
    $('.display_delivery_row [name*="d_qty"]').each(function(index, item) {
        inputQty = inputQty + Number($(item).val());
        let remainingQty = itemQty - (inputQty - Number($(e.target).val()));
        if (Number($(e.target).val()) > remainingQty) {
            Swal.fire({
                title: 'Error!',
                text: 'You cannot add more than the available item quantity.',
                icon: 'error',
            });
            $(e.target).val(remainingQty);
        }
    });
    totalScheduleQty();
});

/*Open item remark modal*/
$(document).on('click', '.addRemarkBtn', (e) => {
    let rowCount = e.target.closest('div').getAttribute('data-row-count');
    $("#itemRemarkModal #row_count").val(rowCount);
    let remarkValue = $("#itemTable #row_"+rowCount).find("[name*='remark']");
    if(!remarkValue.length) {
        $("#itemRemarkModal textarea").val('');
    } else {
        $("#itemRemarkModal textarea").val(remarkValue.val());
    }
    $("#itemRemarkModal").modal('show');
});

/*Submit item remark modal*/
$(document).on('click', '.itemRemarkSubmit', (e) => {
    let rowCount = $("#itemRemarkModal #row_count").val();
    let remarkValue = $("#itemTable #row_"+rowCount).find("[name*='remark']");
     let textValue = $("#itemRemarkModal").find("textarea").val();
    if(!remarkValue.length) {
        rowHidden = `<input type="hidden" value="${textValue}" name="components[${rowCount}][remark]" />`;
        $("#itemTable #row_"+rowCount).find('.addRemarkBtn').after(rowHidden);
        
    } else{
        $("#itemTable #row_"+rowCount).find("[name*='remark']").val(textValue);
    }
    $("#itemRemarkModal").modal('hide');
});

function summaryDisTotal()
{
    let total = 0.00;
    $(".display_summary_discount_row [name*='[d_amnt]']").each(function(index, item) {
        total += Number($(item).val()) || 0;
    });
    $("#disSummaryFooter #total").attr('amount', total.toFixed(2)).text(total.toFixed(2));
}

/*Open summary discount modal*/
$(document).on('click', '.summaryDisBtn', (e) => {
    e.stopPropagation();
    if(!Number($(`[name*="[item_value]"]`).val())) {
        Swal.fire({
            title: 'Error!',
            text: 'Please first enter qty & rate in table.',
            icon: 'error'
        });
        return false;
    }
    $("#summaryDiscountModal").modal('show');
    initializeAutocompleteTED("new_dis_name_select", "new_discount_id", "new_dis_name", "po_module_discount", "new_dis_perc");
    return false;
});

/*summaryDiscountSubmit*/
$(document).on('click', '.summaryDiscountSubmit', (e) => {
    $("#summaryDiscountModal").modal('hide');
    return false;
});

/*delete summary discount row*/
$(document).on('click', '.deleteSummaryDiscountRow', (e) => {
    let trId = $(e.target).closest('tr').find('[name*="[d_id]"]').val();
    if(!trId) {
        $(e.target).closest('tr').remove();
        summaryDisTotal();
        setTableCalculation();
        if (!Number($("#disSummaryFooter #total").attr('amount'))) {
            $("#f_header_discount_hidden").addClass('d-none');
        } else {
            $("#f_header_discount_hidden").removeClass('d-none');
        }
    }
});

/*Open summary expen modal*/
$(document).on('click', '.summaryExpBtn', (e) => {
    e.stopPropagation();
    if(!Number($(`[name*="[item_value]"]`).val())) {
        Swal.fire({
            title: 'Error!',
            text: 'Please first enter qty & rate in table.',
            icon: 'error'
        });
        return false;
    }
    $("#summaryExpenModal").modal('show');
    initializeAutocompleteTED("new_exp_name_select", "new_exp_id", "new_exp_name", "po_module_expense", "new_exp_perc");
    return false;
});

/*delete summary exp row*/
$(document).on('click', '.deleteExpRow', (e) => {
    let trId = $(e.target).closest('tr').find('[name*="[e_id]"]').val();
    if(!trId) {
        $(e.target).closest('tr').remove();
        summaryExpTotal();
    }
});

// summaryExpSubmit
$(document).on('click', '.summaryExpSubmit', (e) => {
    $("#summaryExpenModal").modal('hide');
    // setTableCalculation();
    return false;
});

function summaryExpTotal()
{
    let total = 0.00;
    $(".display_summary_exp_row [name*='e_amnt']").each(function(index, item) {
        total = total + Number($(item).val());
    });
    $("#expSummaryFooter #total").attr('amount', total);
    $("#expSummaryFooter #total").text(total.toFixed(2));
}

$(document).on('input change', '#itemTable input', (e) => {
    setTableCalculation();
});

/*Check filled all basic detail*/
function checkBasicFilledDetail()
{
    let filled = false;
    let bookId = $("#book_id").val() || '';
    let documentNumber = $("#document_number").val() || '';
    let documentDate = $("[name='document_date']").val() || '';
    // let referenceNumber = $("[name='reference_number']").val() || '';
    if(bookId && documentNumber && documentDate/* && referenceNumber*/) {
        filled = true;
    }
    return filled;
}

/*Check filled vendor detail*/
function checkVendorFilledDetail()
{
    let filled = false;
    let vName = $("#vendor_name").val();
    let vCurrency = $("[name='currency_id']").val();
    let vPaymentTerm = $("[name='payment_term_id']").val();
    let shippingId = $("#shipping_id").val();
    let billingId = $("#billing_id").val();
    if(vName && vCurrency && vPaymentTerm && shippingId && billingId) {
        filled = true;
    }
    return filled;
} 

/*Check filled component*/
function checkComponentRowExist()
{
    let filled = false;
    let rowCount = $("#itemTable [id*='row_']").length;
    if(rowCount) {
        filled = true;
    }
    return filled;
}

// $('input, select, textarea').on('input change blur', function() {
//     sectionEnabledAndDisabled();
// });
// sectionEnabledAndDisabled();

// function sectionEnabledAndDisabled() {
//     // Check if basic details are filled
//     if (!checkBasicFilledDetail()) {
//         // Disable vendor and item sections and block click events
//         $('#vendor_section :input').prop('disabled', true);
//         $('#vendor_section').on('click', function(e) {
//             e.preventDefault();
//             e.stopPropagation();
//         });
        
//         $('#item_section :input').prop('disabled', true);
//         $('#item_section').on('click', function(e) {
//             e.preventDefault();
//             e.stopPropagation();
//         });
//     } else {
//         $('#vendor_section :input').prop('disabled', false);
//         $('#vendor_section').off('click');        
//         if (!checkVendorFilledDetail()) {
//             $('#item_section :input').prop('disabled', true);
//             $('#item_section').on('click', function(e) {
//                 e.preventDefault();
//                 e.stopPropagation();
//             });
//         } else {
//             $('#item_section :input').prop('disabled', false);
//             $('#item_section').off('click');
//         }
//     }
// }

$('#attribute').on('hidden.bs.modal', function () {
   let rowCount = $("[id*=row_].trselected").attr('data-index');
    if ($(`[name="components[${rowCount}][qty]"]`).is('[readonly]')) {
        $(`[name="components[${rowCount}][rate]"]`).trigger('focus');
    } else {
        $(`[name="components[${rowCount}][qty]"]`).trigger('focus');
    }
});

/*Vendor change update field*/
$(document).on('blur', '#vendor_name', (e) => {
    if(!e.target.value) {
        $("#vendor_id").val('');
        $("#vendor_code").val('');
        $("#shipping_id").val('');
        $("#billing_id").val('');
        $("[name='currency_id']").val('').trigger('change');
        $("[name='payment_term_id']").val('').trigger('change');
        $(".shipping_detail").text('-');
        $(".billing_detail").text('-');
    }
});

$(document).on('input', '.qty-input', function() {
    const maxAmount = Number($(this).attr('maxAmount')) || 0;
    if (Number(this.value) > maxAmount) {
        Swal.fire({
            title: 'Error!',
            text: 'Po is more than indent qty.',
            icon: 'error',
        });
        this.value = maxAmount;
    }
});

//Disable form submit on enter button
document.querySelector("form").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();  // Prevent form submission
    }
});
$("input[type='text']").on("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();  // Prevent form submission
    }
});
$("input[type='number']").on("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();  // Prevent form submission
    }
});

/*Add New Summary Discount*/
$(document).on('click', '#add_new_item_dis', (e) => {
    e.preventDefault();
    let rowCount = $("#disItemFooter #row_count").val();
    const new_item_dis_name = $("#new_item_dis_name").val() || '';
    const new_item_dis_id = $("#new_item_discount_id").val() || '';
    const new_item_dis_perc = (Number($("#new_item_dis_perc").val()) || 0).toFixed(2);
    const new_item_dis_value = (Number($("#new_item_dis_value").val()) || 0).toFixed(2);
    let item_dis = 0;
    $(`.display_discount_row`).find('[name*="[item_d_amnt]"]').each(function(index,item) {
        item_dis+=parseFloat($(item).val() || 0);
    });
    let _total_head_dis_all = item_dis +  parseFloat(new_item_dis_value);
    let totalCost = parseFloat($(`[name*='components[${rowCount}][item_value]']`).val()) || 0;
    if(_total_head_dis_all > totalCost) {
        Swal.fire({
            title: 'Error!',
            text: 'You can not give total discount more then total cost.',
            icon: 'error',
        });
        return false;
    }

    if (!new_item_dis_name || (!new_item_dis_perc && !new_item_dis_value)) return;
    const tbl_row_count = $("#eachRowDiscountTable .display_discount_row").length + 1;
    const tr = `
    <tr class="display_discount_row">
        <td>${tbl_row_count}</td>
        <td>${new_item_dis_name}
            <input type="hidden" value="${new_item_dis_id}" name="disc_item[${tbl_row_count}][ted_d_id]">
            <input type="hidden" value="" name="disc_item[${tbl_row_count}][item_d_id]">
            <input type="hidden" value="${new_item_dis_name}" name="disc_item[${tbl_row_count}][item_d_name]" />
        </td>
        <td class="text-end">${new_item_dis_perc}
            <input type="hidden" value="${new_item_dis_perc}" name="disc_item[${tbl_row_count}][item_d_perc]" />
        </td>
        <td class="text-end">${new_item_dis_value}
        <input type="hidden" value="${new_item_dis_value}" name="disc_item[${tbl_row_count}][item_d_amnt]" />
        </td>
        <td>
            <a data-row-count="${rowCount}" data-id="" href="javascript:;" class="text-danger deleteItemDiscountRow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </a>
        </td>
    </tr>`
    if(!$(".display_discount_row").length) {
        $("#eachRowDiscountTable #disItemFooter").before(tr);
    } else {
        $(".display_discount_row:last").after(tr);
    }
    $("#new_item_dis_name_select").val('');  
    $("#new_item_discount_id").val('');  
    $("#new_item_dis_name").val('');  
    $("#new_item_dis_perc").val('').prop('readonly',false);  
    $("#new_item_dis_value").val('').prop('readonly',false);
    let total_head_dis = 0;
    $("[name*='[item_d_amnt]']").each(function(index,item) {
        total_head_dis+=Number($(item).val());
    });
    $("#disItemFooter #total").text(total_head_dis.toFixed(2));
    $(`[id*='row_${rowCount}']`).find("[name*='[discounts]'").remove();

    let hiddenDis = '';
    let totalAmnt = 0;
    $(".display_discount_row").each(function(index,item) {
        let id = $(item).find('[name*="[item_d_id]"]').val(); 
        let tedId = $(item).find('[name*="[ted_d_id]"]').val(); 
        let name = $(item).find('[name*="[item_d_name]"]').val();
        let perc = $(item).find('[name*="[item_d_perc]"]').val();
        let amnt = $(item).find('[name*="[item_d_amnt]"]').val();
        totalAmnt+=Number(amnt);
        hiddenDis+= `<input type="hidden" value="${id}" name="components[${rowCount}][discounts][${index+1}][id]">
        <input type="hidden" value="${tedId}" name="components[${rowCount}][discounts][${index+1}][ted_id]">
        <input type="hidden" value="${name}" name="components[${rowCount}][discounts][${index+1}][dis_name]">
        <input type="hidden" value="${perc}" name="components[${rowCount}][discounts][${index+1}][dis_perc]">
        <input type="hidden" value="${amnt}" name="components[${rowCount}][discounts][${index+1}][dis_amount]">`;
    });
    $(`[name*="components[${rowCount}][discount_amount]"]`).val(totalAmnt);
    $(`[name*="components[${rowCount}][discount_amount]"]`).after(hiddenDis);
    setTableCalculation();  
});

/*Header discount perc change*/
$(document).on('keyup', '#new_item_dis_perc', (e) => {
    e.preventDefault();
    let rowCount = $("#disItemFooter #row_count").val();
    let input = $(e.target);
    input.prop('readonly',false);
    let value = parseFloat(input.val());
    let percAmount = 0;
    let totalCost = 0
    if (isNaN(value)) {
        input.val('');
        value = 0;
    }
    if (value < 0) {
        value = 0;
        input.val(value);
    } else if (value > 100) {
        let _total_perc = 0;
        $(`.display_discount_row`).find('[name*="[item_d_perc]"]').each(function(index,item) {
            _total_perc+=parseFloat($(item).val() || 0);
        });
        value = 100 - _total_perc;
        input.val(value);
        setTimeout(() => {
            Swal.fire({
                title: 'Error!',
                text: 'You cannot add more than 100%.',
                icon: 'error',
            });
        },0);
    }

    totalCost = parseFloat($(`[name*='components[${rowCount}][item_value]']`).val()) || 0;

    percAmount = parseFloat((totalCost * value) / 100);
    $("#new_item_dis_value").prop('readonly', Boolean(percAmount)).val(percAmount ? percAmount.toFixed(2) : '');
    return false;
});

/*Header discount value change*/
$(document).on('keyup', '#new_item_dis_value', (e) => {
    e.preventDefault();
    let input = $(e.target);
    input.prop('readonly',false);
    let value = parseFloat(input.val());
    $("#new_item_dis_perc").prop('readonly', Boolean(value)).val('');    
    return false;
});

/*Add New Summary Discount*/
$(document).on('click', '#add_new_head_dis', (e) => {
    e.preventDefault();
    const new_dis_id = $("#new_discount_id").val() || '';
    const new_dis_name = $("#new_dis_name").val() || '';
    const new_dis_perc = (Number($("#new_dis_perc").val()) || 0).toFixed(2);
    const new_dis_value = (Number($("#new_dis_value").val()) || 0).toFixed(2);

    let _total_head_dis = 0;
    $("[name*='[d_amnt]']").each(function(index,item) {
        _total_head_dis+=Number($(item).val());
    });

    let totalCost = parseFloat($("#TotalEachRowAmount").attr('amount')) || 0;
    let _total_head_dis_all = _total_head_dis + Number(new_dis_value);
    if(_total_head_dis_all > totalCost) {
        Swal.fire({
            title: 'Error!',
            text: 'You can not give total discount more then total cost.',
            icon: 'error',
        });
        return false;
    }

    if (!new_dis_name || (!new_dis_perc && !new_dis_value)) return;
    const tbl_row_count = $("#summaryDiscountTable .display_summary_discount_row").length + 1;
    const tr = `
    <tr class="display_summary_discount_row">
        <td>${tbl_row_count}</td>
        <td>${new_dis_name}
            <input type="hidden" value="${new_dis_id}" name="disc_summary[${tbl_row_count}][ted_d_id]">
            <input type="hidden" value="" name="disc_summary[${tbl_row_count}][d_id]">
            <input type="hidden" value="${new_dis_name}" name="disc_summary[${tbl_row_count}][d_name]" />
        </td>
        <td class="text-end">${new_dis_perc}
            <input type="hidden" value="${new_dis_perc}" name="disc_summary[${tbl_row_count}][d_perc]" />
        </td>
        <td class="text-end">${new_dis_value}
        <input type="hidden" value="${new_dis_value}" name="disc_summary[${tbl_row_count}][d_amnt]" />
        </td>
        <td>
            <a href="javascript:;" class="text-danger deleteSummaryDiscountRow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </a>
        </td>
    </tr>`
    if(!$(".display_summary_discount_row").length) {
        $("#summaryDiscountTable #disSummaryFooter").before(tr);
    } else {
        $(".display_summary_discount_row:last").after(tr);
    }
    $("#new_discount_id").val('');  
    $("#new_dis_name_select").val('');  
    $("#new_dis_name").val('');  
    $("#new_dis_perc").val('').prop('readonly',false);  
    $("#new_dis_value").val('').prop('readonly',false);
    let total_head_dis = 0;
    $("[name*='[d_amnt]']").each(function(index,item) {
        total_head_dis+=Number($(item).val());
    });
    if(total_head_dis) {
        $('#f_header_discount_hidden').removeClass('d-none');
    } else {
        $('#f_header_discount_hidden').addClass('d-none');
    }
    $("#disSummaryFooter #total").text(total_head_dis.toFixed(2));
    setTableCalculation();  
});

/*Header discount perc change*/
$(document).on('keyup', '#new_dis_perc', (e) => {
    e.preventDefault();
    let input = $(e.target);
    input.prop('readonly',false);
    let value = parseFloat(input.val());
    let percAmount = 0;
    let totalCost = 0
    if (isNaN(value)) {
        input.val('');
        value = 0;
    }
    if (value < 0) {
        value = 0;
        input.val(value);
    } else if (value > 100) {
        let _total_perc = 0;
        $("[name*='[d_perc]']").each(function(index,item) {
            _total_perc+=Number($(item).val());
        });
        value = 100 - _total_perc;
        input.val(value);
        Swal.fire({
            title: 'Error!',
            text: 'You cannot add more than 100%.',
            icon: 'error',
        });
    }
    totalCost = parseFloat($("#TotalEachRowAmount").attr('amount')) || 0;
    percAmount = parseFloat((totalCost * value) / 100);
    $("#new_dis_value").prop('readonly', Boolean(percAmount)).val(percAmount ? percAmount.toFixed(2) : '');
    return false;
});

/*Header discount value change*/
$(document).on('keyup', '#new_dis_value', (e) => {
    e.preventDefault();
    let input = $(e.target);
    input.prop('readonly',false);
    let value = parseFloat(input.val());
    $("#new_dis_perc").prop('readonly', Boolean(value)).val('');    
    return false;
});

/*Add New Summary Discount*/
$(document).on('click', '#add_new_head_exp', (e) => {
    e.preventDefault();
    const new_exp_id = $("#new_exp_id").val() || '';
    const new_exp_name = $("#new_exp_name").val() || '';
    const new_exp_perc = (Number($("#new_exp_perc").val()) || 0).toFixed(2);
    const new_exp_value = (Number($("#new_exp_value").val()) || 0).toFixed(2);

    let _total_head_exp = 0;
    $("[name*='[e_amnt]']").each(function(index,item) {
        _total_head_exp+=Number($(item).val());
    });

    let totalCost = parseFloat($("#f_total_after_tax").attr('amount')) || 0;
    let _total_head_exp_all = _total_head_exp + Number(new_exp_value);
    if(_total_head_exp_all > totalCost) {
        Swal.fire({
            title: 'Error!',
            text: 'You can not give total exp more then after tax value.',
            icon: 'error',
        });
        return false;
    }

    if (!new_exp_name || (!new_exp_perc && !new_exp_value)) return;
    const tbl_row_count = $("#summaryExpTable .display_summary_exp_row").length + 1;
    const tr = `
    <tr class="display_summary_exp_row">
        <td>${tbl_row_count}</td>
        <td>${new_exp_name}
            <input type="hidden" value="${new_exp_id}" name="exp_summary[${tbl_row_count}][ted_e_id]">
            <input type="hidden" value="" name="exp_summary[${tbl_row_count}][e_id]">
            <input type="hidden" value="${new_exp_name}" name="exp_summary[${tbl_row_count}][e_name]" />
        </td>
        <td class="text-end">${new_exp_perc}
            <input type="hidden" value="${new_exp_perc}" name="exp_summary[${tbl_row_count}][e_perc]" />
        </td>
        <td class="text-end">${new_exp_value}
        <input type="hidden" value="${new_exp_value}" name="exp_summary[${tbl_row_count}][e_amnt]" />
        </td>
        <td>
            <a href="javascript:;" class="text-danger deleteExpRow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </a>
        </td>
    </tr>`
    if(!$(".display_summary_exp_row").length) {
        $("#summaryExpTable #expSummaryFooter").before(tr);
    } else {
        $(".display_summary_exp_row:last").after(tr);
    }
    $("#new_exp_name_select").val('');  
    $("#new_exp_id").val('');  
    $("#new_exp_name").val('');  
    $("#new_exp_perc").val('').prop('readonly',false);  
    $("#new_exp_value").val('').prop('readonly',false);
    let total_head_exp = 0;
    $("[name*='[e_amnt]']").each(function(index,item) {
        total_head_exp+=Number($(item).val());
    });
    $("#expSummaryFooter #total").text(total_head_exp.toFixed(2));
    setTableCalculation();  
});

/*Header discount perc change*/
$(document).on('keyup', '#new_exp_perc', (e) => {
    e.preventDefault();
    let input = $(e.target);
    input.prop('readonly',false);
    let value = parseFloat(input.val());
    let percAmount = 0;
    let totalCost = 0
    if (isNaN(value)) {
        input.val('');
        value = 0;
    }
    if (value < 0) {
        value = 0;
        input.val(value);
    } else if (value > 100) {
        let _total_perc = 0;
        $("[name*='[e_perc]']").each(function(index,item) {
            _total_perc+=Number($(item).val());
        });
        value = 100 - _total_perc;
        input.val(value);
        Swal.fire({
            title: 'Error!',
            text: 'You cannot add more than 100%.',
            icon: 'error',
        });
    }
    totalCost = parseFloat($("#f_total_after_tax").attr('amount')) || 0;
    percAmount = parseFloat((totalCost * value) / 100);
    $("#new_exp_value").prop('readonly', Boolean(percAmount)).val(percAmount ? percAmount.toFixed(2) : '');
    return false;
});

/*Header discount value change*/
$(document).on('keyup', '#new_exp_value', (e) => {
    e.preventDefault();
    let input = $(e.target);
    input.prop('readonly',false);
    let value = parseFloat(input.val());
    $("#new_exp_perc").prop('readonly', Boolean(value)).val('');    
    return false;
});

/*Qty enabled and disabled*/
function qtyEnabledDisabled() {    
    $("tr[id*='row_']").each(function(index,item) {
        let qtyDisabled = false;
        if($(item).find("[name*='[attr_name]']").length) {
            $(item).find("[name*='[attr_name]']").each(function () {
                if ($(this).val().trim() === "") {
                    qtyDisabled = true;
                }
            });
            $(item).find("[name*='[qty]']").attr('readonly',Boolean(qtyDisabled));
            if(qtyDisabled) {
                $(item).find("[name*='[qty]']").val('');
            }
        } else {
            $(item).find("[name*='[qty]']").attr('readonly',false);
        }
    });
}
qtyEnabledDisabled();

setTimeout(() => {
    if($("tr[id*='row_']").length) {
        setTableCalculation();
    }
},0);

$(document).on('blur','[name*="component_item_name"]',(e) => {
    if(!e.target.value) {
        $(e.target).closest('tr').find('[name*="[item_name]"]').val('');
    }
});

// Quantity on change
$(document).on('keyup', "input[name*='[qty]']", (e) => {
    let items = [];
    let inputEle = e.target;
    $("tr[id*='row_']").each(function(index, item) {
        let itemId = $(item).find("input[name*='[item_id]']").val();
        let attrName = $(item).find("input[name*='[attr_name]']").val();
        let uomId = $(item).find("select[name*='[uom_id]']").val();
        if (itemId && attrName && uomId) {
            let attr = [];
            // Collect attributes
            $(item).find("input[name*='[attr_name]']").each(function(ind, it) {
                const matches = it.name.match(/components\[\d+\]\[attr_group_id\]\[(\d+)\]\[attr_name\]/);
                if (matches) {
                    const attr_id = parseInt(matches[1], 10);
                    const attr_value = parseInt(it.value, 10);
                    if (attr_id && attr_value) {
                        attr.push({ attr_id, attr_value });
                    }
                }
            });
            // Add item details to the items array
            items.push({
                item_id: itemId,
                uom_id: uomId,
                attributes: attr,
            });
        }
    });
    if (items.length) {
        if(hasDuplicateObjects(items)) {
            Swal.fire({
                title: 'Error!',
                text: 'Duplicate item, Can not select item attribute same!',
                icon: 'error',
            });
            $(inputEle).val('');
        }
    }
});

function hasDuplicateObjects(array,inputEle) {
    const seen = new Set();
    for (const obj of array) {
        const objString = JSON.stringify(obj);
        if (seen.has(objString)) {
            return true;
        }
        seen.add(objString);
    }
    return false;
}

// UOM on change bind rate
$(document).on('change', 'select[name*="[uom_id]"]',(e) => {
    let tr = $(e.target).closest('tr');
    getItemCostPrice(tr);
    setTableCalculation();
});