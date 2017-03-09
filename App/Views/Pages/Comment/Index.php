<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Comments</title>

    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" >
<!--    <link href="/comments/css/discus.css" rel="stylesheet">-->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
      csrf_token = "<?= Components\CSRF::generate() ?>";
    </script>
  </head>

  <body role="document">
    <div class="container theme-showcase" role="main">
      <div class="jumbotron">
        <h1>Комментарии</h1>
        <form style="display: none;" class="comment-form main-form form form-horizontal">
          <div id="register-alert"></div>

          <div class="form-group">
            <label class="col-sm-2 control-label">Сообщение</label>
            <div class="col-sm-10">
              <textarea name="comment_text" class="form-control"></textarea>
            </div>
          </div>

          <input type="hidden" name="comment_parent_id" value="0">

          <?php if ( ! Components\Session::getSession( 'user_id' ) ): ?>
            <div class="register-box">
              <div class="form-group">
                <label class="col-sm-2 control-label">Имя</label>
                <div class="col-sm-10">
                  <input type="text" name="comment_user_name" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">E-mail</label>
                <div class="col-sm-10">
                  <input type="text" name="comment_user_email" class="form-control">
                </div>
              </div>

              <div class="form-group comment-form-password">
                <label class="col-sm-2 control-label">Пароль</label>
                <div class="col-sm-10">
                  <input type="password" name="comment_user_password" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="comment_nauth"> Отправить анонимно
                    </label>
                  </div>
                </div>
              </div>
            </div>
          <?php endif ?>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button class="btn btn-default comment-add">Добавить</button>
            </div>
          </div>
        </form>
        <div id="comments">
          <?php /*
          <div class="comment-header">
            <div class="comment-count"><span id="comment-count"><?= $count ?></span> комментариев</div>
          </div>

          <div id="comment-box">
            <?php include_once MSF_APP . "/Views/Helpers/CommentTree.php" ?>
            <?php commentTree( $data ) ?>
          </div>
          */ ?>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script>
      $( document ).ready( function () {
        var preloader = $( '<img />' ).attr( 'src', '/comments/images/preloader.gif' ).addClass( 'center-block' );
        $( '#comments' ).html( preloader );

        $.post( '/comment/init', function ( resp ) {
          if ( resp.status )
          {
            preloader.remove();

            $( '#comments' ).html(
              '<div class="comment-header">\n\
                <div class="comment-count">\n\
                  <span id="comment-count">' + resp.result.count + '</span> комментариев\n\
                </div>\n\
              </div>'
            );

            $( '#comments' ).append(
              $('.main-form').show()
            );

            $.each( resp.result.data, function(a,b){
              addRow( b, 0, $('#comments') );
            });

          }
          else
            $( '#comments' ).html( 'Комментариев еще нет' );
        }, 'json' );

        function addRow( data, margin, target)
        {
          var child = $( '<div />' ).addClass( 'commnet-child' ).text( '' );
          var newc = $( '<div />' )
            .addClass( 'comment-one' )
            .attr( 'data-id', data.comment_id )
            .css( 'margin', '10px 0px 15px ' + margin + 'px' )
          .append( $( '<div />' ).text( 'User: ' + data.user_name ) )
          .append( $( '<div />' ).text( 'Date: ' + data.comment_create ) )
          .append(
            $( '<div />' ).text( 'Rating: ' )
            .append( $( '<span />' ).addClass( 'rating' ).text( data.rating ) )
            .append(
              $( '<button />' ).addClass( 'btn btn-default btn-xs vote' ).text( ' - ' ).attr( 'data-type', 'down' ).attr( 'data-cid', data.comment_id)
            )
            .append(
              $( '<button />' ).addClass( 'btn btn-default btn-xs vote' ).text( ' + ' ).attr( 'data-type', 'up' ).attr( 'data-cid', data.comment_id)
            )
          )
          .append(
            $( '<div />' ).append( $( '<div />' ).css('font-weight', 900).text( data.comment_text)  )
              .append(
                $( '<a />' ).addClass( 'comment-delete' ).text( 'Удалить ' ).attr( 'data-id', data.comment_id)
              )
              .append(
                $( '<a />' ).addClass( 'comment-answer' ).text( 'Ответить' ).attr( 'data-id', data.comment_id)
              )
          )
          .append( child )

          target.append(newc);

          if ( data.child )
          {
            $.each( data.child, function(a,b){
              addRow( b, 30, child );
            });
          }
        }

        $( document ).on( 'click', '.vote', function(e){
          e.preventDefault();
          var box = $(this).closest( '.comment-one' );

          var data = {
            vote_comment_id: $( this ).attr( 'data-cid' ),
            vote_type:       $( this ).attr( 'data-type' ),
            token:      csrf_token
          }

          $.post( '/comment/vote', data, function( resp ){
            if ( resp.status == 1 )
            {
              alert( resp.result.text );
              var rating = box.find('.rating').first();
              rating.text( ( ( data.vote_type == 'up' ) ? parseInt( rating.text() ) + 1 : parseInt( rating.text() ) -1 ) )
            }
            else
              alert( resp.info );
          }, 'json' );
        });

        $( document ).on( 'click', '.comment-delete', function(e){
          e.preventDefault();
          box = $( this ).closest( '.comment-one' );

          if ( confirm( 'Вы действительно хотите удалить этот комментарий?' ) )
          {
            var data = {
              comment_id: $( this ).attr( 'data-id' ),
              token:      csrf_token
            }

            $.post( '/comment/delete', data, function( resp ){
              if ( resp.status == 1 )
                box.remove();
              else
                alert( resp.info );
            }, 'json' );
          }
        });

        $( document ).on( 'click', '.comment-answer', function(e){
          var form = $( '.main-form' ).clone();

          form.removeClass( 'main-form' )
            .find('input[name="comment_parent_id"]')
            .val( $(this).attr('data-id') );
          form.insertAfter( $(this) );
        });

        $( document ).on( 'click', '.comment-add', function(e){
          e.preventDefault();

          $( '#register-alert' ).text('');
          var form = $(this).closest('form');
          var nauth = form.find('input[name="comment_nauth"]');

          if ( nauth.prop('checked') || nauth.length == 0 )
            addComment( form );
          else
          {
            var data = {
              user_name:     form.find('input[name="comment_user_name"]').val(),
              user_email:    form.find('input[name="comment_user_email"]').val(),
              user_password: form.find('input[name="comment_user_password"]').val(),
              token:         csrf_token
            }

            $.post( '/comment/register', data, function( resp ){
              if ( resp.status == 1 )
              {
                $( '.register-box' ).remove();
                $( '#register-alert' ).append( $('<div />').addClass('alert alert-success').text(resp.result.text) );
                addComment( form );
              }
              else
                $( '#register-alert' ).append( $('<div />').addClass('alert alert-error').text(resp.info) );
            }, 'json' );
          }
        });

        function addComment( form )
        {
          var data = {
            comment_text:       form.find('textarea[name="comment_text"]').val(),
            comment_parent_id:  form.find('input[name="comment_parent_id"]').val(),
            comment_user_name:  form.find('input[name="comment_user_name"]').val(),
            comment_user_email: form.find('input[name="comment_user_email"]').val(),
            token:              csrf_token
          }

          $.post( '/comment/add', data, function( resp ){
            if ( resp.status == 1 )
            {
              var child = form.closest( '.comment-one' ).find( '.commnet-child' ).first();

              if ( child.length != 0 )
              {
                addRow( resp.data, 30, child );
                form.remove();
              }
              else
              {
                addRow( resp.data, 0, $( '#comments' ) );
                form[0].reset();
              }
            }
            else
              alert( resp.info );
          }, 'json' );
        }

        $( document ).on( 'click', 'input[name="comment_nauth"]', function(){
          var form = $(this).closest('form');

          if ( $(this).prop('checked') )
            form.find('.comment-form-password').hide();
          else
            form.find('.comment-form-password').show();
        } );
      } );
    </script>
  </body>
</html>