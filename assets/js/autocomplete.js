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
   
           $("#brand").blur(function(e) {
             $(this).parent().children('#brand-error-message').remove();
           });
         }
       });

       // autocompletion du champ brand du formualaire search by name
       $( function() {
         $( "#search-by-name #brand" ).autocomplete({
           source: brandsName
         });
       }); 

       // autocompletion du champ brand du formualaire search by family note
       $( function() {
         $( "#search-by-family-note #brand" ).autocomplete({
           source: brandsName
         });
       }); 
  
    /* ----------------
    autocompletion du champ product
    ---------------------------*/
    var productsName = [];

    $("#search-by-name #brand").change(function(){
      
        var brandName = $("#search-by-name #brand").val();
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
                if (productsName.includes(value.name) === false)
                {
                  productsName.push(value.name);
                }
              });

            } else if (data.success == false && data.type == 'fail') {

                var div = $('#search-by-name #product').after('<div class="alert alert-warning" id="product-error-message"></div>');
                $('#search-by-name #product').parent().children('div').text(data.message);
  
                $('#search-by-name #product').focus(function(e) {
                var div = $(this).after('<div class="alert alert-warning" id="product-error-message"></div>');
                $(this).parent().children('div').text(data.message);
                });
      
                $('#search-by-name #product').blur(function(e) {
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
          $( "#search-by-name #product" ).autocomplete({
            source: productsName
          });
        });

    });
      
      
  
    /* ----------------
    autocompletion du champ family note
    ---------------------------*/

    var familyNotes = [];

    $("#search-by-family-note #brand").change(function(){
      
        var brandName = $("#search-by-family-note #brand").val();
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
              console.log(data)
              // vide la variable familyNote
              familyNotes.splice(0, familyNotes.length);
  
              // assigne les nouvelles valeurs à la variable familyNote
              $( data ).each(function( index , value) {
                if (familyNotes.includes(value.familyNotes) === false)
                {
                  familyNotes.push(value.familyNotes);
                }
              });

            } else if (data.success == false && data.type == 'fail') {

                var div = $('#search-by-family-note #family-note').after('<div class="alert alert-warning" id="product-error-message"></div>');
                $('#search-by-family-note #family-note').parent().children('div').text(data.message);
  
                $('#search-by-family-note #family-note').focus(function(e) {
                var div = $(this).after('<div class="alert alert-warning" id="product-error-message"></div>');
                $(this).parent().children('div').text(data.message);
                });
      
                $('#search-by-family-note #family-note').blur(function(e) {
                  $(this).parent().children('#product-error-message').remove();
                });

            } else if (data.success == false && data.type == 'not found') {
              // vide la variable familyNote
              familyNotes.splice(0, familyNotes.length);
            }

          }).fail(function(error) {
              console.log(error);
          });
          
      } else {
        // vide la variable familyNote
        familyNotes.splice(0, familyNotes.length);
      }
        
      // autocompletion de l'input family-note
      $( function() {
        $( "#search-by-family-note #family-note" ).autocomplete({
          source: familyNotes
        });
      });
    });
  
      
      
  });