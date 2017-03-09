<?php
function commentTree( $comments, $margin = 0 )
{
  ?>
  <?php foreach ( $comments as $row ): ?>
    <div data-id="<?= $row['comment_id'] ?>" class="comment-one" style="margin-left: <?= $margin ?>px">
      User: <?= $row['user_name'] ?>
      | Date: <?= $row['comment_create'] ?>
      | Rating:
      <a class="vote" data-type="down" data-cid="<?= $row['comment_id'] ?>">-</a>
      <span class="rating"><?= $row['rating'] ?></span>

      <a class="vote" data-type="up"   data-cid="<?= $row['comment_id'] ?>">+</a>
      <br />
      Comment: <?= $row['comment_text'] ?><br />

      <a class="comment-delete" data-id="<?= $row['comment_id'] ?>">Удалить</a>
      <a class="comment-answer" data-id="<?= $row['comment_id'] ?>">Ответить</a>
      <hr />
      <div class="commnet-child">
      <?php
      if ( isset( $row['child'] ) and $row['child'] )
        commentTree( $row['child'], 30 )
      ?>
      </div>

    </div>
  <?php endforeach ?>
  <?php
}