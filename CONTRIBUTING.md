# 貢獻指南

感謝您考慮為「台灣 WooCommerce 區塊結帳欄位地址選單」做出貢獻!

## 如何貢獻

### 回報問題

如果您發現了 bug 或有功能建議:

1. 先檢查 [Issues](https://github.com/yourusername/ja-tw-city-select/issues) 是否已有相同的問題
2. 如果沒有,請建立新的 Issue
3. 清楚描述問題或建議
4. 如果是 bug,請提供:
   - WordPress 版本
   - WooCommerce 版本
   - PHP 版本
   - 重現步驟
   - 預期行為
   - 實際行為
   - 截圖(如果適用)

### 提交程式碼

1. **Fork 專案**
   ```bash
   git clone https://github.com/yourusername/ja-tw-city-select.git
   cd ja-tw-city-select
   ```

2. **建立分支**
   ```bash
   git checkout -b feature/your-feature-name
   # 或
   git checkout -b fix/your-bug-fix
   ```

3. **設定開發環境**
   ```bash
   # 安裝 Node.js 相依套件
   npm install
   
   # 安裝 Composer 相依套件(可選)
   composer install
   ```

4. **進行修改**
   - 遵循 [WordPress 編碼標準](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
   - 使用 snake_case 命名函式和變數
   - 使用 PascalCase 命名類別
   - 添加適當的 PHPDoc 註解

5. **測試您的變更**
   ```bash
   # 建置 JavaScript
   npm run build
   
   # 檢查 PHP 編碼標準
   composer phpcs
   
   # 自動修正編碼標準問題
   composer phpcbf
   ```

6. **提交變更**
   ```bash
   git add .
   git commit -m "feat: 您的功能描述"
   # 或
   git commit -m "fix: 您的修正描述"
   ```

7. **推送到您的 Fork**
   ```bash
   git push origin feature/your-feature-name
   ```

8. **建立 Pull Request**
   - 前往 GitHub 上的原始儲存庫
   - 點擊 "New Pull Request"
   - 選擇您的分支
   - 填寫 PR 描述
   - 等待審核

## 編碼標準

### PHP

- 遵循 [WordPress PHP 編碼標準](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- 使用 Tab 縮排(4 個空格寬度)
- 函式和變數使用 `snake_case`
- 類別使用 `PascalCase`
- 常數使用 `UPPER_SNAKE_CASE`
- 所有函式和類別必須有 PHPDoc 註解

### JavaScript

- 遵循 [WordPress JavaScript 編碼標準](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- 使用 Tab 縮排
- 使用現代 ES6+ 語法
- 使用 JSDoc 註解

### CSS

- 遵循 [WordPress CSS 編碼標準](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- 使用 Tab 縮排
- 按字母順序排列屬性
- 使用有意義的類別名稱

## Commit 訊息格式

使用語義化 commit 訊息:

- `feat:` 新增功能
- `fix:` 修正 bug
- `docs:` 文件變更
- `style:` 格式化(不影響程式碼意義)
- `refactor:` 重構(既不修正 bug 也不新增功能)
- `perf:` 效能改善
- `test:` 新增測試
- `chore:` 建置過程或輔助工具變更

範例:
```
feat: 新增高雄市新行政區
fix: 修正郵遞區號自動填入問題
docs: 更新安裝指南
```

## 開發環境設定

### 必要工具

- Node.js 16+
- npm 8+
- PHP 8.0+
- Composer
- WordPress 本地開發環境(Local, XAMPP, MAMP 等)

### 本地測試

1. 在本地 WordPress 安裝中建立符號連結:
   ```bash
   # Windows (以管理員身分執行)
   mklink /D "C:\path\to\wordpress\wp-content\plugins\ja-tw-city-select" "C:\path\to\your\dev\folder"
   
   # macOS/Linux
   ln -s /path/to/your/dev/folder /path/to/wordpress/wp-content/plugins/ja-tw-city-select
   ```

2. 在 WordPress 後台啟用外掛

3. 進行開發並測試

### 建置指令

```bash
# 開發模式(含熱重載)
npm run start

# 建置生產版本
npm run build

# 檢查 JavaScript 語法
npm run lint:js

# 檢查 CSS 語法
npm run lint:css

# 格式化程式碼
npm run format

# 檢查 PHP 編碼標準
composer phpcs

# 自動修正 PHP 編碼標準
composer phpcbf
```

## 地址資料更新

如需更新台灣地址資料:

1. 編輯 `includes/class-tw-address-data.php`
2. 更新 `get_states()` 或 `get_districts()` 方法
3. 確保資料格式正確:
   ```php
   'STATE_CODE' => array(
       'POSTCODE' => '鄉鎮市區名稱',
   ),
   ```
4. 測試變更是否正常運作

## 審核流程

1. 提交的 PR 會由維護者審核
2. 可能會要求修改或提供更多資訊
3. 通過審核後會合併到主分支
4. 貢獻者會列在 CHANGELOG 中

## 版本發布

本專案遵循[語義化版本](https://semver.org/lang/zh-TW/):

- **主版本** (x.0.0): 不相容的 API 變更
- **次版本** (0.x.0): 向下相容的功能新增
- **修訂版** (0.0.x): 向下相容的問題修正

## 行為準則

請以友善和尊重的態度對待所有貢獻者。我們致力於提供一個開放和歡迎的環境。

## 授權

提交貢獻即表示您同意您的程式碼將以 GPL-2.0+ 授權發布。

## 需要協助?

如有任何問題,歡迎:
- 建立 Issue
- 發送 Email 到 [your-email@example.com]
- 在 Discussion 中討論

感謝您的貢獻!

