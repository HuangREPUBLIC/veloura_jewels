<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string $purchase_price
 * @property string $sale_price
 * @property string|null $supplier_email
 *
 * @property \App\Model\Entity\ProductImage[] $product_images
 * @property \App\Model\Entity\Category[] $categories
 */
class Product extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'purchase_price' => true,
        'sale_price' => true,
        'supplier_email' => true,
        'product_images' => true,
        'categories' => true,
    ];
}
