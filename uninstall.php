<?php
/**
 * 外掛解除安裝檔案
 *
 * 當外掛被刪除時執行此檔案
 *
 * @package JA_TW_City_Select
 */

// 如果不是透過 WordPress 解除安裝,則退出
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// 清理選項(如果有設定任何選項的話)
// delete_option( 'ja_tw_city_select_option_name' );

// 清理暫存
// delete_transient( 'ja_tw_city_select_cache' );

// 對多站台支援,清理所有站台的資料
if ( is_multisite() ) {
	global $wpdb;
	
	// 取得所有部落格 ID
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
	
	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		
		// 清理每個站台的選項
		// delete_option( 'ja_tw_city_select_option_name' );
		
		restore_current_blog();
	}
}

// 注意:我們不刪除訂單或客戶資料中的地址資訊
// 因為這些是用戶的重要商業數據

