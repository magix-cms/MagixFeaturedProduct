<?php
declare(strict_types=1);

namespace Plugins\MagixFeaturedProduct\src;

use App\Backend\Controller\BaseController;
use Plugins\MagixFeaturedProduct\db\FeaturedAdminDb;
use Magepattern\Component\HTTP\Request;
// CacheManager si vous l'utilisez
use App\Component\Cache\CacheManager;

class BackendController extends BaseController
{
    public function run(): void
    {
        $db = new FeaturedAdminDb();
        $idLang = (int)($this->defaultLang['id_lang'] ?? 1);

        // RECHERCHE AJAX
        if (isset($_GET['action']) && $_GET['action'] === 'search') {
            $term = $_GET['q'] ?? '';
            if (strlen($term) > 1) {
                echo json_encode($db->searchActiveProducts($term, $idLang));
            } else {
                echo json_encode([]);
            }
            exit;
        }

        // SAUVEGARDE
        if (Request::isMethod('POST')) {
            $token = $_POST['hashtoken'] ?? '';
            if (!$this->session->validateToken($token)) {
                $this->jsonResponse(false, 'Session expirée.');
            }

            // On reçoit le même format que MagixFeaturedPages : data['slug'] = [id1, id2]
            $data = $_POST['featured_products'] ?? [];
            if ($db->saveAllInstances($data)) {
                if (class_exists(CacheManager::class)) CacheManager::clearFrontend('magixfeaturedproduct');
                $this->jsonResponse(true, 'Produits phares mis à jour avec succès.');
            } else {
                $this->jsonResponse(false, 'Erreur de sauvegarde.');
            }
            return;
        }

        // AFFICHAGE
        $this->view->assign([
            'instances'         => $db->getRegisteredInstances(),
            'selected_products' => $db->getSelectedProductsFull($idLang),
            'hashtoken'         => $this->session->getToken()
        ]);

        $this->view->display('index.tpl');
    }
}