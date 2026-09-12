<?php
/**
 * @var \App\View\AppView $this
 * @var string $firstName
 * @var string $userMessage
 */

use App\View\EmailTheme as T;

$this->assign('title', 'We received your message');
$this->assign('preheader', 'Thanks for writing in — we will come back to you shortly.');
$this->assign('footnote', 'You received this email because a message was sent to us from this address.');
?>
<p class="t-accent" style="margin:0 0 10px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
          letter-spacing:0.2em;text-transform:uppercase;color:<?= T::GOLD_DEEP ?>;">
  Message Received
</p>

<h1 class="h1 t-ink" style="margin:0 0 18px;font-family:<?= T::FONT_DISPLAY ?>;font-size:28px;
           font-weight:400;line-height:1.2;color:<?= T::EMERALD ?>;">
  Thank you for reaching out.
</h1>

<p class="t-mid" style="margin:0 0 24px;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  Hi <?= h($firstName) ?>, we have received your message and will get back to you
  as soon as possible.
</p>

<table role="presentation" class="panel" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="width:100%;margin:0 0 28px;background-color:<?= T::BEIGE_LIGHT ?>;
              border:1px solid <?= T::BORDER ?>;border-left:3px solid <?= T::EARTH_YELLOW ?>;
              border-radius:8px;">
  <tr>
    <td style="padding:16px 20px;">
      <p class="t-soft" style="margin:0 0 6px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
                letter-spacing:0.14em;text-transform:uppercase;color:<?= T::MUTED ?>;">
        Your message
      </p>
      <p class="t-mid" style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:14px;
                line-height:1.7;color:<?= T::BODY ?>;">
        <?= nl2br(h($userMessage)) ?>
      </p>
    </td>
  </tr>
</table>

<p class="t-mid" style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  Kind regards,<br>
  <strong class="t-ink" style="color:<?= T::EMERALD ?>;">Veloura Jewels</strong>
</p>
