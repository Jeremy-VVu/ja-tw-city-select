# 快速入門指南

本指南將幫助您快速開始使用「台灣 WooCommerce 區塊結帳欄位地址選單」外掛。

## 5 分鐘快速安裝

### 步驟 1: 檢查需求

確保您的環境符合以下需求:

- ✅ WordPress 6.0+
- ✅ WooCommerce 8.0+
- ✅ PHP 8.0+
- ✅ 使用 WooCommerce 區塊結帳(非簡碼)

### 步驟 2: 安裝外掛

**方式 A: WordPress 後台安裝**

1. 下載 `ja-tw-city-select.zip`
2. 前往 WordPress 後台 → **外掛** → **安裝外掛**
3. 點擊 **上傳外掛** → 選擇 ZIP 檔案
4. 點擊 **立即安裝** → **啟用外掛**

**方式 B: FTP 上傳**

1. 解壓縮 `ja-tw-city-select.zip`
2. 將 `ja-tw-city-select` 資料夾上傳到 `/wp-content/plugins/`
3. 前往 WordPress 後台 → **外掛** → 找到外掛並啟用

### 步驟 3: 驗證安裝

1. 前往您的 **WooCommerce 結帳頁面**
2. 將國家/地區設定為 **台灣**
3. 確認看到:
   - ✅ 縣市欄位變成下拉選單
   - ✅ 鄉鎮市區欄位變成下拉選單
   - ✅ 郵遞區號欄位已隱藏

### 步驟 4: 測試功能

1. **測試縣市選擇:**
   - 點擊縣市下拉選單
   - 選擇任一縣市(例如: 台北市)

2. **測試鄉鎮市區連動:**
   - 確認鄉鎮市區選單自動更新
   - 顯示該縣市的所有鄉鎮市區

3. **測試郵遞區號:**
   - 選擇一個鄉鎮市區
   - 打開瀏覽器開發者工具(F12)
   - 檢查郵遞區號欄位是否自動填入

## 常見問題快速解決

### ❌ 地址欄位沒有變成下拉選單

**檢查清單:**

1. 確認國家/地區是否設定為「台灣」
2. 確認結帳頁面使用的是「區塊」而非簡碼
   ```
   錯誤: [woocommerce_checkout]
   正確: WooCommerce 結帳區塊
   ```
3. 清除瀏覽器快取 (Ctrl + Shift + Delete)
4. 清除 WordPress 快取(如使用快取外掛)

### ❌ 主控台出現 JavaScript 錯誤

**解決方案:**

1. 確認檔案是否正確上傳:
   ```
   /wp-content/plugins/ja-tw-city-select/assets/js/checkout-block.js
   ```

2. 重新安裝外掛:
   - 停用並刪除外掛
   - 重新上傳並啟用

### ❌ 鄉鎮市區選單沒有更新

**可能原因:**

1. 瀏覽器快取 → 清除快取
2. 衝突的外掛 → 停用其他地址相關外掛
3. 主題衝突 → 切換到預設主題測試

## 轉換傳統結帳到區塊結帳

如果您目前使用傳統簡碼結帳,請按照以下步驟轉換:

### 方法 1: 直接轉換(推薦)

1. 前往 **頁面** → 找到您的結帳頁面
2. 點擊 **編輯**
3. 移除 `[woocommerce_checkout]` 簡碼
4. 點擊 **+** 新增區塊
5. 搜尋並插入 **WooCommerce 結帳區塊**
6. 點擊 **更新**

### 方法 2: 建立新頁面

1. 前往 **頁面** → **新增頁面**
2. 標題輸入「結帳」
3. 插入 **WooCommerce 結帳區塊**
4. 發佈頁面
5. 前往 **WooCommerce** → **設定** → **進階** → **頁面設定**
6. 將「結帳頁面」設定為新建立的頁面
7. 儲存變更

## 開發者快速開始

### 本地開發環境設定

