var rowInputFields = ['amount', 'cost', 'VAT', 'VATpercent', 'discount', 'total'];
$(document).ready(function(e){
// ######### Product section ######### //

  var formSubmitted = false;
  var returnSubmmited = false;
$('#imageSelector').change(function() {
    var file = this.files[0].name;
    $('#imageSelectorLabel').text(file);
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#productImage')
          .attr('src', e.target.result).width(200).height(200);
    };
    reader.readAsDataURL(this.files[0]);
  });

  $('#productAddForm').click(function (e){
    if ($('#productAdditionSuccess small').text().length > 0)
    $('#productAdditionSuccess small').text('');
  });
  
  if($(document).attr('title') == 'Edit product'){
  $(function() {
    $("#searchProductName").autocomplete({
      source: "/training/productsearchautocomplete",
    });
 });
  }

 $('#searchProductName').keypress(function (e) {
  if (e.which == 13) {
    if ($('#searchProductName').val().length > 0)
      $('#searchProductAutoComplete').submit();
    return false;
  }
});

$('#searchProductName, #searchProviderName').click(function (e){
  if ($('#searchProductNameWarning, #AdditionSuccess small').text().length > 0)
  $('#searchProductNameWarning, #AdditionSuccess small').text('');
});


$('#searchProductName').on('input',function(e){
  if ($('#searchProductName').val().length == 0 && $('#searchProductNameWarning small').text().length > 0){
    $('#searchProductNameWarning small').text('');
  }
});

$("form").submit(function (e) {
   formSubmitted = true;
});

  window.onbeforeunload = function(){
    if (!formSubmitted){
    $.ajax({
      type: 'GET',
      url: '/training/unsetproductsession',
    });
  };
  }

// ######### End of product section ######### //


// ######### Purchase section ######### //



  
  $('#invoiceSubmit').prop('disabled', true);
  var products = [];
  var providers= [];
  var providerObj;
  var productObj;
    $("#searchProductNameInvoice").autocomplete({
      source: function(request, response) {  
        $.ajax({  
            url: "/training/invoiceInfosearch",  
            type: "GET",  
            dataType: "json",  
            data: { term: request.term },  
            success: function (data) {  
                response($.map(data, function (item) {
                    if (item != null){
                      products.push(item);
                      return { label: item.name, value: item.name };
                    }
                      
                }))  
            }  
        })  
    },
    });

  $('#searchProductNameInvoice').keypress(function (e) {
    
    if (e.which == 13 && $('#searchProductNameInvoice').val().length > 0) {
     productObj = products[products.findIndex(x => x.name === $('#searchProductNameInvoice').val())];
     products = [];
     var elementID = '#product'+productObj['ID'];
     if(!$(elementID).length){
     var newRow = '<tr id="product'+productObj['ID']+'">';
     newRow    += '<th scope="row">'+productObj['ID']+'</th>';
     newRow    += '<td>'+productObj['name']+'</td>';
     newRow    += '<td><input type="text" name="price'+productObj['ID']+'" class="form-control" id="price'+productObj['ID']+'" value="'+productObj['price']+'" readonly></td>';
     newRow    += '<td>'+productObj['quantity']+'</td>';
     newRow    += '<td id="amountcell'+productObj['ID']+'"><input type="number" class="form-control" min = "1" value="0" name="'+rowInputFields[0]+productObj['ID']+'" id="'+rowInputFields[0]+productObj['ID']+'"></td>';
     newRow    += '<td id="costcell'+productObj['ID']+'"><input type="number" max="'+productObj['price']+'" min = "1" step="0.01" class="form-control" value="0" name="'+rowInputFields[1]+productObj['ID']+'" id="'+rowInputFields[1]+productObj['ID']+'" ></td>';
     newRow    += '<td id="VATcell'+productObj['ID']+'"><input type="number" class="form-control" value="0" min = "0" step="0.01" name="'+rowInputFields[2]+productObj['ID']+'" id="'+rowInputFields[2]+productObj['ID']+'" ></td>';
     newRow    += '<td id="VATpercentcell'+productObj['ID']+'"><input type="number" max="100" min="0" step="0.01" class="form-control" value="0" name="'+rowInputFields[3]+productObj['ID']+'" id="'+rowInputFields[3]+productObj['ID']+'" ></td>';
     newRow    += '<td id="discountcell'+productObj['ID']+'"><input type="number" max="100" min="0" step="0.01" class="form-control" value="0" name="'+rowInputFields[4]+productObj['ID']+'" id="'+rowInputFields[4]+productObj['ID']+'" ></td>';
     newRow    += '<td id="totalcell'+productObj['ID']+'"><input type="number" class="form-control" value="0" name="'+rowInputFields[5]+productObj['ID']+'" id="'+rowInputFields[5]+productObj['ID']+'" disabled></td>';
     newRow    += '<td><button type="button" class="btn btn-danger" onClick="$(this).parent().parent().remove(); calculate();" id="product'+productObj['ID']+'">Delete</button></td>';
     newRow    += '</tr>';
     $('#productsRows').append(newRow);
     
     if ($('#searchProductNameInvoice').val().length > 0)
          $('#searchProductNameInvoice').val('');
    
    var latestAmount = '#amount'+productObj['ID'];
    $(latestAmount).focus();
    $(latestAmount).select();
    
    }
    else{
      e.preventDefault();
      alert('This product is already added!');
      if ($('#searchProductNameInvoice').val().length > 0)
          $('#searchProductNameInvoice').val('');
    }
  }
   else if(e.which == 13 && $('#searchProductNameInvoice').val().length == 0)
      e.preventDefault();
    
  });
    
    $('#invoiceTable').keypress(function(e){
      if (e.which == 13){
        var focusedInput = $(':focus').attr("id");
        if ($('#'+focusedInput).val() == '') $('#'+focusedInput).val("0.00"); 
        var inputfield = focusedInput.match(/\D+/);
        var productID = focusedInput.match(/\d+/);
        var inputFieldindex = rowInputFields.findIndex(x=>x == inputfield);
        nextInputFieldID = '#'+rowInputFields[inputFieldindex+1]+productID;
        if (inputFieldindex == (rowInputFields.length - 2)){
          $('#searchProductNameInvoice').focus();
          e.preventDefault();
          
        } 
        else{
          $(nextInputFieldID).focus();
          $(nextInputFieldID).select();
          e.preventDefault();
        }
      }
    });

    if (!$('#invoiceNumber').is(':disabled')) calculate();
     

    function calculate(){
        var subtotal        = 0;
        var totalVAT        = 0;
        var invoiceTotal    = 0;
        var percentDiscount = 0;
      
    $('#invoiceTable').on('change keyup click', function(e){
         subtotal         = 0;
         totalVAT         = 0;
         invoiceTotal     = 0;
         totalPrices      = 0;
         percentDiscount  = 0;
         if ($('#productsRows >tr').length == 0) $('#invoiceSubmit').prop('disabled', true);
         else {
          $('#invoiceSubmit').prop('disabled', false);
          $('#invoiceNumber').css("background-color","#ffffff");
         }
        
      $('#invoiceTable > tbody  > tr').each(function(index, tr) { 
        var rowID = $(this).attr('id');
        var productID = rowID.match(/\d+/);
        var amount = parseInt($('#amount'+productID).val());
        var cost = parseFloat($('#cost'+productID).val());
        var VAT = parseFloat($('#VAT'+productID).val());
        var discount = parseFloat($('#discount'+productID).val());
        var VATpercent = parseFloat($('#VATpercent'+productID).val());
        var price = parseFloat($('#price'+productID).val());

        if ($('#discount'+productID).is(":focus")){
          cost = price - ((price * discount) / 100);
          $('#cost'+productID).val(cost.toFixed(2));
          VAT = ((cost * VATpercent)/100);
          $('#VAT'+productID).val(VAT.toFixed(2));
        }

        if($('#VATpercent'+productID).is(":focus")){
          VAT = ((cost * VATpercent)/100);
          $('#VAT'+productID).val(VAT.toFixed(2));
        }
        
        if($('#VAT'+productID).is(":focus") && cost > 0){
          VATpercent = (VAT/cost)* 100;
          $('#VATpercent'+productID).val(VATpercent.toFixed(2));
        }

        if ($('#cost'+productID).is(":focus") && price > 0){
          discount = (1 - (cost/price)) * 100;
          $('#discount'+productID).val(discount.toFixed(2));
        }
          
        var total = amount * cost + VAT * amount;
        subtotal += amount * cost;
        totalVAT += VAT * amount;
        totalPrices += price * amount;
        $('#total'+productID).val(total.toFixed(2));
        
        
      });
      $('#subtotal').val(subtotal.toFixed(2));
      $('#vat').val(totalVAT.toFixed(2));
      percentDiscount = parseFloat($('#discountpercent').val()).toFixed(2) * (subtotal + totalVAT) /100;
      invoiceTotal = subtotal + totalVAT -  parseFloat($('#discount').val()).toFixed(2) - percentDiscount;
      $('#total').val(invoiceTotal.toFixed(2));
    });

    $('#discount').on('focus change keyup', function(e){
      if ($('#discount').val() == '') $('#discount').val(0.00);
      if ($.isNumeric($('#discount').val())){
        invoiceTotal = subtotal + totalVAT -  parseFloat($('#discount').val()).toFixed(2) - percentDiscount;
      $('#total').val(invoiceTotal.toFixed(2));
      }
      
    });

    $('#discountpercent').on('focus change keyup', function(e){
      if ($('#discountpercent').val() == '') $('#discountpercent').val(0.00);
      if ($.isNumeric($('#discountpercent').val())){
        percentDiscount = parseFloat($('#discountpercent').val()).toFixed(2) * (subtotal + totalVAT) /100;
        invoiceTotal = subtotal + totalVAT -  percentDiscount - parseFloat($('#discount').val()).toFixed(2);
      $('#total').val(invoiceTotal.toFixed(2));
      }
      
    });
  };

  $('#provider, #searchProviderName').autocomplete({
    source: function(request, response) {  
      $.ajax({  
          url: "/training/invoiceInfosearch",  
          type: "POST",  
          dataType: "json",  
          data: { provider_name: request.term, function: 'getProviderName' },  
          success: function (data) {  
              response($.map(data, function (provider) {
                  if (provider != null){
                    providers.push(provider);
                    return { label: provider.provider_name, value: provider.provider_name };
                  }
                    
              }))  
          }  
      })  
  },


  });

  pressEnterTransit('#invoiceNumber', '#provider');
  pressEnterTransit('#discountpercent', '#searchProductNameInvoice');
  pressEnterTransit('#discount', '#discountpercent');
  pressEnterTransit('#provider', '#discount');

  $('#provider').keypress(function (e) {
    if (e.which == 13) {
      $('#addProviderID').empty();
      if ($('#provider').val().length > 0 || $('#providerID').val().length == 0){
      providerObj = providers[providers.findIndex(x => x.provider_name === $('#provider').val())];
      var providerIDInput = '<input type="hidden" id="providerID" name="providerID" value="'+providerObj.ID+'">'
      $('#providerbalance').val(providerObj.balance);
      $('#addProviderID').append(providerIDInput);
      return false;
      }
    }
  });

  $('#invoiceSubmit').keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });



  $('#provider').on('focusout', function(e){
    if (typeof providerObj == 'undefined')
      providerObj = providers[providers.findIndex(x => x.provider_name === $('#provider').val())];
    
    if ($('#invoiceNumber').val().length > 0 && $('#provider').val().length > 0){
    $.post('invoiceInfosearch', {function: 'repeatedInvoiceNumber', invoiceNumber: $('#invoiceNumber').val(), provider: providerObj.ID}, function (data, status, xhr){
      if (data == 'true') {
        alert('This invoice number for this provider already exists in the database');
        $('#invoiceNumber').focus();
        $('#invoiceNumber').css("background-color","#fc5151");
        $('#invoiceSubmit').prop('disabled', true);
      }
      else if (data == 'false' && $('#productsRows >tr').length > 0){
        $('#invoiceNumber').css("background-color","#ffffff");
        $('#invoiceSubmit').prop('disabled', false);
      }
      else if (data == 'false' && $('#productsRows >tr').length == 0)
        $('#invoiceNumber').css("background-color","#ffffff");
    });
  }
  });

  $('#invoiceNumber').on('focusout', function(e){
    
    if ($('#invoiceNumber').val().length > 0 && $('#provider').val().length > 0){
    $.post('invoiceInfosearch', {function: 'repeatedInvoiceNumber', invoiceNumber: $('#invoiceNumber').val(), provider: providerObj.ID}, function (data, status, xhr){
      if (data == 'true') {
        $('#invoiceNumber').focus();
        $('#invoiceNumber').css("background-color","#fc5151");
        $('#invoiceSubmit').prop('disabled', true);
      }
      else if (data == 'false' && $('#productsRows >tr').length > 0){
        $('#invoiceNumber').css("background-color","#ffffff");
        $('#invoiceSubmit').prop('disabled', false);
      }
      else if (data == 'false' && $('#productsRows >tr').length == 0)
        $('#invoiceNumber').css("background-color","#ffffff");
    });
  }
  });

  $("#invoiceAddForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = form.attr('action');
    
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
         if (data = 'Success'){
          $('#searchProductNameWarning').append('<h6 class="text-success" >Invoice added successfully.</h6>');
          $('#invoiceSubmit').prop('disabled', true);
          $('#invoiceTable').on('change keyup click', function(e){
            $("#invoiceAddForm :input").prop("disabled", true);
           });
          $("#invoiceAddForm :input").prop("disabled", true);
         }
         else{
          $('#AdditionSuccess').append('<h6 class="text-success" >'+data+'</h6>');
         }
        }
    });
    
});

