
'use strict';

// var EMPLACEMENT_STORAGE = "panier";

var PanierSession = function()
{
    $(".ajouterProduit").on('click', this.onAddproduct.bind(this));
    $("#panier").on('click',"#supprimerProduit", this.onSupprimProduct.bind(this));
    $("#panier").on('click',"#allErase", this.onSupprimAllProduct.bind(this));

    this.$panier              = [];
    this.$emplacementStorage  = "panier";
    // this.run();

};

PanierSession.prototype.loadPanier = function()
{
    var panierLoaded = loadDataFromDomStorage(this.$emplacementStorage);
    if (panierLoaded == null) {
        panierLoaded = [];
    }
    return panierLoaded;
};
PanierSession.prototype.savePanier = function(liste)
{
    saveDataToDomStorage(this.$emplacementStorage,liste);
};



PanierSession.prototype.refreshPanier = function()
{
    var panier = this.loadPanier();
    console.log(panier);
    var eltUL = $("#panier ul");
    //on vide la liste
    eltUL.html("");
    //pour chaque produit dans la liste
        for (var i = 0; i < panier.length; i++) {
    // Ajouter un LI contenant prenom et nom
        eltUL.append("<li data-index='"+ i +"'><input type='checkbox' name='checkproduct' data-check='"+i+"' data-id='"+panier[i].id+"'/>nbre: "+ panier[i].quantity+"   - nbre:"+panier[i].name + "</li>");
        }
        if (panier.length != 0) {
            eltUL.append('<button type="button" name="supprimerProduit" id="supprimerProduit" >Supprimer</button>');
            eltUL.append('<button type="button" name="allErase" id="allErase" >Tout supprimer</button>');
        }
};

PanierSession.prototype.onAddproduct = function(event)
{

    event.preventDefault();
    var id = $(event.currentTarget).data("id");
    var name = $(event.currentTarget).data("name");
    var temp = $(event.currentTarget).parents('td').siblings('td');
    var quantity = $(temp[4]).children('select').val();
    var objetElementpanier = {
        id : id,
        name : name,
        quantity : quantity
    };
    console.log(objetElementpanier);
    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    //on ajoute un produit
    this.$panier.push(objetElementpanier);
    console.log(this.$panier);
    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};
PanierSession.prototype.onSupprimProduct = function(event)
{

    event.preventDefault();
    console.log("test");
    // il faut récuper la liste existante
    this.$panier = this.loadPanier();
    console.log(this.$panier);

    // //on ajoute un produit
    // this.$panier.push(objetElementpanier);
    // console.log(this.$panier);
    // //on sauvegarde la liste dans le local storage
    // this.savePanier(this.$panier);
    // //on refresh
    // this.refreshPanier();

};
PanierSession.prototype.onSupprimAllProduct = function(event)
{
    //on annuel l'action submit par défaut
    event.preventDefault();
    // il faut récuper la liste existante
    this.$panier = [];

    //on sauvegarde la liste dans le local storage
    this.savePanier(this.$panier);
    //on refresh
    this.refreshPanier();

};

// PanierSession.prototype.run = function()
// {
//     // Installation d'un gestionnaire d'évènement spour l'ajout d'éléments.
//     // this.$panier.on('submit', this.onSubmitpanier.bind(this));
//     $(".ajouterProduit").on('click', this.onSubmitpanier.bind(this));
// };
