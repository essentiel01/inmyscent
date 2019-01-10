$(document).ready(function() {

  /* ----------------
    autocompletion du champ brand
    ---------------------------*/
  var brands = [];
    // requete ajax
    $.get( "http://localhost/inmyscent/public/index.php/get-brands/all/", function( data ) {

    if (data.success == undefined) {
        $( data ).each(function( index , value) {
          brands.push(value.name);
        });
      }

      if (data.success == false) {
        $('#brands').focus(function(e) {
          var div = $(this).after('<div class="alert alert-danger" id="error-message"></div>');
          $(this).parent().children('div').text(data.message);
        });

        $('#brands').blur(function(e) {
          $(this).parent().children('#error-message').remove();
        });
      }
    });

    // autocompletion
    $( function() {
        $( "#brands" ).autocomplete({
          source: brands
        });
    });

    
});