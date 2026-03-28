<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductsTable Test Case
 */
class ProductsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductsTable
     */
    protected $Products;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Products',
        'app.ProductImages',
        'app.Categories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Products') ? [] : ['className' => ProductsTable::class];
        $this->Products = $this->getTableLocator()->get('Products', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Products);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ProductsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
