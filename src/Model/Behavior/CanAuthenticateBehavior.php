<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Validation\Validator;
use Exception;

/**
 * A set of helper code to ensure that:
 *  - The database contains the relevant columns.
 *  - When resetting a password, the relevant fields are validated correctly.
 *
 * It is used by adding `$this->addBehaviour('CanAuthenticate')` to the `initialize()` method of the `UsersTable` class.
 */
class CanAuthenticateBehavior extends Behavior {

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $emailColumn = $this->table()->getSchema()->getColumn('email');
        $passwordColumn = $this->table()->getSchema()->getColumn('password');
        $nonceColumn = $this->table()->getSchema()->getColumn('nonce');
        $nonceExpiryColumn = $this->table()->getSchema()->getColumn('nonce_expiry');

        if (!$emailColumn) {
            throw new Exception('Users table does not contain an "email" column.');
        }

        if (!$passwordColumn) {
            throw new Exception('Users table does not contain a "password" column.');
        }

        if (!$nonceColumn) {
            throw new Exception('Users table does not contain a "nonce" column.');
        }

        if (!$nonceExpiryColumn) {
            throw new Exception('Users table does not contain a "nonce_expiry" column.');
        }
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        // Validate retyped password
        $validator
            ->requirePresence('password_confirm', 'create')
            ->sameAs('password_confirm', 'password', 'Both passwords must match');

        $validator
            ->uuid('nonce')
            ->maxLength('nonce', 128)
            ->allowEmptyString('nonce');

        return $validator;
    }

    /**
     * Reset Password validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationResetPassword(Validator $validator): Validator {
        $validator
            ->scalar('password')
            ->requirePresence('password', 'reset-password')
            ->notEmptyString('password');

        // Validate retyped password
        $validator
            ->requirePresence('password_confirm', 'reset-password')
            ->sameAs('password_confirm', 'password', 'Both passwords must match');

        $validator
            ->uuid('nonce')
            ->maxLength('nonce', 128)
            ->allowEmptyString('nonce');

        return $validator;
    }
}