$("#invoiceSearchForm").submit(function(e) {

  e.preventDefault(); // avoid to execute the actual submit of the form.
  $('#invoicesRows').empty();
  var form = $(this);
  var actionUrl = form.attr('action');
  
  $.ajax({
      type: "POST",
      url: actionUrl,
      data: form.serialize(), // serializes the form's elements.
      success: function(response)
      {
        data = JSON.parse(response);
        $.each(data, function(i, item) {
            var tableRow = '<tr>';
            tableRow    += '<th scope="row">'+item.provider+'</th>';
            tableRow    += '<td>'+item.date+'</td>';
            tableRow    += '<td>'+item.discount+'</td>';
            tableRow    += '<td>'+item.percentdiscount+'</td>';
            tableRow    += '<td>'+item.invoiceNumber+'</td>';
            tableRow    += '<td>'+item.cost+'</td>';
            tableRow    += '<td>'+item.vat+'</td>';
            tableRow    += '<td>'+item.numberOfProducts+'</td>';
            tableRow    += '<td>'+item.total+'</td>';
            tableRow    += '<td><form action="invoiceInfosearch" method="POST">';
            tableRow    += '<input type="hidden" id="invoiceNumberHidden" name="invoiceNumberHidden" value="'+item.invoiceNumber+'">';
            tableRow    += '<input type="hidden" id="providerHidden" name="providerHidden" value="'+item.provider+'">';
            tableRow    += '<input type="hidden" id="discountHidden" name="discountHidden" value="'+item.discount+'">';
            tableRow    += '<input type="hidden" id="percentdiscountHidden" name="percentdiscountHidden" value="'+item.percentdiscount+'">';
            tableRow    += '<input type="hidden" id="costHidden" name="costHidden" value="'+item.cost+'">';
            tableRow    += '<input type="hidden" id="vatHidden" name="vatHidden" value="'+item.vat+'">';
            tableRow    += '<input type="hidden" id="totalHidden" name="totalHidden" value="'+item.total+'">';
            tableRow    += '<input type="hidden" id="pageNameHidden" name="pageNameHidden" value="'+$(document).attr('title')+'">';
            tableRow    += '<button type="submit" class="btn btn-success" name="invoiceRecallButton" id="invoiceRecallButton">Recall</button>';
            tableRow    += '</form></td>';
            tableRow    += '</tr>';
            $('#invoicesRows').append(tableRow);
        });
      }
  });
  
});

