<?php
/**
 * 簡化的結帳整合類別
 *
 * @package JA_TW_City_Select
 */

// 防止直接訪問檔案
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 簡化的結帳整合實作
 */
class JA_TW_Simple_Checkout_Integration {

	/**
	 * 初始化整合
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'add_inline_script' ) );
	}

	/**
	 * 載入腳本和樣式
	 */
	public function enqueue_scripts() {
		// 只在結帳頁面載入
		if ( ! is_checkout() ) {
			return;
		}


		// 載入主要 JavaScript
		wp_enqueue_script(
			'ja-tw-city-select-checkout-block',
			JA_TW_CITY_SELECT_PLUGIN_URL . '/assets/js/checkout-block.js',
			array( 'jquery', 'wp-hooks' ),
			JA_TW_CITY_SELECT_VERSION,
			true
		);

		// 載入 CSS
		wp_enqueue_style(
			'ja-tw-city-select-checkout-block',
			JA_TW_CITY_SELECT_PLUGIN_URL . '/assets/css/checkout-block.css',
			array(),
			JA_TW_CITY_SELECT_VERSION
		);

		// 傳遞資料給前端
		wp_localize_script(
			'ja-tw-city-select-checkout-block',
			'jaTwCitySelectData',
			array(
				'states'    => JA_TW_Address_Data::get_states(),
				'districts' => JA_TW_Address_Data::get_districts(),
				'i18n'      => array(
					'selectState'    => __( '請選擇縣市', 'ja-tw-city-select' ),
					'selectDistrict' => __( '請選擇鄉鎮市區', 'ja-tw-city-select' ),
				),
			)
		);
	}

	/**
	 * 添加內嵌腳本
	 */
	public function add_inline_script() {
		// 只在結帳頁面執行
		if ( ! is_checkout() ) {
			return;
		}
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// 等待 WooCommerce Blocks 載入
			var checkBlocks = function() {
				if (typeof wc !== 'undefined' && wc.blocksCheckout) {
					// WooCommerce Blocks 已載入
					initTaiwanAddressSelect();
				} else {
					// 等待 100ms 後重試
					setTimeout(checkBlocks, 100);
				}
			};
			checkBlocks();

			function initTaiwanAddressSelect() {
				// 監聽國家變更
				$(document).on('change', 'select[name*="country"]', function() {
					var country = $(this).val();
					if (country === 'TW') {
						convertToDropdowns();
					}
				});

				// 檢查是否已經是台灣
				if ($('select[name*="country"]').val() === 'TW') {
					convertToDropdowns();
				}
			}

			function convertToDropdowns() {
				// 轉換縣市欄位
				convertStateField();
				// 轉換鄉鎮市區欄位
				convertCityField();
				// 隱藏郵遞區號欄位
				hidePostcodeField();
			}

			function convertStateField() {
				var stateFields = $('input[name*="state"], select[name*="state"]').not('.ja-tw-converted');
				
				stateFields.each(function() {
					var $field = $(this);
					var fieldName = $field.attr('name');
					var currentValue = $field.val();
					
					// 建立下拉選單
					var $select = $('<select>').attr('name', fieldName).addClass('ja-tw-converted');
					
					// 添加預設選項
					$select.append('<option value="">請選擇縣市</option>');
					
					// 添加縣市選項
					if (window.jaTwCitySelectData && window.jaTwCitySelectData.states) {
						$.each(window.jaTwCitySelectData.states, function(key, label) {
							var $option = $('<option>').attr('value', key).text(label);
							if (key === currentValue) {
								$option.prop('selected', true);
							}
							$select.append($option);
						});
					}
					
					// 替換原始欄位
					$field.replaceWith($select);
					
					// 監聽變更事件
					$select.on('change', function() {
						var stateCode = $(this).val();
						updateCityOptions(fieldName, stateCode);
						clearPostcode(fieldName);
					});
				});
			}

			function convertCityField() {
				var cityFields = $('input[name*="city"], select[name*="city"]').not('.ja-tw-converted');
				
				cityFields.each(function() {
					var $field = $(this);
					var fieldName = $field.attr('name');
					var currentValue = $field.val();
					
					// 建立下拉選單
					var $select = $('<select>').attr('name', fieldName).addClass('ja-tw-converted');
					
					// 添加預設選項
					$select.append('<option value="">請選擇鄉鎮市區</option>');
					
					// 替換原始欄位
					$field.replaceWith($select);
					
					// 監聽變更事件
					$select.on('change', function() {
						var cityName = $(this).val();
						var stateFieldName = fieldName.replace('city', 'state');
						var stateCode = $('select[name="' + stateFieldName + '"]').val();
						updatePostcode(fieldName, stateCode, cityName);
					});
				});
			}

			function updateCityOptions(fieldName, stateCode) {
				var cityFieldName = fieldName.replace('state', 'city');
				var $citySelect = $('select[name="' + cityFieldName + '"]');
				
				if (!$citySelect.length) return;
				
				// 清空選項
				$citySelect.empty().append('<option value="">請選擇鄉鎮市區</option>');
				
				// 添加鄉鎮市區選項
				if (stateCode && window.jaTwCitySelectData && window.jaTwCitySelectData.districts && window.jaTwCitySelectData.districts[stateCode]) {
					$.each(window.jaTwCitySelectData.districts[stateCode], function(postcode, districtName) {
						$citySelect.append('<option value="' + districtName + '" data-postcode="' + postcode + '">' + districtName + '</option>');
					});
				}
			}

			function updatePostcode(fieldName, stateCode, cityName) {
				var postcodeFieldName = fieldName.replace('city', 'postcode');
				var $postcodeField = $('input[name="' + postcodeFieldName + '"]');
				
				if (!$postcodeField.length || !stateCode || !cityName) return;
				
				// 查找對應的郵遞區號
				if (window.jaTwCitySelectData && window.jaTwCitySelectData.districts && window.jaTwCitySelectData.districts[stateCode]) {
					$.each(window.jaTwCitySelectData.districts[stateCode], function(postcode, districtName) {
						if (districtName === cityName) {
							$postcodeField.val(postcode);
							return false;
						}
					});
				}
			}

			function hidePostcodeField() {
				$('input[name*="postcode"]').closest('.form-row, .wc-block-components-address-form__postcode').hide();
			}

			function clearPostcode(fieldName) {
				var postcodeFieldName = fieldName.replace('state', 'postcode');
				$('input[name="' + postcodeFieldName + '"]').val('');
			}
		});
		</script>
		<?php
	}
}
