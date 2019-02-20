$(document).ready(function() {
    $('#search-by-name #submit').on("click", searchByName);
    $('#search-by-family-note #submit').on("click", searchByFamilyNote);

    /*----------------------------------------------
    requete ajax searchByname
    ------------------------------------------------- */

    function searchByName(e) {
        e.preventDefault();
        // variables
        var brand = $("#search-by-name #brand").val();
        var product = $("#search-by-name #product").val();
        // nettoyage des espaces blancs
        brand = brand.trim();
        product = product.trim();
        
        if (brand != '')
        {
            console.log(brand)
            removeRedBorderFrom("#search-by-name #brand");

            if (product != '')
            {
                removeRedBorderFrom("#search-by-name #product");

                $.ajax(
                    {
                        url: "././index.php/searchByName",
                        method: "POST",
                        dataType: "json",
                        data: {brand: brand, product: product}
                    }
                ).done(function(data) {
                    if (data.type == undefined)
                    {
                        // variables
                        var resultCount = data.length;
                        if (resultCount > 1)
                        {
                            var resultTitle = "<div><h2>" + resultCount + " résultats correspondent à votre recherche</h2></div>";
                        } else if (resultCount == 1)
                        {
                            var resultTitle = "<div><h2>" + resultCount + " résultat correspond à votre recherche</h2></div>";
                        } 

                        var accordion = "<div id =\"accordion\"></div>";
                        var product = "";
                        var genderIcon;

                        $( data ).each(function( index , value)
                        {
                            // définit l'icon à utiliser pour le produit
                            if (value.gender == "female")
                            {
                                genderIcon = "<i class=\"fas fa-venus\"></i>";
                            }
                            else if (value.gender == "male")
                            {
                                genderIcon = "<i class=\"fas fa-mars\"></i>";
                            }

                            // titre
                            product += "<h3>";
                                product += "<spam class=\"name\">" + value.name + "</spam>";
                                product += "<spam class=\"type\"> " + value.type + "</spam>";
                                product += "<spam class=\"gender\"> " + genderIcon + "</spam>";
                            product += "</h3>";
                                
                            // les notes
                            product += "<div>";
                                product += "<p>Family note: " + value.familyNotes + "</p>";
                                product += "<p>Top notes: " + value.topNotes + "</p>";
                                product += "<p>Heart notes: " + value.heartNotes + "</p>";
                                product += "<p>Base notes: " + value.baseNotes + "</p>";
                                product += "<p>Notes: " + value.notes + "</p>";
                            product += "</div>";
                        
                        });

                        //efface le result-container a chaque requête
                        $("#search-by-name #result-container").empty();
                        // ajoute resultTitle au result-container
                        $("#search-by-name #result-container").append( resultTitle);
                        // ajoute accordion au result-container
                        $("#search-by-name #result-container").append(accordion);
                        // ajoute le produit à acoordion
                        $("#accordion").append(product);

                        // function jquery-ui 
                        $( function() 
                        {
                            $( "#accordion" ).accordion();
                        } );
                    }
                    else if (data.type == 'not found')
                    {
                        // $("#search-by-name").after("<div id=\"result-container\"></div>");
                        $("#search-by-name #result-container").html(data.message);
                    }
                    else if (data.type == 'fail')
                    {
                        // $("#search-by-name").after("<div id=\"result-container\"></div>");
                        $("#search-by-name #result-container").html(data.message);
                    }


                }).fail(function(error) {

                });
            } 
            else
            {
                addRedBorderTo("#search-by-name #product");
            }
        }
        else 
        {
            if (product == '')
            {
                addRedBorderTo("#search-by-name #brand");
                addRedBorderTo("#search-by-name #product");
            }
            else 
            {
                addRedBorderTo("#search-by-name #brand");
                removeRedBorderFrom("#search-by-name #product");
            }
        }
    }



    /*----------------------------------------------
    requete ajax searchByFamilyNote
    ------------------------------------------------- */

    function searchByFamilyNote(e) {
        e.preventDefault();
        // variables
        var brand = $("#search-by-family-note #brand").val();
        var familyNote = $("#search-by-family-note #family-note").val();
        // nettoyage des espaces blancs
        brand = brand.trim();
        familyNote = familyNote.trim();
        
        if (brand != '')
        {
            console.log(brand)
            removeRedBorderFrom("#search-by-family-note #brand");

            if (familyNote != '')
            {
                removeRedBorderFrom("#search-by-family-note #family-note");

                $.ajax(
                    {
                        url: "././index.php/searchByFamilyNote",
                        method: "POST",
                        dataType: "json",
                        data: {brand: brand, familyNote: familyNote}
                    }
                ).done(function(data) {
                    if (data.type == undefined)
                    {
                        // variables
                        var resultCount = data.length;
                        if (resultCount > 1)
                        {
                            var resultTitle = "<div><h2>" + resultCount + " résultats correspondent à votre recherche</h2></div>";
                        } else if (resultCount == 1)
                        {
                            var resultTitle = "<div><h2>" + resultCount + " résultat correspond à votre recherche</h2></div>";
                        } 

                        var accordion = "<div id =\"accordion\"></div>";
                        var product = "";
                        var genderIcon;

                        $( data ).each(function( index , value)
                        {
                            // définit l'icon à utiliser pour le produit
                            if (value.gender == "female")
                            {
                                genderIcon = "<i class=\"fas fa-venus\"></i>";
                            }
                            else if (value.gender == "male")
                            {
                                genderIcon = "<i class=\"fas fa-mars\"></i>";
                            }

                            // titre
                            product += "<h3>";
                                product += "<spam class=\"name\">" + value.name + "</spam>";
                                product += "<spam class=\"type\"> " + value.type + "</spam>";
                                product += "<spam class=\"gender\"> " + genderIcon + "</spam>";
                            product += "</h3>";
                                
                            // les notes
                            product += "<div>";
                                product += "<p>Family note: " + value.familyNotes + "</p>";
                                product += "<p>Top notes: " + value.topNotes + "</p>";
                                product += "<p>Heart notes: " + value.heartNotes + "</p>";
                                product += "<p>Base notes: " + value.baseNotes + "</p>";
                                product += "<p>Notes: " + value.notes + "</p>";
                            product += "</div>";
                        
                        });

                        //efface le result-container a chaque requête
                        $("#search-by-family-note #result-container").empty();
                        // ajoute resultTitle au result-container
                        $("#search-by-family-note #result-container").append( resultTitle);
                        // ajoute accordion au result-container
                        $("#search-by-family-note #result-container").append(accordion);
                        // ajoute le produit à acoordion
                        $("#accordion").append(product);

                        // function jquery-ui 
                        $( function() 
                        {
                            $( "#accordion" ).accordion();
                        } );
                    }
                    else if (data.type == 'not found')
                    {
                        // $("#search-by-name").after("<div id=\"result-container\"></div>");
                        $("#search-by-family-note #result-container").html(data.message);
                    }
                    else if (data.type == 'fail')
                    {
                        // $("#search-by-name").after("<div id=\"result-container\"></div>");
                        $("#search-by-family-note #result-container").html(data.message);
                    }


                }).fail(function(error) {

                });
            } 
            else
            {
                addRedBorderTo("#search-by-family-note #family-note");
            }
        }
        else 
        {
            if (familyNote == '')
            {
                addRedBorderTo("#search-by-family-note #brand");
                addRedBorderTo("#search-by-family-note #family-note");
            }
            else 
            {
                addRedBorderTo("#search-by-family-note #brand");
                removeRedBorderFrom("#search-by-family-note #family-note");
            }
        }
    }



    

    /**
     * Supprime la class red-border pour l'élément spécifié
     * @param {String} selector 
     */
    function removeRedBorderFrom(selector)
    {
        if ($(selector).hasClass("red-border"))
        {
            $(selector).removeClass("red-border")
        }
    }

    /**
     * ajoute une bordure rouge à l'élément spécifié en paramètre
     * @param {String} selector 
     */
    function addRedBorderTo(selector)
    {
        $(selector).addClass("red-border");
    }
});