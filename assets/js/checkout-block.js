/**
 * WooCommerce Block Checkout 台灣地址整合
 *
 * @package JA_TW_City_Select
 */

(function() {
    'use strict';

    // 等待 DOM 載入完成
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // 檢查資料是否存在
        if (!window.jaTwCitySelectData) {
            console.warn('JA TW City Select: 資料未載入');
            return;
        }


        // 等待頁面完全載入
        setTimeout(initTaiwanAddressSelect, 500);
    }

    function initTaiwanAddressSelect() {
        // 根據實際 HTML 結構查找國家欄位
        var countryField = document.querySelector('#billing-country, #shipping-country');

        if (!countryField) {
            setTimeout(initTaiwanAddressSelect, 1000);
            return;
        }

        // 監聽國家變更
        countryField.addEventListener('change', handleCountryChange);

        // 檢查當前國家
        var currentCountry = getCountryValue();
        if (currentCountry === 'TW') {
            convertToDropdowns();
        }
    }

    function findFields(selectors) {
        var fields = [];
        selectors.forEach(function(selector) {
            var elements = document.querySelectorAll(selector);
            for (var i = 0; i < elements.length; i++) {
                if (fields.indexOf(elements[i]) === -1) {
                    fields.push(elements[i]);
                }
            }
        });
        return fields;
    }

    function getCountryValue() {
        // 根據實際 HTML 結構查找國家欄位
        var countrySelect = document.querySelector('#billing-country, #shipping-country');
        if (countrySelect) {
            return countrySelect.value;
        }
        return null;
    }

    function handleCountryChange(event) {
        if (event.target.value === 'TW') {
            convertToDropdowns();
        }
    }

    function convertToDropdowns() {
        convertStateField();
        convertCityField();
        hidePostcodeField();
    }

    function convertStateField() {
        // 根據實際 HTML 結構查找縣市欄位
        var stateFields = findFields([
            '#billing-state',
            '#shipping-state',
            'input[id*="state"]',
            'select[id*="state"]'
        ]).filter(function(field) {
            return !field.classList.contains('ja-tw-converted');
        });

        stateFields.forEach(function(field) {
            var fieldName = field.name || field.id; // 使用 name 或 id 作為識別
            var currentValue = field.value;
            var container = field.closest('.wc-block-components-text-input');

            // 建立下拉選單
            var select = document.createElement('select');
            select.name = field.name; // 保持原始 name 屬性
            select.id = field.id; // 保持原始 id 屬性
            select.className = field.className + ' ja-tw-converted';
            select.setAttribute('data-original-type', field.type);

            // 添加預設選項
            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '請選擇縣市';
            select.appendChild(defaultOption);

            // 添加縣市選項
            var states = window.jaTwCitySelectData.states;
            for (var key in states) {
                if (states.hasOwnProperty(key)) {
                    var option = document.createElement('option');
                    option.value = key;
                    option.textContent = states[key];
                    if (key === currentValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                }
            }

            // 替換原始欄位
            if (container) {
                container.replaceChild(select, field);
            } else {
                field.parentNode.replaceChild(select, field);
            }

            // 監聽變更事件
            select.addEventListener('change', function() {
                var stateCode = this.value;
                var fieldId = this.id;
                updateCityOptions(fieldId, stateCode);
                clearPostcode(fieldId);
            });

        });
    }

    function convertCityField() {
        // 根據實際 HTML 結構查找鄉鎮市區欄位
        var cityFields = findFields([
            '#billing-city',
            '#shipping-city',
            'input[id*="city"]',
            'select[id*="city"]'
        ]).filter(function(field) {
            return !field.classList.contains('ja-tw-converted');
        });

        cityFields.forEach(function(field) {
            var fieldName = field.name || field.id;
            var container = field.closest('.wc-block-components-text-input');

            // 建立下拉選單
            var select = document.createElement('select');
            select.name = field.name; // 保持原始 name 屬性
            select.id = field.id; // 保持原始 id 屬性
            select.className = field.className + ' ja-tw-converted';
            select.setAttribute('data-original-type', field.type);

            // 添加預設選項
            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '請選擇鄉鎮市區';
            select.appendChild(defaultOption);

            // 替換原始欄位
            if (container) {
                container.replaceChild(select, field);
            } else {
                field.parentNode.replaceChild(select, field);
            }

            // 監聽變更事件
            select.addEventListener('change', function() {
                var cityName = this.value;
                var fieldId = this.id;
                var stateFieldId = fieldId.replace('city', 'state');
                var stateCode = getStateValue(stateFieldId);
                updatePostcode(fieldId, stateCode, cityName);
            });

        });
    }

    function getStateValue(stateFieldName) {
        // 根據實際 HTML 結構查找縣市欄位
        var stateField = document.querySelector('#billing-state, #shipping-state');
        if (stateField) {
            return stateField.value;
        }
        return null;
    }

    function updateCityOptions(stateFieldId, stateCode) {
        // 根據縣市欄位 ID 找到對應的鄉鎮市區欄位
        var cityFieldId = stateFieldId.replace('state', 'city');
        var cityField = document.querySelector('#' + cityFieldId);
        
        if (!cityField) {
            return;
        }

        if (cityField.tagName === 'SELECT') {
            // 清空選項
            cityField.innerHTML = '<option value="">請選擇鄉鎮市區</option>';

            // 添加鄉鎮市區選項
            if (stateCode && window.jaTwCitySelectData.districts[stateCode]) {
                var districts = window.jaTwCitySelectData.districts[stateCode];
                for (var postcode in districts) {
                    if (districts.hasOwnProperty(postcode)) {
                        var option = document.createElement('option');
                        option.value = districts[postcode];
                        option.textContent = districts[postcode];
                        option.setAttribute('data-postcode', postcode);
                        cityField.appendChild(option);
                    }
                }
            }

        }
    }

    function updatePostcode(cityFieldId, stateCode, cityName) {
        // 根據鄉鎮市區欄位 ID 找到對應的郵遞區號欄位
        var postcodeFieldId = cityFieldId.replace('city', 'postcode');
        var postcodeField = document.querySelector('#' + postcodeFieldId);

        if (!postcodeField || !stateCode || !cityName || !window.jaTwCitySelectData.districts[stateCode]) {
            return;
        }

        var districts = window.jaTwCitySelectData.districts[stateCode];
        for (var postcode in districts) {
            if (districts.hasOwnProperty(postcode) && districts[postcode] === cityName) {
                postcodeField.value = postcode;

                break;
            }
        }
    }

    function hidePostcodeField() {
        // 根據實際 HTML 結構隱藏郵遞區號欄位
        var postcodeFields = findFields([
            '#billing-postcode',
            '#shipping-postcode'
        ]);

        postcodeFields.forEach(function(field) {
            var container = field.closest('.wc-block-components-address-form__postcode, .wc-block-components-text-input');
            if (container) {
                container.style.display = 'none';
            }
        });

    }

    function clearPostcode(stateFieldId) {
        // 根據縣市欄位 ID 找到對應的郵遞區號欄位並清空
        var postcodeFieldId = stateFieldId.replace('state', 'postcode');
        var postcodeField = document.querySelector('#' + postcodeFieldId);
        
        if (postcodeField) {
            postcodeField.value = '';
        }
    }

})();