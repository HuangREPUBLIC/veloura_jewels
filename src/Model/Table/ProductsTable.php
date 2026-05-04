<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\ProductImagesTable&\Cake\ORM\Association\HasMany $ProductImages
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsToMany $Categories
 *
 * @method \App\Model\Entity\Product newEmptyEntity()
 * @method \App\Model\Entity\Product newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Product> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Product findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Product> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Product saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product> deleteManyOrFail(iterable $entities, array $options = [])
 * @property \App\Model\Table\ProductVariantsTable&\Cake\ORM\Association\HasMany $ProductVariants
 */
class ProductsTable extends Table
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->hasMany('ProductImages', [
            'foreignKey' => 'product_id',
        ]);
        $this->belongsToMany('Categories', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'categories_products',
        ]);
        $this->hasMany('ProductVariants', [
            'foreignKey' => 'product_id',
        ]);
    }

    public function findBestSales(SelectQuery $query, string $productType, int $limit = 4): SelectQuery
    {
        return $query
            ->select(['total_sold' => 'SUM(order_items.quantity)'])
            ->enableAutoFields(true)
            ->join([
                'order_items' => [
                    'table' => 'order_items',
                    'type' => 'INNER',
                    'conditions' => 'order_items.product_id = Products.id',
                ],
                'categories_products' => [
                    'table' => 'categories_products',
                    'type' => 'INNER',
                    'conditions' => 'categories_products.product_id = Products.id',
                ],
                'categories' => [
                    'table' => 'categories',
                    'type' => 'INNER',
                    'conditions' => [
                        'categories.id = categories_products.category_id',
                        'categories.type' => $productType
                    ],
                ],
            ])
            ->groupBy('Products.id')
            ->orderBy([
                'total_sold' => 'DESC',
                'Products.id' => 'DESC'
            ])
            ->limit($limit);
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
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->decimal('purchase_price')
            ->requirePresence('purchase_price', 'create')
            ->notEmptyString('purchase_price');

        $validator
            ->decimal('sale_price')
            ->requirePresence('sale_price', 'create')
            ->notEmptyString('sale_price');

        $validator
            ->email('supplier_email')
            ->maxLength('supplier_email', 320)
            ->allowEmptyString('supplier_email');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('story')
            ->requirePresence('story', 'create')
            ->notEmptyString('story');
        $validator
            ->boolean('featured')
            ->notEmptyString('featured');

        return $validator;
    }
}
