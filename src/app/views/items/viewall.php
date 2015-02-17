<form action="../items/add" method="post">
  <input type="text" value="" onclick="this.value=''" name="item">
  <input type="submit" value="add">
</form>

<?php

if (sizeof($list) > 0) {

  foreach ($list as $todoitem): ?>
    <a class="big" href="../items/view/<?php echo $todoitem['id']?>/<?php echo strtolower(str_replace(" ","-",$todoitem['item_name']))?>">
      <span class="item">
        <?php echo $todoitem['item_name']?>
      </span>
    </a><br/>
  <?php endforeach;

} else {
  echo 'No Items found.';
}
