<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered rounded-0">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= session()->get('type') ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= session()->get('message') ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Close</button>
                <button type="button" id="understood" class="btn btn-primary rounded-0" data-bs-dismiss="modal">Understood</button>
            </div>
        </div>
    </div>
</div>