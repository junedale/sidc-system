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
                            <td colspan="3"><?= isset($request['date_added']) ? Time::parse($request['date_added'])->toLocalizedString('MMMM d, YYYY') : '' ?></td>
                        </tr>

                        <tr>
                            <th colspan="1">Transit </th>
                            <td colspan="3"><?= $request['transit'] ?? '' ?></td>
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

    <section>
        <div class="card border-0 shadow rounded-0">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table text-center align-middle table-bordered">

                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col span="2">
                            <col>
                        </colgroup>

                        <thead class="">

                        <tr>
                            <th scope="col" rowspan="2">Date</th>
                            <th scope="col" rowspan="2">Destination</th>
                            <th scope="col" colspan="2">Time</th>
                            <th scope="col" rowspan="2">Purpose</th>
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
