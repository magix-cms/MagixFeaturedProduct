<?php
declare(strict_types=1);

namespace Plugins\MagixFeaturedProduct\db;

use App\Backend\Db\BaseDb;
use Magepattern\Component\Database\QueryBuilder;

class FeaturedAdminDb extends BaseDb
{
    public function searchActiveProducts(string $term, int $idLang): array
    {
        $qb = new QueryBuilder();
        $qb->select([
            'p.id_product', 'p.reference_p', 'c.name_p', 'cat_c.name_cat'
        ])
            ->from('mc_catalog_product', 'p')
            ->join('mc_catalog_product_content', 'c', 'p.id_product = c.id_product')
            ->join('mc_catalog', 'cat_rel', 'p.id_product = cat_rel.id_product AND cat_rel.default_c = 1')
            ->join('mc_catalog_cat_content', 'cat_c', 'cat_rel.id_cat = cat_c.id_cat AND cat_c.id_lang = c.id_lang')
            ->where('c.id_lang = :lang', ['lang' => $idLang])
            ->where('c.published_p = 1')
            ->where('(c.name_p LIKE :term OR p.reference_p LIKE :term)', ['term' => '%' . $term . '%'])
            ->limit(10);

        return $this->executeAll($qb) ?: [];
    }

    /**
     * Récupère les produits groupés par instance_slug (Comme pour les pages)
     */
    public function getSelectedProductsFull(int $idLang): array
    {
        $qb = new QueryBuilder();
        $qb->select([
            'p.id_product', 'p.reference_p', 'c.name_p', 'cat_c.name_cat', 'feat.position', 'feat.instance_slug'
        ])
            ->from('mc_plug_featured_product', 'feat')
            ->join('mc_catalog_product', 'p', 'feat.id_product = p.id_product')
            ->join('mc_catalog_product_content', 'c', 'p.id_product = c.id_product AND c.id_lang = ' . $idLang)
            ->leftJoin('mc_catalog', 'cat_rel', 'p.id_product = cat_rel.id_product AND cat_rel.default_c = 1')
            ->leftJoin('mc_catalog_cat_content', 'cat_c', 'cat_rel.id_cat = cat_c.id_cat AND cat_c.id_lang = ' . $idLang)
            ->orderBy('feat.position', 'ASC');

        $results = $this->executeAll($qb) ?: [];

        $grouped = [];
        foreach ($results as $row) {
            $grouped[$row['instance_slug']][] = $row;
        }
        return $grouped;
    }

    /**
     * Sauvegarde massive par instance
     * Structure: ['slug_1' => [id, id], 'slug_2' => [id]]
     */
    public function saveAllInstances(array $data): bool
    {
        $this->executeRawSql('TRUNCATE TABLE mc_plug_featured_product');

        foreach ($data as $slug => $ids) {
            if (!is_array($ids)) continue;
            foreach ($ids as $pos => $id) {
                $qb = new QueryBuilder();
                $qb->insert('mc_plug_featured_product', [
                    'id_product'    => (int)$id,
                    'instance_slug' => $slug,
                    'position'      => $pos
                ]);
                $this->executeInsert($qb);
            }
        }
        return true;
    }

    /**
     * Récupère la liste des instances déclarées dans le Layout pour le plugin
     */
    public function getRegisteredInstances(): array
    {
        $qb = new QueryBuilder();
        $qb->select('item_slug')
            ->from('mc_hook_item')
            ->where('module_name = "MagixFeaturedProduct"'); // Le nom du plugin cible

        $res = $this->executeAll($qb);
        $instances = [];

        if ($res) {
            foreach ($res as $row) {
                $slug = !empty($row['item_slug']) ? $row['item_slug'] : 'default';
                if (!in_array($slug, $instances)) {
                    $instances[] = $slug;
                }
            }
        }
        return empty($instances) ? ['default'] : $instances;
    }
}