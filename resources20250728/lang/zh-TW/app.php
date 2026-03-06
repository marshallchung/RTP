<?php

return [
    'name'                   => '內政部消防署',
    'createSuccess'          => '你的:type已成功創建',
    'importSuccess'          => '你的:type已成功匯入（匯入成功：:success筆）',
    'updateSuccess'          => '你的:type已成功修改',
    'deleteSuccess'          => '你的:type已成功刪除',
    'resetSuccess'           => '成功重新設定密碼',
    'report'                 => [
        'notPublic'    => '各縣市政府及鄉鎮市區公所期末資料，將於:date後，公開檔案查詢。',
        'changeYear'   => '年度',
        'allowedMimes' => '請確定檔案格式為 pdf, doc, docx, jpg, jpeg, png, gif, zip, rar, txt, csv, xlsx, odf, mp4, mov, ppt, pptx',
    ],
    'committee'              => [
        'instructions' => '深耕計畫評鑑專區將各縣市政府分為兩梯次（第一梯、第二梯）；各鄉鎮市區公所分為兩梯次（第一梯、第二梯），每梯次各有第一類和第二類。請優先閱讀評鑑方式說明並下載評鑑表單再進行各縣市政府及鄉鎮市區公所之資料評鑑。',
    ],
    'users'                  => [
        'password' => [
            'requirements' => '密碼至少須為6個字符',
        ],
        'reset'    => [
            'info' => '點選「重設密碼」後，請洽系統管理員取得預設密碼',
            'hint' => '防災士與韌性社區帳號管理請至各自對應頁面',
        ],
    ],
    'createPartiallySuccess' => '你的:type部份創建，以下資料因為「:reason」未建置成功：:fails',
    'reviewSuccess'          => '對於:type的審查已完成',
];
