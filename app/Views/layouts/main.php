<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= link_tag('sass/main.css') ?>
    <title><?= $this->renderSection('title') ?></title>
</head>
<body class="">
<?= $this->include('App\Views\components\navigation') ?>

<div class="container-md vh-100">

    <?= view('App\Views\components\breadcrumb') ?>

    <main class="pb-4">
        <?= $this->renderSection('main') ?>
    </main>

</div>

<?= script_tag('bootstrap/dist/js/bootstrap.bundle.js') ?>

<?php if(session()->get('modal')): ?>

    <script>
        const modal = bootstrap.Modal.getOrCreateInstance('#staticBackdrop', {})
        modal.show()

    </script>

    <?php session()->remove(['message', 'type', 'modal']); ?>

<?php endif; ?>


</body>
</html>