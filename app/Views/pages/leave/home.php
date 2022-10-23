<?= $this->extend('App\Views\layouts\main') ?>

<?= $this->section('title') ?>
<?= $title ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<main>

    <section class="mb-5">

        <div class="row">

            <div class="col-12 mb-3 col-lg">
                <?= view('App\Views\components\card', ['title' => 'Total Request', 'stat' => $stats['total'] ?? '--']) ?>
            </div>

        </div>

        <div class="row">

            <div class="col-12 mb-3 col-lg">
                <?= view('App\Views\components\card', ['title' => 'Approved Request', 'stat' => $stats['approved'] ?? '--']) ?>
            </div>

            <div class="col-12 mb-3 col-lg">
                <?= view('App\Views\components\card', ['title' => 'Disapproved Request', 'stat' => $stats['disapproved'] ?? '--']) ?>
            </div>

            <div class="col-12 mb-3 col-lg">
                <?= view('App\Views\components\card', ['title' => 'Cancelled Request', 'stat' => $stats['cancelled'] ?? '--']) ?>
            </div>

        </div>

    </section>

    <section class="">
        <div class="card border-0 shadow rounded-0">
            <div class="card-body">

                <div class="d-flex flex-column flex-lg-row justify-content-between mb-3">
                    <h5 class="card-title lead">Leave Request List</h5>
                    <a href="<?= base_url('leave/create') ?>" class="btn btn-primary rounded-0 col-12 col-lg-auto">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                              <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                              <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                            </svg>
                        </span>
                        File Request
                    </a>
                </div>

                <table id="leave" class="table table-striped align-middle w-100">

                </table>

            </div>
        </div>
    </section>

</main>

<?= $this->include('App\Views\scripts\leave\datatable') ?>

<?= $this->endSection() ?>
