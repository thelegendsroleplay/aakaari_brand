<?php
/**
 * Custom Live Chat Support System
 * Real-time chat between customers and support staff
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register Custom Post Type for Chat Conversations
function aakaari_register_live_chat_post_type() {
    register_post_type( 'live_chat', array(
        'labels' => array(
            'name' => 'Live Chats',
            'singular_name' => 'Chat',
            'menu_name' => 'Live Chat Support',
            'add_new' => 'New Chat',
            'edit_item' => 'View Chat',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-format-chat',
        'menu_position' => 26,
        'supports' => array( 'title', 'author' ),
        'capability_type' => 'post',
    ) );
}
add_action( 'init', 'aakaari_register_live_chat_post_type' );

// AJAX: Start new chat (supports both logged-in and guest users)
function aakaari_start_chat() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    $subject = sanitize_text_field( $_POST['subject'] );
    $message = sanitize_textarea_field( $_POST['message'] );

    // Handle both logged-in and guest users
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $user = wp_get_current_user();
        $user_name = $user->display_name;
        $user_email = $user->user_email;
        $session_key = 'user_' . $user_id;
    } else {
        // Guest user - get name and email
        $user_name = sanitize_text_field( $_POST['guest_name'] ?? 'Guest' );
        $user_email = sanitize_email( $_POST['guest_email'] ?? '' );
        $user_id = 0;

        // Generate unique session key for guest
        if ( ! session_id() ) {
            session_start();
        }
        $session_key = 'guest_' . session_id();
    }

    // Check if user/guest already has an active chat
    $existing_chat = get_posts( array(
        'post_type' => 'live_chat',
        'meta_query' => array(
            array(
                'key' => '_chat_session_key',
                'value' => $session_key,
            ),
            array(
                'key' => '_chat_status',
                'value' => 'active',
            ),
        ),
        'posts_per_page' => 1,
    ) );

    if ( ! empty( $existing_chat ) ) {
        // Return existing chat
        $chat_id = $existing_chat[0]->ID;
    } else {
        // Create new chat
        $chat_id = wp_insert_post( array(
            'post_type' => 'live_chat',
            'post_title' => $subject . ' - ' . $user_name,
            'post_status' => 'publish',
            'post_author' => $user_id > 0 ? $user_id : 1, // Use admin ID for guests
        ) );

        if ( ! $chat_id ) {
            wp_send_json_error( array( 'message' => 'Failed to start chat.' ) );
        }

        update_post_meta( $chat_id, '_chat_status', 'active' );
        update_post_meta( $chat_id, '_chat_started', current_time( 'mysql' ) );
        update_post_meta( $chat_id, '_chat_session_key', $session_key );
        update_post_meta( $chat_id, '_chat_user_name', $user_name );
        update_post_meta( $chat_id, '_chat_user_email', $user_email );
        update_post_meta( $chat_id, '_chat_is_guest', $user_id == 0 ? 'yes' : 'no' );

        // Send email notification to admin
        aakaari_send_chat_notification_email( $chat_id, $user_name, $user_email, $subject, $message );
    }

    // Add initial message
    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    $messages[] = array(
        'user_id' => $user_id,
        'user_name' => $user_name,
        'message' => $message,
        'timestamp' => current_time( 'mysql' ),
        'is_admin' => false,
    );

    update_post_meta( $chat_id, '_chat_messages', $messages );
    update_post_meta( $chat_id, '_chat_updated', current_time( 'mysql' ) );

    // Store session in cookie for guest users
    if ( $user_id == 0 ) {
        setcookie( 'aakaari_chat_session', $session_key, time() + (86400 * 30), '/' );
    }

    wp_send_json_success( array(
        'chat_id' => $chat_id,
        'session_key' => $session_key,
        'message' => 'Chat started successfully!',
    ) );
}
add_action( 'wp_ajax_aakaari_start_chat', 'aakaari_start_chat' );
add_action( 'wp_ajax_nopriv_aakaari_start_chat', 'aakaari_start_chat' );

// AJAX: Send message (supports text and images)
function aakaari_send_chat_message() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    $chat_id = intval( $_POST['chat_id'] );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );
    $session_key = sanitize_text_field( $_POST['session_key'] ?? '' );

    $chat = get_post( $chat_id );
    if ( ! $chat || $chat->post_type !== 'live_chat' ) {
        wp_send_json_error( array( 'message' => 'Invalid chat.' ) );
    }

    // Get user info
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $user = wp_get_current_user();
        $user_name = $user->display_name;
        $current_session = 'user_' . $user_id;
    } else {
        // Guest user
        $user_id = 0;
        $user_name = get_post_meta( $chat_id, '_chat_user_name', true );
        if ( ! session_id() ) {
            session_start();
        }
        $current_session = 'guest_' . session_id();
    }

    // Verify permission
    $chat_session_key = get_post_meta( $chat_id, '_chat_session_key', true );
    if ( $chat_session_key !== $current_session && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    // Handle image upload
    $image_url = '';
    if ( ! empty( $_FILES['image'] ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $uploaded = wp_handle_upload( $_FILES['image'], array( 'test_form' => false ) );
        if ( ! empty( $uploaded['url'] ) ) {
            $image_url = $uploaded['url'];
        }
    }

    // Require either message or image
    if ( empty( $message ) && empty( $image_url ) ) {
        wp_send_json_error( array( 'message' => 'Message or image is required.' ) );
    }

    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    $new_message = array(
        'user_id' => $user_id,
        'user_name' => $user_name,
        'message' => $message,
        'timestamp' => current_time( 'mysql' ),
        'is_admin' => current_user_can( 'manage_options' ),
    );

    if ( ! empty( $image_url ) ) {
        $new_message['image'] = $image_url;
    }

    $messages[] = $new_message;

    update_post_meta( $chat_id, '_chat_messages', $messages );
    update_post_meta( $chat_id, '_chat_updated', current_time( 'mysql' ) );

    wp_send_json_success( array(
        'message' => 'Message sent!',
        'messages' => $messages,
    ) );
}
add_action( 'wp_ajax_aakaari_send_chat_message', 'aakaari_send_chat_message' );
add_action( 'wp_ajax_nopriv_aakaari_send_chat_message', 'aakaari_send_chat_message' );

// AJAX: Get chat messages (for real-time updates)
function aakaari_get_chat_messages() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    $chat_id = intval( $_POST['chat_id'] );

    $chat = get_post( $chat_id );
    if ( ! $chat || $chat->post_type !== 'live_chat' ) {
        wp_send_json_error( array( 'message' => 'Invalid chat.' ) );
    }

    // Get current user session
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $current_session = 'user_' . $user_id;
    } else {
        if ( ! session_id() ) {
            session_start();
        }
        $current_session = 'guest_' . session_id();
    }

    // Check permission
    $chat_session_key = get_post_meta( $chat_id, '_chat_session_key', true );
    if ( $chat_session_key !== $current_session && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    $status = get_post_meta( $chat_id, '_chat_status', true );

    wp_send_json_success( array(
        'messages' => $messages,
        'status' => $status,
        'updated' => get_post_meta( $chat_id, '_chat_updated', true ),
    ) );
}
add_action( 'wp_ajax_aakaari_get_chat_messages', 'aakaari_get_chat_messages' );
add_action( 'wp_ajax_nopriv_aakaari_get_chat_messages', 'aakaari_get_chat_messages' );

// AJAX: Get user's active chat
function aakaari_get_active_chat() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    // Get session key for both logged-in and guest users
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $session_key = 'user_' . $user_id;
    } else {
        // Guest user - check session
        if ( ! session_id() ) {
            session_start();
        }
        $session_key = 'guest_' . session_id();
        $user_id = 0;
    }

    // Look for active chat by session key
    $chat = get_posts( array(
        'post_type' => 'live_chat',
        'meta_query' => array(
            array(
                'key' => '_chat_session_key',
                'value' => $session_key,
            ),
            array(
                'key' => '_chat_status',
                'value' => 'active',
            ),
        ),
        'posts_per_page' => 1,
    ) );

    if ( empty( $chat ) ) {
        wp_send_json_success( array( 'has_chat' => false ) );
    }

    $chat_id = $chat[0]->ID;
    $messages = get_post_meta( $chat_id, '_chat_messages', true );

    wp_send_json_success( array(
        'has_chat' => true,
        'chat_id' => $chat_id,
        'session_key' => $session_key,
        'subject' => $chat[0]->post_title,
        'messages' => is_array( $messages ) ? $messages : array(),
    ) );
}
add_action( 'wp_ajax_aakaari_get_active_chat', 'aakaari_get_active_chat' );
add_action( 'wp_ajax_nopriv_aakaari_get_active_chat', 'aakaari_get_active_chat' );

// AJAX: End chat
function aakaari_end_chat() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $chat_id = intval( $_POST['chat_id'] );
    $user_id = get_current_user_id();

    $chat = get_post( $chat_id );
    if ( ! $chat || $chat->post_type !== 'live_chat' ) {
        wp_send_json_error( array( 'message' => 'Invalid chat.' ) );
    }

    // Check permission
    if ( $chat->post_author != $user_id && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    update_post_meta( $chat_id, '_chat_status', 'closed' );
    update_post_meta( $chat_id, '_chat_ended', current_time( 'mysql' ) );

    wp_send_json_success( array( 'message' => 'Chat ended.' ) );
}
add_action( 'wp_ajax_aakaari_end_chat', 'aakaari_end_chat' );

// AJAX: Get all active chats (for admin)
function aakaari_get_all_chats() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $chats = get_posts( array(
        'post_type' => 'live_chat',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_chat_status',
                'value' => 'active',
            ),
        ),
        'orderby' => 'meta_value',
        'meta_key' => '_chat_updated',
        'order' => 'DESC',
    ) );

    $chats_data = array();
    foreach ( $chats as $chat ) {
        $messages = get_post_meta( $chat->ID, '_chat_messages', true );
        $author = get_userdata( $chat->post_author );

        $chats_data[] = array(
            'id' => $chat->ID,
            'subject' => $chat->post_title,
            'customer_name' => $author ? $author->display_name : 'Unknown',
            'customer_email' => $author ? $author->user_email : '',
            'started' => get_post_meta( $chat->ID, '_chat_started', true ),
            'updated' => get_post_meta( $chat->ID, '_chat_updated', true ),
            'message_count' => is_array( $messages ) ? count( $messages ) : 0,
        );
    }

    wp_send_json_success( array( 'chats' => $chats_data ) );
}
add_action( 'wp_ajax_aakaari_get_all_chats', 'aakaari_get_all_chats' );

/**
 * Send email notification to admin when a new chat starts
 */
