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

<style>

    #login {
        width: 90%;
    }

    @media only screen and (min-width: 576px) {
        #login {
            width: 80%;
        }
    }
    @media only screen and (min-width: 768px) {
        #login {
            width: 60%;
        }
    }

    @media only screen and (min-width: 992px) {
        #login {
            width: 33.33333%;
        }
    }


</style>

<body>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <?= $this->renderSection('main') ?>
    </div>
</body>
</html>