/*Checkbox*/
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

/*Approve modal*/
$(document).on('click', '#approved-button', (e) => {
   let actionType = 'approve';
   $("#approveModal").find("#action_type").val(actionType);
   $("#approveModal #popupTitle").text("Approve Application");
   $("#approveModal").modal('show');
});
$(document).on('click', '#reject-button', (e) => {
   let actionType = 'reject';
   $("#approveModal #popupTitle").text("Reject Application");
   $("#approveModal").find("#action_type").val(actionType);
   $("#approveModal").modal('show');
});

/*Delete Row*/
$(document).on('click','#deleteBtn', (e) => {
    let itemIds = [];
    let editItemIds = [];
    $('#itemTable > tbody .form-check-input').each(function() {
        if ($(this).is(":checked")) {
            if($(this).attr('data-id')) {
               editItemIds.push($(this).attr('data-id'));
            } else {
               itemIds.push($(this).val());
            }
        }
    });
    if (itemIds.length) {
        itemIds.forEach(function(item,index) {
            $(`#row_${item}`).remove();
        });
    }
    if(editItemIds.length == 0 && itemIds.length == 0) {
      alert("Please first add & select row item.");
    }
    if (editItemIds.length) {
      $("#deleteComponentModal").find("#deleteConfirm").attr('data-ids',JSON.stringify(editItemIds));
      $("#deleteComponentModal").modal('show');
    }

    if(!$("tr[id*='row_']").length) {
        $("#itemTable > thead .form-check-input").prop('checked',false);
        $(".prSelect").prop('disabled',false);
    }
    let indexData = $("#row_1").attr('data-index');
    totalCostEachRow(indexData);
});

/*Attribute on change*/
$(document).on('change', '[name*="comp_attribute"]', (e) => {
    let rowCount = e.target.closest('tr').querySelector('[name*="row_count"]').value;
    let attrGroupId = e.target.getAttribute('data-attr-group-id');
    $(`[name="components[${rowCount}][attr_group_id][${attrGroupId}][attr_name]"]`).val(e.target.value);
    qtyEnabledDisabled();

    let itemId = $("#attribute tbody tr").find('[name*="[item_id]"]').val();
   let itemAttributes = [];
   $("#attribute tbody tr").each(function(index, item) {
      let attr_id = $(item).find('[name*="[attribute_id]"]').val();
      let attr_value = $(item).find('[name*="[attribute_value]"]').val();
      itemAttributes.push({
            'attr_id': attr_id,
            'attr_value': attr_value
        });
   });
   getBomItemCost(itemId,itemAttributes);
});

/*Over Head item btn*/
$(document).on('click', '.overheadItemBtn', (e) => {
    let rowCount = $("#itemOverheadSummaryFooter [name='row_count']").val();
    $("#overheadItemPopup").modal('hide');
   /*Bind overall total*/
   // totalCostEachRow(rowCount);
});

/*Overhead Summary Popup*/
$(document).on('click', '.addOverHeadSummaryBtn', (e) => {
    $('#overheadSummaryPopup').modal('show');

});

/*Waste Summary submit Btn*/
$(document).on('click', '.wasteSummaryBtn', (e) => {
   $("#wastagePopup").modal('show');
});

// wastagePopupSummaryBtn
$(document).on('click', '.wastagePopupSummaryBtn', (e) => {
   let totalWasteAmount = $("#wastagePopup").find("[name='waste_amount']").val();
   let wasteTotal = 0;
   $("#itemTable tbody [id*='row_']").each(function(index, item) {
      let amnt = $(item).find('[name*=waste_amount]').val();
      wasteTotal = wasteTotal + Number(amnt); 
   });
   let allTotal = wasteTotal + Number(totalWasteAmount);
   $("#footerWasteAmount").text(Number(allTotal).toFixed(2));
   $("#footerWasteAmount").attr('amount',allTotal);
   $("#wastagePopup").modal('hide');
   overallTotal();
});

/*Submit main overhead*/
$(document).on('click','.overheadSummaryBtn', (e) => {
   $("#overheadSummaryPopup").modal('hide');
});

/*Delete overhead sumary row*/
$(document).on('click', '.deleteOverHeadSummary', (e) => {
   let id = $(e.target).closest('a').attr('data-id');
   if(!id) {
      $(e.target).closest('tr').remove();
      setTableCalculation();
   }
});

