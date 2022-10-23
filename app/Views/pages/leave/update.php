<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<main>

    <section>
        <div class="card border-0 shadow rounded-0">

            <?= form_open(current_url(), ['class' => 'card-body']) ?>

                <input type="hidden" name="_method" value="PUT">

                <div class="mb-3">

                    <?= form_label('Leave Date', 'leave', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'leave_date', 'id' => 'leave', 'type' => 'date', 'value' => $request['leave_date'] ?? '', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'leave_date') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Reason', 'reason', ['class' => 'form-label']) ?>
                    <?= form_dropdown(['name' => 'reason', 'reason', 'options' => $type  ?? '', 'selected' => $request['reason'] ?? '', 'class' => 'form-select rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'reason') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Explanation', 'explanation', ['class' => 'form-label']) ?>
                    <?= form_textarea(['name' => 'purpose', 'id' => 'explanation', 'placeholder' => 'Write something here', 'value' => $request['purpose'] ?? '', 'rows' => 2, 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'purpose') : '' ?></span>

                </div>

                <div class="d-flex gap-1">

                    <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 w-100']) ?>
                    <?= form_button(['content' => 'Cancel Request', 'value' => $request['id'] ?? '', 'type' => 'button', 'id' => 'cancel-request', 'class' => 'btn btn-danger rounded-0 w-100']) ?>

                </div>

            <?= form_close() ?>

        </div>
    </section>
</main>

<?= $this->include('App\Views\components\modal') ?>
<?= $this->include('App\Views\scripts\leave\buttons') ?>

<?= $this->endSection() ?>
