<?php if (!defined('MW_THEME')) die('No direct script access allowed');

/**
 * Custom comment template callback function
 */
if (!function_exists('mw_theme_comment')) {
    function mw_theme_comment($comment, $args, $depth) {
        global $post;
        $GLOBALS['comment'] = $comment;
        $avatar_size = ($depth==1) ? '60' : '40';
        $comment_style = ($depth==1) ? "depth-{$depth} pad border round" : "depth-{$depth}";
        ?>
        
        <li class="comment-list-item <?php echo $comment_style; ?>" id="li-comment-<?php comment_ID(); ?>">
            <div class="comment" id="comment-<?php comment_ID(); ?>">
                <?php echo get_avatar($comment, $avatar_size); ?>
                <div class="comment-txt">
                    <h5 class="comment-author"><?php echo get_comment_author_link(); ?></h5>
                    <p class="comment-date"><?php echo get_comment_date('D dS M Y'); ?> at <?php echo get_comment_time(); ?></p>
                    <?php
                    if ($comment->comment_approved == '0') echo '<h4>' . __( 'Your comment is awaiting moderation', 'mw_theme' ) . '</h4>';
                    comment_text();
                    ?>
                    <?php if ($post->comment_status == 'open'): ?>
                    <span class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php
    }
}