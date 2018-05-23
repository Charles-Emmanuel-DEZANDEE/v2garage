var Panier = function() {
	this.storageKey = "Panier"; // nom de l'emplacement du localStorage dans lequel on stocke notre panier
	this.contenu = null;
	this.load();
    // console.log(this.contenu);
};

Panier.prototype.loadPanier = function()
{
    var panierLoaded = loadDataFromDomStorage(this.storageKey);
    if (panierLoaded == null) {
        panierLoaded = [];
    }
    return panierLoaded;
};
Panier.prototype.savePanier = function(liste)
{
    saveDataToDomStorage(this.storageKey,liste);
};

/*
	Charge dans la propriété contenu les informations stockées dans le localStorage
*/
Panier.prototype.load = function() {
	this.contenu = loadDataFromDomStorage( this.storageKey );
    // si on n'a aucun contenu dans le panier on veut au moins que la propriété soit un tableau vide
    if ( this.contenu == null ) {
    	this.contenu = [];
    }
}

/*
	Ajoute au panier le produit dont on passe l'id en paramètre
*/
Panier.prototype.ajouter = function(idProduit, name, quantity)
{
	// ETAPE 1 : Déterminer si oui ou non l'objet donc on a passé l'id est présent dans le panier
    //on lit le contenu
    // var panier = this.contenu;
    // console.log(this.contenu);
        // (Parcourir les contenus pour voir si l'un d'entre eux a une propriété id égale à l'idProduit passé en paramètre)
        for (var i = 0; i < this.contenu.length; i++) {
            // console.log(this.contenu[i].id);
    	// Oui : incrémenter la propriété quantite
            if (this.contenu[i].id == idProduit) {
                // console.log("existe");
                this.contenu[i].quantity = parseInt(this.contenu[i].quantity) + parseInt(quantity);
				this.savePanier(this.contenu);
				return;
            }
        }
            // Non : Ajouter un élément dans le tableau contenu (id : idProduit passé en paramètre, quantite : 1)
                // console.log("id n'existe pas");
                var ligneOrder = {
                    id: idProduit,
					name : name,
                    quantity : quantity
                };
                this.contenu.push(ligneOrder);

    // }
    // console.log(this.contenu);

    // Sauver le panier dans le localStorage
    this.savePanier(this.contenu);
}
