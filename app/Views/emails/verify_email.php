<p>Hi <?= esc($name) ?>,</p>
<p>Please confirm your email address to finish setting up your account:</p>
<p><a href="<?= esc($url, 'attr') ?>">Verify my email</a></p>
<p>This link expires in 24 hours. If you didn't sign up, ignore this message.</p>