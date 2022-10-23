<nav class="py-4 m-0" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">

        <?php if(isset($crumb)): ?>

        <?php foreach($crumb as $index => $item): ?>

            <?php if($index !== array_key_last($crumb)): ?>

                    <li class="breadcrumb-item"><a href="<?= base_url($item['url']) ?>" class="text-decoration-none"><?= $item['title'] ?></a></li>

            <?php else: ?>

                    <li class="breadcrumb-item active"><?= $item['title'] ?></li>

            <?php endif; ?>

        <?php endforeach; ?>

        <?php endif; ?>

    </ol>
</nav>
