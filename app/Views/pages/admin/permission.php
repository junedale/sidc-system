<div class="mb-3">

    <h5 class="card-title lead mb-3">Account Permission</h5>
    
    <div class="row">

        <div class="col">

            <div class="mb-3">

                <h6 class="mb-3">User Permissions</h6>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'form-create', 'value' => 'form.create', 'checked' => is_checked('form.create', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Create Request', 'form-create', ['class' => 'form-check-label']) ?>

                </div>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'form-modify', 'value' => 'form.edit', 'checked' => is_checked('form.edit', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Modify Request', 'form-modify', ['class' => 'form-check-label']) ?>

                </div>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'form-cancel', 'value' => 'form.cancel', 'checked' => is_checked('form.cancel', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Cancel Request', 'form-cancel', ['class' => 'form-check-label']) ?>

                </div>

            </div>

            <div class="">

                <h6 class="mb-3">Special Permissions</h6>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'initial-approval', 'value' => 'approval.initial', 'checked' => is_checked('approval.initial', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Initial Request Approval', 'initial-approval', ['class' => 'form-check-label']) ?>

                </div>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'final-approval', 'value' => 'approval.final', 'checked' => is_checked('approval.final', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Final Request Approval', 'final-approval', ['class' => 'form-check-label']) ?>

                </div>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'initial-stock-approval', 'value' => 'approval.stock.initial', 'checked' => is_checked('approval.stock.initial', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Initial Stock Request Approval', 'initial-stock-approval', ['class' => 'form-check-label']) ?>

                </div>

                <div class="form-check">

                    <?= form_checkbox(['name' => 'permissions[]', 'id' => 'final-stock-approval', 'value' => 'approval.stock.final', 'checked' => is_checked('approval.stock.final', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                    <?= form_label('Final Stock Request Approval', 'final-stock-approval', ['class' => 'form-check-label']) ?>

                </div>

            </div>

        </div>

        <div class="col">

            <h6 class="mb-3">Admin Permissions</h6>

            <div class="form-check">

                <?= form_checkbox(['name' => 'permissions[]', 'id' => 'admin-access', 'value' => 'admin.access', 'checked' => is_checked('admin.access', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                <?= form_label('Admin Access', 'admin-access', ['class' => 'form-check-label']) ?>

            </div>

            <div class="form-check">

                <?= form_checkbox(['name' => 'permissions[]', 'id' => 'user-create', 'value' => 'user.create', 'checked' => is_checked('user.create', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                <?= form_label('Create User Account', 'user-create', ['class' => 'form-check-label']) ?>

            </div>

            <div class="form-check">

                <?= form_checkbox(['name' => 'permissions[]', 'id' => 'user-modify', 'value' => 'user.edit', 'checked' => is_checked('user.edit', $permission ?? []), 'class' => 'form-check-input rounded-0']) ?>
                <?= form_label('Modify User Account', 'user-modify', ['class' => 'form-check-label']) ?>

            </div>

            <div class="form-check">

                <?= form_checkbox(['name' => 'permissions[]', 'id' => 'user-disable', 'value' => 'user.disable',  'checked' => is_checked('user.disable', $permission ?? []),'class' => 'form-check-input rounded-0']) ?>
                <?= form_label('Disable User Account', 'user-disable', ['class' => 'form-check-label']) ?>

            </div>

        </div>
        
    </div>

</div>