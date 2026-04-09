<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 */
$this->assign('title', 'Orders');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="submissions-wrapper">
    <div class="orders index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard']) ?>

        <h3 class="page-title"><?= __('Orders') ?></h3>

        <div class="table-responsive" id="datatable" style="padding: 10px">
            <table id="ordersTable" class="display">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th><?= $this->Paginator->sort('customer_email', 'Customer') ?></th>
                    <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                    <th><?= $this->Paginator->sort('total_amount', 'Total') ?></th>
                    <th><?= $this->Paginator->sort('currency', 'Currency') ?></th>
                    <th><?= $this->Paginator->sort('created', 'Date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= h($order->id) ?></td>
                        <td><?= h($order->customer_email) ?></td>
                        <td>
                            <?php
                            $statusColors = [
                                'paid' => 'color:green;font-weight:600;',
                                'pending' => 'color:#b7860b;font-weight:600;',
                                'cancelled' => 'color:#c0392b;font-weight:600;',
                            ];
                            $style = $statusColors[$order->status] ?? '';
                            ?>
                            <span style="<?= $style ?>"><?= h(ucfirst($order->status)) ?></span>
                        </td>
                        <td>$<?= number_format((float)$order->total_amount, 2) ?></td>
                        <td><?= strtoupper(h($order->currency)) ?></td>
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
            order: [[5, 'desc']],
            language: {
                lengthMenu: '_MENU_ Entries Per Page',
                search: 'Search:'
            },
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5],
                    searchable: true
                }
            ]
        });
    });
</script>
