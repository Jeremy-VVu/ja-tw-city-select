# 專案結構說明

本文件說明「台灣 WooCommerce 區塊結帳欄位地址選單」外掛的檔案結構與各檔案用途。

## 目錄結構

```
ja-tw-city-select/
├── assets/                          # 前端資源檔案
│   ├── css/
│   │   └── checkout-block.css      # 結帳區塊樣式
│   ├── js/
│   │   ├── checkout-block.js       # 主要前端邏輯(ES6+)
│   │   └── checkout-block.asset.php # JavaScript 相依套件定義
│   └── index.php                    # 防止目錄瀏覽
│
├── includes/                        # PHP 核心類別
│   ├── class-tw-address-data.php   # 台灣地址資料類別
│   ├── class-block-checkout-integration.php # WooCommerce Blocks 整合
│   └── index.php                    # 防止目錄瀏覽
│
├── languages/                       # 多語言檔案
│   ├── ja-tw-city-select.pot       # 翻譯模板
│   └── index.php                    # 防止目錄瀏覽
│
├── ja-tw-city-select.php           # 外掛主檔案
├── uninstall.php                    # 解除安裝清理腳本
├── index.php                        # 防止目錄瀏覽
│
├── README.md                        # 專案說明(GitHub)
├── readme.txt                       # 外掛說明(WordPress.org)
├── CHANGELOG.md                     # 更新日誌
├── INSTALL.md                       # 安裝指南
├── CONTRIBUTING.md                  # 貢獻指南
├── PROJECT_STRUCTURE.md             # 本文件
│
├── package.json                     # Node.js 專案設定
├── composer.json                    # PHP 專案設定
├── webpack.config.js                # Webpack 建置設定
│
├── .gitignore                       # Git 忽略檔案清單
├── .editorconfig                    # 編輯器設定
├── .npmrc                          # npm 設定
└── phpcs.xml                        # PHP 編碼標準設定
```

## 核心檔案說明

### 外掛主檔案

#### `ja-tw-city-select.php`
- 外掛入口點
- 定義外掛常數和元資料
- 實作單例模式的主類別 `JA_TW_City_Select`
- 處理外掛啟用/停用邏輯
- 檢查相依性(WooCommerce)
- 載入其他核心類別

**主要功能:**
- `init()`: 初始化外掛
- `load_dependencies()`: 載入相依檔案
- `init_blocks_integration()`: 初始化區塊整合
- `activate()`: 啟用時執行
- `deactivate()`: 停用時執行

### 核心類別 (includes/)

#### `class-tw-address-data.php`
提供台灣地址資料的靜態類別。

**主要方法:**
- `get_states()`: 取得所有縣市資料
- `get_districts()`: 取得所有鄉鎮市區資料(含郵遞區號)
- `get_districts_by_state($state_code)`: 根據縣市取得鄉鎮市區
- `get_district_name_by_postcode($postcode, $state_code)`: 根據郵遞區號取得鄉鎮市區名稱

**資料格式:**
```php
// 縣市
'TXG' => '台中市'

// 鄉鎮市區
'TXG' => array(
    '400' => '中區',
    '401' => '東區',
    // ...
)
```

#### `class-block-checkout-integration.php`
實作 WooCommerce Blocks 整合介面。

**主要方法:**
- `initialize()`: 初始化整合
- `get_name()`: 取得整合名稱
- `get_script_data()`: 提供給前端的資料
- `get_script_handles()`: JavaScript 檔案控制代碼
- `get_style_handles()`: CSS 檔案控制代碼
- `register_rest_routes()`: 註冊 REST API 端點
- `get_districts_callback()`: REST API 回呼函式

**REST API 端點:**
- `GET /wp-json/ja-tw-city-select/v1/districts/{state}`: 取得指定縣市的鄉鎮市區資料

### 前端資源 (assets/)

#### `assets/js/checkout-block.js`
WooCommerce Block Checkout 前端邏輯。

**主要功能:**
- 修改地址欄位為下拉選單
- 監聽縣市變更事件
- 動態更新鄉鎮市區選項
- 自動填入郵遞區號
- 隱藏郵遞區號欄位

**技術實作:**
- 使用 `@woocommerce/blocks-checkout` 的 `registerCheckoutFilters`
- 使用 `@wordpress/data` 進行狀態管理
- 使用 `@wordpress/element` 的 React Hooks

#### `assets/js/checkout-block.asset.php`
定義 JavaScript 檔案的相依套件和版本。

```php
return array(
    'dependencies' => array(
        'wp-element',
        'wp-i18n',
        'wp-data',
        'wc-blocks-checkout',
    ),
    'version' => '0.0.1',
);
```

#### `assets/css/checkout-block.css`
結帳區塊的自訂樣式。

**包含樣式:**
- 下拉選單基本樣式
- Focus 狀態樣式
- 錯誤狀態樣式
- 禁用狀態樣式
- 響應式設計
- 載入動畫

### 設定檔案

#### `package.json`
Node.js 專案設定,定義前端建置流程。

**主要腳本:**
- `npm run build`: 建置生產版本
- `npm run start`: 開發模式(熱重載)
- `npm run lint:js`: JavaScript 語法檢查
- `npm run lint:css`: CSS 語法檢查

#### `composer.json`
PHP 專案設定,定義 PHP 相依套件和開發工具。

**開發相依套件:**
- `wp-coding-standards/wpcs`: WordPress 編碼標準
- `phpcompatibility/php-compatibility`: PHP 相容性檢查

#### `webpack.config.js`
Webpack 建置設定,定義如何打包 JavaScript 檔案。

