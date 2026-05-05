<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 */
$this->assign('title', 'Orders');

$now = new \DateTime();
$todayStr   = $now->format('Y-m-d');
$weekAgo    = (clone $now)->modify('-7 days')->setTime(0, 0, 0);
$monthStart = (clone $now)->modify('first day of this month')->setTime(0, 0, 0);

$stats = [
    'today' => ['sales' => 0, 'profit' => 0],
    'week'  => ['sales' => 0, 'profit' => 0],
    'month' => ['sales' => 0, 'profit' => 0],
    'all'   => ['sales' => 0, 'profit' => 0],
];

$orderProfits = [];
foreach ($orders as $order) {
    $profit = 0;
    foreach ($order->order_items as $item) {
        if ($item->product) {
            $profit += ($item->unit_price - $item->product->purchase_price) * $item->quantity;
        }
    }
    $orderProfits[$order->id] = $profit;

    if ($order->status === 'paid') {
        $d = $order->created;
        $stats['all']['sales'] += $order->total_amount;
        $stats['all']['profit']  += $profit;
        if ($d->format('Y-m-d') === $todayStr)  { $stats['today']['sales'] += $order->total_amount; $stats['today']['profit'] += $profit; }
        if ($d >= $weekAgo)                      { $stats['week']['sales']  += $order->total_amount; $stats['week']['profit']  += $profit; }
        if ($d >= $monthStart)                   { $stats['month']['sales'] += $order->total_amount; $stats['month']['profit'] += $profit; }
    }
}
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="orders index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <h3 class="page-title"><?= __('Orders') ?></h3>
        </div>

        <div class="orders-summary-header">
            <div>
                <h4>Revenue Summary</h4>
                <p>Track sales and estimated profit across different time periods.</p>
            </div>
        </div>

        <div class="orders-stat-cards">
            <?php foreach ([
                               'Today'      => $stats['today'],
                               'This Week'  => $stats['week'],
                               'This Month' => $stats['month'],
                               'All Time'   => $stats['all'],
                           ] as $label => $s): ?>
                <div class="orders-stat-card">
                    <div class="stat-top">
                        <span class="stat-label"><?= $label ?></span>
                        <span class="stat-currency">AUD</span>
                    </div>

                    <div class="stat-main">
                        $<?= number_format($s['profit'], 2) ?>
                    </div>

                    <div class="stat-sub">
                        Sales $<?= number_format($s['sales'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="orders-filter-section">
            <div class="orders-filter-header">
                <div>
                    <h4>Order Filters</h4>
                    <p>Filter orders by date, status and performance.</p>
                </div>
            </div>

            <div class="orders-filter-grid">
                <div class="orders-filter-group">
                    <span class="orders-filter-label">Date</span>
                    <div class="orders-filter-controls">
                        <button class="filter-btn active" data-type="date" data-filter="all">All</button>
                        <button class="filter-btn" data-type="date" data-filter="today">Today</button>
                        <button class="filter-btn" data-type="date" data-filter="week">This Week</button>
                        <button class="filter-btn" data-type="date" data-filter="month">This Month</button>
                    </div>
                </div>

                <div class="orders-filter-group">
                    <span class="orders-filter-label">Custom Range</span>
                    <div class="orders-date-range">
                        <input type="date" id="dateFrom" class="date-field" max="<?= date('Y-m-d') ?>">
                        <span class="date-range-to">to</span>
                        <input type="date" id="dateTo" class="date-field" max="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="orders-filter-group">
                    <span class="orders-filter-label">Status</span>
                    <div class="orders-filter-controls">
                        <button class="filter-btn active" data-type="status" data-filter="all">All</button>
                        <button class="filter-btn" data-type="status" data-filter="paid">Paid</button>
                        <button class="filter-btn" data-type="status" data-filter="pending">Pending</button>
                        <button class="filter-btn" data-type="status" data-filter="cancelled">Cancelled</button>
                    </div>
                </div>

                <div class="orders-filter-group">
                    <span class="orders-filter-label">Sort by</span>
                    <div class="orders-filter-controls">
                        <button class="filter-btn active" data-type="sort" data-col="4" data-dir="desc">Newest</button>
                        <button class="filter-btn" data-type="sort" data-col="3" data-dir="desc">Most Profit</button>
                    </div>
                </div>
            </div>
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
                <?php
                $statusClass = ['paid' => 'status-pill-paid', 'pending' => 'status-pill-pending', 'cancelled' => 'status-pill-cancelled'];
                foreach ($orders as $order):
                    $orderProfit = $orderProfits[$order->id];
                    $profitColor = $orderProfit >= 0 ? '#2e7d32' : '#c62828';
                    $cls = $statusClass[$order->status] ?? 'status-pill-pending';
                    ?>
                    <tr data-date="<?= h($order->created->format('Y-m-d')) ?>"
                        data-status="<?= h($order->status) ?>">
                        <td><?= h($order->customer_email) ?></td>
                        <td><span class="status-pill <?= $cls ?>"><?= h(ucfirst($order->status)) ?></span></td>
                        <td data-order="<?= (float)$order->total_amount ?>">$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></td>
                        <td data-order="<?= $orderProfit ?>" style="color:<?= $profitColor ?>;font-weight:600;">$<?= number_format($orderProfit, 2) ?> <?= strtoupper(h($order->currency)) ?></td>
                        <td><?= h($order->created) ?></td>
                        <td class="actions"><?= $this->Html->link(__('View'), ['action' => 'view', $order->id]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let dateFilter   = 'all';
        let statusFilter = 'all';
        let dateFrom     = null;
        let dateTo       = null;

        const table = $('#ordersTable').DataTable({
            order: [[4, 'desc']],
            language: { lengthMenu: '_MENU_ Entries Per Page', search: 'Search:' },
            columnDefs: [{ targets: [0,1,2,3,4], searchable: true }]
        });

        const localISO = d => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const node = table.row(dataIndex).node();
            if (!node) return true;

            if (statusFilter !== 'all' && node.dataset.status !== statusFilter) return false;

            const dateStr = node.dataset.date;
            if (!dateStr) return true;

            const rowDate = new Date(dateStr + 'T00:00:00');
            const today   = new Date(); today.setHours(0, 0, 0, 0);

            if (dateFrom || dateTo) {
                if (dateFrom && rowDate < dateFrom) return false;
                if (dateTo   && rowDate > dateTo)   return false;
                return true;
            }

            if (dateFilter === 'all')   return true;
            if (dateFilter === 'today') return dateStr === localISO(today);
            if (dateFilter === 'week')  { const w = new Date(today); w.setDate(w.getDate() - 7); return rowDate >= w; }
            if (dateFilter === 'month') return rowDate >= new Date(today.getFullYear(), today.getMonth(), 1);
            return true;
        });

        $('[data-type="date"]').on('click', function() {
            dateFilter = $(this).data('filter');
            const today = new Date();
            const iso   = localISO;
            const todayISO = iso(today);
            let from = '', to = '';
            if (dateFilter === 'today') {
                from = to = todayISO;
            } else if (dateFilter === 'week') {
                const w = new Date(today); w.setDate(w.getDate() - 7);
                from = iso(w); to = todayISO;
            } else if (dateFilter === 'month') {
                from = iso(new Date(today.getFullYear(), today.getMonth(), 1)); to = todayISO;
            }
            dateFrom = from ? new Date(from + 'T00:00:00') : null;
            dateTo   = to   ? new Date(to   + 'T00:00:00') : null;
            $('#dateFrom').val(from).attr('max', to || '<?= date('Y-m-d') ?>');
            $('#dateTo').val(to).attr('min', from);
            $('[data-type="date"]').removeClass('active');
            $(this).addClass('active');
            table.draw();
        });

        $('#dateFrom, #dateTo').on('change', function() {
            const from = $('#dateFrom').val();
            const to   = $('#dateTo').val();
            if (from) $('#dateTo').attr('min', from);
            if (to)   $('#dateFrom').attr('max', to);
            dateFrom = from ? new Date(from + 'T00:00:00') : null;
            dateTo   = to   ? new Date(to   + 'T00:00:00') : null;
            if (from || to) {
                $('[data-type="date"]').removeClass('active');
                dateFilter = 'custom';
            }
            table.draw();
        });

        $('[data-type="status"]').on('click', function() {
            statusFilter = $(this).data('filter');
            $('[data-type="status"]').removeClass('active');
            $(this).addClass('active');
            table.draw();
        });

        $('[data-type="sort"]').on('click', function() {
            table.order([parseInt($(this).data('col')), $(this).data('dir')]).draw();
            $('[data-type="sort"]').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>