var dateTimeNow = new Date();
dateTimeNow.setTime(dateTimeNow.getTime() - dateTimeNow.getTimezoneOffset() * 60000);
dateTimeNow.setSeconds(00, 00);
$('#fromDate, #toDate').val(dateTimeNow.toJSON().slice(0,19));

    
$('#recallInvoice').click(function(){
  $('.invoiceRecallPopup').addClass('active');
});

$('.closeBtn').click(function(){
  $('#invoicesRows').empty();
  $('.invoiceRecallPopup').removeClass('active');
});



$('#invoiceRetunsTable').on('change keyup', function(e){
  var returnsTotal = 0;

  $('#invoiceRetunsTable > tbody  > tr').each(function(index, tr) {
        
        var rowID = $(this).attr('id');
        var productID = rowID.match(/\d+/);
        var cost = parseFloat($('#cost'+productID).val());
        var VAT = parseFloat($('#VAT'+productID).val());
        if ($('#returns'+productID).val() == '') $('#returns'+productID).val(0.00);
        var returns = parseFloat($('#returns'+productID).val());
        if ($.isNumeric(returns) && !$('#returns'+productID).is(':disabled')){
        returnsTotal += returns * (cost + VAT);
        }
  });
    $('#returnsTotal').val(returnsTotal.toFixed(2));

});




