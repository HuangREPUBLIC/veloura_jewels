<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductImages Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ProductImage newEmptyEntity()
 * @method \App\Model\Entity\ProductImage newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductImage> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductImage get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProductImage findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProductImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductImage> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductImage|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProductImage saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductImage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductImage> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ProductImagesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('product_images');
        $this->setDisplayField('filename');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('product_id')
            ->notEmptyString('product_id');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 4096)
            ->requirePresence('filename', 'create')
            ->notEmptyString('filename');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);

        return $rules;
    }
}
