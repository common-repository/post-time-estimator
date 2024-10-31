<?php

function post_timer_estimator_checkTheOption($post_id)
{
  $result = get_post_meta($post_id, 'inani_show_timer', true);
  if(isset($result) && $result == 1)
      echo "checked";
}
?>

<label class="selectit">
    <input
        value="1"
        name="show_timer"
        id="show_timer"
        <?php post_timer_estimator_checkTheOption(get_the_ID()); ?>
        type="checkbox"> Show Timer
</label>
