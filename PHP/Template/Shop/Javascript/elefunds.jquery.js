var elefunds = (function(parent, $) {

  var roundSumContainer, roundSum, currency, decimal, decimalAlt, total, toolTipPosition;
  var enabled = false;

  function init(options) {
    roundSumContainer = options.roundSumContainer;
    roundSum = options.roundSum;
    currency = options.currency;
    decimal = options.decimal;
    decimalAlt = options.deciamlAlt;
    total = options.total;
    toolTipPosition = options.toolTipPosition;

    $('#elefunds').data('elefunds-roundSum', convertToFloat(total + parseInt($('#elefunds_donation_cent').val(), 10)));
    $('#elefunds').data('elefunds-donation', {donationCent: parseInt($('#elefunds_donation_cent').val(), 10), donationFloat: convertToFloat(parseInt($('#elefunds_donation_cent').val(), 10))});

    function enable() {
      $('#elefunds_checkbox').prop('checked', true);
      $("#elefunds_input").addClass('elefunds_input_active');
      roundSumContainer.slideDown();

      $('#elefunds').trigger('elefunds_enabled');
      enabled = true;
    }

    function disable() {
      $('#elefunds_checkbox').prop('checked', false);
      $('#elefunds_input').removeClass('elefunds_input_active');
      roundSumContainer.slideUp();

      $('#elefunds').trigger('elefunds_disabled');
      enabled = false;
    }

    // Change button backgrounds when (un)selected
    $('.elefunds_receiver > input').on('change', function() {
      //If all are unchecked
      if($('#elefunds_bottom').find('input[type="checkbox"]:checked').length == 0) {
        disable();
      } else if(!enabled) {
        enable();
      }

      $(this).parent().toggleClass('elefunds_receiver_selected');
    });

    // When the plugin is (de)activated
    $('#elefunds_checkbox').on('change', function() {
      if($('#elefunds_checkbox').prop('checked') && $('#elefunds_bottom').find('input[type="checkbox"]:checked').length == 0) {
        $('#elefunds_bottom').find('input[type="checkbox"]').prop('checked', true);
        $('#elefunds_bottom').find('input[type="checkbox"]').parent().toggleClass('elefunds_receiver_selected');
      }

      if(!$('#elefunds_checkbox:checked').length) {
        roundSumContainer.slideUp();
        $('#elefunds').trigger('elefunds_disabled');
        enabled = false;

      } else {
        if ($('#elefunds_checkbox:checked').length) {
            roundSumContainer.slideDown();

            //Store data
            var centValue = parseInt($('#elefunds_donation_cent').val(), 10);
            $('#elefunds').data('elefunds-donation', {donationCent: centValue, donationFloat: convertToFloat(centValue)});

            $('#elefunds').trigger('elefunds_enabled');
            enabled = true;
        }
      }

      $('#elefunds_input').toggleClass('elefunds_input_active');
    });

    $('#elefunds_input').keyup(function(e){
      if(e.which == 13) e.preventDefault();
    });

    // Enable tooltips
    $('.tiptip').tipTip({
      defaultPosition: toolTipPosition || 'top',
      edgeOffset: -12,
      delay: 200
    });
  } //END INIT

  //Check if elefunds has already been created in the view
  (function() {
    if(parent.hasOwnProperty('options')) {
      init(parent.options);
    }
  })();

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
      centArray[1] += '0';
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
      floatValue = '0' + decimal + '0' + centValue;
    } else if(centValue < 100) {
      floatValue = '0' + decimal + centValue;
    } else {
      centValue = centValue.toString();
      floatValue = centValue.slice(0, -2) + decimal + centValue.slice(-2);
    }

    return floatValue;
  }

  // Update the donation input field
  function updateDonation(centValue) {
    var floatValue = convertToFloat(centValue);
    $('#elefunds_input').val(floatValue);

    render(centValue);

    if(enabled) {
      $('#elefunds').trigger('elefunds_donationChange');
    }
  }

  function render(centValue) {
    // Update the hidden donation input field
    // containing the value of the donation in cents
    $('#elefunds_donation_cent').val(centValue);

    // Store data
    $('#elefunds').data('elefunds-roundSum', convertToFloat(total + centValue));
    $('#elefunds').data('elefunds-donation', {donationCent: centValue, donationFloat: convertToFloat(centValue)});

    //Update round sum
    if(roundSum) {
      roundSum.html(convertToFloat(total + centValue));
    }
  }

  return {

    init: function(options) {
      init(options);
    },

    // Decrease the donation sum by 1.00
    decreaseDonation: function() {
      var centValue = parseInt($('#elefunds_donation_cent').val(), 10);

      if(centValue > 100) {
        centValue = centValue - 100;
        updateDonation(centValue);
      }

      return centValue;
    },

    // Increase the donation sum by 1.00
    increaseDonation: function() {
      var centValue = parseInt($('#elefunds_donation_cent').val(), 10);

      centValue = parseInt(centValue, 10) + 100;

      updateDonation(centValue);
      return centValue;
    },

    // Update the hidden field with the current donation value
    donationChange: function(floatValue) {
      var centValue = convertToCent(floatValue);
      render(centValue);

      var timeHandler = $('#elefunds').data('elefunds-timer');
      var now = (new Date()).getTime();
      var delay = 800;

      if (typeof(timeHandler) !== 'undefined' && timeHandler !== null) {
        if(now - timeHandler.time < delay) {
          clearTimeout(timeHandler.timer);
        }
      }

      if(enabled) {
        var timer = setTimeout(function() {
          $('#elefunds').trigger('elefunds_donationChange');
        }, delay);

        $('#elefunds').data('elefunds-timer', {timer: timer, time: (new Date()).getTime()});
      }

      return centValue;
    }

  };
}(elefunds || {}, jQuery));
