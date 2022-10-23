<div class="modal fade" id="update-modal" data-bs-backdrop="static">

    <?= form_open('', ['class' => 'modal-dialog modal-dialog-centered']) ?>

        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Destination</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <?= form_label('Date', 'modal-date', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'sched_date', 'type' => 'date', 'id' => 'modal-date', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'sched_date') : '' ?></span>
                </div>

                <div class="mb-3">
                    <?= form_label('Destination', 'modal-destination', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'destination', 'id' => 'modal-destination', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'destination') : '' ?></span>
                </div>

                <div class="mb-3">
                    <?= form_label('Departure time', 'modal-departure', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'departure', 'type' => 'time', 'id' => 'modal-departure', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'departure') : '' ?></span>
                </div>

                <div class="mb-3">
                    <?= form_label('Arrival time', 'modal-arrival', ['class' => 'form-label']) ?>
                    <?= form_input(['name' => 'arrival', 'type' => 'time', 'id' => 'modal-arrival', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'arrival') : '' ?></span>
                </div>

                <div class="mb-3">
                    <?= form_label('Purpose', 'modal-purpose', ['class' => 'form-label']) ?>
                    <?= form_textarea(['name' => 'purpose', 'rows' => 2, 'id' => 'modal-purpose', 'class' => 'form-control rounded-0']) ?>
                    <span class="text-danger"><?= isset($validation) ? display_error($validation, 'purpose') : '' ?></span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Close</button>
                <button type="button" id="modal-save-item" class="btn btn-primary rounded-0">Save</button>
            </div>
        </div>

    <?= form_close() ?>

</div>