/*qty on change*/
$(document).on('change input',"table input[name*='qty']",(e) => {
   let tr = e.target.closest('tr');
   let qty = e.target;
   let itemCost = $(e.target).closest('tr').find("[name*='item_cost']");
   let superceededCost = $(e.target).closest('tr').find("[name*='superceeded_cost']"); 
   let itemValue = $(e.target).closest('tr').find("[name*='item_value']");
   if (Number(superceededCost.val())) {
      let totalItemValue = Number(qty.value) * Number(superceededCost.val());
      itemValue.val(totalItemValue.toFixed(2));
   } else {
      let totalItemValue = Number(qty.value) * Number(itemCost.val());
      itemValue.val(totalItemValue.toFixed(2));
   }
   totalCostEachRow(tr.getAttribute('data-index'));
});

/*superceeded_cost*/
$(document).on('change input',"[name*='superceeded_cost']",(e) => {
   let tr = e.target.closest('tr');
   let superceededCost = e.target;
   let itemCost = $(e.target).closest('tr').find("[name*='item_cost']");
   let qty = $(e.target).closest('tr').find("[name*='qty']"); 
   let itemValue = $(e.target).closest('tr').find("[name*='item_value']");
   if (Number(superceededCost.value)) {
      let totalItemValue = Number(qty.val()) * Number(superceededCost.value);
      itemValue.val(totalItemValue.toFixed(2));
   } else {
      let totalItemValue = Number(qty.val()) * Number(itemCost.val());
      itemValue.val(totalItemValue.toFixed(2));
   }
   totalCostEachRow(tr.getAttribute('data-index'));
});

// waste_perc
$(document).on('change input',"#itemTable [name*='waste_perc']",(e) => {
   let tr = e.target.closest('tr');
   let wastePerc = e.target;
   let wasteAmount = $(e.target).closest('tr').find("[name*='waste_amount']");
   if(Number(wastePerc.value) > 100) {
      wastePerc.value = 100;
      alert("Percentage can not be more than 100.");
   }
   if (Number(wastePerc.value) > 0) {
      wasteAmount.attr('readonly', true);
   let itemValue = $(e.target).closest('tr').find("[name*='item_value']");
   let wasteAmountTotal = Number(itemValue.val()) * Number(wastePerc.value) / 100;

   if(wasteAmountTotal) {
      wasteAmount.val(wasteAmountTotal);
   }
   } else {
      wasteAmount.attr('readonly', false);
      wasteAmount.val('');
   }
   totalCostEachRow(tr.getAttribute('data-index'));
});

// waste_amount
$(document).on('change input',"#itemTable [name*='waste_amount']",(e) => {
   let tr = e.target.closest('tr');
   let wasteAmount = e.target;
   let wastePece = $(e.target).closest('tr').find("[name*='waste_perc']");
   if (Number(wasteAmount.value) > 0) {
      wastePece.attr('readonly', true);
   } else {
      wastePece.attr('readonly', false);
   }
   totalCostEachRow(tr.getAttribute('data-index'));
});

/*Som each row item cost*/
function totalCostEachRow(rowIndex) {
   setTableCalculation();
};

/*Edit mode table calculation filled*/
if($("#itemTable .mrntableselectexcel tr").length) {
   setTimeout(()=> {
      $("[name*='component_item_name[1]']").trigger('focus');
      $("[name*='component_item_name[1]']").trigger('blur');
   },100);
}
/*Sum overall total cost*/
function overallTotal() {
   let a = $("#footerSubTotal").attr('amount') || 0;
   let b = $("#footerOverhead").attr('amount') || 0;
   let c = $("#footerWasteAmount").attr('amount') || 0;
   let total = Number(a) +  Number(b) + Number(c);
   $("#footerTotalCost").text(total.toFixed(2));
}

// waste_perc
$(document).on('change input', "#wastagePopup [name='waste_perc']", (e) => {
   if(Number(e.target.value) > 0) {
      $("#wastagePopup [name='waste_amount']").attr('readonly', true);
      $("#wastagePopup [name='waste_amount']").val('');
      if(Number(e.target.value) > 100) {
         e.target.value = 100;
         alert("Percentage can not be more than 100.");
      }
   } else {
      $("#wastagePopup [name='waste_amount']").attr('readonly', false);
   }

   let subTotal = $("#footerSubTotal").attr('amount');
   let wastePercAmount = Number(subTotal) * e.target.value / 100;
   $("#wastagePopup [name='waste_amount']").val(wastePercAmount);
});

