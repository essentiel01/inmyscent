$(document).ready(function() {
  /* ----------------
    autocompletion du champ brand
    ---------------------------*/
  var brandsName = [];
  var brands = [];
    // requete ajax
    $.get( "././index.php/brands", function( data ) {
      if (data.success == undefined) {
        $( data ).each(function( index , value) {
          brands.push(value);
          brandsName.push(value.name);
        });
      }

      if (data.success == false) {
        $(this).focus(function(e) {
          var div = $(this).after('<div class="alert alert-danger" id="brand-error-message"></div>');
          $(this).parent().children('div').text(data.message);
        });

        $('#brand').blur(function(e) {
          $(this).parent().children('#brand-error-message').remove();
        });
      }
    });


    

    // autocompletion
    $( function() {
        $( "#brand" ).autocomplete({
          source: brandsName
        });
    }); 


    /* ----------------
    autocompletion du champ product
    ---------------------------*/
    var productsName = [];

    $("#brand").change(function(){
      
        var brandName = $("#brand").val();
        brandName = brandName.trim();

        if (brandName) {
          $.ajax(
              {
                  url: "././index.php/products",
                  method: "POST",
                  dataType: "json",
                  data: { brandName: brandName }
              }
          ).done(function(data) {

            if (data.success == undefined) {
              // vide les variables products et productsName
              productsName.splice(0, productsName.length);
  
              // assigne les nouvelles valeurs à products et productsName
              $( data ).each(function( index , value) {
                productsName.push(value.name);
              });

            } else if (data.success == false && data.type == 'fail') {

                var div = $('#product').after('<div class="alert alert-warning" id="product-error-message"></div>');
                $('#product').parent().children('div').text(data.message);
  
                $('#product').focus(function(e) {
                var div = $(this).after('<div class="alert alert-warning" id="product-error-message"></div>');
                $(this).parent().children('div').text(data.message);
                });
      
                $('#product').blur(function(e) {
                  $(this).parent().children('#product-error-message').remove();
                });

            } else if (data.success == false && data.type == 'not found') {
              // vide les variables products et productsName
              productsName.splice(0, productsName.length);
            }

          }).fail(function(error) {
              console.log(error);
          });
          
      } else {
        // vide les variables products et productsName
        productsName.splice(0, productsName.length);
      }
      
       
        // autocompletion
        $( function() {
          $( "#product" ).autocomplete({
            source: productsName
          });
        });

    });
    
    

    

    
    
});