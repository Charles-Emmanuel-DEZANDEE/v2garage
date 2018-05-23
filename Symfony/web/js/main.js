'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
function runFormValidation()
{
	// Vérifier si un form est présent dans la page
    var form = $('form[data-validation="true"]');
    if (form.length == 1)
    {
    	// Si oui créer un objet FormValidator associé
    	var formValidator = new FormValidator(form);
        console.log(formValidator);
    	// et mettre en place l'écouteur au submit (appeler la méthode run)
    	formValidator.run();
    }
}
function runPanier()
{
    var panier = new PanierSession();
    panier.refreshPanier();
}
function runDatepiscker()
{
    var datePikcker = new datePickerJquery();
}

function runCommande() {
	var orderForm = new OrderForm();
    orderForm.run();
}



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////

$( function() {
    console.log("Le DOM est connu, on peut mettre le code principal ici");


    runDatepiscker();
    //utilisation de class validator
	runFormValidation();

    //on lance le panier
    // runPanier();
    // initialisations...
    runCommande();



});
