<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 */
$this->assign('title', 'Orders');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="orders index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard']) ?>

        <div class="page-header-row">
            <h3 class="page-title"><?= __('Orders') ?></h3>
        </div>

        <div class="table-responsive" style="padding: 10px">
            <table id="ordersTable" class="display">
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Profit</th>
                    <th>Date</th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= h($order->customer_email) ?></td>
                        <td>
                            <?php
                            $statusClass = [
                                'paid'      => 'status-pill-paid',
                                'pending'   => 'status-pill-pending',
                                'cancelled' => 'status-pill-cancelled',
                            ];
                            $cls = $statusClass[$order->status] ?? 'status-pill-pending';
                            ?>
                            <span class="status-pill <?= $cls ?>"><?= h(ucfirst($order->status)) ?></span>
                        </td>
                        <td>$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></td>
                        <?php
                        $orderProfit = 0;
                        foreach ($order->order_items as $item) {
                            if ($item->product) {
                                $orderProfit += ($item->unit_price - $item->product->purchase_price) * $item->quantity;
                            }
                        }
                        $profitColor = $orderProfit >= 0 ? '#2e7d32' : '#c62828';
                        ?>
                        <td style="color: <?= $profitColor ?>; font-weight: 600;">
                            $<?= number_format($orderProfit, 2) ?> <?= strtoupper(h($order->currency)) ?>
                        </td>
                        <td><?= h($order->created) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $order->id]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            order: [[4, 'desc']],
            language: { lengthMenu: '_MENU_ Entries Per Page', search: 'Search:' },
            columnDefs: [{ targets: [0,1,2,3,4], searchable: true }]
        });
    });
</script>