function aakaari_send_chat_notification_email( $chat_id, $user_name, $user_email, $subject, $message ) {
    $admin_email = get_option( 'admin_email' );
    $site_name = get_bloginfo( 'name' );

    $email_subject = sprintf( '[%s] New Live Chat: %s', $site_name, $subject );

    $email_body = sprintf(
        "A new customer has started a live chat on your website.\n\n" .
        "Customer Name: %s\n" .
        "Customer Email: %s\n" .
        "Subject: %s\n\n" .
        "Initial Message:\n%s\n\n" .
        "---\n" .
        "View and respond to this chat in your WordPress admin:\n%s\n\n" .
        "This is an automated notification from %s",
        $user_name,
        $user_email ? $user_email : 'Not provided',
        $subject,
        $message,
        admin_url( 'post.php?post=' . $chat_id . '&action=edit' ),
        $site_name
    );

    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    if ( $user_email ) {
        $headers[] = 'Reply-To: ' . $user_name . ' <' . $user_email . '>';
    }

    wp_mail( $admin_email, $email_subject, $email_body, $headers );
}

/**
 * Customize the admin columns for Live Chat post type
 */
function aakaari_chat_admin_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Subject',
        'customer' => 'Customer',
        'status' => 'Status',
        'messages' => 'Messages',
        'started' => 'Started',
        'updated' => 'Last Update',
    );
    return $columns;
}
add_filter( 'manage_live_chat_posts_columns', 'aakaari_chat_admin_columns' );

