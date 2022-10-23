<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>



<?= $this->section('main') ?>

<main>

    <section class="mb-5">
        <div class="card border-0 shadow rounded-0">
            <div class="card-body">
                <h5 class="card-title lead">Reset Password</h5>
                <button id="reset-password" value="<?= $user['emp_no'] ?? '' ?>" class="btn btn-danger rounded-0 col-12">Reset</button>
            </div>
        </div>
    </section>

    <section>
        <div class="card border-0 shadow rounded-0">

            <?= form_open(current_url(), ['class' => 'card-body']) ?>

                <h5 class="card-title lead mb-3">Account Details</h5>

                <div class="row mb-3">

                    <div class="col-12 col-md-4 col-lg mb-3">

                        <?= form_label('Employee Number', 'emp-no', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'emp_no', 'emp-no', 'placeholder' => '0000000', 'value' => $user['emp_no'] ?? '', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'emp_no') : '' ?></span>

                    </div>

                    <div class="col-12 col-md-8 col-lg">

                        <?= form_label('Full Name', 'emp-name', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'name', 'id' => 'emp-name', 'placeholder' => 'John Doe', 'value' => $user['name'] ?? '', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'name') : '' ?></span>

                    </div>

                </div>
            
                <div class="row mb-3">

                    <div class="col-12 col-md-6 col-lg mb-3">

                        <?= form_label('Username', 'username', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'username', 'id' => 'username', 'placeholder' => 'john0', 'value' => $user['username'] ?? '', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'username') : '' ?></span>

                    </div>

                    <div class="col-12 col-md-6 col-lg">

                        <?= form_label('Email', 'email', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'email', 'id' => 'email', 'type' => 'email', 'placeholder' => 'johndoe@gmail.com', 'value' => $user['email'] ?? '', 'class' => 'form-control rounded-0']) ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>

                    </div>

                </div>

                <div class="mb-3">

                    <?= form_label('Department', 'department', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'department', 'id' => 'department', 'options' => $department ?? '', 'selected' => $user['department'] ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'department') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Position', 'position', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'position', 'id' => 'position', 'options' => $position ?? '', 'selected' => $user['position'] ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'position') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Superior', 'superior', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'superior', 'id' => 'superior', 'options' => $superior ?? '', 'selected' => $user['superior'] ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'superior') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('User Group', 'user-group', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'user_group', 'id' => 'user-group', 'options' => $group ?? '', 'selected' => $user['user_group'] ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'user-group') : '' ?></span>

                </div>

                <?= $this->include('App\Views\pages\admin\permission') ?>

                <?= form_button(['content' => 'Save Changes', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 col-12']) ?>

            <?= form_close() ?>

        </div>
    </section>
</main>

<?= $this->include('App\Views\components\modal') ?>
<?= $this->include('App\Views\scripts\admin\buttons') ?>

<?= $this->endSection() ?>
