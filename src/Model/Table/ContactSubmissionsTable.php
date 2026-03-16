<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContactSubmissions Model
 *
 * @method \App\Model\Entity\ContactSubmission newEmptyEntity()
 * @method \App\Model\Entity\ContactSubmission newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ContactSubmission> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContactSubmission get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ContactSubmission findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ContactSubmission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ContactSubmission> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContactSubmission|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ContactSubmission saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ContactSubmission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactSubmission>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactSubmission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactSubmission> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactSubmission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactSubmission>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactSubmission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactSubmission> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContactSubmissionsTable extends Table
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

        $this->setTable('contact_submissions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->notEmptyString('first_name', 'Please enter your first name')
            ->maxLength('first_name', 50, 'First name must be 50 characters or less');

        $validator
            ->notEmptyString('last_name', 'Please enter your last name')
            ->maxLength('last_name', 50, 'Last name must be 50 characters or less');

        $validator
            ->notEmptyString('email', 'Please enter your email')
            ->email('email', false, 'Please enter a valid email address');

        $validator
            ->notEmptyString('message', 'Please enter a message');

        $validator
            ->boolean('captcha_passed')
            ->notEmptyString('captcha_passed');

        return $validator;
    }
}
