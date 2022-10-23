<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>



<?= $this->section('main') ?>

<main>

    <section>
        <div class="card border-0 shadow rounded-0">

            <?= form_open(current_url(), ['class' => 'card-body']) ?>

                <h5 class="card-title lead mb-3">Account Details</h5>

                <div class="row mb-3">

                    <div class="col-12 col-md-4 col-lg mb-3">

                        <?= form_label('Employee Number', 'emp-no', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'emp_no', 'emp-no', 'placeholder' => '0000000', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'emp_no') : '' ?></span>

                    </div>

                    <div class="col-12 col-md-8 col-lg">

                        <?= form_label('Full Name', 'emp-name', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'name', 'id' => 'emp-name', 'placeholder' => 'John Doe', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'name') : '' ?></span>

                    </div>

                </div>
            
                <div class="row mb-3">

                    <div class="col-12 col-md-6 col-lg mb-3">

                        <?= form_label('Username', 'username', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'username', 'id' => 'username', 'placeholder' => 'john0', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'username') : '' ?></span>

                    </div>

                    <div class="col-12 col-md-6 col-lg">

                        <?= form_label('Email', 'email', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'email', 'id' => 'email', 'type' => 'email', 'placeholder' => 'johndoe@gmail.com', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>

                    </div>

                </div>

                <div class="mb-3">

                    <?= form_label('Department', 'department', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'department', 'id' => 'department', 'options' => $department ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'department') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Position', 'position', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'position', 'id' => 'position', 'options' => $position ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'position') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Superior', 'superior', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'superior', 'id' => 'superior', 'options' => $superior ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'superior') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('User Group', 'user-group', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'user_group', 'id' => 'user-group', 'options' => $group ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'user_group') : '' ?></span>

                </div>

                <?= $this->include('App\Views\pages\admin\permission') ?>

                <?= form_button(['content' => 'Create User Account', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 col-12']) ?>

            <?= form_close() ?>

        </div>
    </section>
</main>

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>
