<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $product_id
 * @property string $size
 * @property int $stock
 */
class ProductVariant extends Entity
{
    protected array $_accessible = [
        'product_id' => true,
        'size'       => true,
        'stock'      => true,
    ];
}
