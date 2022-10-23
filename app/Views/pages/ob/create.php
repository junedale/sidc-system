<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<main>

    <section>
        <div class="card border-0 shadow rounded-0">

            <?= form_open('', ['class' => 'card-body']) ?>

            <div class="mb-3">
                <?= form_label('Transit type', 'transit', ['class' => 'form-label']) ?>
                <?= form_dropdown(['name' => 'transit', 'id' => 'transit', 'options' => $transit ?? '', 'class' => 'form-select rounded-0']) ?>
            </div>

            <?= form_button(['content' => 'Save Request', 'type' => 'submit', 'class' => 'btn btn-primary rounded-0 col-12']) ?>

            <?= form_close() ?>

        </div>
    </section>

</main>

<?= $this->include('App\Views\components\modal') ?>

<?= $this->endSection() ?>
