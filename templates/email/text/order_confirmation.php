<?php
/**
 * Built with explicit newlines rather than inline tags: `?>` swallows the
 * newline that follows it, which ran every item together on one line.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\OrderItem[] $items
 */

$lines = [
    'YOUR ORDER IS CONFIRMED',
    '',
    'Thank you for your purchase. Order #' . $order->id . ' has been confirmed',
    'and is being prepared with care.',
];

if (!empty($order->created)) {
    $lines[] = '';
    $lines[] = 'Order date: ' . $order->created->i18nFormat('d MMM yyyy');
}

$lines[] = '';
$lines[] = 'ITEMS ORDERED';

foreach ($items as $item) {
    $size = !empty($item->selected_size) && $item->selected_size !== 'One Size'
        ? ' (' . $item->selected_size . ')'
        : '';
    $lines[] = sprintf(
        '  %s%s  x%d  $%s',
        $item->product_name,
        $size,
        (int)$item->quantity,
        number_format((float)$item->subtotal, 2),
    );
}

$lines[] = '';
$lines[] = 'TOTAL: $' . number_format((float)$order->total_amount, 2) . ' ' . strtoupper((string)$order->currency);
$lines[] = '';
$lines[] = "We'll be in touch once your order is on its way.";

echo implode("\n", $lines);
