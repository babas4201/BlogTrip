
/* Fonction de confirmation de suppression de commentaire ou article (utilisation JQuery) */
$(document).ready(function() {
    $('#deleteComment').on("click", function(e) {
        if(!confirm("Voulez vous vraiment supprimer le commentaire?")) {
            e.preventDefault();
        }
    });
    $('#deleteArticle').on("click", function(e) {
        if(!confirm("Voulez vous vraiment supprimer cet article ?")) {
            e.preventDefault();
        }
    });
})