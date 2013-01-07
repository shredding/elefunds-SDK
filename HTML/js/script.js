elefunds = (function($) {
  
  // Final round sum (Total + Donation)
  var roundSum;
  
  // Currency decimal symbols
  var decimal, decimalAlt;
  
  var enabled = false;
  
  $(function(){
    roundSum = $(elefundsVars['roundSum']);
    roundSumContainer = $(elefundsVars['roundSumContainer']);
    
    function enable() {
      $('#elefunds_checkbox').prop('checked', true);
      $("#elefunds_input").addClass('elefunds_input_active');
      roundSumContainer.removeClass('elefunds_hidden');
      enabled = true;
    };
    
    function disable() {
      $('#elefunds_checkbox').prop('checked', false);
      $("#elefunds_input").removeClass('elefunds_input_active');
      roundSumContainer.addClass('elefunds_hidden');
      enabled = false;
    }
    
    if(elefundsVars['decimal'] === ',') {
      decimal = ',';
      decimalAlt = '.';
    } else {
      decimal = '.';
      decimalAlt = ',';
    }
    
    // Change button backgrounds when (un)selected
    $(".elefunds_receiver > input").on('change', function() {
      //If all are unchecked
      if($('#elefunds_bottom input[type="checkbox"]:checked').length == 0) {
        disable();
      } else if(!enabled) {
        enable();
      }
      
      $(this).parent().toggleClass("elefunds_receiver_selected");
    });
    
    // When the plugin is (de)activated
    $("#elefunds_checkbox").on('change', function() {
      if($("#elefunds_checkbox").attr('checked') && $('#elefunds_bottom input[type="checkbox"]:checked').length == 0) {
        $('#elefunds_bottom input[type="checkbox"]').prop('checked', true);
        $('#elefunds_bottom input[type="checkbox"]').parent().toggleClass("elefunds_receiver_selected");
      }
      
      $("#elefunds_input").toggleClass("elefunds_input_active");
      roundSumContainer.toggleClass("elefunds_hidden");
    });
    
    // Enable tooltips
    $(".tiptip").tipTip({
      defaultPosition: 'top',
      edgeOffset: -12,
      delay: 200
    });
  });
  
  // Convert float sum to cents
  function convertToCent(floatValue) {
    var centValue;
    var centArray;
    
    if(floatValue.indexOf(decimal) !== -1) {
      centArray = floatValue.split(decimal,2);
    } else if(floatValue.indexOf(decimalAlt) !== -1) {
      centArray = floatValue.split(decimalAlt,2);
    } else {
      centArray = [floatValue, 0];
    }
    
    if(centArray[1].length > 2) {
      centArray[1] = centArray[1].substr(0,2);
    }

    while(centArray[1].length < 2) {
      centArray[1] += "0";
    }

    if(centArray[0].length < 1) {
      centArray[0] = 0;
    }

    centValue = parseInt(centArray[0], 10)*100 + parseInt(centArray[1], 10);
    
    // Check for valid value
    if(isNaN(centValue) || centValue < 0) {
        centValue = 0;
    }
    
    return centValue;
  }
  
  // Convert cent value to float currency equivalent
  function convertToFloat(centValue) {
    var floatValue;
    
    if(centValue < 10) {
      floatValue = "0" + decimal + "0" + centValue;
    } else if(centValue < 100) {
      floatValue = "0" + decimal + centValue;
    } else {
      centValue = centValue.toString();
      floatValue = centValue.slice(0, -2) + decimal + centValue.slice(-2);
    }
    
    return floatValue;
  }
  
  // Update the donation input field
  function updateDonation(centValue) {
    var floatValue = convertToFloat(centValue);
    $("#elefunds_input").val(floatValue);
    
    render(centValue);
  }
  
  function render(centValue) {
    // Update the hidden donation input field
    // containing the value of the donation in cents
    $("#elefunds_donation_cent").val(centValue);
    
    // Update the Round Sum (Grand Total + donation)
    roundSum.html(convertToFloat(elefundsVars['grandTotal'] + centValue));
  }
  
  // Public functions needed for the user interaction
  return {
    
    // Decrease the donation sum by 1.00
    decreaseDonation: function() {
      var centValue = parseInt($("#elefunds_donation_cent").val(), 10);
      
      if(centValue > 100) {
        centValue = centValue - 100;
        updateDonation(centValue);
      }
      
      return centValue;
    },
    
    // Increase the donation sum by 1.00
    increaseDonation: function() {
      var centValue = parseInt($("#elefunds_donation_cent").val(), 10);
      
      centValue = parseInt(centValue, 10) + 100;
      
      updateDonation(centValue);
      return centValue;
    },
    
    // Update the hidden field with the current donation value
    donationChange: function(floatValue) {
      var centValue = convertToCent(floatValue);
      
      render(centValue);
      return centValue;
    }
  };
})(window.jQuery);