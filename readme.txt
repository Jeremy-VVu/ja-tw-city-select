=== 台灣 WooCommerce 區塊結帳欄位地址選單 ===
Contributors: jeremy
Tags: woocommerce, taiwan, address, checkout, block, 台灣, 地址, 結帳
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.0
Stable tag: 0.0.9
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

專為台灣 WooCommerce 商店設計的區塊結帳地址選單外掛，提供縣市、鄉鎮市區下拉選單及自動郵遞區號填入功能。

== Description ==

這是一款專為台灣 WooCommerce 商店設計的輕量級外掛，旨在解決原生 WooCommerce **區塊結帳 (Block-based Checkout)** 頁面沒有提供台灣縣市、鄉鎮市區下拉選單的問題。

本外掛能將預設的文字輸入欄位轉換為互動式的下拉選單，提供更流暢、更準確的地址填寫體驗。

= 解決的痛點 =

傳統上，許多台灣網站開發者會使用 `WC City Select` 或其延伸外掛 `RY City Select for WooCommerce` 來實現地址選單功能。然而，隨著 WooCommerce 核心逐漸採用基於 React.js 的「區塊結帳」取代傳統的簡碼 (shortcode) 結帳，這些依賴舊版 PHP Hooks 的外掛已無法在新版結帳頁面正常運作。

此專案的目標就是提供一個專門為**新版區塊結帳**設計的現代化解決方案。

= 主要功能 =

* **專為區塊結帳設計:** 100% 支援 WooCommerce 最新的區塊化結帳體驗
* **縣市下拉選單:** 將「縣市」(State/County) 欄位轉換為下拉選單
* **鄉鎮市區下拉選單:** 將「鄉鎮市區」(City) 欄位轉換為下拉選單
* **動態連動:** 「鄉鎮市區」的選項會根據所選的「縣市」動態更新
* **自動郵遞區號:** 隱藏郵遞區號欄位，但會根據鄉鎮市區選擇結果自動填入郵遞區號
* **支援帳單與運送地址:** 同時適用於帳單地址 (Billing Address) 與運送地址 (Shipping Address)
* **完整地址資料:** 包含台灣 22 縣市及所有鄉鎮市區的完整資料

= 系統需求 =

* WordPress 6.0 或更高版本
* WooCommerce 8.0 或更高版本
* PHP 8.0 或更高版本
* 您的結帳頁面必須是使用「結帳」區塊 (Checkout Block) 來建構，而非傳統的 `[woocommerce_checkout]` 簡碼

= 使用方式 =

1. 安裝並啟用外掛
2. 確保您的結帳頁面使用的是「結帳區塊」而非簡碼
3. 前往結帳頁面並將國家/地區設定為「台灣」
4. 縣市和鄉鎮市區欄位會自動轉換為下拉選單
5. 選擇縣市後，鄉鎮市區選單會自動更新
6. 選擇鄉鎮市區後，郵遞區號會自動填入

== Installation ==

= 自動安裝 =

1. 登入 WordPress 管理後台
2. 前往「外掛」>「安裝外掛」
3. 搜尋「台灣 WooCommerce 區塊結帳欄位地址選單」
4. 點擊「立即安裝」
5. 安裝完成後點擊「啟用」

= 手動安裝 =

1. 下載外掛 ZIP 檔案
2. 登入 WordPress 管理後台
3. 前往「外掛」>「安裝外掛」
4. 點擊「上傳外掛」
5. 選擇下載的 ZIP 檔案並安裝
6. 啟用外掛

= FTP 安裝 =

1. 將外掛資料夾上傳到 `/wp-content/plugins/` 目錄
2. 透過 WordPress 的「外掛」選單啟用外掛

== Frequently Asked Questions ==

= 為什麼不支援傳統簡碼結帳? =

本外掛專門針對新版區塊結帳設計。如果您仍使用 `[woocommerce_checkout]` 簡碼，建議使用其他外掛如 `RY City Select for WooCommerce`。

= 郵遞區號欄位為什麼看不到? =

郵遞區號欄位被設為隱藏，但會在您選擇鄉鎮市區時自動填入。這樣可以避免使用者手動輸入錯誤的郵遞區號。

= 可以自訂地址資料嗎? =

可以。您可以透過修改 `includes/class-tw-address-data.php` 檔案來自訂縣市和鄉鎮市區資料。

= 如何確認我的結帳頁面是否使用區塊結帳? =

在 WordPress 後台編輯結帳頁面，如果看到 WooCommerce 結帳區塊而非 `[woocommerce_checkout]` 簡碼，就是使用區塊結帳。

= 外掛支援多站台嗎? =

是的，外掛完全支援 WordPress 多站台環境。

= 外掛支援 WooCommerce HPOS 嗎? =

是的，外掛完全相容 WooCommerce 的高效能訂單儲存 (HPOS) 功能。外掛已正確宣告 HPOS 相容性，不會與 HPOS 產生衝突。

== Screenshots ==

1. 結帳頁面顯示縣市下拉選單
2. 選擇縣市後鄉鎮市區選單動態更新
3. 完整的台灣地址選擇流程
4. 響應式設計支援行動裝置

== Changelog ==

= 0.0.1 - 2025-10-16 =
* 初始版本發布
* 支援 WooCommerce 區塊結帳
* 台灣縣市下拉選單功能
* 鄉鎮市區下拉選單功能
* 縣市與鄉鎮市區動態連動
* 自動郵遞區號填入
* 支援帳單地址和運送地址
* 完整的台灣 22 縣市資料
* 響應式設計支援
* 完全相容 WooCommerce HPOS (高效能訂單儲存)

== Upgrade Notice ==

= 0.0.1 =
初始版本發布。

== Additional Info ==

= 技術支援 =

如有任何問題或建議，請透過以下方式聯繫：
* GitHub: https://github.com/yourusername/ja-tw-city-select
* Email: your-email@example.com

= 貢獻 =

歡迎提交 Issue 或 Pull Request 來改善這個外掛！

= 授權 =

本外掛採用 GPL-2.0+ 授權。