#### `phpcs.xml`
PHP CodeSniffer 設定,定義 PHP 編碼標準規則。

**規則:**
- 遵循 WordPress 編碼標準
- 檢查 PHP 8.0+ 相容性
- 檢查文字域(text domain)
- 要求 PHPDoc 註解

### 文件檔案

#### `README.md`
GitHub 專案說明,包含:
- 專案概述
- 功能介紹
- 安裝指南
- 使用方式
- 技術細節
- 常見問題

#### `readme.txt`
WordPress.org 外掛目錄格式的說明檔。

#### `INSTALL.md`
詳細的安裝步驟指南,包含:
- 系統需求
- 安裝方式(WordPress 後台/FTP/WP-CLI)
- 驗證安裝
- 故障排除

#### `CHANGELOG.md`
版本更新記錄,遵循語義化版本規範。

#### `CONTRIBUTING.md`
貢獻者指南,說明:
- 如何回報問題
- 如何提交程式碼
- 編碼標準
- Commit 訊息格式
- 審核流程

### 其他檔案

#### `uninstall.php`
外掛解除安裝時執行的清理腳本。

**清理項目:**
- 外掛選項
- 暫存資料
- 多站台支援

#### `index.php` (各目錄)
防止直接存取目錄,增強安全性。

#### `.gitignore`
Git 版本控制忽略清單。

#### `.editorconfig`
統一不同編輯器的編碼風格設定。

#### `.npmrc`
npm 特定設定,如 legacy-peer-deps。

## 資料流程

### 1. 外掛載入流程

```
WordPress 載入外掛
    ↓
ja-tw-city-select.php 執行
    ↓
JA_TW_City_Select::get_instance()
    ↓
init() 檢查 WooCommerce
    ↓
load_dependencies() 載入類別
    ↓
init_blocks_integration() 註冊整合
    ↓
JA_TW_Block_Checkout_Integration 初始化
    ↓
註冊腳本和樣式
```

### 2. 前端渲染流程

```
結帳頁面載入
    ↓
WooCommerce Blocks 渲染
    ↓
載入 checkout-block.js
    ↓
registerCheckoutFilters 註冊過濾器
    ↓
修改地址欄位為下拉選單
    ↓
渲染縣市和鄉鎮市區選單
```

### 3. 地址選擇流程

```
使用者選擇縣市
    ↓
handleStateChange() 觸發
    ↓
清空鄉鎮市區和郵遞區號
    ↓
更新鄉鎮市區選項
    ↓
使用者選擇鄉鎮市區
    ↓
handleCityChange() 觸發
    ↓
查找對應郵遞區號
    ↓
自動填入郵遞區號(隱藏欄位)
```

## 開發工作流程

### 1. 新增或修改功能

```bash
# 1. 建立功能分支
git checkout -b feature/new-feature

# 2. 進行開發
# 編輯相關檔案

# 3. 啟動開發模式(自動重新建置)
npm run start

# 4. 在瀏覽器中測試

# 5. 建置生產版本
npm run build

# 6. 檢查編碼標準
composer phpcs
npm run lint:js

# 7. 提交變更
git add .
git commit -m "feat: 新功能描述"
git push origin feature/new-feature
```

### 2. 修正 Bug

```bash
# 1. 建立修正分支
git checkout -b fix/bug-description

# 2. 修正問題

# 3. 測試修正

# 4. 提交並推送
git commit -m "fix: bug 描述"
git push origin fix/bug-description
```

### 3. 更新地址資料

1. 編輯 `includes/class-tw-address-data.php`
2. 更新 `get_states()` 或 `get_districts()` 方法
3. 測試更新是否正常
4. 提交變更

## 測試檢查清單

在發布前請確認:

- [ ] PHP 語法檢查通過 (`composer phpcs`)
- [ ] JavaScript 語法檢查通過 (`npm run lint:js`)
- [ ] 建置無錯誤 (`npm run build`)
- [ ] 在本地 WordPress 環境測試
- [ ] 測試帳單地址選單
- [ ] 測試運送地址選單
- [ ] 測試縣市變更時鄉鎮市區更新
- [ ] 測試郵遞區號自動填入
- [ ] 測試在不同瀏覽器運作
- [ ] 測試響應式設計(手機/平板)
- [ ] 檢查主控台無錯誤訊息
- [ ] 更新 CHANGELOG.md
- [ ] 更新版本號碼

## 版本發布流程

1. 更新所有檔案中的版本號碼:
   - `ja-tw-city-select.php` (外掛標頭)
   - `package.json`
   - `readme.txt`
   - `CHANGELOG.md`

2. 建置生產版本:
   ```bash
   npm run build
   ```

3. 建立 Git 標籤:
   ```bash
   git tag -a v0.0.2 -m "版本 0.0.2"
   git push origin v0.0.2
   ```

4. 建立 GitHub Release

5. 準備部署套件(排除開發檔案)

## 技術棧

**後端:**
- PHP 8.0+
- WordPress 6.0+
- WooCommerce 8.0+

**前端:**
- React (透過 WordPress Gutenberg)
- ES6+ JavaScript
- CSS3

**建置工具:**
- Webpack 5
- @wordpress/scripts
- Babel

**程式碼品質:**
- PHP_CodeSniffer
- WordPress Coding Standards
- ESLint

**相依套件:**
- @woocommerce/blocks-checkout
- @wordpress/data
- @wordpress/element
- @wordpress/i18n

---

本文件隨專案發展持續更新。最後更新: 2025-10-16

