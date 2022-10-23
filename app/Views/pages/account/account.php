<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>


<?= $this->section('main') ?>

    <section>
        <div class="card border-0 shadow rounded-0">

            <?= form_open(current_url(), ['class' => 'card-body']) ?>

<!--                <input type="hidden" name="_method" value="PUT">-->

                <div class="mb-3">

                    <?= form_label('Username', 'username', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'username', 'id' => 'username', 'value' => $user['username'] ?? '', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"> <?= isset($validation) && !empty($validation) ? display_error($validation, 'username') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Email', 'email', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'email', 'id' => 'email', 'value' => $user['email'] ?? '', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"> <?= isset($validation) && !empty($validation) ? display_error($validation, 'email') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Password', 'password', ['class' => 'form_label']) ?>
                    <?= form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?=  isset($validation) ? display_error($validation, 'password') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Confirm Password', 'c-password', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'c_password', 'id' => 'c-password', 'type' => 'password', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"> <?= isset($validation) && !empty($validation) ? display_error($validation, 'c_password') : '' ?></span>

                </div>

                <?= form_button(['content' => 'Save Changes', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 col-12']) ?>

            <?= form_close() ?>

        </div>
    </section>

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>