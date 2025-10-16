# WooCommerce HPOS 相容性說明

## 概述

本外掛「台灣 WooCommerce 區塊結帳欄位鄉鎮市區選單」已完全相容 WooCommerce 的**高效能訂單儲存** (High-Performance Order Storage, HPOS) 功能。

## 什麼是 HPOS？

HPOS 是 WooCommerce 的一項新功能，它將訂單資料從傳統的 WordPress posts 表格移動到專門的訂單表格中，以提升效能和可擴充性。

## 相容性確認

### ✅ 外掛標頭宣告

外掛主檔案 `ja-tw-city-select.php` 已包含 HPOS 相容性宣告：

```php
/**
 * HPOS: yes
 */
```

### ✅ 程式碼宣告

在 `JA_TW_City_Select` 類別中已加入 HPOS 相容性宣告：

```php
/**
 * 宣告 HPOS 相容性
 */
public function declare_hpos_compatibility() {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
}
```

### ✅ 資料處理方式

本外掛的設計完全符合 HPOS 要求：

1. **不直接操作訂單資料**
   - 外掛僅修改結帳表單的欄位類型
   - 不直接讀取或寫入訂單資料庫

2. **使用 WooCommerce Blocks API**
   - 透過 `registerCheckoutFilters` 修改表單欄位
   - 使用 WooCommerce 官方 API 處理地址資料

3. **靜態地址資料**
   - 地址資料儲存在靜態陣列中
   - 不依賴資料庫查詢

## 技術實作細節

### 地址資料處理

```php
// 靜態資料，不涉及資料庫操作
class JA_TW_Address_Data {
    public static function get_states() {
        return array(
            'TPE' => '台北市',
            'TXG' => '台中市',
            // ...
        );
    }
}
```

### 前端整合

```javascript
// 使用 WooCommerce Blocks API
import { registerCheckoutFilters } from '@woocommerce/blocks-checkout';

// 修改表單欄位，不直接操作訂單
const modifyAddressFields = ( fields, extensions, args ) => {
    // 僅修改欄位類型，不觸及訂單資料
};
```

### REST API 端點

```php
// 僅提供地址資料查詢，不涉及訂單操作
public function get_districts_callback( $request ) {
    $state_code = strtoupper( $request['state'] );
    $districts  = JA_TW_Address_Data::get_districts_by_state( $state_code );
    
    return rest_ensure_response( array(
        'success'   => true,
        'districts' => $districts,
    ) );
}
```

## 驗證相容性

### 在 WooCommerce 後台檢查

1. 前往 **WooCommerce > 設定 > 進階 > 功能**
2. 確認「高效能訂單儲存」已啟用
3. 前往 **外掛 > 已安裝的外掛**
4. 確認本外掛不再顯示「與 WooCommerce 功能不相容」警告

### 程式碼驗證

您可以使用以下程式碼驗證外掛的 HPOS 相容性：

```php
// 檢查外掛是否宣告 HPOS 相容性
if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
    $compatibility = \Automattic\WooCommerce\Utilities\FeaturesUtil::get_compatible_plugins_for_feature( 'custom_order_tables' );
    
    if ( in_array( 'ja-tw-city-select/ja-tw-city-select.php', $compatibility ) ) {
        echo '外掛已正確宣告 HPOS 相容性';
    }
}
```

## 常見問題

### Q: 為什麼之前顯示不相容？

A: 之前的版本沒有在外掛標頭和程式碼中宣告 HPOS 相容性，導致 WooCommerce 無法識別外掛是否支援 HPOS。

### Q: 啟用 HPOS 後外掛還能正常運作嗎？

A: 是的，外掛的運作方式完全不變。HPOS 只影響訂單資料的儲存方式，不影響結帳表單的運作。

### Q: 需要重新安裝外掛嗎？

A: 不需要，只需要重新啟用外掛即可。WooCommerce 會重新檢查外掛的相容性宣告。

### Q: 如何確認 HPOS 已啟用？

A: 在 WooCommerce 後台前往 **設定 > 進階 > 功能**，確認「高效能訂單儲存」狀態為「已啟用」。

## 效能優勢

使用 HPOS 後，您的商店將獲得以下效能提升：

1. **更快的訂單查詢**
2. **更好的資料庫效能**
3. **更強的擴充性**
4. **更穩定的訂單處理**

## 技術支援

如果您在使用過程中遇到任何 HPOS 相關問題：

1. 確認 WooCommerce 版本為 8.0 或更高
2. 確認外掛版本為 0.0.1 或更高
3. 檢查 WooCommerce 後台的功能設定
4. 重新啟用外掛

## 更新記錄

- **v0.0.1 (2025-10-16)**: 初始版本，完全支援 HPOS

---

**注意**: 本外掛從設計之初就考慮了 HPOS 相容性，因此不會有任何功能上的限制或問題。
