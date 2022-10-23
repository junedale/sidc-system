<?= $this->extend('App\Views\layouts\auth') ?>


<?= $this->section('main') ?>

<div class="text-center">
    <span class="display-1">403 - Forbidden</span>
    <br>
    <span class="text-muted">You don't have access to this resource</span>
</div>

<?= $this->endSection() ?>