$("#returnItemForm").submit(function(e) {

  e.preventDefault(); // avoid to execute the actual submit of the form.
  $('#searchProductNameWarning').empty();
  var form = $(this);
  var actionUrl = form.attr('action');
  
  $.ajax({
      type: "POST",
      url: actionUrl,
      data: form.serialize(), // serializes the form's elements.
      success: function(data)
      { 
       if (data == 'Success'){
        $('#searchProductNameWarning').append('<h6 class="text-success" >Returns inserted successfully.</h6>');
        returnSubmmited = true;
        $("#returnItemForm :input").prop("disabled", true);
      }
       else $('#searchProductNameWarning').append('<h6 class="text-danger" >No returns were inserted.</h6>');
      }
  });
  
});

// ######### End of purchase section ######### //


// ######### Provider section ######### //

$('#searchProviderName').keypress(function (e) {
  if (e.which == 13) {
    if ($('#searchProviderName').val().length > 0)
      $('#searchProviderAutoComplete').submit();
    return false;
  }
});



// ######### End of provider section ######### //


// End of document #########
});


// Functions //


function pressEnterTransit(location, destination){
  $(location).keypress(function (e) {
    if (e.which == 13) {
      if ($(location).val().length > 0)
        $(destination).focus();
      return false;
    }
  });

}
