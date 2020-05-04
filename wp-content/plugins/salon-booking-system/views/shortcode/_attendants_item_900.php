<label for="<?php echo $elemId ?>" class="col-xs-12">
    <div class="sln-attendant">
        <div class="row">
            <div class="col-xs-12 col-sm-1 sln-radiobox sln-steps-check sln-attendant-check <?php $isChecked  ? 'is-checked' : '' ?>">
                <?php SLN_Form::fieldRadioboxForGroup($field, $field, $attendant->getId(), $isChecked, $settings) ?>
            </div>
            <div class="col-xs-8 col-sm-3 col-md-3 sln-steps-thumb sln-attendant-thumb">
                    <?php echo $thumb ?>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <div class="row sln-steps-info sln-attendant-info">
                    <div class="col-xs-12">
                            <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                    </div>
                </div>
                <div class="row sln-steps-description sln-attendant-description">
                    <div class="col-xs-12">
                            <p><?php echo $attendant->getContent() ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <?php echo $tplErrors ?>
            </div>
            <div class="sln-service__fkbkg"></div>
        </div>
    </div>
</label>
<div class="clearfix"></div>