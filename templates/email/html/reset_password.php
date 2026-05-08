<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 * @var string $nonce
 */
$resetUrl = $this->Url->build(['controller' => 'Auth', 'action' => 'resetPassword', $nonce], ['fullBase' => true]);
?>
<p style="margin:0 0 10px;font-family:Arial,sans-serif;font-size:10px;
           letter-spacing:0.2em;text-transform:uppercase;color:#e1a95e;">
  Account Security
</p>

<h1 style="margin:0 0 24px;font-family:Georgia,'Times New Roman',serif;
            font-size:28px;font-weight:400;color:#786c3b;line-height:1.2;">
  Reset your password.
</h1>

<p style="margin:0 0 16px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  Hi <?= h($first_name) ?>, we received a request to reset the password for your
  Veloura Jewels account.
</p>

<p style="margin:0 0 36px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  This link is valid for <strong style="color:#786c3b;">7 days</strong>.
  If you didn&rsquo;t request a reset, you can safely ignore this email.
</p>

<!-- CTA button -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0"
       align="center" style="margin:0 auto 32px;">
  <tr>
    <td align="center" style="background-color:#786c3b;border-radius:2px;">
      <a href="<?= h($resetUrl) ?>" target="_blank"
         style="display:inline-block;padding:15px 44px;
                font-family:Arial,Helvetica,sans-serif;
                font-size:11px;font-weight:700;
                letter-spacing:0.16em;text-transform:uppercase;
                color:#f0ede4;text-decoration:none;">
        Reset Password
      </a>
    </td>
  </tr>
</table>

<!-- Divider -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="margin-bottom:24px;">
  <tr>
    <td bgcolor="#e8e3d8" height="1"
        style="background-color:#e8e3d8;height:1px;font-size:0;line-height:0;"></td>
  </tr>
</table>

<!-- Fallback link -->
<p style="margin:0;font-family:Arial,sans-serif;font-size:12px;
           color:#9b9080;line-height:1.7;">
  Or copy this link into your browser:<br>
  <a href="<?= h($resetUrl) ?>"
     style="color:#9b9080;word-break:break-all;text-decoration:underline;">
    <?= h($resetUrl) ?>
  </a>
</p>
