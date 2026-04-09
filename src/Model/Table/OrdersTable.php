<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class OrdersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('orders');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('OrderItems', [
            'foreignKey' => 'order_id',
        ]);
    }
}
