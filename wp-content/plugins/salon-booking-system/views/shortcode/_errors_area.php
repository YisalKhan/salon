<?php if ($size == '400') : ?>
    <?php if ($errors) : ?>
        <div class="col-xs-12">
            <span class="errors-area" data-class="sln-alert sln-alert-medium sln-alert--problem">
                <div class="sln-alert sln-alert-medium sln-alert--problem sln-service-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error ?></p>
                    <?php endforeach ?>
                </div>
            </span>
        </div>
    <?php endif ?>
<?php else: ?>

    <?php if ($errors) : ?>
        <div class="row">
            <div class="col-xs-12 col-sm-11 col-sm-offset-4">
                <span class="errors-area" data-class="sln-alert sln-alert-medium sln-alert--problem">
                    <div class="sln-alert sln-alert-medium sln-alert--problem sln-service-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error ?></p>
                        <?php endforeach ?>
                    </div>
                </span>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>
