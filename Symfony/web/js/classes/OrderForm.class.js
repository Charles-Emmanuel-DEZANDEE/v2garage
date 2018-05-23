var OrderForm = function() {

    this.panier = new Panier();

};

// Méthode qui permet de faire l'initialisation (mettre en place les écouteurs sur éléments d'interaction)
OrderForm.prototype.run = function() {

	// Ecoute des boutons ajouter
    $('.ajouterProduit').on('click', this.ajouterProduit.bind(this)); // avec le bind, this gardera la signification "instance" dans la méthode ajouterProduit
    $("#panier").on('click',"#allErase", this.onSupprimAllProduct.bind(this));
    $("#panier").on('click',".supprimerProduit", this.onSupprimProduct.bind(this));
    $("#panier").on('click',"#valideCommande", this.onValideCommande.bind(this));
    this.refreshPanier();
};

OrderForm.prototype.onValideCommande = function() {
    //on récupére le tableau
    var tableau = this.panier.contenu;
    //adresse du controlleur
    var url = getRequestUrl() + "/user/panier";
    //on récupére l'id de l'adresse
    var idAdresse = $("#orderAdresse option:selected").val();
    // console.log(idAdresse);
    //on récupré si on est en livraison
    var isLivraison = $("#order_status_delivery option:selected").val();

    //on récupré la date de livraison
    var order_datetime_delivery = $("#datetimepicker").val();
    // console.log($("#datetimepicker"));
    // console.log(order_datetime_delivery);

    //on envois le tableau
    $.post(url, { panier: tableau, idAdresse: idAdresse,order_status_delivery: isLivraison, order_datetime_delivery: order_datetime_delivery} , this.redirigerCommande);
    //on vide la commande
    this.panier.contenu = [];
    // Sauver le panier dans le localStorage
    this.panier.savePanier(this.panier.contenu);
    // MAJ l'affichage de l'encart panier
    //on refresh
    this.refreshPanier();

};

OrderForm.prototype.redirigerCommande = function(idCommande) {
	//appel d'une page avec envois en get de l'id commande
    console.log("idcommande  :"+idCommande);
    var url = getRequestUrl() + "/user/paiement?idCommande="+idCommande;
    console.log(url);
    window.location.replace(url);
};





OrderForm.prototype.ajouterProduit = function(event) {
	// console.log(this);
    // console.log(event.currentTarget); // lorsque le this garde la signification "instance", il nous reste toujours cette propriété de l'évènement pour viser l'élément du DOM qui vient de réagir
    var idProduit = $(event.currentTarget).data("id"); // this.dataset.index
    var name = $(event.currentTarget).data("name");
    var temp = $(event.currentTarget).parents('td').siblings('td');
    var quantity = $(temp[4]).children('select').val();

    // Ajouter le produit au panier (appel à la méthode ajouter de this.panier)
    this.panier.ajouter(idProduit, name, quantity);
    // MAJ l'affichage de l'encart panier
    this.refreshPanier();
};

OrderForm.prototype.onSupprimAllProduct = function(event)
{
    // console.log("erase");
    //on annuel l'action submit par défaut
    event.preventDefault();
    // il faut récuper la liste existante et mettre un tableau vide
    this.panier.contenu = [];

    // Sauver le panier dans le localStorage
    this.panier.savePanier(this.panier.contenu);
    // MAJ l'affichage de l'encart panier
    //on refresh
    this.refreshPanier();
};

OrderForm.prototype.onSupprimProduct = function(event)
{
    console.log("erase one");
    //on annuel l'action submit par défaut
    event.preventDefault();
    //on récupére l'index de la ligne à supprimer
    var index = $(event.currentTarget).data("index");
    // console.log(index);
    // il faut récuper la liste existante
    var tab = this.panier.contenu;
    // console.log(tab);
    //on supprime la ligne
    tab.splice(index,1);
    // console.log(tab);

    // Sauver le panier dans le localStorage
    this.panier.savePanier(tab);
    // MAJ l'affichage de l'encart panier
    //on refresh
    this.refreshPanier();
};

OrderForm.prototype.refreshPanier = function()
{
    var panier = this.panier.loadPanier();
    // console.log(panier);
    var eltUL = $("#panier_ul");
    //on vide la liste
    eltUL.html("");
    //pour chaque produit dans la liste
        for (var i = 0; i < panier.length; i++) {
    // Ajouter un LI contenant prenom et nom
        eltUL.append("<li data-index='"+ i +"'> "+ panier[i].quantity+"  X "+panier[i].name + "<button type='button' name='supprimerProduit' class='supprimerProduit' data-index='"+ i +"' data-id='"+panier[i].id+"'>Sup</button></li>");
        }
        if (panier.length != 0) {
            eltUL.append('<button type="button" name="allErase" id="allErase" >Tout supprimer</button>');
            eltUL.append('<button type="button" name="valideCommande" id="valideCommande" >Valider la commande</button>');
        }
};
