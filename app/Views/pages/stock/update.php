<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>


<?= $this->section('main') ?>

<section class="mb-4">
    <div class="card border-0 shadow rounded-0">

        <?= form_open(current_url(), ['class' => 'card-body']) ?>

        <div class="mb-3">

            <?= form_label('Purpose', 'purpose', ['class' => 'form-label']) ?>
            <?= form_textarea(['name' => 'purpose', 'id' => 'purpose', 'placeholder' => 'Write something here', 'value' => $request['purpose'] ?? '', 'class' => 'form-control rounded-0', 'rows' => 3]) ?>
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
                <?= isset($validation) ? display_error($validation, 'quantity') : ''  ?>

            </div>

        </div>

        <div class="d-flex gap-1">
            <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 w-100']) ?>
            <?= form_button(['content' => 'Cancel Request', 'id' => 'cancel-request', 'type' => 'button', 'value' => $request['id'] ?? '', 'class' => 'btn btn-danger rounded-0 w-100']) ?>
        </div>

        <?= form_close() ?>

    </div>
</section>

<section>
    <div class="card border-0 shadow rounded-0">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-striped align-middle text-center">

                    <thead>
                    <tr>
                        <th scope="col">Item Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if(!empty($items)): ?>

                    <?php foreach($items as $item): ?>

                        <tr>
                            <td><?= $item['item_name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><button type="button" value="<?= $item['id'] ?>" class="btn btn-outline-danger rounded-0 delete-item">Delete Item</button></td>
                        </tr>

                    <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>
        </div>
    </div>
</section>

<?= $this->include('App\Views\components\modal') ?>
<?= $this->include('App\Views\scripts\stock\buttons') ?>

<?= $this->endSection() ?>
