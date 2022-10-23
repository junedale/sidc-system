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
                    <?= form_input(['name' => 'ot_date', 'id' => 'overtime', 'type' => 'date', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'ot_date') : '' ?></span>

                </div>

                <div class="mb-3">

                    <?= form_label('Purpose', 'purpose', ['class' => 'form-label']) ?>
                    <?= form_textarea(['name' => 'purpose', 'id' => 'purpose', 'placeholder' => 'Write something here', 'rows' => 2, 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'purpose') : '' ?></span>

                </div>

                <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 col-12']) ?>

            <?= form_close() ?>

        </div>
    </section>
</main>

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>
