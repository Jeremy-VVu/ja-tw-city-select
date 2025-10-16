<?php
/**
 * Plugin Name: 台灣 WooCommerce 區塊結帳欄位鄉鎮市區選單
 * Plugin URI: https://github.com/yourusername/ja-tw-city-select
 * Description: 專為台灣 WooCommerce 商店設計的區塊結帳鄉鎮市區選單外掛，提供縣市、鄉鎮市區下拉選單及自動郵遞區號填入功能。
 * Version: 0.0.9
 * Author: Jeremy
 * Author URI: https://yourwebsite.com
 * Text Domain: ja-tw-city-select
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * WC requires at least: 8.0
 * WC tested up to: 9.0
 * HPOS: yes
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package JA_TW_City_Select
 */

// 防止直接訪問檔案
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 定義外掛常數
define( 'JA_TW_CITY_SELECT_VERSION', '0.0.1' );
define( 'JA_TW_CITY_SELECT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JA_TW_CITY_SELECT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'JA_TW_CITY_SELECT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * 主要外掛類別
 */
class JA_TW_City_Select {

	/**
	 * 單例模式實例
	 *
	 * @var JA_TW_City_Select
	 */
	private static $instance = null;

	/**
	 * 取得單例實例
	 *
	 * @return JA_TW_City_Select
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * 建構函式
	 */
	private function __construct() {
		// 檢查 WooCommerce 是否啟用
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		
		// 註冊啟用和停用鉤子
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		// 宣告 HPOS 相容性
		add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
	}

	/**
	 * 初始化外掛
	 */
	public function init() {
		// 檢查 WooCommerce 是否已啟用
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
			return;
		}

		// 載入相依檔案
		$this->load_dependencies();

		// 載入文字域
		load_plugin_textdomain( 'ja-tw-city-select', false, dirname( JA_TW_CITY_SELECT_PLUGIN_BASENAME ) . '/languages' );

		// 初始化區塊結帳整合
		add_action( 'woocommerce_blocks_loaded', array( $this, 'init_blocks_integration' ) );
		
		// 初始化簡化整合 (備用方案)
		new JA_TW_Simple_Checkout_Integration();
	}

	/**
	 * 載入相依檔案
	 */
	private function load_dependencies() {
		require_once JA_TW_CITY_SELECT_PLUGIN_DIR . 'includes/class-tw-address-data.php';
		require_once JA_TW_CITY_SELECT_PLUGIN_DIR . 'includes/class-block-checkout-integration.php';
		require_once JA_TW_CITY_SELECT_PLUGIN_DIR . 'includes/class-simple-checkout-integration.php';
	}

	/**
	 * 初始化區塊結帳整合
	 */
	public function init_blocks_integration() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface' ) ) {
			add_action(
				'woocommerce_blocks_checkout_block_registration',
				array( $this, 'register_checkout_block_integration' )
			);
		}
	}

	/**
	 * 註冊結帳區塊整合
	 *
	 * @param object $integration_registry 整合註冊器
	 */
	public function register_checkout_block_integration( $integration_registry ) {
		$integration_registry->register( new JA_TW_Block_Checkout_Integration() );
	}

	/**
	 * WooCommerce 未安裝通知
	 */
	public function woocommerce_missing_notice() {
		?>
		<div class="notice notice-error">
			<p><?php esc_html_e( '「台灣 WooCommerce 區塊結帳欄位地址選單」需要安裝並啟用 WooCommerce 才能運作。', 'ja-tw-city-select' ); ?></p>
		</div>
		<?php
	}

	/**
	 * 外掛啟用時執行
	 */
	public function activate() {
		// 檢查 WordPress 和 PHP 版本
		if ( version_compare( get_bloginfo( 'version' ), '6.0', '<' ) ) {
			deactivate_plugins( JA_TW_CITY_SELECT_PLUGIN_BASENAME );
			wp_die( esc_html__( '此外掛需要 WordPress 6.0 或更高版本。', 'ja-tw-city-select' ) );
		}

		if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
			deactivate_plugins( JA_TW_CITY_SELECT_PLUGIN_BASENAME );
			wp_die( esc_html__( '此外掛需要 PHP 8.0 或更高版本。', 'ja-tw-city-select' ) );
		}
	}

	/**
	 * 外掛停用時執行
	 */
	public function deactivate() {
		// 清理工作（如果需要）
	}

	/**
	 * 宣告 HPOS 相容性
	 */
	public function declare_hpos_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}

/**
 * 啟動外掛
 *
 * @return JA_TW_City_Select
 */
function ja_tw_city_select() {
	return JA_TW_City_Select::get_instance();
}

// 初始化外掛
ja_tw_city_select();

