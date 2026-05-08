<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\OrderItem[] $items
 */
$shopUrl = $this->Url->build('/jewelry', ['fullBase' => true]);
?>
<p style="margin:0 0 10px;font-family:Arial,sans-serif;font-size:10px;
           letter-spacing:0.2em;text-transform:uppercase;color:#e1a95e;">
  Order Confirmed
</p>

<h1 style="margin:0 0 24px;font-family:Georgia,'Times New Roman',serif;
            font-size:28px;font-weight:400;color:#786c3b;line-height:1.2;">
  Thank you for your purchase.
</h1>

<p style="margin:0 0 6px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  Your order <strong style="color:#786c3b;">#<?= h($order->id) ?></strong> has been confirmed
  and is being prepared with care.
</p>

<!-- Divider -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="margin:24px 0 20px;">
  <tr>
    <td bgcolor="#e1a95e" height="1"
        style="background-color:#e1a95e;height:1px;font-size:0;line-height:0;"></td>
  </tr>
</table>

<p style="margin:0 0 12px;font-family:Arial,sans-serif;font-size:10px;
           letter-spacing:0.14em;text-transform:uppercase;color:#9b9080;">
  Items Ordered
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="margin-bottom:20px;">
  <?php foreach ($items as $item): ?>
  <tr>
    <td style="padding:10px 0;font-family:Arial,sans-serif;font-size:14px;
                color:#3d3d3a;line-height:1.4;border-bottom:1px solid #ede8df;">
      <?= h($item->product_name) ?>
      <?php if (!empty($item->selected_size) && $item->selected_size !== 'One Size'): ?>
        <span style="color:#9b9080;font-size:12px;"> &mdash; <?= h($item->selected_size) ?></span>
      <?php endif; ?>
      <span style="color:#9b9080;font-size:12px;"> &times;<?= (int)$item->quantity ?></span>
    </td>
    <td style="padding:10px 0;font-family:Arial,sans-serif;font-size:14px;
                color:#3d3d3a;text-align:right;white-space:nowrap;
                border-bottom:1px solid #ede8df;">
      $<?= number_format((float)$item->subtotal, 2) ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td style="padding:14px 0 0;font-family:Arial,sans-serif;font-size:13px;
                letter-spacing:0.1em;text-transform:uppercase;
                color:#786c3b;font-weight:700;">
      Total
    </td>
    <td style="padding:14px 0 0;font-family:Georgia,'Times New Roman',serif;font-size:16px;
                color:#786c3b;text-align:right;font-weight:700;">
      $<?= number_format((float)$order->total_amount, 2) ?>
      <span style="font-family:Arial,sans-serif;font-size:11px;
                   color:#9b9080;font-weight:400;"> <?= strtoupper(h($order->currency)) ?></span>
    </td>
  </tr>
</table>

<p style="margin:0 0 36px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  We&rsquo;ll be in touch once your order is on its way.
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0"
       align="center" style="margin:0 auto;">
  <tr>
    <td align="center" style="background-color:#786c3b;border-radius:2px;">
      <a href="<?= h($shopUrl) ?>"
         style="display:inline-block;padding:15px 44px;
                font-family:Arial,Helvetica,sans-serif;
                font-size:11px;font-weight:700;
                letter-spacing:0.16em;text-transform:uppercase;
                color:#f0ede4;text-decoration:none;">
        Continue Shopping
      </a>
    </td>
  </tr>
</table>
