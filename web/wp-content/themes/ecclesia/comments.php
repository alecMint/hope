<?php if (!defined('MW_THEME')) die('No direct script access allowed'); ?>

<?php if ( post_password_required() ) : ?>
    <p class="large"><?php _e('This post is password protected. Enter the password to view comments.', 'mw_theme'); ?></p>
<?php return; endif; ?>

<div id="comments">

    <?php if (!have_comments() && $post->comment_status == 'closed'): ?>
        <h2 id="comments-heading"><?php _e('No comments yet', 'mw_theme'); ?></h2>
        <div id="comments-closed">
            <p class="large"><?php _e('Comments are closed', 'mw_theme'); ?></p>
        </div>
    <?php else: ?>

        <h2 id="comments-heading"><?php comments_number(__('No comments yet', 'mw_theme'), __('1 Comment', 'mw_theme'), __('% Comments', 'mw_theme')); ?></h2>
        <?php if ( have_comments() ) : ?>
            <ol id="comment-list" class="comment-list">
                <?php wp_list_comments('type=comment&style=li&callback=mw_theme_comment'); ?>
            </ol>
            <?php if (get_comment_pages_count() > 1) : ?>
                <div id="comments-nav">
                    <span id="older-comments"><?php previous_comments_link(__('&lsaquo; Older comments', 'mw_theme')) ?></span>
                    <span id="newer-comments"><?php next_comments_link(__('Newer comments &rsaquo;', 'mw_theme')) ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($post->comment_status == 'open'): ?>
        
            <div id="respond" class="frame-center">
                <h2 id="comment-form-heading"><?php _e('Add comment', 'mw_theme'); ?></h2>
                <?php cancel_comment_reply_link('Cancel comment reply'); ?>
                <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
                    <p class="large">
                    <?php printf(
                        __('You must be %s to post a comment.', 'mw_theme'),
                        sprintf('<a href="%s/wp-login.php?redirect_to=%s">%s</a>', get_option('siteurl'), urlencode(get_permalink()), __('logged in', 'mw_theme'))
                    ); ?>
                    </p>
                <?php else : ?>
                    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment-form" >
                        <?php if ( $user_ID ) : ?>
                        <div class="form-element"><p><?php _e('Logged in as', 'mw_theme'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.&nbsp;<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p></div>
                        <?php else : ?>
                        <div class="form-element">
                            <label for="author"><?php _e('Your name', 'mw_theme'); if ($req) echo '<span class="req-mark">*</span>'; ?></label>
                            <input type="text" class="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                        </div>
                        <div class="form-element">
                            <label for="email"><?php _e('Your email address', 'mw_theme'); if ($req) echo '<span class="req-mark">*</span>'; ?> &nbsp; <em class="small"><?php _e('(will not be published)', 'mw_theme'); ?></em></label>
                            <input type="text" class="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                        </div>
                        <div class="form-element">
                            <label for="url"><?php _e('Your website', 'mw_theme'); ?></label>
                            <input type="text" class="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
                        </div>
                        <?php endif; ?>
                        <div class="form-element">
                            <label for="comment"><?php _e('Your comment', 'mw_theme'); ?></label>
                            <div id="comment-text-wrapper"><textarea name="comment" id="comment" cols="65" rows="7" tabindex="4"></textarea></div>
                        </div>
                        <div class="form-element"><input class="submit" type="submit" id="comment-submit" value="<?php _e('Submit comment', 'mw_theme'); ?>" tabindex="5" /></div>
                        <?php do_action('comment_form', $post->ID); ?>
                        <div><?php comment_id_fields(); ?></div>
                    </form>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
        
            <div id="comments-closed">
                <p class="large"><?php _e('Comments are closed', 'mw_theme'); ?></p>
            </div>
            
        <?php endif; ?>

    <?php endif; ?>
</div>
