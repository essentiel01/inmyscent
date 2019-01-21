$(document).ready(function() {

  /* ----------------
    autocompletion du champ brand
    ---------------------------*/
  var brandsName = [];
  var brands = [];
    // requete ajax
    $.get( "http://localhost/inmyscent/public/index.php/brands", function( data ) {
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
    var brandSlug, brandId;
    var products = [];
    
    $("#brand").change(function(){
      
      if($("#brand").val() != null || $("#brand").val() != " ") {
        for (var b of brands) {
          if (b.name == $("#brand").val()){
            brandSlug = b.slug;
            brandId = b.id;
            
            // requete ajax
            $.get( "http://localhost/inmyscent/public/index.php/products/"+brandId+"/"+brandSlug, function( data ) {
              
              if (data.success == undefined) {
                // vide la variable products
                products.splice(0, products.length);
                // assigne les nouvelles valeurs à products
                $( data ).each(function( index , value) {
                  products.push(value.name);
                });
              } else if (data.success == false) {
                  var div = $('#product').after('<div class="alert alert-warning" id="product-error-message"></div>');
                  $('#product').parent().children('div').text(data.message);

                  $('#product').focus(function(e) {
                  var div = $(this).after('<div class="alert alert-warning" id="product-error-message"></div>');
                  $(this).parent().children('div').text(data.message);
                  });
        
                  $('#product').blur(function(e) {
                    $(this).parent().children('#product-error-message').remove();
                  });
              }
            });
            // autocompletion
            $( function() {
              $( "#product" ).autocomplete({
                source: products
              });
            });
          } 
        }
      }
    });
    
    

    

    
    
});