/**
 * Populate custom columns
 */
function aakaari_chat_admin_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'customer':
            $customer_name = get_post_meta( $post_id, '_chat_user_name', true );
            $customer_email = get_post_meta( $post_id, '_chat_user_email', true );
            $is_guest = get_post_meta( $post_id, '_chat_is_guest', true );

            echo '<strong>' . esc_html( $customer_name ) . '</strong>';
            if ( $customer_email ) {
                echo '<br><a href="mailto:' . esc_attr( $customer_email ) . '">' . esc_html( $customer_email ) . '</a>';
            }
            if ( $is_guest === 'yes' ) {
                echo '<br><span style="color: #999; font-size: 11px;">Guest User</span>';
            }
            break;

        case 'status':
            $status = get_post_meta( $post_id, '_chat_status', true );
            $color = ( $status === 'active' ) ? '#10b981' : '#6b7280';
            echo '<span style="display: inline-block; padding: 4px 8px; background: ' . $color . '; color: white; border-radius: 4px; font-size: 11px; font-weight: 600;">' . esc_html( ucfirst( $status ) ) . '</span>';
            break;

        case 'messages':
            $messages = get_post_meta( $post_id, '_chat_messages', true );
            $count = is_array( $messages ) ? count( $messages ) : 0;
            echo '<strong>' . $count . '</strong> message' . ( $count !== 1 ? 's' : '' );
            break;

        case 'started':
            $started = get_post_meta( $post_id, '_chat_started', true );
            if ( $started ) {
                echo esc_html( date( 'M j, Y g:i a', strtotime( $started ) ) );
            }
            break;

        case 'updated':
            $updated = get_post_meta( $post_id, '_chat_updated', true );
            if ( $updated ) {
                $time_diff = human_time_diff( strtotime( $updated ), current_time( 'timestamp' ) );
                echo esc_html( $time_diff . ' ago' );
            }
            break;
    }
}
add_action( 'manage_live_chat_posts_custom_column', 'aakaari_chat_admin_column_content', 10, 2 );

