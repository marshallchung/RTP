<?php

namespace App\Nfa\Repositories;

use App\Video;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class VideoRepository implements VideoRepositoryInterface
{
    public function getVideo()
    {
        return Video::sorted()->with('author')->latest('created_at')->paginate(20);
    }

    public function getDashboardVideo()
    {
        return Video::sorted()->with('author', 'files')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function processData($data): array
    {
        $explode_result = explode('_', $data['sort'], 2);
        $sort = $explode_result[0] ?: null;
        $sub_sort = count($explode_result) >= 2 ? $explode_result[1] : null;

        return array_merge($data, [
            'sort'     => $sort,
            'sub_sort' => $sub_sort,
        ]);
    }

    public function create($data)
    {
        $user = Auth::user();

        return $user->Video()->create($this->processData($data));
    }

    public function update(Video $video, $data)
    {
        return $video->update($this->processData($data));
    }

    public function getDashboardVideoLeft()
    {
        return Video::with('author', 'files')->where('active', true)->whereHas('VideoType', function ($query) {
            /** @var Builder|VideoType $query */
            $query->where('name', '宣導資訊');
        })->latest('created_at')->simplePaginate(5, ['*'], 'page_l');
    }

    public function getDashboardVideoRight()
    {
        return Video::with('author', 'files')->where('active', true)->whereDoesntHave('VideoType', function ($query) {
            /** @var Builder|VideoType $query */
            $query->where('name', '宣導資訊');
        })->latest('created_at')->simplePaginate(5, ['*'], 'page_r');
    }
}
