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

    if ( ! is_user_logged_in() ) {
        wp_send_json_success( array( 'has_chat' => false ) );
    }

    $user_id = get_current_user_id();

    $chat = get_posts( array(
        'post_type' => 'live_chat',
        'author' => $user_id,
        'meta_query' => array(
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
