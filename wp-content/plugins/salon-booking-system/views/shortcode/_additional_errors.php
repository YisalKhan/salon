<?php if ($additional_errors): ?>
<div class="row sln-box--main--flattop">
    <div class="col-xs-12">
        <?php foreach ($additional_errors as $error): ?>
            <div class="sln-alert sln-alert--general sln-alert--problem"><?php echo $error ?></div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>