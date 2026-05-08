<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Cake\I18n\DateTime $created
 * @property \App\Model\Entity\Product $product
 */
class Wishlist extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'product_id' => true,
        'created' => false,
    ];
}
