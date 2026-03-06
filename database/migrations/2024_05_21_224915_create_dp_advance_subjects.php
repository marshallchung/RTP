<?php

use App\DpSubject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $dpSubjects = [
            [
                'name' => 'A1.簡易搜救的原則、任務範圍、應用時機與基礎培訓需求',
                'position' => 0,
                'advance' => true,
            ],
            [
                'name' => 'A2.個人防護裝備的選擇與使用方法',
                'position' => 1,
                'advance' => true,
            ],
            [
                'name' => 'A3.簡易搜救的安全準則及協助受災民眾的方法（情境想定）',
                'position' => 2,
                'advance' => true,
            ],
            [
                'name' => 'A4.與政府正規救援行動的銜接',
                'position' => 3,
                'advance' => true,
            ],
            [
                'name' => 'B1.救災護理及各類型傷情處置訓練',
                'position' => 4,
                'advance' => true,
            ],
            [
                'name' => 'B2.社區緊急救護行動之準備與團隊合作',
                'position' => 5,
                'advance' => true,
            ],
            [
                'name' => 'B3.基礎生命維持技能BLS訓練',
                'position' => 6,
                'advance' => true,
            ],
            [
                'name' => 'B4.社區大量傷患事件之因應管理對策',
                'position' => 7,
                'advance' => true,
            ],
            [
                'name' => 'C1.避難收容處所的空間配置規劃、分工與後勤管理',
                'position' => 8,
                'advance' => true,
            ],
            [
                'name' => 'C2.避雞收容處所管理運作實作培訓',
                'position' => 9,
                'advance' => true,
            ],
            [
                'name' => 'D1.大規模災害及衝突對企業的衝擊（情境想定）',
                'position' => 10,
                'advance' => true,
            ],
            [
                'name' => 'D2.企業持續營運及安全防護模擬實作',
                'position' => 11,
                'advance' => true,
            ],
            [
                'name' => 'E1.警報訊息種類、e點通使用與推廣',
                'position' => 12,
                'advance' => true,
            ],
            [
                'name' => 'E2.通訊方法實作',
                'position' => 13,
                'advance' => true,
            ],
        ];
        foreach ($dpSubjects as $subjects) {
            DpSubject::create($subjects);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
