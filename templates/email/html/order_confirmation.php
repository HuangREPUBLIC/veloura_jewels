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

<?php
echo $this->Html->image('homepage.png', ['alt' => 'CakePHP']);
?>

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

<!-- Items -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="margin-bottom:20px;">


  <?php foreach ($items as $item):
    $imgFilename = $item->product->product_images[0]->filename ?? null;
    $imgUrl = $imgFilename
      ? $this->Url->image('products/' . $imgFilename, ['fullBase' => true])
      : null;
  ?>
  <tr>
    <!-- Product image -->
    <td width="72" valign="top"
        style="padding:10px 12px 10px 0;border-bottom:1px solid #ede8df;">
      <?php if ($imgUrl): ?>
      <img src="<?= h($imgUrl) ?>" alt="<?= h($item->product_name) ?>"
           width="60"
           style="display:block;width:60px;height:auto;
                  border-radius:4px;border:1px solid #e8e3d8;">
      <?php endif; ?>
    </td>
    <!-- Product name and size -->
    <td valign="middle"
        style="padding:10px 0;font-family:Arial,sans-serif;font-size:14px;
                color:#3d3d3a;line-height:1.4;border-bottom:1px solid #ede8df;">
      <?= h($item->product_name) ?>
      <?php if (!empty($item->selected_size) && $item->selected_size !== 'One Size'): ?>
        <span style="color:#9b9080;font-size:12px;"> &mdash; <?= h($item->selected_size) ?></span>
      <?php endif; ?>
      <br>
      <span style="color:#9b9080;font-size:12px;">Qty: <?= (int)$item->quantity ?></span>
    </td>
    <!-- Price -->
    <td valign="middle" align="right"
        style="padding:10px 0;font-family:Arial,sans-serif;font-size:14px;
                color:#3d3d3a;white-space:nowrap;border-bottom:1px solid #ede8df;">
      $<?= number_format((float)$item->subtotal, 2) ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <!-- Total -->
  <tr>
    <td colspan="2"
        style="padding:14px 0 0;font-family:Arial,sans-serif;font-size:13px;
                letter-spacing:0.1em;text-transform:uppercase;
                color:#786c3b;font-weight:700;">
      Total
    </td>
    <td align="right"
        style="padding:14px 0 0;font-family:Georgia,'Times New Roman',serif;font-size:16px;
                color:#786c3b;font-weight:700;white-space:nowrap;">
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
