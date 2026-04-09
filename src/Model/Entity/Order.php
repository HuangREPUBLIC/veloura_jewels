<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $stripe_session_id
 * @property string|null $stripe_payment_intent_id
 * @property string|null $customer_email
 * @property string $status
 * @property float $total_amount
 * @property string $currency
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\OrderItem[] $order_items
 */
class Order extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'stripe_session_id' => true,
        'stripe_payment_intent_id' => true,
        'customer_email' => true,
        'status' => true,
        'total_amount' => true,
        'currency' => true,
        'created' => false,
        'modified' => false,
    ];
}