/**
 * Make columns sortable
 */
function aakaari_chat_sortable_columns( $columns ) {
    $columns['started'] = 'started';
    $columns['updated'] = 'updated';
    return $columns;
}
add_filter( 'manage_edit-live_chat_sortable_columns', 'aakaari_chat_sortable_columns' );

/**
 * Add meta box for chat conversation
 */
function aakaari_chat_meta_boxes() {
    add_meta_box(
        'chat_conversation',
        'Chat Conversation',
        'aakaari_chat_conversation_metabox',
        'live_chat',
        'normal',
        'high'
    );

    add_meta_box(
        'chat_info',
        'Chat Information',
        'aakaari_chat_info_metabox',
        'live_chat',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aakaari_chat_meta_boxes' );

/**
 * Chat conversation meta box
 */
function aakaari_chat_conversation_metabox( $post ) {
    $messages = get_post_meta( $post->ID, '_chat_messages', true );
    $status = get_post_meta( $post->ID, '_chat_status', true );

    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    wp_nonce_field( 'aakaari_chat_reply_nonce', 'aakaari_chat_reply_nonce_field' );
    ?>
    <div id="chat-conversation-container" style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; max-height: 600px; overflow-y: auto;">
        <?php if ( empty( $messages ) ) : ?>
            <p style="text-align: center; color: #666;">No messages yet.</p>
        <?php else : ?>
            <?php foreach ( $messages as $msg ) : ?>
                <div style="margin-bottom: 1.5rem; <?php echo $msg['is_admin'] ? 'margin-left: auto; text-align: right;' : 'margin-right: auto;'; ?> max-width: 80%;">
                    <div style="margin-bottom: 0.5rem;">
                        <strong style="font-size: 14px;"><?php echo esc_html( $msg['user_name'] ); ?></strong>
                        <span style="color: #999; font-size: 12px; margin-left: 8px;">
                            <?php echo esc_html( date( 'M j, Y g:i a', strtotime( $msg['timestamp'] ) ) ); ?>
                        </span>
                        <?php if ( $msg['is_admin'] ) : ?>
                            <span style="background: #000; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 8px;">ADMIN</span>
                        <?php endif; ?>
                    </div>
                    <div style="background: <?php echo $msg['is_admin'] ? '#000' : '#fff'; ?>; color: <?php echo $msg['is_admin'] ? '#fff' : '#000'; ?>; padding: 1rem; border-radius: 8px; <?php echo $msg['is_admin'] ? '' : 'border: 1px solid #e5e7eb;'; ?> display: inline-block; text-align: left;">
                        <?php if ( ! empty( $msg['message'] ) ) : ?>
                            <p style="margin: 0; line-height: 1.6;"><?php echo nl2br( esc_html( $msg['message'] ) ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $msg['image'] ) ) : ?>
                            <img src="<?php echo esc_url( $msg['image'] ); ?>" alt="Attached image" style="max-width: 300px; margin-top: <?php echo ! empty( $msg['message'] ) ? '0.5rem' : '0'; ?>; border-radius: 4px;">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ( $status === 'active' ) : ?>
        <div style="background: white; padding: 1.5rem; border: 1px solid #e5e7eb; border-radius: 8px;">
            <h4 style="margin-top: 0;">Send Reply</h4>
            <textarea id="admin_reply_message" name="admin_reply_message" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 14px;" placeholder="Type your response..."></textarea>

            <div style="margin-top: 1rem;">
                <label style="display: inline-block; padding: 0.5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer;">
                    📎 Attach Image
                    <input type="file" id="admin_reply_image" name="admin_reply_image" accept="image/*" style="display: none;" onchange="previewAdminImage(this)">
                </label>
                <div id="admin_image_preview" style="margin-top: 1rem; display: none;">
                    <img id="admin_preview_img" src="" alt="Preview" style="max-width: 200px; border-radius: 4px; border: 1px solid #e5e7eb;">
                    <button type="button" onclick="clearAdminImage()" style="display: block; margin-top: 0.5rem; padding: 0.25rem 0.5rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Remove</button>
                </div>
            </div>

            <button type="button" onclick="sendAdminReply(<?php echo $post->ID; ?>)" style="margin-top: 1rem; padding: 0.75rem 1.5rem; background: #000; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px;">
                Send Reply
            </button>
            <span id="reply_status" style="margin-left: 1rem; color: #10b981; display: none;">✓ Reply sent!</span>
        </div>

        <script>
        function previewAdminImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('admin_preview_img').src = e.target.result;
                    document.getElementById('admin_image_preview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearAdminImage() {
            document.getElementById('admin_reply_image').value = '';
            document.getElementById('admin_image_preview').style.display = 'none';
        }

        function sendAdminReply(chatId) {
            var message = document.getElementById('admin_reply_message').value;
            var imageInput = document.getElementById('admin_reply_image');
            var formData = new FormData();

            formData.append('action', 'aakaari_admin_send_reply');
            formData.append('nonce', document.getElementById('aakaari_chat_reply_nonce_field').value);
            formData.append('chat_id', chatId);
            formData.append('message', message);

            if (imageInput.files && imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }

            // Disable button
            var btn = event.target;
            btn.disabled = true;
            btn.textContent = 'Sending...';

            fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear inputs
                    document.getElementById('admin_reply_message').value = '';
                    clearAdminImage();

                    // Show success
                    document.getElementById('reply_status').style.display = 'inline';
                    setTimeout(() => {
                        document.getElementById('reply_status').style.display = 'none';
                    }, 3000);

                    // Reload messages
                    location.reload();
                } else {
                    alert('Failed to send reply: ' + (data.data || 'Unknown error'));
                }

                // Re-enable button
                btn.disabled = false;
                btn.textContent = 'Send Reply';
            })
            .catch(error => {
                alert('Error: ' + error);
                btn.disabled = false;
                btn.textContent = 'Send Reply';
            });
        }
        </script>
    <?php else : ?>
        <p style="text-align: center; color: #999; padding: 2rem; background: #f9fafb; border-radius: 8px;">
            This chat has been closed. Reopen it to send a reply.
        </p>
    <?php endif; ?>
    <?php
}

