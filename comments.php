<?php
/**
 * The template for displaying comments
 */

/* if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Denied access'); */

/**
 *
 */
function bs4_list_comments_callback($comment, $args, $depth) {
	// $GLOBALS['comment'] = $comment;

	echo '<' . $args['style'] . ' id="comment-' . get_comment_ID() . '" ';
	comment_class('media');
	echo '>';

	$href = $comment->comment_author_url;
	if (empty($href)) $href = false;

	if ($href) echo '<a class="media-left" href="' . $href . '">';
	else echo '<div class="media-left">';

	$link = get_avatar(
        	$comment,
        	$args['avatar_size'],
        	'',
        	$comment->comment_author,
        	array(
        		'class' => AVATAR_CLASS,
        		'extra_attr' => 'title="' . esc_attr($comment->comment_author) . '"'
			));
	if ($link) echo $link;
	else echo get_bs4_user_i($comment);

	if ($href) echo '</a>';
	else echo '</div>';

	echo '<div class="media-body">';

	echo '<h5 class="media-heading comment-heading">';
	echo $comment->comment_author;
	echo '<small class="says"> said:</small></h5>';

	echo '<p class="meta-data text-muted small">';
	// echo '<a class="meta-item comment-link" href="' . get_comment_link( $comment->comment_ID ) .
	//     '" title="Link to comment">' . get_bs4_i('link') . '</a>';
	echo '<time datetime=""' . get_comment_time('c') . '" class="comment-time">';
	echo '<span class="meta-item">';
	bs4_i('calendar', '', ' ');
	echo get_comment_date() . ' ';
	echo '</span><span class="meta-item">';
	bs4_i('clock', '', ' ');
	echo get_comment_time();
	echo '</span>';
	echo '</time>';
	if ( '0' == $comment->comment_approved )
		echo ' <span class="meta-item text-info comment-awaiting">' .
        		get_bs4_i('wait', '', ' ') . 'Your comment is awaiting moderation.</span>';
	echo '</p>';

	echo '<p class="comment-text">' . get_comment_text() . '</p>';

	echo'<p class="comment-buttons">';

	$link = get_edit_comment_link();
	if ($link) {
        	echo '<a href="' . $link . '" class="btn btn-warning-outline btn-sm small"';
        	echo ' title="Edit Comment">' . get_bs4_i('edit', '', ' ') . 'Edit</a>';
	}

	comment_reply_link(array_merge( $args, array(
        	'depth'     => $depth,
        	'max_depth' => $args['max_depth'],
        	'add_below' => 'response',
        	'reply_text'    => get_bs4_i('reply', '', ' ') . 'Reply',
        	'reply_to_text' => get_bs4_i('reply', '', ' ') . 'Reply to %s',
        	'login_text'    => get_bs4_i('login', '', ' ') . 'Log in to Reply',
        	)));

	echo '</p>';
	echo '<a id="response-' . get_comment_ID() . '"></a>';
}

function bs4_list_comments_end_callback($comment, $args, $depth) {
	echo '</div></' . $args['style'] . '>';  // first div is .media-body
}

