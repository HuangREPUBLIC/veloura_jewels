<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderItem Entity
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $product_name
 * @property float $unit_price
 * @property int $quantity
 * @property float $subtotal
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property \App\Model\Entity\Order $order
 * @property \App\Model\Entity\Product $product
 */
class OrderItem extends Entity
{
    protected array $_accessible = [
        'order_id' => true,
        'product_id' => true,
        'product_name' => true,
        'unit_price' => true,
        'quantity' => true,
        'subtotal' => true,
        'created' => false,
        'modified' => false,
    ];
}