/**
 * Chat info meta box
 */
function aakaari_chat_info_metabox( $post ) {
    $customer_name = get_post_meta( $post->ID, '_chat_user_name', true );
    $customer_email = get_post_meta( $post->ID, '_chat_user_email', true );
    $is_guest = get_post_meta( $post->ID, '_chat_is_guest', true );
    $status = get_post_meta( $post->ID, '_chat_status', true );
    $started = get_post_meta( $post->ID, '_chat_started', true );
    $updated = get_post_meta( $post->ID, '_chat_updated', true );
    ?>
    <div style="font-size: 13px;">
        <p><strong>Customer Name:</strong><br><?php echo esc_html( $customer_name ); ?></p>

        <?php if ( $customer_email ) : ?>
            <p><strong>Email:</strong><br>
                <a href="mailto:<?php echo esc_attr( $customer_email ); ?>"><?php echo esc_html( $customer_email ); ?></a>
            </p>
        <?php endif; ?>

        <p><strong>User Type:</strong><br><?php echo $is_guest === 'yes' ? 'Guest' : 'Registered User'; ?></p>

        <p><strong>Status:</strong><br>
            <span style="display: inline-block; padding: 4px 8px; background: <?php echo $status === 'active' ? '#10b981' : '#6b7280'; ?>; color: white; border-radius: 4px; font-size: 11px;"><?php echo esc_html( ucfirst( $status ) ); ?></span>
        </p>

        <?php if ( $started ) : ?>
            <p><strong>Started:</strong><br><?php echo esc_html( date( 'M j, Y g:i a', strtotime( $started ) ) ); ?></p>
        <?php endif; ?>

        <?php if ( $updated ) : ?>
            <p><strong>Last Update:</strong><br><?php echo esc_html( human_time_diff( strtotime( $updated ), current_time( 'timestamp' ) ) . ' ago' ); ?></p>
        <?php endif; ?>

        <?php if ( $status === 'active' ) : ?>
            <p>
                <button type="button" onclick="closeChat(<?php echo $post->ID; ?>)" style="width: 100%; padding: 0.5rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Close Chat
                </button>
            </p>
        <?php endif; ?>
    </div>

    <script>
    function closeChat(chatId) {
        if (!confirm('Are you sure you want to close this chat?')) {
            return;
        }

        var formData = new FormData();
        formData.append('action', 'aakaari_admin_close_chat');
        formData.append('nonce', '<?php echo wp_create_nonce( 'aakaari_admin_chat_nonce' ); ?>');
        formData.append('chat_id', chatId);

        fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to close chat: ' + (data.data || 'Unknown error'));
            }
        });
    }
    </script>
    <?php
}

/**
 * AJAX: Admin sends reply
 */
