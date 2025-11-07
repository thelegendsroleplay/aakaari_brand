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

// AJAX: Start new chat
function aakaari_start_chat() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'Please login to start a chat.' ) );
    }

    $user_id = get_current_user_id();
    $user = wp_get_current_user();
    $subject = sanitize_text_field( $_POST['subject'] );
    $message = sanitize_textarea_field( $_POST['message'] );

    // Check if user already has an active chat
    $existing_chat = get_posts( array(
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

    if ( ! empty( $existing_chat ) ) {
        // Return existing chat
        $chat_id = $existing_chat[0]->ID;
    } else {
        // Create new chat
        $chat_id = wp_insert_post( array(
            'post_type' => 'live_chat',
            'post_title' => $subject,
            'post_status' => 'publish',
            'post_author' => $user_id,
        ) );

        if ( ! $chat_id ) {
            wp_send_json_error( array( 'message' => 'Failed to start chat.' ) );
        }

        update_post_meta( $chat_id, '_chat_status', 'active' );
        update_post_meta( $chat_id, '_chat_started', current_time( 'mysql' ) );
    }

    // Add initial message
    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    $messages[] = array(
        'user_id' => $user_id,
        'user_name' => $user->display_name,
        'message' => $message,
        'timestamp' => current_time( 'mysql' ),
        'is_admin' => false,
    );

    update_post_meta( $chat_id, '_chat_messages', $messages );
    update_post_meta( $chat_id, '_chat_updated', current_time( 'mysql' ) );

    wp_send_json_success( array(
        'chat_id' => $chat_id,
        'message' => 'Chat started successfully!',
    ) );
}
add_action( 'wp_ajax_aakaari_start_chat', 'aakaari_start_chat' );

// AJAX: Send message
function aakaari_send_chat_message() {
    check_ajax_referer( 'aakaari_chat_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $chat_id = intval( $_POST['chat_id'] );
    $message = sanitize_textarea_field( $_POST['message'] );
    $user_id = get_current_user_id();
    $user = wp_get_current_user();

    if ( empty( $message ) ) {
        wp_send_json_error( array( 'message' => 'Message cannot be empty.' ) );
    }

    $chat = get_post( $chat_id );
    if ( ! $chat || $chat->post_type !== 'live_chat' ) {
        wp_send_json_error( array( 'message' => 'Invalid chat.' ) );
    }

    // Check permission
    if ( $chat->post_author != $user_id && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $messages = get_post_meta( $chat_id, '_chat_messages', true );
    if ( ! is_array( $messages ) ) {
        $messages = array();
    }

    $messages[] = array(
        'user_id' => $user_id,
        'user_name' => $user->display_name,
        'message' => $message,
        'timestamp' => current_time( 'mysql' ),
        'is_admin' => current_user_can( 'manage_options' ),
    );

    update_post_meta( $chat_id, '_chat_messages', $messages );
    update_post_meta( $chat_id, '_chat_updated', current_time( 'mysql' ) );

    wp_send_json_success( array(
        'message' => 'Message sent!',
        'messages' => $messages,
    ) );
}
add_action( 'wp_ajax_aakaari_send_chat_message', 'aakaari_send_chat_message' );

// AJAX: Get chat messages (for real-time updates)
function aakaari_get_chat_messages() {
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