```bash
# 1. 進入外掛目錄
cd wp-content/plugins/ja-tw-city-select

# 2. 安裝相依套件
npm install

# 3. 啟動開發模式
npm run start

# 4. 進行開發...

# 5. 建置生產版本
npm run build
```

### 自訂地址資料

編輯 `includes/class-tw-address-data.php`:

```php
// 新增縣市
public static function get_states() {
    return array(
        'TPE' => '台北市',
        'NEW' => '新縣市', // 新增的縣市
        // ...
    );
}

// 新增鄉鎮市區
public static function get_districts() {
    return array(
        'NEW' => array(
            '100' => '新區域',
            '101' => '其他區域',
        ),
        // ...
    );
}
```

### 擴充功能範例

**範例 1: 修改下拉選單樣式**

編輯 `assets/css/checkout-block.css`:

```css
.wc-block-components-address-form__state select,
.wc-block-components-address-form__city select {
    /* 您的自訂樣式 */
    background-color: #f0f0f0;
    border: 2px solid #333;
    border-radius: 8px;
}
```

**範例 2: 新增自訂事件監聽器**

編輯 `assets/js/checkout-block.js`:

```javascript
// 在選擇鄉鎮市區後觸發自訂事件
const handleCityChange = ( context, stateCode, cityName ) => {
    // 原有邏輯...
    
    // 觸發自訂事件
    document.dispatchEvent(new CustomEvent('jaTwCitySelected', {
        detail: { context, stateCode, cityName }
    }));
};
```

## 效能優化建議

### 1. 使用快取外掛

推薦的快取外掛:
- WP Rocket
- W3 Total Cache
- WP Super Cache

### 2. 啟用 Gzip 壓縮

在 `.htaccess` 中加入:

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
</IfModule>
```

### 3. 使用 CDN

將靜態資源(CSS/JS)移到 CDN 可加快載入速度。

## 多語言支援

### 新增翻譯

1. 複製 `languages/ja-tw-city-select.pot`
2. 使用 Poedit 開啟
3. 翻譯字串
4. 儲存為 `ja-tw-city-select-{locale}.po` 和 `.mo`
5. 上傳到 `languages/` 目錄

### 支援的翻譯字串

- "請選擇縣市"
- "請選擇鄉鎮市區"
- 錯誤訊息

## 測試檢查清單

在正式環境部署前,請完成以下測試:

- [ ] 安裝並啟用外掛
- [ ] 前往結帳頁面
- [ ] 設定國家為台灣
- [ ] 測試縣市下拉選單
- [ ] 測試鄉鎮市區下拉選單
- [ ] 驗證郵遞區號自動填入
- [ ] 測試帳單地址
- [ ] 測試運送地址
- [ ] 測試不同縣市切換
- [ ] 在手機上測試
- [ ] 在平板上測試
- [ ] 測試不同瀏覽器(Chrome, Firefox, Safari, Edge)
- [ ] 完成一筆測試訂單
- [ ] 檢查訂單資料是否正確儲存

## 取得更多協助

### 文件

- 📖 [README.md](README.md) - 完整功能說明
- 📖 [INSTALL.md](INSTALL.md) - 詳細安裝指南
- 📖 [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - 專案結構說明
- 📖 [CONTRIBUTING.md](CONTRIBUTING.md) - 貢獻指南

### 支援管道

- 🐛 [GitHub Issues](https://github.com/yourusername/ja-tw-city-select/issues) - 回報問題
- 💬 [GitHub Discussions](https://github.com/yourusername/ja-tw-city-select/discussions) - 討論區
- 📧 Email: your-email@example.com

### 社群

- ⭐ 如果覺得有用,請在 GitHub 給我們一個 Star!
- 🤝 歡迎貢獻程式碼或回報問題
- 📣 分享給其他台灣的 WooCommerce 使用者

---

**恭喜!您已完成快速入門。開始享受更好的結帳體驗吧!** 🎉

