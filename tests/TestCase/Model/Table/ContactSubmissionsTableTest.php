<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContactSubmissionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContactSubmissionsTable Test Case
 */
class ContactSubmissionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContactSubmissionsTable
     */
    protected $ContactSubmissions;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.ContactSubmissions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ContactSubmissions') ? [] : ['className' => ContactSubmissionsTable::class];
        $this->ContactSubmissions = $this->getTableLocator()->get('ContactSubmissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ContactSubmissions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ContactSubmissionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
