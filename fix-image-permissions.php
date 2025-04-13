<?php
/**
 * Plugin Name: Fix Image Permissions
 * Description: Автоматично виставляє права 644 на всі завантажені зображення та їхні копії.
 * Version: 1.0
 * Author: GPT
 */

// Основний файл після завантаження
add_filter( 'wp_handle_upload', 'fip_fix_uploaded_file_permissions' );
function fip_fix_uploaded_file_permissions( $fileinfo ) {
    @chmod( $fileinfo['file'], 0644 );
    return $fileinfo;
}

// Зменшені копії після генерації
add_filter( 'wp_generate_attachment_metadata', 'fip_fix_thumbnail_permissions', 10, 2 );
function fip_fix_thumbnail_permissions( $metadata, $attachment_id ) {
    $file_path = get_attached_file( $attachment_id );

    // Основний файл
    @chmod( $file_path, 0644 );

    // Копії
    if ( isset( $metadata['sizes'] ) ) {
        foreach ( $metadata['sizes'] as $size ) {
            $thumb_path = path_join( dirname( $file_path ), $size['file'] );
            @chmod( $thumb_path, 0644 );
        }
    }

    return $metadata;
}
