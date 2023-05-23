// Script that determines which settings fields to display to admins based on their discount configuration type

(function($) {
  $(document).ready(function() {

    // Determine which settings to show based on the value of the radio button select on the settings page
    function setSettingDisplay(value){
      if (value === 'category'){
        $('.standard_setting').parent('td').parent('tr').show();
        $('.category_setting').parent('td').parent('tr').show();
        $('.cart_setting').parent('td').parent('tr').hide();
      } else if (value === 'cart') {
        $('.standard_setting').parent('td').parent('tr').show();
        $('.category_setting').parent('td').parent('tr').hide();
        $('.cart_setting').parent('td').parent('tr').show();
      } else {
        $('.standard_setting').parent('td').parent('tr').hide();
        $('.category_setting').parent('td').parent('tr').hide();
        $('.cart_setting').parent('td').parent('tr').hide();
      }
    }

    // Check to see which radio button is selected on load and pass to setSettingDisplay function
    setSettingDisplay($("input[type=radio]:checked").val());

    // Listens for changes to the radio button and changes displayed settings as appropriate
    $('input[type=radio]').change(function() {
      if ($(this).is(':checked')) {
        console.log('Radio button selected: ' + $(this).val());
        setSettingDisplay($(this).val());
      }
    });
  });
})(jQuery);