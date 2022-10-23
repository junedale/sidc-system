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
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <tr class="text-center">
                            <th colspan="4">Request Details</th>
                        </tr>

                        <tr>
                            <th colspan="1" class="col-4">Control Number</th>
                            <td colspan="3"><?= $request['id'] ?? '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1" class="col-4">Requester</th>
                            <td colspan="3"><?= $request['name'] ?? '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Department</th>
                            <td colspan="3"><?= $request['dept_name'] ?? '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Request Date</th>
                            <td colspan="3"><?= isset($request['date_added']) ? Time::parse($request['date_added'])->toLocalizedString('MMMM d, yyyy') : '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Purpose</th>
                            <td colspan="3"><?= $request['purpose'] ?? '' ?></td>
                        </tr>

                        <tr class="text-center">
                            <th colspan="4">Approval Details</th>
                        </tr>

                        <tr>
                            <th>Reviewed By</th>
                            <td>

                                <?php if(isset($request['status']) && (int) $request['status'] === 2): ?>

                                    <?= to_status($request['status']) ?>

                                <?php else: ?>

                                    <?php if(isset($request['rev_by'])): ?>

                                        <span><?= $request['rev_by'] ?></span>
                                        <span><?= to_status($request['initial_app'] ?? '') ?></span>

                                    <?php endif; ?>

                                <?php endif; ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Approved By</th>
                            <td>

                                <?php if(isset($request['status']) && (int) $request['status'] === 2): ?>

                                    <?= to_status($request['status']) ?>

                                <?php else: ?>

                                    <?php if(isset($request['app_by'])): ?>

                                        <span><?= $request['app_by'] ?></span>
                                        <span><?= to_status($request['final_app'] ?? '') ?></span>

                                    <?php endif; ?>

                                <?php endif; ?>
                            </td>
                        </tr>

                    </table>

                </div>


                <?php if((isset($approval) && $approval) && (has_permission('approval.stock.initial') || has_permission('approval.stock.final'))): ?>

                    <?= form_open(current_url(), ['class' => 'd-flex flex-column flex-md-column flex-lg-row gap-1']) ?>

                    <?= form_button(['name' => 'approval', 'value' => 1, 'content' => 'Approve Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 w-100']) ?>
                    <?= form_button(['name' => 'approval', 'value' => 0, 'content' => 'Disapprove Request', 'type' => 'submit', 'class' => 'btn btn-danger rounded-0 w-100']) ?>

                    <?= form_close() ?>

                <?php endif; ?>

            </div>
        </div>
    </section>

    <section>
        <div class="card border-0 shadow rounded-0">
            <div class="card-body">


                <div class="table-responsive">

                    <table class="table table-bordered text-center">

                        <thead>
                            <tr class="">
                                <th colspan="3">SR Items</th>
                            </tr>
                        </thead>

                        <thead>
                        <tr class="">
                            <th>Item</th>
                            <th>Quantity</th>

                            <?php if(has_permission('approval.stock.initial') || has_permission('approval.stock.final') || in_group('teamlead') || in_group('team-lead')): ?>

                                <th>Status</th>

                            <?php else: ?>

                                <th>Action</th>

                            <?php endif; ?>

                        </tr>
                        </thead>

                        <tbody>

                        <?php if(isset($items)): ?>

                        <?php foreach($items as $item): ?>

                            <tr>
                                <td><?= $item['item_name'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= to_status($request['status'] ?? null) ?></td>
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

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>
