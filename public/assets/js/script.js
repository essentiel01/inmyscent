$(document).ready(function() {
    $('#search-by-name #submit').on("click", searchByName);

    function searchByName(e) {
        e.preventDefault();
        var brand = $("#brand").val();
        var product = $("#product").val();
        brand = brand.trim();
        product = product.trim();
        
        if (brand != undefined && product != undefined) 
        {
            $.ajax(
                {
                    url: "././index.php/search",
                    method: "POST",
                    dataType: "json",
                    data: {brand: brand, product: product}
                }
            ).done(function(data) {
                console.log(data)
            }).fail(function(error) {

            });
        }
    }

    

});