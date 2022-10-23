<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<main>

    <section class="mb-4">
        <div class="card border-0 shadow rounded-0">

            <?= form_open(current_url(), ['class' => 'card-body']) ?>

            <div class="mb-3">

                <?= form_label('Transit type', 'transit', ['class' => 'form-label']) ?>
                <?= form_dropdown(['name' => 'transit', 'id' => 'transit', 'options' => $transit ?? '', 'selected' => $request['transit'] ?? '', 'class' => 'form-select rounded-0']) ?>
                <span class="text-danger"><?= isset($validation) ? display_error($validation, 'transit') : '' ?></span>

            </div>

            <div class="row mb-3">

                <div class="col-12 col-lg mb-3">

                    <?= form_label('Departure Date', 'sched', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'sched_date', 'id' => 'sched', 'type' => 'date', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'sched_date') : '' ?></span>

                </div>

                <div class="col-12 col-lg">

                    <?= form_label('Destination', 'destination', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'destination', 'id' => 'destination', 'placeholder' => 'Batangas City', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'destination') : '' ?></span>

                </div>

            </div>

            <div class="mb-3">

                <?= form_label('Purpose', 'purpose', ['class' => 'form-label']) ?>
                <?= form_textarea(['name' => 'purpose', 'id' => 'purpose', 'rows' => 2, 'placeholder' => 'Write something here', 'class' => 'form-control rounded-0']) ?>
                <span class="text-danger"><?= isset($validation) ? display_error($validation, 'purpose') : '' ?></span>

            </div>


            <div class="d-flex gap-1">

                <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 w-100']) ?>
                <?= form_button(['content' => 'Cancel Request', 'type' => 'button', 'id' => 'cancel-request', 'value' => $request['id'] ?? '', 'class' => 'btn btn-danger rounded-0 w-100']) ?>

            </div>

            <?= form_close() ?>

        </div>
    </section>

    <section>

        <div class="card border-0 shadow rounded-0">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle text-center">

                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col span="2">
                            <col>
                        </colgroup>

                        <thead>
                        <tr>
                            <th scope="col" rowspan="2">Date</th>
                            <th scope="col" rowspan="2">Destination</th>
                            <th scope="col" colspan="2">Time</th>
                            <th scope="col" rowspan="2">Purpose</th>
                            <th scope="col" rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th scope="col">Departure</th>
                            <th scope="col">Arrival</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if(isset($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <tr>
                                <td>
                                    <?= isset($item['sched_date']) ? Time::parse($item['sched_date'])->toLocalizedString('MMMM d, yyyy') : '' ?>
                                </td>
                                <td><?= $item['destination'] ?></td>
                                <td>
                                    <?= isset($item['departure']) ? Time::parse($item['departure'])->toLocalizedString('h:mm a') : '' ?>
                                </td>
                                <td>
                                    <?= isset($item['arrival']) ? Time::parse($item['arrival'])->toLocalizedString('h:mm a') : '' ?>
                                </td>
                                <td><?= $item['purpose'] ?></td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-outline-primary rounded-0 edit-modal" data-bs-toggle="modal" data-bs-target="#update-modal" value="<?= $item['id'] ?>">Edit</button>
                                    <button class="btn btn-outline-danger rounded-0 delete-item" value="<?= $item['id'] ?>">Delete</button>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </section>

</main>

<?= $this->include('App\Views\pages\ob\update_modal') ?>
<?= $this->include('App\Views\components\modal') ?>
<?= $this->include('App\Views\scripts\ob\buttons') ?>

<?= $this->endSection() ?>
