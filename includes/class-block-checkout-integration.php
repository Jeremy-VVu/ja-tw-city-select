<?php
/**
 * WooCommerce 區塊結帳整合類別
 *
 * @package JA_TW_City_Select
 */

// 防止直接訪問檔案
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * 區塊結帳整合實作
 */
class JA_TW_Block_Checkout_Integration implements IntegrationInterface {

	/**
	 * 整合名稱
	 *
	 * @var string
	 */
	private $name = 'ja-tw-city-select';

	/**
	 * 初始化整合
	 */
	public function initialize() {
		$this->register_scripts();
		$this->register_endpoints();
	}

	/**
	 * 取得整合名稱
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * 當呼叫時，設定將資料傳遞給腳本
	 *
	 * @return array
	 */
	public function get_script_data() {
		return array(
			'states'    => JA_TW_Address_Data::get_states(),
			'districts' => JA_TW_Address_Data::get_districts(),
			'i18n'      => array(
				'selectState'    => __( '請選擇縣市', 'ja-tw-city-select' ),
				'selectDistrict' => __( '請選擇鄉鎮市區', 'ja-tw-city-select' ),
			),
		);
	}

	/**
	 * 註冊腳本檔案
	 */
	private function register_scripts() {
		$script_path       = '/assets/js/checkout-block.js';
		$script_asset_path = JA_TW_CITY_SELECT_PLUGIN_DIR . 'assets/js/checkout-block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array( 'wp-hooks' ),
				'version'      => JA_TW_CITY_SELECT_VERSION,
			);

		wp_register_script(
			'ja-tw-city-select-checkout-block',
			JA_TW_CITY_SELECT_PLUGIN_URL . $script_path,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		// 將資料傳遞給前端
		wp_localize_script(
			'ja-tw-city-select-checkout-block',
			'jaTwCitySelectData',
			$this->get_script_data()
		);

		// 註冊樣式
		wp_register_style(
			'ja-tw-city-select-checkout-block',
			JA_TW_CITY_SELECT_PLUGIN_URL . '/assets/css/checkout-block.css',
			array(),
			JA_TW_CITY_SELECT_VERSION
		);
	}

	/**
	 * 取得腳本控制代碼陣列以在前端加入佇列
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'ja-tw-city-select-checkout-block' );
	}

	/**
	 * 取得要在前端加入佇列的編輯器腳本控制代碼陣列
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'ja-tw-city-select-checkout-block' );
	}

	/**
	 * 取得樣式表控制代碼陣列以在前端加入佇列
	 *
	 * @return string[]
	 */
	public function get_style_handles() {
		return array( 'ja-tw-city-select-checkout-block' );
	}

	/**
	 * 註冊 REST API 端點
	 */
	private function register_endpoints() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * 註冊 REST 路由
	 */
	public function register_rest_routes() {
		register_rest_route(
			'ja-tw-city-select/v1',
			'/districts/(?P<state>[a-zA-Z]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_districts_callback' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'state' => array(
						'required'          => true,
						'validate_callback' => function( $param, $request, $key ) {
							return is_string( $param );
						},
					),
				),
			)
		);
	}

	/**
	 * REST API 回呼函式：取得鄉鎮市區
	 *
	 * @param WP_REST_Request $request 請求物件
	 * @return WP_REST_Response
	 */
	public function get_districts_callback( $request ) {
		$state_code = strtoupper( $request['state'] );
		$districts  = JA_TW_Address_Data::get_districts_by_state( $state_code );

		return rest_ensure_response(
			array(
				'success'   => true,
				'districts' => $districts,
			)
		);
	}
}