// waste_amount
$(document).on('change input', "#wastagePopup [name='waste_amount']", (e) => {
   if(Number(e.target.value)) {
      $("#wastagePopup [name='waste_perc']").attr('readonly',true);
      $("#wastagePopup [name='waste_perc']").val('');
   } else {
      $("#wastagePopup [name='waste_perc']").attr('readonly',false);
   }
});

// Waste Amt
function totalWasteCal() {
   let lw = 0;
   $("#itemTable [name*='waste_amount']").each(function(index,item){
      lw = lw + Number($(item).val());
   });
   if(lw) {
      $("#totalWasteAmtValue").text(lw.toFixed(2));
      let totalWasteAmount = $("#wastagePopup").find("[name='waste_amount']").val();
      let to_waste = Number(totalWasteAmount) + lw;
      $("#footerWasteAmount").text(to_waste.toFixed(2));
      $("#footerWasteAmount").attr('amount',to_waste);
   } else {
      $("#totalWasteAmtValue").text('0.00');
   }
}

function totalItemCal() {
   let lw = 0;
   $("#itemTable [name*='[item_value]']").each(function(index,item){
      lw = lw + Number($(item).val());
   });
   if(lw) {
      $("#totalItemValue").text(lw.toFixed(2));
   } else {
      $("#totalItemValue").text('0.00');
   }
}

function totalOverheadCal() {
   let lw = 0;
   $("#itemTable [name*='overhead_amount']").each(function(index,item){
      lw = lw + Number($(item).val());
   });
   if(lw) {
      $("#totalOverheadAmountValue").text(lw.toFixed(2));
   } else {
      $("#totalOverheadAmountValue").text('0.00'); 
   }
}

function totalItemCostCal() {
   let lw = 0;
   $("#itemTable [name*='item_total_cost']").each(function(index,item){
      lw = lw + Number($(item).val());
   });
   if(lw) {
      $("#totalCostValue").text(lw.toFixed(2));
   } else {
      $("#totalCostValue").text('0.00');
   }
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
});

/*Approve modal*/
$(document).on('click', '#approved-button', (e) => {
   let actionType = 'approve';
   $("#approveModal").find("#action_type").val(actionType);
   $("#approveModal").modal('show');
});
$(document).on('click', '#reject-button', (e) => {
   let actionType = 'reject';
   $("#approveModal").find("#action_type").val(actionType);
   $("#approveModal").modal('show');
});

/*Bom detail remark js*/
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


/*Set cost*/
setTimeout(() => {
   $("#itemTable [id*='row_'").each(function(index, item) {
      let rowCount = $(item).attr('data-index');
      let val = $(item).find("[name*='[qty]']").val();
      $(item).find("[name*='[qty]']").trigger('change').val(val);
   });

},100);

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

