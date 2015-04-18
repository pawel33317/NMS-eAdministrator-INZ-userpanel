<?php

if (isset($this->info)) {
    foreach ($this->info as $info) {
        echo '<div class="alert alert-' . $info['type'] . '"><strong>' . @$info['boldtext'] . '</strong>' . @$info['text'] . '</div>';
    }
}
/*
  if ($typ == 1)
  $typ = "alert-success";
  if ($typ == 2)
  $typ = "alert-info";
  if ($typ == 3)
  $typ = "alert-warning";
  if ($typ == 4)
  $typ = "alert-danger";

 */
?>