function aakaari_admin_send_reply() {
    check_ajax_referer( 'aakaari_chat_reply_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized' );
    }

    $chat_id = intval( $_POST['chat_id'] );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );
    $user = wp_get_current_user();

    // Handle image upload
    $image_url = '';
    if ( ! empty( $_FILES['image'] ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $uploaded = wp_handle_upload( $_FILES['image'], array( 'test_form' => false ) );
        if ( ! empty( $uploaded['url'] ) ) {
            $image_url = $uploaded['url'];
        }
    }

    if ( empty( $message ) && empty( $image_url ) ) {
        wp_send_json_error( 'Message or image is required' );
    }

    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    $new_message = array(
        'user_id' => $user->ID,
        'user_name' => $user->display_name,
        'message' => $message,
        'timestamp' => current_time( 'mysql' ),
        'is_admin' => true,
    );

    if ( ! empty( $image_url ) ) {
        $new_message['image'] = $image_url;
    }

    $messages[] = $new_message;

    update_post_meta( $chat_id, '_chat_messages', $messages );
    update_post_meta( $chat_id, '_chat_updated', current_time( 'mysql' ) );

    wp_send_json_success( array( 'message' => 'Reply sent!' ) );
}
add_action( 'wp_ajax_aakaari_admin_send_reply', 'aakaari_admin_send_reply' );

/**
 * AJAX: Admin closes chat
 */
function aakaari_admin_close_chat() {
    check_ajax_referer( 'aakaari_admin_chat_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized' );
    }

    $chat_id = intval( $_POST['chat_id'] );

    update_post_meta( $chat_id, '_chat_status', 'closed' );
    update_post_meta( $chat_id, '_chat_ended', current_time( 'mysql' ) );

    wp_send_json_success( array( 'message' => 'Chat closed' ) );
}
add_action( 'wp_ajax_aakaari_admin_close_chat', 'aakaari_admin_close_chat' );

/**
 * Register settings page for Live Chat
 */
function aakaari_chat_settings_page() {
    add_submenu_page(
        'edit.php?post_type=live_chat',
        'Chat Settings',
        'Settings',
        'manage_options',
        'live-chat-settings',
        'aakaari_chat_settings_page_html'
    );
}
add_action( 'admin_menu', 'aakaari_chat_settings_page' );

/**
 * Settings page HTML
 */
function aakaari_chat_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Save settings
    if ( isset( $_POST['aakaari_chat_settings_nonce'] ) && wp_verify_nonce( $_POST['aakaari_chat_settings_nonce'], 'aakaari_chat_settings' ) ) {
        update_option( 'aakaari_chat_inactivity_timeout', absint( $_POST['inactivity_timeout'] ) );
        update_option( 'aakaari_chat_auto_delete_days', absint( $_POST['auto_delete_days'] ) );
        update_option( 'aakaari_chat_disclaimer_text', wp_kses_post( $_POST['disclaimer_text'] ) );
        echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
    }

    $inactivity_timeout = get_option( 'aakaari_chat_inactivity_timeout', 30 );
    $auto_delete_days = get_option( 'aakaari_chat_auto_delete_days', 7 );
    $disclaimer_text = get_option( 'aakaari_chat_disclaimer_text', 'For your security: We will never ask for passwords, OTPs, credit card CVV, or other sensitive information via chat. Please do not share such details.' );
    ?>
    <div class="wrap">
        <h1>Live Chat Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field( 'aakaari_chat_settings', 'aakaari_chat_settings_nonce' ); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="inactivity_timeout">Auto-Close Inactive Chats</label></th>
                    <td>
                        <input type="number" id="inactivity_timeout" name="inactivity_timeout" value="<?php echo esc_attr( $inactivity_timeout ); ?>" min="5" max="1440" class="regular-text">
                        <p class="description">Close chats automatically after this many minutes of inactivity (default: 30 minutes)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="auto_delete_days">Auto-Delete Closed Chats</label></th>
                    <td>
                        <input type="number" id="auto_delete_days" name="auto_delete_days" value="<?php echo esc_attr( $auto_delete_days ); ?>" min="1" max="90" class="regular-text">
                        <p class="description">Permanently delete chats after this many days of being closed (default: 7 days). For GDPR compliance.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="disclaimer_text">Security Disclaimer</label></th>
                    <td>
                        <textarea id="disclaimer_text" name="disclaimer_text" rows="4" class="large-text"><?php echo esc_textarea( $disclaimer_text ); ?></textarea>
                        <p class="description">This message will be shown to customers before they start a chat</p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings">
            </p>
        </form>

        <hr>

        <h2>Privacy & Data Management</h2>
        <p><strong>Current Policy:</strong></p>
        <ul style="list-style: disc; margin-left: 2rem;">
            <li>Chats are automatically closed after <?php echo esc_html( $inactivity_timeout ); ?> minutes of inactivity</li>
            <li>Chat transcripts are emailed to both parties when a chat is closed</li>
            <li>Closed chats are permanently deleted after <?php echo esc_html( $auto_delete_days ); ?> days</li>
            <li>Customer data is stored securely in your WordPress database</li>
            <li>Customers can close their chat anytime from the chat widget</li>
        </ul>

        <h3>Manual Cleanup</h3>
        <p>
            <button type="button" class="button" onclick="if(confirm('This will delete all closed chats older than <?php echo esc_attr( $auto_delete_days ); ?> days. Continue?')) { cleanupOldChats(); }">
                Delete Old Chats Now
            </button>
        </p>

        <script>
        function cleanupOldChats() {
            fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=aakaari_cleanup_old_chats&nonce=<?php echo wp_create_nonce( 'aakaari_cleanup_nonce' ); ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Deleted ' + data.data.deleted_count + ' old chat(s)');
                    location.reload();
                } else {
                    alert('Cleanup failed: ' + (data.data || 'Unknown error'));
                }
            });
        }
        </script>
    </div>
    <?php
}

