<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 */

use App\View\EmailTheme as T;

$shopUrl = $this->Url->build('/jewelry', ['fullBase' => true]);

$this->assign('title', 'Welcome to Veloura Jewels');
$this->assign('preheader', 'Your account is ready — the collection is waiting.');
$this->assign('footnote', 'You received this email because an account was created with this address.');
$this->assign('trustbar', 'on');
?>
<p class="t-accent" style="margin:0 0 10px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
          letter-spacing:0.2em;text-transform:uppercase;color:<?= T::GOLD_DEEP ?>;">
  Welcome
</p>

<h1 class="h1 t-ink" style="margin:0 0 18px;font-family:<?= T::FONT_DISPLAY ?>;font-size:28px;
           font-weight:400;line-height:1.2;color:<?= T::EMERALD ?>;">
  Your account is ready.
</h1>

<p class="t-mid" style="margin:0 0 16px;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  Hi <?= h($first_name) ?>, welcome to Veloura Jewels. Your account has been created
  and you&rsquo;re all set to explore our collection.
</p>

<p class="t-mid" style="margin:0 0 32px;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  Discover fine jewellery and home d&eacute;cor crafted with care.
</p>

<?= $this->element('email/button', ['url' => $shopUrl, 'label' => 'Browse Collection', 'width' => 250, 'last' => true]) ?>
