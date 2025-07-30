// Fonctions simples pour la navigation
function showProductItems(){
    window.location.href = './adminView/viewAllProducts.php';
}

function showCategory(){
    window.location.href = './adminView/viewCategories.php';
}

function showCustomers(){
    window.location.href = './adminView/viewCustomers.php';
}

function showOrders(){
    window.location.href = './adminView/viewAllOrders.php';
}

function showFactures(){
    console.log('Fonction showFactures appelée');
    window.location.href = './adminView/viewFactures.php';
}

// Fonction simple pour changer le statut de commande
function ChangeOrderStatus(id){
    if(confirm('Changer le statut de la commande ?')) {
        window.location.href = './controller/updateOrderStatus.php?record=' + id;
    }
}

// Fonction simple pour changer le statut de paiement
function ChangePay(id){
    if(confirm('Changer le statut de paiement ?')) {
        window.location.href = './controller/updatePayStatus.php?record=' + id;
    }
}

// Fonction simple pour supprimer un produit
function itemDelete(id){
    if(confirm('Voulez-vous vraiment supprimer ce produit ?')) {
        window.location.href = './controller/deleteItemController.php?record=' + id;
    }
}

// Fonction simple pour supprimer une catégorie
function categoryDelete(id){
    if(confirm('Voulez-vous vraiment supprimer cette catégorie ?')) {
        window.location.href = './controller/catDeleteController.php?record=' + id;
    }
}

// Fonction simple pour éditer un produit
function itemEditForm(id){
    window.location.href = './adminView/editItemForm.php?record=' + id;
}

// Fonction simple pour la recherche
function search(id){
    window.location.href = './controller/searchController.php?record=' + id;
}

// Fonction simple pour le panier
function cartDelete(id){
    if(confirm('Supprimer du panier ?')) {
        window.location.href = './controller/deleteCartController.php?record=' + id;
    }
}

// Fonction simple pour les détails
function eachDetailsForm(id){
    window.location.href = './view/viewEachDetails.php?record=' + id;
}

// Fonction simple pour le checkout
function checkout(){
    window.location.href = './view/viewCheckout.php';
}

// Fonction simple pour la wishlist
function removeFromWish(id){
    if(confirm('Retirer de la wishlist ?')) {
        window.location.href = './controller/removeFromWishlist.php?record=' + id;
    }
}

function addToWish(id){
    window.location.href = './controller/addToWishlist.php?record=' + id;
}

// Fonction simple pour les quantités
function quantityPlus(id){
    window.location.href = './controller/addQuantityController.php?record=' + id;
}

function quantityMinus(id){
    window.location.href = './controller/subQuantityController.php?record=' + id;
}