<?php foreach ( $a_blog as $row ): ?>
  <?= $row['date'] ?> | <?= $row['title'] ?>
  <div style="color:green">
    <?= $row['text'] ?>
  </div>
<?php endforeach ?>
