# 台灣 WooCommerce 區塊結帳欄位地址選單

**代稱:** ja-tw-city-select  
**版本:** 0.0.9  
**作者:** Jeremy  
**適用 WordPress 版本:** 6.0 或更高版本  
**適用 WooCommerce 版本:** 8.0 或更高版本  
**授權:** GPL-2.0+

---

## 概述

這是一款專為台灣 WooCommerce 商店設計的輕量級外掛，旨在解決原生 WooCommerce **區塊結帳 (Block-based Checkout)** 頁面沒有提供台灣縣市、鄉鎮市區下拉選單的問題。

本外掛能將預設的文字輸入欄位轉換為互動式的下拉選單,提供更流暢、更準確的地址填寫體驗。

## 解決的痛點

傳統上,許多台灣網站開發者會使用 `WC City Select` 或其延伸外掛 `RY City Select for WooCommerce` 來實現地址選單功能。然而,隨著 WooCommerce 核心逐漸採用基於 React.js 的「區塊結帳」取代傳統的簡碼 (shortcode) 結帳,這些依賴舊版 PHP Hooks 的外掛已無法在新版結帳頁面正常運作。

此專案的目標就是提供一個專門為**新版區塊結帳**設計的現代化解決方案。

## 主要功能

* **專為區塊結帳設計:** 100% 支援 WooCommerce 最新的區塊化結帳體驗。
* **縣市下拉選單:** 將「縣市」(State/County) 欄位轉換為下拉選單。
* **鄉鎮市區下拉選單:** 將「鄉鎮市區」(City) 欄位轉換為下拉選單。
* **動態連動:** 「鄉鎮市區」的選項會根據所選的「縣市」動態更新。
* **自動郵遞區號:** 隱藏郵遞區號欄位,但會根據鄉鎮市區選擇結果自動填入郵遞區號,以便後續運費判斷等功能的判斷基礎。
* **支援帳單與運送地址:** 同時適用於帳單地址 (Billing Address) 與運送地址 (Shipping Address)。

## 安裝需求

* WordPress 6.0 或更高版本
* WooCommerce 8.0 或更高版本
* PHP 8.0 或更高版本
* Node.js 16+ (開發環境)
* 您的結帳頁面必須是使用「結帳」區塊 (Checkout Block) 來建構,而非傳統的 `[woocommerce_checkout]` 簡碼

## 安裝步驟

### 方式一:透過 WordPress 後台安裝

1. 下載外掛 ZIP 檔案
2. 登入 WordPress 後台
3. 前往 **外掛 > 安裝外掛**
4. 點擊 **上傳外掛**
5. 選擇下載的 ZIP 檔案並安裝
6. 啟用外掛

### 方式二:手動安裝

1. 將外掛資料夾上傳到 `/wp-content/plugins/` 目錄
2. 透過 WordPress 的「外掛」選單啟用外掛

### 開發環境設定

如果您要進行開發或自訂修改:

```bash
# 安裝相依套件
npm install

# 開發模式(含熱重載)
npm run start

# 建置生產版本
npm run build
```

## 使用方式

1. 確保您的 WooCommerce 結帳頁面使用的是「結帳區塊」而非簡碼
2. 啟用外掛後,前往結帳頁面
3. 將國家/地區設定為「台灣」
4. 縣市和鄉鎮市區欄位會自動轉換為下拉選單
5. 選擇縣市後,鄉鎮市區選單會自動更新對應選項
6. 選擇鄉鎮市區後,郵遞區號會自動填入(隱藏欄位)

## 檔案結構

```
ja-tw-city-select/
├── assets/
│   ├── css/
│   │   └── checkout-block.css
│   └── js/
│       ├── checkout-block.js
│       └── checkout-block.asset.php
├── includes/
│   ├── class-tw-address-data.php
│   └── class-block-checkout-integration.php
├── languages/
│   └── ja-tw-city-select.pot
├── ja-tw-city-select.php
├── package.json
├── README.md
└── readme.txt
```

## 技術細節

### 後端整合

- 實作 `IntegrationInterface` 介面與 WooCommerce Blocks 整合
- 提供完整的台灣縣市和鄉鎮市區資料(含郵遞區號)
- 註冊 REST API 端點供前端查詢地址資料

### 前端整合

- 使用 `@woocommerce/blocks-checkout` 提供的過濾器系統
- 透過 `registerCheckoutFilters` 修改地址欄位類型
- 監聽地址變更事件並動態更新選項
- 自動處理郵遞區號填入邏輯

## 相容性

- ✅ WooCommerce 區塊結帳
- ❌ WooCommerce 簡碼結帳(不支援)
- ✅ WordPress 6.0+
- ✅ WooCommerce 8.0+
- ✅ PHP 8.0+
- ✅ WooCommerce HPOS (高效能訂單儲存)

## 常見問題

### 為什麼不支援傳統簡碼結帳?

本外掛專門針對新版區塊結帳設計。如果您仍使用 `[woocommerce_checkout]` 簡碼,建議使用其他外掛如 `RY City Select for WooCommerce`。

### 郵遞區號欄位為什麼看不到?

郵遞區號欄位被設為隱藏,但會在您選擇鄉鎮市區時自動填入。這樣可以避免使用者手動輸入錯誤的郵遞區號。

### 可以自訂地址資料嗎?

可以。您可以透過修改 `includes/class-tw-address-data.php` 檔案來自訂縣市和鄉鎮市區資料。

### 外掛支援 WooCommerce HPOS 嗎?

是的，外掛完全相容 WooCommerce 的高效能訂單儲存 (HPOS) 功能。外掛已正確宣告 HPOS 相容性，不會與 HPOS 產生衝突。

## 更新日誌

### 0.0.9 (2025-01-20)
* 正式版本發布
* 移除所有開發除錯功能
* 優化效能和穩定性
* 隱藏國家欄位（預設為台灣）

### 0.0.1 (2025-10-16)
* 初始版本發布
* 支援台灣縣市下拉選單
* 支援鄉鎮市區下拉選單
* 動態連動功能
* 自動郵遞區號填入

## 貢獻

歡迎提交 Issue 或 Pull Request 來改善這個外掛!

## 授權

本外掛採用 GPL-2.0+ 授權。

## 支援

如有任何問題或建議,請透過以下方式聯繫:

- GitHub Issues: [https://github.com/Jeremy-VVu/ja-tw-city-select/issues]

---

**注意:** 本外掛需要 WooCommerce 區塊結帳功能。請確保您的商店使用的是區塊結帳而非傳統簡碼結帳。


