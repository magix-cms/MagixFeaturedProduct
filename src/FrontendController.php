<?php
declare(strict_types=1);

namespace Plugins\MagixFeaturedProduct\src;

use Plugins\MagixFeaturedProduct\db\FeaturedFrontDb;
use App\Frontend\Db\ProductDb;
use App\Frontend\Model\ProductPresenter;
use Magepattern\Component\Tool\SmartyTool;
// 🛡️ NOUVELLE INCLUSION
use App\Frontend\Model\ThemeManager;

class FrontendController
{
    public static function renderWidget(array $params = []): string
    {
        // 1. SÉCURITÉ / AIGUILLAGE
        $hookName = $params['name'] ?? '';
        if (!str_starts_with($hookName, 'displayHome')) {
            return '';
        }

        // 2. On récupère l'identifiant unique du bloc passé par le Layout
        $instanceSlug = !empty($params['instance_slug']) ? $params['instance_slug'] : 'default';

        // 3. TRAITEMENT NORMAL
        try {
            $view = SmartyTool::getInstance('front');

            $currentLang = $view->getTemplateVars('current_lang') ?: ['id_lang' => 1, 'iso_lang' => 'fr'];
            $idLang      = (int)$currentLang['id_lang'];
            $siteUrl     = $view->getTemplateVars('site_url') ?: 'http://localhost';

            $companyInfo = $view->getTemplateVars('companyData') ?: [];
            $mcSettings  = $view->getTemplateVars('mc_settings') ?: [];

            // On sécurise la récupération du dossier du skin via ThemeManager
            $skinFolder  = ThemeManager::getThemeFolder();

            $featuredDb = new FeaturedFrontDb();

            // On filtre les produits par instance
            $productIds = $featuredDb->getFeaturedProductIds($instanceSlug);

            if (empty($productIds)) return '';

            if (!method_exists(ProductDb::class, 'getProductsByIds')) return '';

            $productDb = new ProductDb();
            $rawProducts = $productDb->getProductsByIds($productIds, $idLang);

            if (empty($rawProducts)) return '';

            $formattedProducts = [];
            foreach ($rawProducts as $row) {
                $formatted = ProductPresenter::format($row, $currentLang, $siteUrl, $companyInfo, $skinFolder, $mcSettings);
                if ($formatted) {
                    $formattedProducts[] = $formatted;
                }
            }

            // On passe le slug et les données à la vue
            $view->assign([
                'featured_products' => $formattedProducts,
                'instance_slug'     => $instanceSlug
            ]);

            // ==========================================================
            // 🛡️ GESTION DE LA SURCHARGE VIA THEMEMANAGER
            // ==========================================================
            $themePath = ThemeManager::getThemePath();

            // Convention de surcharge : skin/MON_THEME/plugins/magixfeaturedproduct/widget.tpl
            $overridePath = $themePath . DS . 'plugins' . DS . 'magixfeaturedproduct' . DS . 'widget.tpl';
            $defaultPath  = ROOT_DIR . 'plugins' . DS . 'MagixFeaturedProduct' . DS . 'views' . DS . 'front' . DS . 'widget.tpl';

            $tplToLoad = file_exists($overridePath) ? $overridePath : $defaultPath;

            return $view->fetch($tplToLoad);

        } catch (\Throwable $e) {
            return '';
        }
    }
}