/**
 * Auto-close inactive chats (run via cron)
 */
function aakaari_auto_close_inactive_chats() {
    $timeout = get_option( 'aakaari_chat_inactivity_timeout', 30 );
    $timeout_date = date( 'Y-m-d H:i:s', strtotime( "-{$timeout} minutes" ) );

    $args = array(
        'post_type' => 'live_chat',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_chat_status',
                'value' => 'active',
            ),
            array(
                'key' => '_chat_updated',
                'value' => $timeout_date,
                'compare' => '<',
                'type' => 'DATETIME',
            ),
        ),
    );

    $inactive_chats = get_posts( $args );

    foreach ( $inactive_chats as $chat ) {
        // Close the chat
        update_post_meta( $chat->ID, '_chat_status', 'closed' );
        update_post_meta( $chat->ID, '_chat_ended', current_time( 'mysql' ) );
        update_post_meta( $chat->ID, '_chat_close_reason', 'auto_inactivity' );

        // Send transcripts
        aakaari_send_chat_transcript( $chat->ID );
    }
}

// Schedule cron job for auto-closing
if ( ! wp_next_scheduled( 'aakaari_auto_close_chats_hook' ) ) {
    wp_schedule_event( time(), 'hourly', 'aakaari_auto_close_chats_hook' );
}
add_action( 'aakaari_auto_close_chats_hook', 'aakaari_auto_close_inactive_chats' );

/**
 * Auto-delete old closed chats (run via cron)
 */
function aakaari_auto_delete_old_chats() {
    $delete_after_days = get_option( 'aakaari_chat_auto_delete_days', 7 );
    $delete_before_date = date( 'Y-m-d H:i:s', strtotime( "-{$delete_after_days} days" ) );

    $args = array(
        'post_type' => 'live_chat',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_chat_status',
                'value' => 'closed',
            ),
            array(
                'key' => '_chat_ended',
                'value' => $delete_before_date,
                'compare' => '<',
                'type' => 'DATETIME',
            ),
        ),
    );

    $old_chats = get_posts( $args );

    foreach ( $old_chats as $chat ) {
        // Permanently delete the chat and all its meta data
        wp_delete_post( $chat->ID, true );
    }
}

// Schedule cron job for auto-deletion
if ( ! wp_next_scheduled( 'aakaari_auto_delete_chats_hook' ) ) {
    wp_schedule_event( time(), 'daily', 'aakaari_auto_delete_chats_hook' );
}
add_action( 'aakaari_auto_delete_chats_hook', 'aakaari_auto_delete_old_chats' );

/**
 * Manual cleanup via AJAX
 */
function aakaari_cleanup_old_chats_ajax() {
    check_ajax_referer( 'aakaari_cleanup_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized' );
    }

    $delete_after_days = get_option( 'aakaari_chat_auto_delete_days', 7 );
    $delete_before_date = date( 'Y-m-d H:i:s', strtotime( "-{$delete_after_days} days" ) );

    $args = array(
        'post_type' => 'live_chat',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_chat_status',
                'value' => 'closed',
            ),
            array(
                'key' => '_chat_ended',
                'value' => $delete_before_date,
                'compare' => '<',
                'type' => 'DATETIME',
            ),
        ),
    );

    $old_chats = get_posts( $args );
    $deleted_count = 0;

    foreach ( $old_chats as $chat ) {
        wp_delete_post( $chat->ID, true );
        $deleted_count++;
    }

    wp_send_json_success( array( 'deleted_count' => $deleted_count ) );
}
add_action( 'wp_ajax_aakaari_cleanup_old_chats', 'aakaari_cleanup_old_chats_ajax' );

/**
 * Send chat transcript via email when chat closes
 */
