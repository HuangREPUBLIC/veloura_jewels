<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 */
$shopUrl = $this->Url->build('/jewelry', ['fullBase' => true]);
?>
<p style="margin:0 0 10px;font-family:Arial,sans-serif;font-size:10px;
           letter-spacing:0.2em;text-transform:uppercase;color:#e1a95e;">
  Welcome
</p>

<h1 style="margin:0 0 24px;font-family:Georgia,'Times New Roman',serif;
            font-size:28px;font-weight:400;color:#284d40;line-height:1.2;">
  Your account is ready.
</h1>

<p style="margin:0 0 16px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  Hi <?= h($first_name) ?>, welcome to Veloura Jewels. Your account has been created
  and you&rsquo;re all set to explore our collection.
</p>

<p style="margin:0 0 36px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  Discover fine jewellery and home d&eacute;cor crafted with care.
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0"
       align="center" style="margin:0 auto;">
  <tr>
    <td align="center" style="background-color:#284d40;border-radius:2px;">
      <a href="<?= h($shopUrl) ?>"
         style="display:inline-block;padding:15px 44px;
                font-family:Arial,Helvetica,sans-serif;
                font-size:11px;font-weight:700;
                letter-spacing:0.16em;text-transform:uppercase;
                color:#f0ede4;text-decoration:none;">
        Browse Collection
      </a>
    </td>
  </tr>
</table>
