<?php
declare(strict_types=1);

namespace Plugins\MagixFeaturedProduct\db;

use App\Frontend\Db\BaseDb;
use Magepattern\Component\Database\QueryBuilder;

class FeaturedFrontDb extends BaseDb
{
    /**
     * Récupère UNIQUEMENT la liste des IDs des produits phares pour une instance,
     * triés par la position définie dans l'administration.
     */
    public function getFeaturedProductIds(string $instanceSlug = 'default'): array
    {
        $qb = new QueryBuilder();
        $qb->select(['id_product'])
            ->from('mc_plug_featured_product')
            ->where('instance_slug = :slug', ['slug' => $instanceSlug])
            ->orderBy('position', 'ASC');

        $results = $this->executeAll($qb) ?: [];
        return array_column($results, 'id_product');
    }
}