function aakaari_send_chat_transcript( $chat_id ) {
    $chat = get_post( $chat_id );
    if ( ! $chat ) {
        return;
    }

    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    $customer_name = get_post_meta( $chat_id, '_chat_user_name', true );
    $customer_email = get_post_meta( $chat_id, '_chat_user_email', true );
    $started = get_post_meta( $chat_id, '_chat_started', true );
    $ended = get_post_meta( $chat_id, '_chat_ended', true );
    $close_reason = get_post_meta( $chat_id, '_chat_close_reason', true );

    if ( ! is_array( $messages ) || empty( $messages ) ) {
        return;
    }

    $site_name = get_bloginfo( 'name' );

    // Build transcript
    $transcript = "CHAT TRANSCRIPT\n";
    $transcript .= "================\n\n";
    $transcript .= "Website: " . get_bloginfo( 'url' ) . "\n";
    $transcript .= "Chat Subject: " . $chat->post_title . "\n";
    $transcript .= "Customer: " . $customer_name . "\n";
    if ( $customer_email ) {
        $transcript .= "Email: " . $customer_email . "\n";
    }
    $transcript .= "Started: " . date( 'F j, Y g:i a', strtotime( $started ) ) . "\n";
    $transcript .= "Ended: " . date( 'F j, Y g:i a', strtotime( $ended ) ) . "\n";

    if ( $close_reason === 'auto_inactivity' ) {
        $transcript .= "Closed Reason: Automatic (Inactivity)\n";
    }

    $transcript .= "\n" . str_repeat( '-', 60 ) . "\n\n";

    foreach ( $messages as $msg ) {
        $sender = $msg['is_admin'] ? 'SUPPORT' : strtoupper( $customer_name );
        $time = date( 'g:i a', strtotime( $msg['timestamp'] ) );

        $transcript .= "[{$time}] {$sender}:\n";
        if ( ! empty( $msg['message'] ) ) {
            $transcript .= $msg['message'] . "\n";
        }
        if ( ! empty( $msg['image'] ) ) {
            $transcript .= "[Image: " . $msg['image'] . "]\n";
        }
        $transcript .= "\n";
    }

    $transcript .= str_repeat( '-', 60 ) . "\n\n";
    $transcript .= "This chat has been closed and will be permanently deleted after " . get_option( 'aakaari_chat_auto_delete_days', 7 ) . " days.\n";
    $transcript .= "If you have any further questions, please start a new chat or contact us directly.\n\n";
    $transcript .= "Thank you,\n";
    $transcript .= $site_name;

    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );

    // Send to customer (if email provided)
    if ( $customer_email ) {
        $customer_subject = "[{$site_name}] Chat Transcript - " . $chat->post_title;
        wp_mail( $customer_email, $customer_subject, $transcript, $headers );
    }

    // Send to admin
    $admin_email = get_option( 'admin_email' );
    $admin_subject = "[{$site_name}] Chat Closed - Transcript for " . $customer_name;
    wp_mail( $admin_email, $admin_subject, $transcript, $headers );
}

/**
 * Get chat settings for frontend
 */
function aakaari_get_chat_settings() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    $disclaimer_text = get_option( 'aakaari_chat_disclaimer_text', 'For your security: We will never ask for passwords, OTPs, credit card CVV, or other sensitive information via chat. Please do not share such details.' );

    wp_send_json_success( array(
        'disclaimer' => $disclaimer_text,
    ) );
}
add_action( 'wp_ajax_aakaari_get_chat_settings', 'aakaari_get_chat_settings' );
add_action( 'wp_ajax_nopriv_aakaari_get_chat_settings', 'aakaari_get_chat_settings' );

/**
 * Customer closes their own chat
 */
function aakaari_customer_close_chat() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    $chat_id = intval( $_POST['chat_id'] );
    $session_key = sanitize_text_field( $_POST['session_key'] ?? '' );

    $chat = get_post( $chat_id );
    if ( ! $chat || $chat->post_type !== 'live_chat' ) {
        wp_send_json_error( 'Invalid chat' );
    }

    // Verify permission
    $chat_session_key = get_post_meta( $chat_id, '_chat_session_key', true );

    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $current_session = 'user_' . $user_id;
    } else {
        if ( ! session_id() ) {
            session_start();
        }
        $current_session = 'guest_' . session_id();
    }

    if ( $chat_session_key !== $current_session ) {
        wp_send_json_error( 'Unauthorized' );
    }

    // Close the chat
    update_post_meta( $chat_id, '_chat_status', 'closed' );
    update_post_meta( $chat_id, '_chat_ended', current_time( 'mysql' ) );
    update_post_meta( $chat_id, '_chat_close_reason', 'customer' );

    // Send transcripts
    aakaari_send_chat_transcript( $chat_id );

    wp_send_json_success( array( 'message' => 'Chat closed successfully. Transcript has been sent to your email.' ) );
}
add_action( 'wp_ajax_aakaari_customer_close_chat', 'aakaari_customer_close_chat' );
add_action( 'wp_ajax_nopriv_aakaari_customer_close_chat', 'aakaari_customer_close_chat' );
