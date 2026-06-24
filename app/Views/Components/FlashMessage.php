<?php
$success = session()->getFlashdata('success');
$error   = session()->getFlashdata('error');
$errors  = session()->getFlashdata('errors');
?>

<?php if ($success) : ?>
    <div class="FlashMessage isSuccess"><?= esc($success) ?></div>
<?php endif; ?>

<?php if ($error) : ?>
    <div class="FlashMessage isError"><?= esc($error) ?></div>
<?php endif; ?>

<?php if (is_array($errors) && $errors !== []) : ?>
    <div class="FlashMessage isError">
        <?php foreach ($errors as $message) : ?>
            <div><?= esc($message) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