function setTableCalculation() {
   let totalItemValue = 0;
   let totalItemCost = 0;
   let totalItemOverhead = 0;
   let totalItemWasteAmnt = 0;
   let totalHeaderWasteAmnt = 0;
   let totalHeaderOverhead = 0;
   let grandTotal = 0;

   /*Detail Level Calculation Set*/
   $("#itemTable [id*='row_']").each(function (index, item) {
      let itemWasteAmnt = 0;
      let itemOverheadAmnt = 0;
      let itemCost = 0;
      let rowCount = Number($(item).attr('data-index'));
      let qty = Number($(item).find("[name*='[qty]']").val()) || 0;
      let rate = $(item).find("[name*='[superceeded_cost]']").val() || 0;
      if(!Number(rate)) {
         rate = $(item).find("[name*='[item_cost]']").val() || 0;
      }
      let wastePerc = $(item).find("[name*='[waste_perc]']").val() || 0;
      let itemValue = (Number(qty) * Number(rate)) || 0;
      totalItemValue+=itemValue;
      $(item).find("[name*='[item_value]']").val(itemValue.toFixed(2));
      if(wastePerc) {
         itemWasteAmnt = itemValue * wastePerc / 100;
      } else {
         itemWasteAmnt = Number($(item).find("[name*='[waste_amount]']").val()) || 0;
      }
      totalItemWasteAmnt+=itemWasteAmnt;
      $(item).find("[name*='[waste_amount]']").val(itemWasteAmnt.toFixed(2));

      if ($(item).find("[name*='[amnt]']").length) {
         $(item).find("[name*='[amnt]']").each(function(index,item) {
            itemOverheadAmnt += Number($(item).val()) || 0;
         });
      } else {
         itemOverheadAmnt = 0;
      }
      totalItemOverhead+=itemOverheadAmnt;
      $(item).find("[name*='[overhead_amount]']").val(itemOverheadAmnt.toFixed(2));
      itemCost = itemValue + itemWasteAmnt + itemOverheadAmnt;
      totalItemCost+=itemCost;
      $(item).find("[name*='[item_total_cost]']").val(itemCost.toFixed(2));
   });

   $("#totalItemValue").attr('amount',totalItemValue).text(totalItemValue.toFixed(2));
   $("#totalWasteAmtValue").attr('amount',totalItemWasteAmnt).text(totalItemWasteAmnt.toFixed(2));
   $("#totalOverheadAmountValue").attr('amount',totalItemOverhead).text(totalItemOverhead.toFixed(2));
   $("#totalCostValue").attr('amount',totalItemCost).text(totalItemCost.toFixed(2));
   $("#footerSubTotal").attr('amount',totalItemValue).text(totalItemValue.toFixed(2));

   /*Header Level Calculation Set*/
   $(".display_overhead_summary_row").find("[name*='[amnt]']").each(function(index,item) {
      totalHeaderOverhead+= Number($(item).val()) || 0;
   });
   
   $("#footerOverhead").attr('amount',(totalItemOverhead + totalHeaderOverhead)).text((totalItemOverhead + totalHeaderOverhead).toFixed(2));

   $("#overheadSummaryFooter #total").attr('amount',totalHeaderOverhead).text(totalHeaderOverhead.toFixed(2));
   let headerWastePercentage = Number($("#wastagePopup").find('[name="waste_perc"]').val()) || 0;
   if(headerWastePercentage) {
      totalHeaderWasteAmnt = totalItemValue * headerWastePercentage / 100;
   } else {
      totalHeaderWasteAmnt = Number($("#wastagePopup").find('[name="waste_amount"]').val()) || 0; 
   }

   $("#footerWasteAmount").attr('amount',(totalItemWasteAmnt + totalHeaderWasteAmnt)).text((totalItemWasteAmnt + totalHeaderWasteAmnt).toFixed(2));

   $("#wastagePopup").find('[name="waste_amount"]').val(totalHeaderWasteAmnt.toFixed(2));

   grandTotal = totalItemValue + totalItemWasteAmnt + totalItemOverhead + totalHeaderOverhead + totalHeaderWasteAmnt;
   $("#footerTotalCost").attr('amount',grandTotal).text(grandTotal.toFixed(2));
 }

 /*Add New Overhead*/
 $(document).on('click', '#add_new_overhead', (e) => {
   e.preventDefault();
   let description = $("#overhead_description").val();
   let amnt = Number($("#overhead_amount").val() || 0).toFixed(2);
   let ledger = $("#overhead_ledger").val();
   let ledger_id = $("#overhead_ledger_id").val();
   let totalAmount = 0;
   if (!amnt || (!ledger && !description)) return;
   let tbl_row_count = $("#headerOverheadTbl .display_overhead_summary_row").length + 1;
   const tr = `<tr class="display_overhead_summary_row">
                 <td>${tbl_row_count}</td>
                 <td>${description}
                 <input type="hidden"  name="overhead[${tbl_row_count}][id]">
                 <input type="hidden" value="${description}" name="overhead[${tbl_row_count}][description]"></td>
                 <td class="text-end">${amnt}
                 <input type="hidden" value="${amnt}" name="overhead[${tbl_row_count}][amnt]"></td>
                 <td>${ledger}
                     <input type="hidden" value="${ledger}" name="overhead[${tbl_row_count}][leadger]" />
                     <input type="hidden" value="${ledger_id}" name="overhead[${tbl_row_count}][leadger_id]">
                  </td>
                  <td>
                  <a href="javascript:;" class="text-danger deleteOverHeadSummary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                  </td>
               </tr>`;

   if(!$(".display_overhead_summary_row").length) {
        $("#headerOverheadTbl #overheadSummaryFooter").before(tr);
    } else {
        $(".display_overhead_summary_row:last").after(tr);
    }

   $("#headerOverheadTbl").find('[name*="[amnt]"]').each(function(index,item){
      totalAmount+= Number($(item).val()) || 0;  
   });

    $("#overheadSummaryFooter #total").attr('amount',totalAmount).text(totalAmount.toFixed(2));
    
    $("#overhead_description").val('');
    $("#overhead_amount").val('');
    $("#overhead_ledger").val('');
    $("#overhead_ledger_id").val('');
    setTableCalculation();
 });

  /*Add New item level Overhead*/
 $(document).on('click', '#add_new_item_overhead', (e) => {
   e.preventDefault();
   let rowCount = $("#itemOverheadSummaryFooter").find("[name='row_count']").val();
   let description = $("#item_overhead_description").val();
   let amnt = Number($("#item_overhead_amount").val() || 0).toFixed(2);
   let ledger = $("#item_overhead_ledger").val();
   let ledger_id = $("#item_overhead_ledger_id").val();
   let totalAmount = 0;
   if (!amnt || (!ledger && !description)) return;
   let tbl_row_count = $("#itemOverheadTbl .display_overhead_row").length + 1;
   const tr = `<tr class="display_overhead_row">
                 <td>${tbl_row_count}</td>
                 <td>${description}
                 <input type="hidden"  name="components[${rowCount}][overhead][${tbl_row_count}][id]">
                 <input type="hidden" value="${description}" name="components[${rowCount}][overhead][${tbl_row_count}][description]"></td>
                 <td class="text-end">${amnt}
                 <input type="hidden" value="${amnt}" name="components[${rowCount}][overhead][${tbl_row_count}][amnt]"></td>
                 <td>${ledger}
                     <input type="hidden" value="${ledger}" name="components[${rowCount}][overhead][${tbl_row_count}][leadger]" />
                     <input type="hidden" value="${ledger_id}" name="components[${rowCount}][overhead][${tbl_row_count}][leadger_id]">
                  </td>
                  <td>
                  <a href="javascript:;" class="text-danger deleteOverHeadItem"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                  </td>
               </tr>`;

   if(!$(".display_overhead_row").length) {
        $("#itemOverheadTbl #itemOverheadSummaryFooter").before(tr);
    } else {
        $(".display_overhead_row:last").after(tr);
    }

   $("#itemOverheadTbl").find('[name*="[amnt]"]').each(function(index,item){
      totalAmount+= Number($(item).val()) || 0;  
   });

    $("#itemOverheadSummaryFooter #total").attr('amount',totalAmount).text(totalAmount.toFixed(2));
    
    $("#item_overhead_description").val('');
    $("#item_overhead_amount").val('');
    $("#item_overhead_ledger").val('');
    $("#item_overhead_ledger_id").val('');
    let hiddenOverheadRow = '';
    $(`[id*='row_'] [name*='components[${rowCount}][overhead]']`).remove();
    $("#itemOverheadTbl .display_overhead_row").each(function(index, item) {
      let _id = $(item).find('[name*="[id]"]').val();      
      let _description = $(item).find('[name*="[description]"]').val();  

      let _amnt = $(item).find('[name*="[amnt]"]').val();      
      let _leadger = $(item).find('[name*="[leadger]"]').val();      
      let _leadger_id = $(item).find('[name*="[leadger_id]"]').val();
      hiddenOverheadRow+=`<input type="hidden" name="components[${rowCount}][overhead][${index + 1}][id]" value="${_id}">
      <input type="hidden" name="components[${rowCount}][overhead][${index + 1}][description]" value="${_description}">
      <input type="hidden" value="${_amnt}" name="components[${rowCount}][overhead][${index + 1}][amnt]">
      <input type="hidden" value="${_leadger}" name="components[${rowCount}][overhead][${index + 1}][leadger]">
      <input type="hidden" value="${_leadger_id}" name="components[${rowCount}][overhead][${index + 1}][leadger_id]">`;
    });
    
    $(`[name*='components[${rowCount}][overhead_amount]']`).closest('div').after(hiddenOverheadRow);
    $(`[name*='components[${rowCount}][overhead_amount]']`).val(totalAmount.toFixed(2));
    setTableCalculation();
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

$('#attribute').on('hidden.bs.modal', function () {
   let rowCount = $("[id*=row_].trselected").attr('data-index');
   // $(`[id*=row_${rowCount}]`).find('.addSectionItemBtn').trigger('click');
   //  if ($(`[name="components[${rowCount}][qty]"]`).is('[readonly]')) {
   //      $(`[name="components[${rowCount}][superceeded_cost]"]`).trigger('focus');
   //  } else {
   //      $(`[name="components[${rowCount}][qty]"]`).trigger('focus');
   //  }

   if(!$("#consumption_method").val().includes('manual')) {
      $(`[name="components[${rowCount}][qty]"]`).closest('td').find('.consumption_btn button').trigger('click');
    } else {
      $(`[name="components[${rowCount}][qty]"]`).val('').focus();
    }

});

// Set Calculate Consumption Qty
document.addEventListener("DOMContentLoaded", function () {
   const qtyPerUnit = document.getElementById("qty_per_unit");
   const totalQty = document.getElementById("total_qty");
   const stdQty = document.getElementById("std_qty");
   const output = document.getElementById("output");
   const selectButton = document.querySelector(".submit_consumption");
   function calculateOutput() {
      const qty = parseFloat(qtyPerUnit.value) || 0;
      const total = parseFloat(totalQty.value) || 0;
      const std = parseFloat(stdQty.value) || 0;
      const result = total > 0 ? (std / total * qty) : 0;
      output.value = isNaN(result) ? "0.000000" : result.toFixed(6);
      let rowCount = $("#consumptionPopup input[name='consumption_row']").val();
      $(`[name="components[${rowCount}][qty]"]`).val(output.value);
      let hiddenInput = `
      <input type="hidden" name="components[${rowCount}][qty_per_unit]" value="${isNaN(parseFloat(qtyPerUnit.value)) ? '' : parseFloat(qtyPerUnit.value)}">
      <input type="hidden" name="components[${rowCount}][total_qty]" value="${isNaN(parseFloat(totalQty.value)) ? '' : parseFloat(totalQty.value)}">
      <input type="hidden" name="components[${rowCount}][std_qty]" value="${isNaN(parseFloat(stdQty.value)) ? '' : parseFloat(stdQty.value)}">`;
      $(`[name="components[${rowCount}][qty_per_unit]"]`).remove();
      $(`[name="components[${rowCount}][total_qty]"]`).remove();
      $(`[name="components[${rowCount}][std_qty]"]`).remove();
      $(`[name="components[${rowCount}][qty]"]`).after(hiddenInput);
      setTableCalculation();
   }
   qtyPerUnit.addEventListener("input", calculateOutput);
   totalQty.addEventListener("input", calculateOutput);
   stdQty.addEventListener("input", calculateOutput);
   selectButton.addEventListener("click", function () {
      let isValid = true;
      [qtyPerUnit, totalQty, stdQty].forEach((field) => {
         if (!field.value.trim()) {
            isValid = false;
            field.classList.add("is-invalid");
         } else {
            field.classList.remove("is-invalid");
         }
      });
      if (!isValid) {
         Swal.fire({
            title: 'Error!',
            text: "Please fill out all required fields." ,
            icon: 'error',
        });
      } else {
         $("#consumptionPopup").modal('hide');
      }
   });
});

$(document).on('click', '.consumption_btn', (e) => {
   let rowCount = $(e.target).attr('data-row-count');
   $("#consumptionPopup").modal('show');
   $("#consumptionPopup input[name='consumption_row']").val(rowCount);
   let qty_per_unit = $(`[name="components[${rowCount}][qty_per_unit]"]`).val() || '';
   let total_qty = $(`[name="components[${rowCount}][total_qty]"]`).val() || '';
   let std_qty = $(`[name="components[${rowCount}][std_qty]"]`).val() || '';
   $("#qty_per_unit").val(qty_per_unit);
   $("#total_qty").val(total_qty);
   $("#std_qty").val(std_qty);
   const qty = parseFloat(qty_per_unit) || 0;
   const total = parseFloat(total_qty) || 0;
   const std = parseFloat(std_qty) || 0;
   const result = total > 0 ? (std / total * qty) : 0;
   const output  = isNaN(result) ? "0.000000" : result.toFixed(6);
   $("#output").val(output);
});