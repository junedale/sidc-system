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

                <?= 'Message:'. session()->getFlashdata('message') ?>

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
                            <td colspan="3"><?= $request['department'] ?? '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Request Date</th>
                            <td colspan="3"><?= isset($request['date_added']) ? Time::parse($request['date_added'])->toLocalizedString('MMMM d, yyyy') : '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Leave Date</th>
                            <td colspan="3"><?= isset($request['date_added']) ? Time::parse($request['leave_date'])->toLocalizedString('MMMM d, yyyy') : '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Reason</th>
                            <td colspan="3"><?= $request['reason'] ?? '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Explanation</th>
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

                <?= session()->getFlashdata('message') ?>

                <?php if((isset($approval) && $approval) && (has_permission('approval.initial') || has_permission('approval.final'))): ?>

                    <?= form_open(current_url(), ['class' => 'd-flex gap-1']) ?>

                        <input type="hidden" name="_method" value="PUT">

                        <?= form_button(['name' => 'approval', 'value' => 1, 'content' => 'Approve Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 w-100']) ?>
                        <?= form_button(['name' => 'approval', 'value' => 0, 'content' => 'Disapprove Request', 'type' => 'submit', 'class' => 'btn btn-danger rounded-0 w-100']) ?>


                    <?= form_close() ?>


                <?php endif; ?>

            </div>
        </div>
    </section>

</main>

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>
