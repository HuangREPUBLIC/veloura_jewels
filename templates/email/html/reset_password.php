<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 * @var string $nonce
 */

use App\View\EmailTheme as T;

$resetUrl = $this->Url->build(['controller' => 'Auth', 'action' => 'resetPassword', $nonce], ['fullBase' => true]);

$this->assign('title', 'Reset your password');
$this->assign('preheader', 'Choose a new password for your Veloura Jewels account.');
$this->assign('footnote', 'You received this email because a password reset was requested for this account.');
?>
<p class="t-accent" style="margin:0 0 10px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
          letter-spacing:0.2em;text-transform:uppercase;color:<?= T::GOLD_DEEP ?>;">
  Account Security
</p>

<h1 class="h1 t-ink" style="margin:0 0 18px;font-family:<?= T::FONT_DISPLAY ?>;font-size:28px;
           font-weight:400;line-height:1.2;color:<?= T::EMERALD ?>;">
  Reset your password.
</h1>

<p class="t-mid" style="margin:0 0 26px;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  Hi <?= h($first_name) ?>, we received a request to set a new password on your
  Veloura Jewels account. Use the button below to choose one.
</p>

<?= $this->element('email/detail_card', [
    'rows' => [
        ['label' => 'Link expires', 'value' => '7 days from now'],
    ],
]) ?>

<?= $this->element('email/button', ['url' => $resetUrl, 'label' => 'Reset Password', 'width' => 230]) ?>

<p class="t-soft" style="margin:0 0 24px;font-family:<?= T::FONT_BODY ?>;font-size:12.5px;
          line-height:1.7;color:<?= T::MUTED ?>;">
  If the button does not work, copy this link into your browser:<br>
  <a class="t-accent" href="<?= h($resetUrl) ?>"
     style="color:<?= T::GOLD_DEEP ?>;word-break:break-all;text-decoration:underline;">
    <?= h($resetUrl) ?>
  </a>
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="hair" style="padding-top:22px;border-top:1px solid <?= T::HAIRLINE ?>;">
      <p class="t-soft" style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:13px;
                line-height:1.7;color:<?= T::MUTED ?>;">
        Did not request this? You can safely ignore this email. Your password
        stays as it is until the link above is used.
      </p>
    </td>
  </tr>
</table>