function bs4_comment_form($args = array(), $post_id = null) {
	$req = get_option( 'require_name_email' );
	$html_req = ( $req ? ' required' : '' );
	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$fields = array(
		'author' =>
        		'<div class="row">' .
        		'<div class="col-xs-12 col-md-4 form-group"><div class="input-group">' .
        		'<div class="input-group-addon"><label for="author"' . ($req ? ' class="text-danger"' : '') . '>' . get_bs4_user_i() . '</label></div>' .
        		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" class="form-control" placeholder="Name"' . $html_req . '>' .
        		'</div></div>',
		'email' =>
        		'<div class="col-xs-12 col-md-4 form-group"><div class="input-group">' .
        		'<div class="input-group-addon"><label for="email"' . ($req ? ' class="text-danger"' : '') . '>' . get_bs4_i('email') . '</label></div>' .
        		'<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" class="form-control" placeholder="Email"' . $html_req . '>' .
        		'</div></div>',
		'url' =>
        		'<div class="col-xs-12 col-md-4 form-group"><div class="input-group">' .
        		'<div class="input-group-addon"><label for="url">' . get_bs4_i('link') . '</label></div>' .
        		'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" class="form-control" placeholder="Website">' .
        		'</div></div>' .
        		'</div>',
        	);

	$args = array(
        	// 'format' => 'html5',  // doesn't matter
        	'fields' => $fields,
        	'title_reply' => 'Leave a Reply',
		'title_reply_to' => 'Leave a Reply to %s',
		'cancel_reply_link' => get_bs4_i('cancel', '', ' ') . 'Cancel',

        	'comment_notes_before' =>
        		'<p class="text-muted form-group">' .
        		($req ?
                		'The <i class="text-danger">name</i>, <i class="text-danger">email</i> and <i class="text-danger">comment</i> are required fields.' :
                		'The <i class="text-danger">comment</i> field is required.') .
        		'</p>',
        	'comment_field' =>
        		'<div class="row">' .
        		'<div class="col-xs-12 form-group"><div class="input-group">' .
        		'<div class="input-group-addon"><label for="comment" class="text-danger">' . get_bs4_i('commenta') . '</label></div>' .
        		'<textarea id="comment" name="comment" placeholder="Comment" rows="3" class="form-control" required></textarea>' .
        		'</div></div>' .
        		'</div>',
        	/* 'comment_notes_after' =>
        		'<div class="row"><div class="col-xs-12 form-group">' .
			'<small class="text-muted">' .
        		'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: <code>' . allowed_tags() . '</code>' .
        		'</small>' .
        		'</div></div>',  /* */

        	'must_log_in' =>
        		'<p>' .
        		'<a href="' . wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) .
			'" class="btn btn-success-outline btn-sm small">' .
			get_bs4_i('login', '', ' ') . 'Log in</a> to post a comment.' .
        		'</p>',
        	'logged_in_as' =>
        		'<p>' .
			'As ' . get_bs4_user_i(($user->exists() ? $user->ID : null), '', ' ') .
        		'<a href="' . get_edit_user_link() . '">' . $user_identity . '</a>. <a href="' .
			wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) .
			'" class="btn btn-danger-outline btn-sm small">' .
			get_bs4_i('logout', '', ' ') . 'Log out</a></p>',

		'label_submit' => 'Submit' . get_bs4_i('post', ' '),
        	'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary">%4$s</button>',
        	'submit_field' => '%1$s %2$s',

        	// New in WP 4.4
        	'title_reply_before'   => '<h4>' . get_bs4_i('commentr', '', ' '),
		'title_reply_after'    => '</h4>',
		'cancel_reply_before'  => '',  // DONE: From WP4.4
		'cancel_reply_after'   => '',  // DONE: From WP4.4
        );

	comment_form_2($args);
}
?>

<section id="comments" class="comments no-print">

<hr class="soft">

<?php
if ( post_password_required() ) :

	?><div class="alert alert-info"><?php
	bs4_i('info lg')
	?>This post is password protected. Enter the password to view comments.</div><?php

else :

	$cn = get_comments_number();
	?><h4><?php
	echo ' ' . sprintf( _n(
        	get_bs4_i('comment', '', ' ') . '%s comment',
        	get_bs4_i('comments', '', ' ') . '%s comments',
        	$cn), $cn);
	?></h4><?php

	wp_list_comments( array(
		'style'        => 'div',
		'avatar_size'  => 48,
		'callback'     => 'bs4_list_comments_callback',
		'end-callback' => 'bs4_list_comments_end_callback',
		) );

		paginate_comments_links_2( array(
			'type' => 'list',
			'next_text' => '&raquo;',
			'prev_text' => '&laquo;',
			'dots' => '&vellip;',
			'before' => '<nav class="text-center"><ul class="pagination pagination-sm">',
			'after' => '</ul></nav>',
		) );

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
			?><p class="text-info comments-closed">Comments are closed.</p><?php
		} else {
			?><hr class="soft"><?php
			bs4_comment_form();
		}
endif;

?></section>
