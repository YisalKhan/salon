<?php
/**
 * @var array $data
 */
?>

<table class="table sln-salon-booking-calendar" data-attrs="<?php echo esc_attr(json_encode($data['attrs'])); ?>">
    <thead>
    <tr>
        <td class="sln-sc--cal__date"><?php _e('Date','salon-booking-system'); ?></td>
        <?php foreach($data['attendants'] as $att): ?>
        <td class="sln-sc--cal__attendant <?php echo $att['color'] ?>"><?php echo $att['name'] ?></td>
        <?php endforeach ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data['dates'] as $k => $date): ?>
        <tr>
            <td data-th="<?php _e('Date','salon-booking-system'); ?>" class="sln-sc--cal__date"><?php echo $date ?></td>
            <?php foreach($data['attendants'] as $att): ?>
                <td data-th="<?php echo $att['name'];?>" class="sln-sc--cal__attendant <?php echo $att['color'] ?>">
                    <?php if(isset($att['events'][$k])): ?>
                        <?php foreach($att['events'][$k] as $event): ?>
                            <div style="text-transform: none;"><span data-toggle="tooltip" data-placement="right" data-html="true" title="<?php echo $event['desc'] ?>"><?php echo $event['title'] ?></span></div>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
    <?php ?>
    </tbody>
</table>