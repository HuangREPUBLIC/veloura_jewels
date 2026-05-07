<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class FaqItemsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('faq_items');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }
}
