<?= $this->extend('App\Views\layouts\auth') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>


<?= $this->section('main') ?>

<div id="login">
    <div class="mb-5">
        <h1 class="p-0 m-0">SIDC Forms</h1>
        <h6 class="text-muted">Login to continue</h6>
    </div>

    <?php if(!empty(session()->getFlashdata('message'))): ?>

        <div class="alert alert-danger mb-3 rounded-0" role="alert">
            <?= session()->getFlashdata('message') ?>
        </div>

    <?php endif; ?>

    <?= form_open(current_url()) ?>

    <div class="mb-3">

        <?= form_label('Username', 'username', ['class' => 'form-label']) ?>
        <?= form_input(['name' => 'username', 'id' => 'username', 'class' => 'form-control rounded-0', 'placeholder' => 'johndoe0']) ?>
        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'username') : '' ?></span>

    </div>

    <div class="mb-3">

        <?= form_label('Password', 'password', ['class' => 'form-label']) ?>
        <?= form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control rounded-0', 'placeholder' => '********']) ?>
        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>

    </div>

    <?= form_button(['content' => 'SIGN IN', 'type' => 'submit', 'class' => 'btn btn-primary col-12 rounded-0']) ?>

    <?= form_close() ?>

</div>

<div class="position-absolute bottom-0 start-50 translate-middle-x w-100 text-center">
    <h6 class="p-0 m-0 text-muted">Created by: Junedale Nicko Gayeta</h6>
</div>


<?= $this->endSection() ?>
