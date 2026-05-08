<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 * @var string $nonce
 * @var string $email
 */
$resetUrl = $this->Url->build(['controller' => 'Auth', 'action' => 'resetPassword', $nonce], ['fullBase' => true]);
?>
<!-- Eyebrow -->
<p style="margin:0 0 6px;font-family:Arial,sans-serif;font-size:10px;
           letter-spacing:0.18em;text-transform:uppercase;color:#c9a84c;">
  Account Security
</p>

<!-- Heading -->
<h1 style="margin:0 0 20px;font-family:Georgia,'Times New Roman',serif;
            font-size:26px;font-weight:400;color:#3d1f5e;line-height:1.2;">
  Reset your password.
</h1>

<p style="margin:0 0 16px;font-family:Arial,sans-serif;font-size:14px;
           color:#555555;line-height:1.8;">
  Hi <?= h($first_name) ?>, we received a request to reset the password for your Veloura Jewels account.
</p>

<p style="margin:0 0 32px;font-family:Arial,sans-serif;font-size:14px;
           color:#555555;line-height:1.8;">
  This link is valid for 7 days. If you didn't request a password reset, you can safely ignore this email.
</p>

<!-- CTA -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
  <tr>
    <td style="background-color:#3d1f5e;border-radius:2px;">
      <a href="<?= h($resetUrl) ?>"
         style="display:inline-block;padding:14px 36px;font-family:Arial,sans-serif;
                font-size:11px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;
                color:#c9a84c;text-decoration:none;">
        Reset Password
      </a>
    </td>
  </tr>
</table>

<!-- Fallback link -->
<p style="margin:0;font-family:Arial,sans-serif;font-size:12px;color:#9b8fa0;line-height:1.7;">
  Or copy this link into your browser:<br>
  <a href="<?= h($resetUrl) ?>"
     style="color:#9b8fa0;word-break:break-all;"><?= h($resetUrl) ?></a>
</p>
