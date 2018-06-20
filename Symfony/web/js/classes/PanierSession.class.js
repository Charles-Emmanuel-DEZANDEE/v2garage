'use strict';

// var EMPLACEMENT_STORAGE = "panier";

var PanierSession = function (id) {
    $("#ajouterProduit").on('click', this.onAddproduct.bind(this));
    $("#saveModifierProduit").on('click', this.saveModifLignePanier.bind(this));
    $("#allErase").on('click', this.onSupprimAllProduct.bind(this));

    this.$panier = {
        idVehicule: id,
        totalHT: 0,
        totalRemise: 0,
        totalTva: 0,
        totalTtc: 0,
        idCommande: null,
        tabElt: []
    };
    this.$emplacementStorage = "panier";

};


PanierSession.prototype.loadPanier = function () {
    var panierLoaded = loadDataFromDomStorage(this.$emplacementStorage);
    if (panierLoaded == null) {
        panierLoaded = this.$panier;
    }
    return panierLoaded;
};

PanierSession.prototype.savePanier = function (liste) {
    // initialisation des totaux
    liste.totalTva = 0;
    liste.totalRemise = 0;
    liste.totalTtc = 0;
    liste.totalHT = 0;

    //mise à jour des totaux
    for (var i = 0; i < liste.tabElt.length; i++) {
        var ligneTotalHT = liste.tabElt[i].valeurHT * liste.tabElt[i].quantity;
        var ligneRemiseRate = liste.tabElt[i].remiserate;
        var ligneTotalRemise = 0;
        if ( typeof ligneRemiseRate != "undefined") {
            ligneTotalRemise = ligneTotalHT * ligneRemiseRate / 100;
            ligneTotalHT = ligneTotalHT - ligneTotalRemise;
        }
        var ligneTotalTVA = ligneTotalHT * (liste.tabElt[i].taxerate / 100);
        var ligneTotalTTC = ligneTotalHT + ligneTotalTVA;


        liste.totalRemise += ligneTotalRemise;
        liste.totalTva = liste.totalTva + ligneTotalTVA;
        liste.totalTtc = liste.totalTtc + ligneTotalTTC;
        liste.totalHT = liste.totalHT + ligneTotalHT;
    }

    saveDataToDomStorage(this.$emplacementStorage, liste);
};


PanierSession.prototype.refreshPanier = function () {
    var command = this.loadPanier();
    // console.log(objet);
    var panier = command.tabElt;
    // console.log(panier);
    var eltUL = $("#tablePanier");
    //on vide la liste
    eltUL.html("");

    //pour chaque produit dans la liste
    for (var i = 0; i < panier.length; i++) {
        var remiserate = panier[i].remiserate;
        if ( typeof (remiserate) == 'undefined') {
            remiserate = 0;
        }
        var quantity = panier[i].quantity;
        var HT = panier[i].valeurHT;
        var totalHtligne = quantity * HT;

        if (remiserate > 0) {

            totalHtligne = totalHtligne - (totalHtligne * (remiserate / 100));
        }

        // on ajoute une ligne du tableau

        eltUL.append("<tr>" +
            "<td>" + panier[i].nomservice + " "+panier[i].reference+ "</td>" +
            "<td>" + panier[i].quantity + "</td>" +
            "<td> " + HT + "  </td>" +
            "<td class='hidden-xs'> " + remiserate + " % </td>" +
            "<td class='hidden-xs'> " + panier[i].taxerate + " % </td>" +
            "<td> " + totalHtligne + " € </td>" +
            "<td> <div class='btn btn-success'><span data-index='" + i + "' class='glyphicon glyphicon-pencil modifierLignePanier' ></span></div> </td>" +
            "<td> <div class='btn btn-danger'><span data-index='" + i + "' class='glyphicon glyphicon-remove-circle supprimerLignePanier' ></span></div> </td></tr>");

    }

    // mise à jour des totaux
    $('#remisepanier').html(command.totalRemise);
    $('#htpanier').html(command.totalHT);
    $('#tvapanier').html(command.totalTva);
    $('#ttcpanier').html(command.totalTtc);
// on met les ecouteur sur les lignes créées
    $(".supprimerLignePanier").on('click', this.onSupprimOneProduct.bind(this));
    $(".modifierLignePanier").on('click', this.onModifLignePanier.bind(this));
};

PanierSession.prototype.getOneproduct = function () {
    var idcategorie = $('#selectcategorie option:selected').val();
    var idservice = $('#selectservice option:selected').val();
    var nomservice = $('#selectservice option:selected').text();
    var reference = $('#reference').val();
    var valeurHT = parseFloat($('#valeurHT').val());
    var taxerate = parseFloat($('#tauxTVA').val());
    var remiserate = parseFloat($('#selectremise option:selected').val());
    if (typeof remiserate != 'number'){ remiserate = 0;}
    //console.log('remise :'+remiserate);
    var quantity = parseFloat($('#selectquantite option:selected').val());

    var objetElementpanier = {
        idcategorie: idcategorie,
        idservice: idservice,
        nomservice: nomservice,
        reference: reference,
        valeurHT: valeurHT,
        taxerate: taxerate,
        remiserate: remiserate,
        quantity: quantity
    };
    return objetElementpanier
}

