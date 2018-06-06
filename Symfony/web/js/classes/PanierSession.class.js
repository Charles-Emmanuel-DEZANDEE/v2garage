
'use strict';

// var EMPLACEMENT_STORAGE = "panier";

var PanierSession = function(id)
{
    $("#ajouterProduit").on('click', this.onAddproduct.bind(this));
    $("#allErase").on('click', this.onSupprimAllProduct.bind(this));

    this.$panier = {
        idVehicule : id,
        totalHT : 0,
        totalRemise: 0,
        totalTva: 0,
        totalTtc:0,
        idCommande : null,
        tabElt : []
    };
    this.$emplacementStorage  = "panier";

};



PanierSession.prototype.loadPanier = function()
{
    var panierLoaded = loadDataFromDomStorage(this.$emplacementStorage);
    if (panierLoaded == null) {
        panierLoaded = this.$panier;
    }
    return panierLoaded;
};

PanierSession.prototype.savePanier = function(liste)
{
    // initialisation des totaux
    liste.totalTva = 0;
    liste.totalRemise = 0;
    liste.totalTtc = 0;
    liste.totalHT = 0;

    //mise à jour des totaux
    for (var i= 0;i < liste.tabElt.length; i++) {
        var ligneTotalHT = liste.tabElt[i].valeurHT * liste.tabElt[i].quantity;
        var ligneRemise = liste.tabElt[i].remise;
        if (ligneRemise != 0){
            var ligneTotalRemise = ligneTotalHT * ligneRemise /100;
            ligneTotalHT = ligneTotalHT - ligneTotalRemise;
        }
        var ligneTotalTVA = ligneTotalHT * (liste.tabElt[i].taxerate /100);
        var ligneTotalTTC = ligneTotalHT + ligneTotalTVA;


        liste.totalRemise += ligneTotalRemise;
        liste.totalTva = liste.totalTva + ligneTotalTVA;
        liste.totalTtc = liste.totalTtc + ligneTotalTTC;
        liste.totalHT = liste.totalHT + ligneTotalHT;
    }

    saveDataToDomStorage(this.$emplacementStorage,liste);
};



PanierSession.prototype.refreshPanier = function()
{
    var objet = this.loadPanier();
    // console.log(objet);
    var panier = objet.tabElt;
    // console.log(panier);
    var eltUL = $("#panier ul");
    //on vide la liste
    eltUL.html("");
    var totalPanier = 0;
    //pour chaque produit dans la liste
        for (var i = 0; i < panier.length; i++) {
            var remise = panier[i].remise;
            var valeurligne = panier[i].quantity * panier[i].valeurHT;
            if (remise > 0){

                valeurligne = valeurligne - (valeurligne*(remise /100));
            }

            totalPanier += valeurligne;
    // Ajouter un LI contenant prenom et nom
        eltUL.append("<li class='list-group-item' ><strong>Nom</strong>: "+ panier[i].nomservice + "     - <strong>référence:</strong>   " + panier[i].reference + "      - <strong>Quantité</strong>:  " + panier[i].quantity + "      - <strong>Total HT (remise inclue)</strong>:  " + valeurligne  +" €<span data-index='"+ i +"' class='glyphicon glyphicon-remove-circle pull-right supprimerProduit' ></span></li>");
        }
    eltUL.append("<button class='btn btn-success'> Total HT "+ totalPanier +" €</button>");
// on met les ecouteur sur les lignes créées
    $(".supprimerProduit").on('click', this.onSupprimProduct.bind(this));
};

PanierSession.prototype.onAddproduct = function()
{


    var idservice = $('#selectservice option:selected').val();
    var nomservice = $('#selectservice option:selected').text();
    var reference = $('#reference').val();
    var valeurHT = parseFloat($('#valeurHT').val());
    var taxerate = parseFloat($('#tauxTVA').val());
    var remise = parseFloat($('#selectremise option:selected').val());
    var quantity = parseFloat($('#selectquantite option:selected').val());

    var objetElementpanier = {
        idservice : idservice,
        nomservice : nomservice,
        reference : reference,
        valeurHT : valeurHT,
        taxerate : taxerate,
        remise : remise,
        quantity : quantity
    };
    // console.log(objetElementpanier);
    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    //on ajoute un produit
    this.$panier.tabElt.push(objetElementpanier);
    // console.log(this.$panier);
    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

    //on vide les champs pour une nouvelle saisie
    $('#reference').val(null);
    $('#selectremise').val("0");
    $('#selectquantite').val("1");

};

PanierSession.prototype.pushProduct = function(objetElementpanier)
{


    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    //on ajoute un produit
    this.$panier.tabElt.push(objetElementpanier);
    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};

PanierSession.prototype.addIdCommand = function(id)
{


    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    //on ajoute un produit
    this.$panier.idCommande = id;
    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);


};




PanierSession.prototype.onSupprimProduct = function(event)
{

    // event.preventDefault();
    // console.log("suppression");
    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    var index = $(event.currentTarget).data('index');
    // console.log(index);

    // //on supprime le produit
    this.$panier.tabElt.splice(index,1);
    // console.log(this.$panier);

    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};
PanierSession.prototype.onSupprimAllProduct = function(event)
{
    //on annuel l'action submit par défaut
    event.preventDefault();
    // il faut récuper la liste existante
    this.$panier.tabElt = [];

    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};


PanierSession.prototype.clear = function()
{
    // Destruction du panier dans le DOM storage.
    saveDataToDomStorage(this.$emplacementStorage, null);
};

PanierSession.prototype.clearTotal = function()
{
    // Destruction du panier dans le DOM storage.
    this.$panier.totalTva = 0;
    this.$panier.totalRemise = 0;
    this.$panier.totalTtc = 0;
    this.$panier.totalHT = 0;
    saveDataToDomStorage(this.$emplacementStorage, this.$panier);
};
