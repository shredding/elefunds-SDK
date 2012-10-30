elefunds = (function($) {
  
  $(function(){
    //Change button backgrounds when (un)selected
    $(".elefunds_receiver > input").on('change', function() {
      $(this).parent().toggleClass("elefunds_receiver_selected");
    });
    
    //When the plugin is activated
    $("#elefunds_checkbox").on('change', function() {
      var value = $("#elefunds_input").val();
      
      $("#elefunds_input").toggleClass("elefunds_input_active");
      $("#elefunds_sum").toggleClass("elefunds_hidden");
    });
    
    //Enable tooltips
    $(".tiptip").tipTip({
      defaultPosition: 'top',
      edgeOffset: -12,
      delay: 200
    });
    
    //preload activated backgrounds
    $('<img src="https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/default/arrow_on.png" />');
    $('<img src="https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/default/checkbox_off.png" />');
    $('<img src="https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/default/receiver_off_bg.png" />');
  });
  
  //Currency decimal symbol
  var decimal = ".";
  //Alternative decimal symbol
  var decimalAlt = ",";
  
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
    
    //Check for valid value
    if(isNaN(centValue) || centValue < 0) {
        centValue = 0;
    }
    
    return centValue;
  }
  
  // Convert cent value to float equivalent
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
  
  // Update the donation input and hidden field
  function updateDonation(centValue) {
    //Update hidden input field
    $("#elefunds_donation_cent").val(centValue);
    
    //Update visible float input field
    var floatValue = convertToFloat(centValue);
    $("#elefunds_input").val(floatValue);
    
    //Update round sum
    $("#elefunds_round_sum").html(convertToFloat(elefundsVars['grandTotal'] + centValue));
  }
  
  // Update only the hidden donation input field
  // containing the value of the donation in cents
  function updateHiddenField(centValue) {
    $("#elefunds_donation_cent").val(centValue);
  }
  
  function updateRoundSum(centValue) {
    $("#elefunds_round_sum").html(convertToFloat(centValue));
  }
  
  //public functions needed for the user interaction
  return {
    
    // Decrease the donation sum by 1.00
    decreaseDonation: function() {
      var centValue = parseInt($("#elefunds_donation_cent").val(), 10);
      
      if(centValue > 100) {
        centValue = centValue - 100;
      }
      
      updateDonation(centValue);
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
      
      updateHiddenField(centValue);
      updateRoundSum(elefundsVars['grandTotal'] + centValue);
      return centValue;
    }
  };
})(window.jQuery);