PanierSession.prototype.onAddproduct = function () {

    //on cache le bouton modifier
    $('#saveModifierProduit').hide();
    
    // il faut récuper le panier
    var panier = this.loadPanier();
    //on ajoute un produit
    panier.tabElt.push(this.getOneproduct());
    // console.log(this.$panier);
    //on sauvegarde la liste dans le local storage
    this.savePanier(panier);
    //on refresh
    this.refreshPanier();

    //on vide les champs pour une nouvelle saisie
    $('#reference').val(null);
    $('#selectremise').val("0");
    $('#selectquantite').val("1");

};

PanierSession.prototype.saveModifLignePanier = function () {

    //on cache le bouton modifier
    $('#saveModifierProduit').hide();

    // on montre le bouton ajouter
    $('#ajouterProduit').show();

    $('#selectcategorie').prop('disabled', false);
    $('#selectservice').prop('disabled', false);


    //on récupére l'index
    var indexAModifier = $("#saveModifierProduit").data("index");

    // il faut récuper le panier
    var panier = this.loadPanier();


    //on récupère les données saisies
    var saisie = this.getOneproduct();
    //on modifie la ligne du panier
/*
    panier.tabElt[indexAModifier].idcategorie = saisie.idcategorie;
    panier.tabElt[indexAModifier].idservice = saisie.idservice;
    panier.tabElt[indexAModifier].nomservice = saisie.nomservice;
    */
    panier.tabElt[indexAModifier].reference = saisie.reference;
    panier.tabElt[indexAModifier].valeurHT = saisie.valeurHT;
    panier.tabElt[indexAModifier].taxerate = saisie.taxerate;
    panier.tabElt[indexAModifier].remiserate = saisie.remiserate;
    panier.tabElt[indexAModifier].quantity = saisie.quantity;

    //on sauvegarde le panier dans le local storage
    this.savePanier(panier);
    //on refresh
    this.refreshPanier();

    //on vide les champs pour une nouvelle saisie
    $('#reference').val(null);
    $('#selectremise').val("0");
    $('#selectquantite').val("1");

};

PanierSession.prototype.pushProduct = function (objetElementpanier) {


    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    //on ajoute un produit
    this.$panier.tabElt.push(objetElementpanier);
    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};

PanierSession.prototype.addIdCommand = function (id) {


    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    //on ajoute un produit
    this.$panier.idCommande = id;
    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);


};


PanierSession.prototype.onSupprimOneProduct = function (event) {

    // il faut récuper le panier
   var panier = this.loadPanier();
    var index = $(event.currentTarget).data('index');

    // //on supprime le produit
    panier.tabElt.splice(index, 1);


    //on sauvegarde la liste dans le local storage
    this.savePanier(panier);
    //on refresh
    this.refreshPanier();

};

PanierSession.prototype.onModifLignePanier = function (event) {

    // on cache le bouton ajouter
    $('#ajouterProduit').hide();
// il faut récuper la liste existante
    var panier = this.loadPanier();

    // on récuoére l'index
    var index = $(event.currentTarget).data('index');
console.log(index);
    // //on récupére la ligne
    var ligne = panier.tabElt[index];

    // on rend visible le bouton de modification
    $('#saveModifierProduit').show();

    // on affecte l'index au bouton de validation
    $('#saveModifierProduit').attr('data-index', index);

    // on met à jour le formulaire
    console.log("cat : "+ligne.idcategorie);
    console.log("remise : "+ligne.remiserate);

/*
    $('#selectcategorie').val(ligne.idcategorie);
    $('#selectservice').val(ligne.idservice);
*/
    $('#selectcategorie').prop('disabled', true);
    $('#selectservice').prop('disabled', true);

    $('#reference').val(ligne.reference);
    $('#valeurHT').val(ligne.valeurHT);
    $('#tauxTVA').val(ligne.taxerate);
    if ( isNaN(ligne.remiserate)) {ligne.remiserate = 0;}
    $('#selectremise').val(ligne.remiserate);
    $('#selectquantite').val(ligne.quantity);

 };


PanierSession.prototype.onSupprimAllProduct = function (event) {
    //on annuel l'action submit par défaut
    event.preventDefault();
    // il faut récuper la liste existante
    this.$panier.tabElt = [];

    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};


PanierSession.prototype.clear = function () {
    // Destruction du panier dans le DOM storage.
    saveDataToDomStorage(this.$emplacementStorage, null);
};

PanierSession.prototype.clearTotal = function () {
    // Destruction du panier dans le DOM storage.
    this.$panier.totalTva = 0;
    this.$panier.totalRemise = 0;
    this.$panier.totalTtc = 0;
    this.$panier.totalHT = 0;
    saveDataToDomStorage(this.$emplacementStorage, this.$panier);
};
