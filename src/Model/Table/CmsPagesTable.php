<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class CmsPagesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('cms_pages');
        $this->setPrimaryKey('id');
        $this->hasMany('PageContents', ['foreignKey' => 'page_id']);
    }
}
