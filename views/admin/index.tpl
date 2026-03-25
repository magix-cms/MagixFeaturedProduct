{extends file="layout.tpl"}

{block name='head:title'}Configuration Produits Phares{/block}

{block name='article'}
    <div class="row">
        {* COLONNE DE GAUCHE : LA RECHERCHE *}
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-search"></i> Ajouter un produit</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label text-muted small">Rechercher (Nom ou Référence)</label>
                        <input type="text" id="ajaxSearchInput" class="form-control" placeholder="Taper au moins 2 caractères..." autocomplete="off">

                        {* Container des résultats AJAX *}
                        <div id="ajaxSearchResults" class="list-group position-absolute w-100 shadow mt-1" style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {* COLONNE DE DROITE : LES SÉLECTIONNÉS *}
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-star-fill text-warning"></i> Produits en page d'accueil</h5>
                    <span class="badge bg-primary" id="countSelected">{$selected_products|count}</span>
                </div>
                <div class="card-body">
                    {* 🟢 CORRECTION 1 : Retrait de "validate_form" et mise à jour de l'action *}
                    <form method="post" action="index.php?controller=MagixFeaturedProduct">
                        <input type="hidden" name="hashtoken" value="{$hashtoken}">

                        <div class="alert alert-info py-2 small">
                            <i class="bi bi-info-circle me-1"></i> Utilisez les flèches pour modifier l'ordre d'affichage.
                        </div>

                        <ul class="list-group mb-4" id="selectedProductsList">
                            {foreach $selected_products as $p}
                                {* 🟢 CORRECTION 2 : Retrait de l'attribut draggable="true" (Sortable.js s'en occupe) *}
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light border-bottom" data-id="{$p.id_product}">
                                    <input type="hidden" name="featured_products[]" value="{$p.id_product}">
                                    <div class="d-flex align-items-center w-100">
                                        <i class="bi bi-grip-vertical text-muted me-3 fs-5 cursor-move"></i>
                                        <div>
                                            <strong class="d-block text-dark">{$p.name_p}</strong>
                                            <small class="text-muted">Réf: {$p.reference_p|default:'N/A'} - {$p.name_cat}</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove ms-2" title="Retirer"><i class="bi bi-x-lg"></i></button>
                                </li>
                            {/foreach}
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}

{block name="javascripts" append}
    {* 💡 Petite note : Vérifiez que vous avez bien minifié votre fichier en .min.js, sinon utilisez juste MagixItemSelector.js *}
    <script src="templates/js/MagixItemSelector.min.js?v={$smarty.now}"></script>
    <script>
        {literal}
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('input[name="hashtoken"]').value;

            new MagixItemSelector({
                searchInputId: 'ajaxSearchInput',
                searchResultsId: 'ajaxSearchResults',
                selectedListId: 'selectedProductsList',
                countBadgeId: 'countSelected',

                // 🟢 CORRECTION 3 : Mise à jour des URLs vers le nouveau nom du contrôleur
                searchUrl: 'index.php?controller=MagixFeaturedProduct&action=search&q=',
                saveUrl: 'index.php?controller=MagixFeaturedProduct',

                inputName: 'featured_products[]',
                token: token,

                renderResultItem: (item) => `
                    <strong>${item.name_p}</strong><br>
                    <small class="text-muted">Réf: ${item.reference_p || 'N/A'}</small>
                `,

                renderAddedItem: (item) => `
                    <div class="d-flex align-items-center w-100">
                        <i class="bi bi-grip-vertical text-muted me-3 fs-5 cursor-move"></i>
                        <div>
                            <strong class="d-block text-dark">${item.name_p}</strong>
                            <small class="text-muted">Réf: ${item.reference_p || 'N/A'} - ${item.name_cat}</small>
                        </div>
                    </div>
                `
            });
        });
        {/literal}
    </script>
{/block}