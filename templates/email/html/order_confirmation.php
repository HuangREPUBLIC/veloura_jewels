<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\OrderItem[] $items
 */

use App\View\EmailTheme as T;

$shopUrl = $this->Url->build('/jewelry', ['fullBase' => true]);
$currency = strtoupper((string)$order->currency);

$rows = [['label' => 'Order number', 'value' => '#' . $order->id, 'mono' => true]];
if (!empty($order->created)) {
    $rows[] = ['label' => 'Order date', 'value' => $order->created->i18nFormat('d MMM yyyy')];
}

$this->assign('title', 'Your order is confirmed');
$this->assign('preheader', 'Order #' . $order->id . ' is confirmed and being prepared.');
$this->assign('footnote', 'You received this email because an order was placed with this address.');
$this->assign('trustbar', 'on');
?>
<p class="t-accent" style="margin:0 0 10px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
          letter-spacing:0.2em;text-transform:uppercase;color:<?= T::GOLD_DEEP ?>;">
  Order Confirmed
</p>

<h1 class="h1 t-ink" style="margin:0 0 18px;font-family:<?= T::FONT_DISPLAY ?>;font-size:28px;
           font-weight:400;line-height:1.2;color:<?= T::EMERALD ?>;">
  Thank you for your purchase.
</h1>

<p class="t-mid" style="margin:0 0 26px;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  Your order has been confirmed and is being prepared with care.
</p>

<?= $this->element('email/detail_card', ['rows' => $rows]) ?>

<p class="t-soft" style="margin:0 0 10px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
          letter-spacing:0.14em;text-transform:uppercase;color:<?= T::MUTED ?>;">
  Items Ordered
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;margin-bottom:24px;">
  <?php foreach ($items as $item) :
      $imgFilename = $item->product->product_images[0]->filename ?? null;
      $imgUrl = $imgFilename ? $this->Url->image('products/' . $imgFilename, ['fullBase' => true]) : null;
  ?>
  <tr>
    <td class="hair" width="72" valign="top"
        style="padding:12px 12px 12px 0;border-bottom:1px solid <?= T::HAIRLINE ?>;">
      <?php if ($imgUrl) : ?>
      <?php // Empty alt: the product name sits in the next cell, so alt text would
            // only repeat it — and when a client blocks images it wraps to four
            // lines inside a 60px box and doubles the row height.
            // Neutral alpha edge, not a beige one: a brand-tinted border reads as
            // part of the photo. ?>
      <img src="<?= h($imgUrl) ?>" alt="" width="60"
           style="display:block;width:60px;height:auto;border-radius:4px;border:1px solid rgba(0,0,0,0.10);">
      <?php endif; ?>
    </td>
    <?php // width:100% makes the name cell absorb what the fixed thumbnail and the
          // nowrap price leave over; without it the browser splits by content and
          // a four-word product name breaks one word per line on a phone. ?>
    <td class="hair t-ink" valign="middle" width="100%"
        style="padding:12px 0;font-family:<?= T::FONT_BODY ?>;font-size:14.5px;line-height:1.45;
               color:<?= T::INK ?>;border-bottom:1px solid <?= T::HAIRLINE ?>;">
      <?= h($item->product_name) ?>
      <?php if (!empty($item->selected_size) && $item->selected_size !== 'One Size') : ?>
        <span class="t-soft" style="color:<?= T::MUTED ?>;font-size:12.5px;"> &mdash; <?= h($item->selected_size) ?></span>
      <?php endif; ?>
      <br>
      <span class="t-soft" style="color:<?= T::MUTED ?>;font-size:12.5px;font-variant-numeric:tabular-nums;">
        Qty: <?= (int)$item->quantity ?>
      </span>
    </td>
    <td class="hair t-ink" valign="middle" align="right"
        style="padding:12px 0 12px 10px;font-family:<?= T::FONT_BODY ?>;font-size:14.5px;color:<?= T::INK ?>;
               white-space:nowrap;font-variant-numeric:tabular-nums;border-bottom:1px solid <?= T::HAIRLINE ?>;">
      $<?= number_format((float)$item->subtotal, 2) ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="2" class="t-ink"
        style="padding:16px 0 0;font-family:<?= T::FONT_BODY ?>;font-size:12px;font-weight:700;
               letter-spacing:0.14em;text-transform:uppercase;color:<?= T::EMERALD ?>;">
      Total
    </td>
    <td align="right" class="t-ink"
        style="padding:16px 0 0;font-family:<?= T::FONT_DISPLAY ?>;font-size:19px;font-weight:600;
               color:<?= T::EMERALD ?>;white-space:nowrap;font-variant-numeric:tabular-nums;">
      $<?= number_format((float)$order->total_amount, 2) ?><span class="t-soft"
        style="font-family:<?= T::FONT_BODY ?>;font-size:11px;color:<?= T::MUTED ?>;font-weight:400;"> <?= h($currency) ?></span>
    </td>
  </tr>
</table>

<p class="t-mid" style="margin:0 0 32px;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  We&rsquo;ll be in touch once your order is on its way.
</p>

<?= $this->element('email/button', ['url' => $shopUrl, 'label' => 'Continue Shopping', 'width' => 250, 'last' => true]) ?>
