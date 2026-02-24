<?php

namespace App\Nfa\Repositories;

use App\DcUnit;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DcUnitRepository implements DcUnitRepositoryInterface
{
    protected function getDataQuery()
    {
        return DcUnit::with('author')->latest('created_at');
    }

    public function getData()
    {
        return $this->getDataQuery()->paginate(20);
    }

    public function getAllData()
    {
        return $this->getDataQuery()->get();
    }

    public function getFilteredDataQuery($county_id = null)
    {
        /** @var Builder|DcUnit $q */
        $q = DcUnit::with('author', 'county', 'dcUser');
        if ($county_id) {
            $q->where('county_id', $county_id);
        }

        // 模糊搜尋
        $filterableFields = ['name'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $q->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }
        // 精確搜尋
        $filterableFields = ['county_id', 'rank'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $q->where($filterableField, $searchKeyword);
            }
        }
        // 星等即將到期
        $is_close_to_expired_date_or_expired = request()->exists('is_close_to_expired_date_or_expired');
        if ($is_close_to_expired_date_or_expired) {
            $sql = "rank_started_date IS NOT NULL AND "
                . "IF((date_extension=false OR extension_date IS NULL),rank_started_date,extension_date) <=  "
                . "IF(date_extension=true,  "
                . "IF(extension_date IS NULL, "
                . "DATE_ADD(DATE_SUB(CURDATE(),INTERVAL (rank_year+3) YEAR),INTERVAL 11 MONTH), "
                . "DATE_ADD(DATE_SUB(extension_date,INTERVAL 3 YEAR),INTERVAL 11 MONTH)), "
                . "DATE_ADD(DATE_SUB(CURDATE(),INTERVAL rank_year YEAR),INTERVAL 11 MONTH)) AND  "
                . "IF((date_extension=false OR extension_date IS NULL),rank_started_date,extension_date) >=  "
                . "IF(date_extension=true, "
                . "IF(extension_date IS NULL, "
                . "DATE_SUB(CURDATE(),INTERVAL (rank_year+3) YEAR), "
                . "DATE_SUB(extension_date,INTERVAL 3 YEAR)), "
                . "DATE_SUB(CURDATE(),INTERVAL rank_year YEAR)) ";
            $q->whereRaw($sql);
        }
        //檢查效期
        if (request()->has('Year')) {
            $Year = request()->get('Year');
            if ($Year === '0') {
                //已失效
                $sql = "rank_started_date IS NOT NULL AND "
                    . "IF((date_extension=false OR extension_date IS NULL),rank_started_date,extension_date) < IF(date_extension=true,"
                    . "IF(extension_date IS NULL,"
                    . "DATE_SUB(CURDATE(),INTERVAL (rank_year+3) YEAR),"
                    . "DATE_SUB(extension_date,INTERVAL 3 YEAR)),"
                    . "DATE_SUB(CURDATE(),INTERVAL rank_year YEAR))";
                $q->whereRaw($sql);
            } elseif ($Year === '1') {
                //效期內
                $sql = "rank_started_date IS NOT NULL AND "
                    . "IF((date_extension=false OR extension_date IS NULL),rank_started_date,extension_date) >= IF(date_extension=true,"
                    . "IF(extension_date IS NULL,"
                    . "DATE_SUB(CURDATE(),INTERVAL (rank_year+3) YEAR),"
                    . "DATE_SUB(extension_date,INTERVAL 3 YEAR)),"
                    . "DATE_SUB(CURDATE(),INTERVAL rank_year YEAR))";
                $q->whereRaw($sql);
            }
        }
        //審查狀況
        if (request()->has('pass')) {
            $pass = request()->get('pass');
            if ($pass === '0') {
                //未審查
                $q->whereRaw('`rank` IS NULL');
            } elseif ($pass === '1') {
                //已審查
                $q->whereRaw('`rank` IS NOT NULL');
            }
        }
        //原民地區
        if (request()->has('filter_native')) {
            $native = request()->get('filter_native');
            if ($native !== null) {
                $q->where('native', $native);
            }
        }
        //計畫內
        if (request()->has('filter_within_plan')) {
            $within_plan = request()->get('filter_within_plan');
            if ($within_plan !== null) {
                $q->where('within_plan', $within_plan);
            }
        }

        return $q->latest('created_at');
    }

    public function getFilteredData($county_id = null)
    {
        return $this->getFilteredDataQuery($county_id)->paginate(20);
    }

    public function getAllFilteredData($county_id = null)
    {
        return $this->getFilteredDataQuery($county_id)->get();
    }

   public function getRankCount($county_id = null)
    {
        DcUnit::whereNull('rank')->update(['rank' => '未審查']);
        return $this->getFilteredDataQuery($county_id)
            ->getQuery()
            ->select(DB::raw('COALESCE(`rank`, "未審查") as rank, COUNT(*) as sumary'))
            ->groupBy('rank')
            ->pluck('sumary', 'rank'); 
    }
    public function getWithinPlanCount($county_id = null)
    {
        return $this->getFilteredDataQuery($county_id)->where('within_plan', 1)->get()->count();
    }

    public function getNativeCount($county_id = null)
    {
        return $this->getFilteredDataQuery($county_id)->where('native', 1)->get()->count();
    }

    public function getExpireCount($county_id = null)
    {
        return $this->getFilteredDataQuery($county_id)->where('date_extension', 1)->get()->count();
    }

    public function getDateExtensionCount($county_id = null)
    {
       $sql = "rank_started_date IS NOT NULL AND "
        . "IF((date_extension=false OR extension_date IS NULL),rank_started_date,extension_date) < IF(date_extension=true,"
        . "IF(extension_date IS NULL,"
        . "DATE_SUB(CURDATE(),INTERVAL (rank_year+3) YEAR),"
        . "DATE_SUB(extension_date,INTERVAL 3 YEAR)),"
        . "DATE_SUB(CURDATE(),INTERVAL rank_year YEAR))";

    	return $this->getFilteredDataQuery($county_id)
        ->whereRaw($sql)  
        ->get()
        ->count();    
    }

    public function getDashboardUnits()
    {
        $sql = "rank_started_date IS NOT NULL AND "
            . "rank_started_date < IF(date_extension=true,"
            . "IF(extension_date IS NULL,DATE_SUB(CURDATE(),INTERVAL (rank_year+3) YEAR),DATE_SUB(extension_date,INTERVAL 3 YEAR)),"
            . "DATE_SUB(CURDATE(),INTERVAL rank_year YEAR))";
        return DcUnit::with('author')->where('active', true)->whereRaw($sql)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->dcUnits()->create($data);
    }
}
