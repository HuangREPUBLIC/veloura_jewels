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
 * @property string|null $description
 * @property string|null $story
 * @property bool $featured
 * @property int $category_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\ProductImage[] $product_images
 * @property \App\Model\Entity\ProductVariant[] $product_variants
 */
class Product extends Entity
{
    protected array $_accessible = [
        'name'            => true,
        'purchase_price'  => true,
        'sale_price'      => true,
        'supplier_email'  => true,
        'description'     => true,
        'story'           => true,
        'featured'        => true,
        'category_id'     => true,
        'product_images'  => true,
        'product_variants' => true,
        'created'         => false,
        'modified'        => false,
    ];
}
