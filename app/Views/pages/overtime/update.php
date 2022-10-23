<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<main>

    <section>
        <div class="card border-0 shadow rounded-0">

            <?= form_open(current_url(), ['class' => 'card-body']) ?>

                <div class="mb-3">

                    <?= form_label('Overtime Date', 'overtime', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'ot_date', 'id' => 'overtime', 'type' => 'date', 'value' => $request['ot_date'] ?? '', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'ot_date') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Purpose', 'purpose', ['class' => 'form-label']) ?>
                    <?= form_textarea(['name' => 'purpose', 'id' => 'purpose', 'placeholder' => 'Write something here', 'value' => $request['purpose'] ?? '', 'rows' => 2, 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'purpose') : '' ?></span>

                </div>

                <div class="d-flex gap-1">

                    <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 w-100']) ?>
                    <?= form_button(['content' => 'Cancel Request', 'type' => 'button', 'id' => 'cancel-request', 'value' => $request['id'] ?? '', 'class' => 'btn btn-danger rounded-0 w-100']) ?>


                </div>

            <?= form_close() ?>

        </div>
    </section>
</main>

<?= $this->include('App\Views\components\modal') ?>
<?= $this->include('App\Views\scripts\overtime\buttons') ?>

<?= $this->endSection() ?>
