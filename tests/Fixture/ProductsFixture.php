<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductsFixture
 */
class ProductsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'purchase_price' => 1.5,
                'sale_price' => 1.5,
                'supplier_email' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
