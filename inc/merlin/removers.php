<?php

//add_filter( 'wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX );
function change_default_jquery( ){
    wp_dequeue_script( 'jquery');
    wp_deregister_script( 'jquery');
}


add_action( 'admin_menu', 'remove_menus' );
function remove_menus(){
    remove_menu_page( 'index.php' );                  // Консоль
//    remove_menu_page( 'upload.php' );                  // Медиа
    remove_menu_page( 'edit.php' );                   // Записи
    remove_menu_page( 'edit-comments.php' );          // Комментарии
    remove_menu_page( 'users.php' );                  // Пользователи
    remove_menu_page( 'tools.php' );                  // Инструменты
//    remove_menu_page( 'options-general.php' );        // Настройки
}