<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<section>
    <div class="card border-0 shadow rounded-0">

        <?= form_open(current_url(), ['class' => 'card-body']) ?>

        <div class="mb-3">

            <?= form_label('Purpose', 'purpose', ['class' => 'form-label']) ?>
            <?= form_textarea(['name' => 'purpose', 'id' => 'purpose', 'placeholder' => 'Write something here', 'class' => 'form-control rounded-0', 'rows' => 3]) ?>
            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'purpose') : '' ?></span>

        </div>

        <div class="row mb-3">

            <div class="col-12 col-lg mb-3 mb-lg-0">

                <?= form_label('Item', 'item', ['class' => 'form-label']) ?>
                <?= form_input(['name' => 'item_name', 'id' => 'item', 'placeholder' => 'Item name', 'class' => 'form-control rounded-0']) ?>
                <span class="text-danger"><?= isset($validation) ? display_error($validation, 'item_name') : '' ?></span>

            </div>

            <div class="col-12 col-lg mb-3 mb-lg-0">

                <?= form_label('Quantity', 'quantity', ['class' => 'form-label']) ?>
                <?= form_input(['name' => 'quantity', 'id' => 'quantity', 'type' => 'number', 'min' => 1, 'value' => 1, 'class' => 'form-control rounded-0']) ?>
                <span class="text-danger"><?= isset($validation) ? display_error($validation, 'quantity') : '' ?></span>

            </div>

        </div>

        <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 col-12']) ?>

        <?= form_close() ?>

    </div>
</section>

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>
