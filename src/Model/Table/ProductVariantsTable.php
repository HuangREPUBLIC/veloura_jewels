<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductVariantsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('product_variants');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('size')
            ->maxLength('size', 20)
            ->requirePresence('size', 'create')
            ->notEmptyString('size');

        $validator
            ->integer('stock')
            ->requirePresence('stock', 'create')
            ->notEmptyString('stock');

        return $validator;
    }
}
