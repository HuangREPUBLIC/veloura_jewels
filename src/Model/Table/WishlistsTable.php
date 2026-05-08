<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class WishlistsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('wishlists');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp', ['events' => ['Model.beforeSave' => ['created' => 'new']]]);
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
        $this->belongsTo('Products', ['foreignKey' => 'product_id']);
    }
}
