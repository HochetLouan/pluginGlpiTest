$(function() {
    addSelfRequesterIcon();

    // GLPI charge souvent les formulaires en AJAX (onglets), on ré-exécute au chargement d'un onglet
    $(".glpi_tabs").on("tabsload", function(event, ui) {
        addSelfRequesterIcon();
    });
});

var addSelfRequesterIcon = function() {
    // On vérifie si on a déjà ajouté notre bouton pour éviter les doublons
    if ($(".self-requester-added").length === 0) {
        
        // 1. Cibler la zone des demandeurs (Actors)
        // Note: Le sélecteur dépend de la version de GLPI, généralement autour du dropdown des demandeurs
        var requesterLabel = $('label[for^="dropdown_users_id_requester"]');
        
        if (requesterLabel.length > 0) {
            // 2. Créer l'icône (en utilisant Tabler Icons comme demandé)
            var myIcon = $('<a href="#" class="ti ti-user-plus self-requester-added" title="S\'ajouter comme demandeur"></a>');
            
            // Style rapide pour l'alignement
            myIcon.css({'margin-left': '5px', 'cursor': 'pointer'});

            // 3. Ajouter l'icône après le libellé
            requesterLabel.after(myIcon);

            // 4. Action au clic
            myIcon.on('click', function(e) {
                e.preventDefault();
                
                // Récupérer l'ID de l'utilisateur connecté (stocké par GLPI dans la config JS globale)
                var currentUserId = CFG_GLPI.user_id; 
                
                // Cibler le dropdown (Select2) des demandeurs et changer sa valeur
                var select = $('select[name^="users_id_requester"]');
                if (select.length > 0) {
                    select.val(currentUserId).trigger('change');
                }
            });
        }
    }
};