<?php
/**
 * Support Ticket System
 * Custom post type and functionality for support tickets
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register Support Tickets Custom Post Type
function aakaari_register_tickets_post_type() {
    $labels = array(
        'name'               => 'Support Tickets',
        'singular_name'      => 'Ticket',
        'menu_name'          => 'Support Tickets',
        'add_new'            => 'Add New Ticket',
        'add_new_item'       => 'Add New Ticket',
        'edit_item'          => 'Edit Ticket',
        'new_item'           => 'New Ticket',
        'view_item'          => 'View Ticket',
        'search_items'       => 'Search Tickets',
        'not_found'          => 'No tickets found',
        'not_found_in_trash' => 'No tickets found in trash'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'support-ticket' ),
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-tickets-alt',
        'supports'            => array( 'title', 'editor', 'author' ),
    );

    register_post_type( 'support_ticket', $args );
}
add_action( 'init', 'aakaari_register_tickets_post_type' );

// Add ticket status taxonomy
function aakaari_register_ticket_taxonomies() {
    register_taxonomy(
        'ticket_status',
        'support_ticket',
        array(
            'label'        => 'Ticket Status',
            'rewrite'      => false,
            'hierarchical' => false,
            'show_ui'      => true,
            'show_admin_column' => true,
        )
    );

    // Add default statuses if they don't exist
    if ( ! term_exists( 'open', 'ticket_status' ) ) {
        wp_insert_term( 'Open', 'ticket_status', array( 'slug' => 'open' ) );
    }
    if ( ! term_exists( 'in-progress', 'ticket_status' ) ) {
        wp_insert_term( 'In Progress', 'ticket_status', array( 'slug' => 'in-progress' ) );
    }
    if ( ! term_exists( 'resolved', 'ticket_status' ) ) {
        wp_insert_term( 'Resolved', 'ticket_status', array( 'slug' => 'resolved' ) );
    }
    if ( ! term_exists( 'closed', 'ticket_status' ) ) {
        wp_insert_term( 'Closed', 'ticket_status', array( 'slug' => 'closed' ) );
    }

    register_taxonomy(
        'ticket_priority',
        'support_ticket',
        array(
            'label'        => 'Priority',
            'rewrite'      => false,
            'hierarchical' => false,
            'show_ui'      => true,
            'show_admin_column' => true,
        )
    );

    // Add default priorities
    if ( ! term_exists( 'low', 'ticket_priority' ) ) {
        wp_insert_term( 'Low', 'ticket_priority', array( 'slug' => 'low' ) );
    }
    if ( ! term_exists( 'medium', 'ticket_priority' ) ) {
        wp_insert_term( 'Medium', 'ticket_priority', array( 'slug' => 'medium' ) );
    }
    if ( ! term_exists( 'high', 'ticket_priority' ) ) {
        wp_insert_term( 'High', 'ticket_priority', array( 'slug' => 'high' ) );
    }
    if ( ! term_exists( 'urgent', 'ticket_priority' ) ) {
        wp_insert_term( 'Urgent', 'ticket_priority', array( 'slug' => 'urgent' ) );
    }
}
add_action( 'init', 'aakaari_register_ticket_taxonomies' );

// AJAX: Create new ticket
function aakaari_create_ticket() {
    check_ajax_referer( 'aakaari_tickets_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'You must be logged in to create a ticket.' ) );
    }

    $subject = sanitize_text_field( $_POST['subject'] );
    $message = sanitize_textarea_field( $_POST['message'] );
    $priority = sanitize_text_field( $_POST['priority'] );

    if ( empty( $subject ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => 'Subject and message are required.' ) );
    }

    $ticket_id = wp_insert_post( array(
        'post_type'    => 'support_ticket',
        'post_title'   => $subject,
        'post_content' => $message,
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(),
    ) );

    if ( $ticket_id ) {
        // Set status to open
        wp_set_object_terms( $ticket_id, 'open', 'ticket_status' );

        // Set priority
        if ( ! empty( $priority ) ) {
            wp_set_object_terms( $ticket_id, $priority, 'ticket_priority' );
        }

        // Add creation timestamp
        update_post_meta( $ticket_id, '_ticket_created_at', current_time( 'mysql' ) );

        wp_send_json_success( array(
            'message' => 'Ticket created successfully!',
            'ticket_id' => $ticket_id
        ) );
    } else {
        wp_send_json_error( array( 'message' => 'Failed to create ticket.' ) );
    }
}
add_action( 'wp_ajax_aakaari_create_ticket', 'aakaari_create_ticket' );

// AJAX: Update ticket status
function aakaari_update_ticket_status() {
    check_ajax_referer( 'aakaari_tickets_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $ticket_id = intval( $_POST['ticket_id'] );
    $status = sanitize_text_field( $_POST['status'] );

    $ticket = get_post( $ticket_id );

    if ( ! $ticket || $ticket->post_type !== 'support_ticket' ) {
        wp_send_json_error( array( 'message' => 'Invalid ticket.' ) );
    }

    // Check if user owns the ticket or is admin
    if ( $ticket->post_author != get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    wp_set_object_terms( $ticket_id, $status, 'ticket_status' );

    // Update last modified timestamp
    update_post_meta( $ticket_id, '_ticket_updated_at', current_time( 'mysql' ) );

    wp_send_json_success( array( 'message' => 'Ticket status updated!' ) );
}
add_action( 'wp_ajax_aakaari_update_ticket_status', 'aakaari_update_ticket_status' );

// AJAX: Add reply to ticket
function aakaari_add_ticket_reply() {
    check_ajax_referer( 'aakaari_tickets_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $ticket_id = intval( $_POST['ticket_id'] );
    $reply = sanitize_textarea_field( $_POST['reply'] );

    if ( empty( $reply ) ) {
        wp_send_json_error( array( 'message' => 'Reply cannot be empty.' ) );
    }

    $ticket = get_post( $ticket_id );

    if ( ! $ticket || $ticket->post_type !== 'support_ticket' ) {
        wp_send_json_error( array( 'message' => 'Invalid ticket.' ) );
    }

    // Get existing replies
    $replies = get_post_meta( $ticket_id, '_ticket_replies', true );
    if ( ! is_array( $replies ) ) {
        $replies = array();
    }

    // Add new reply
    $replies[] = array(
        'user_id'    => get_current_user_id(),
        'user_name'  => wp_get_current_user()->display_name,
        'message'    => $reply,
        'timestamp'  => current_time( 'mysql' ),
        'is_admin'   => current_user_can( 'manage_options' )
    );

    update_post_meta( $ticket_id, '_ticket_replies', $replies );
    update_post_meta( $ticket_id, '_ticket_updated_at', current_time( 'mysql' ) );

    wp_send_json_success( array(
        'message' => 'Reply added successfully!',
        'reply' => end( $replies )
    ) );
}
add_action( 'wp_ajax_aakaari_add_ticket_reply', 'aakaari_add_ticket_reply' );

// AJAX: Get tickets (for real-time updates)
function aakaari_get_tickets() {
    check_ajax_referer( 'aakaari_tickets_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'Unauthorized' ) );
    }

    $user_id = get_current_user_id();
    $is_admin = current_user_can( 'manage_options' );

    $args = array(
        'post_type'      => 'support_ticket',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // Non-admin users only see their own tickets
    if ( ! $is_admin ) {
        $args['author'] = $user_id;
    }

    $tickets = get_posts( $args );
    $tickets_data = array();

    foreach ( $tickets as $ticket ) {
        $statuses = wp_get_post_terms( $ticket->ID, 'ticket_status', array( 'fields' => 'names' ) );
        $priorities = wp_get_post_terms( $ticket->ID, 'ticket_priority', array( 'fields' => 'names' ) );
        $replies = get_post_meta( $ticket->ID, '_ticket_replies', true );

        $tickets_data[] = array(
            'id'         => $ticket->ID,
            'subject'    => $ticket->post_title,
            'message'    => $ticket->post_content,
            'status'     => ! empty( $statuses ) ? $statuses[0] : 'Open',
            'priority'   => ! empty( $priorities ) ? $priorities[0] : 'Medium',
            'created'    => get_the_date( 'F j, Y g:i A', $ticket->ID ),
            'updated'    => get_post_meta( $ticket->ID, '_ticket_updated_at', true ),
            'author'     => get_the_author_meta( 'display_name', $ticket->post_author ),
            'replies'    => is_array( $replies ) ? $replies : array(),
            'reply_count'=> is_array( $replies ) ? count( $replies ) : 0,
        );
    }

    wp_send_json_success( array( 'tickets' => $tickets_data ) );
}
add_action( 'wp_ajax_aakaari_get_tickets', 'aakaari_get_tickets' );
