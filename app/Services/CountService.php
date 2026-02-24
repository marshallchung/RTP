<?php

namespace App\Services;

use App;
use App\Counter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CountService
{
    public function increase(Model $model, int $n = 1): int
    {
        assert(in_array(App\Traits\CountableTrait::class, class_uses_recursive($model)));
        $counter = $model->counter;
        $just_created = false;
        if (!$counter) {
            // Counter 的主鍵是自訂字串，因此需要直接給一組
            $dummy_name = Str::snake(class_basename($model)) . '_' . $model->getKey();
            $counter = new Counter([
                'name' => $dummy_name,
            ]);
            $counter->countable()->associate($model);
            $just_created = true;
        }
        $counter->count += $n;
        $counter->save();
        if ($just_created) {
            // 剛建立計數器時，須重新載入資料，確保同一次請求中，重新呼叫此方法時，`$model->counter` 能正確取得
            $model->refresh();
        }

        return $counter->count;
    }
}
