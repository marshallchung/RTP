@extends('admin.layouts.dashboard', [
    'heading' => '韌性社區標章通過統計表',
])

@section('title', '查詢與管理韌性社區資料')

@section('styles')
<style>
<style>
/* ==================== 1. 標題 ==================== */
/* 強制 100% 寬度 */
.table-container {
    width: 100vw !important;
    margin-left: calc(-50vw + 50%) !important;
    position: relative;
    left: 50%;
    transform: translateX(-50%);
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    border: 2px solid #adb5bd !important;
    border-top: 4px solid #495057 !important;
}
.table {
    width: 100% !important;
    min-width: 800px; /* 避免太窄時亂掉 */
    margin-bottom: 0;
    table-layout: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 10px;
    border-collapse: separate;
    border-spacing: 0;
}
/* 表格內部也 100% */
.table-responsive {
    width: 100% !important;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table-title {
    text-align: center;
    font-size: 1.6rem;
    font-weight: 700;
    margin: 1.5rem 0;
    color: #1a1a1a;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* ==================== 2. 摘要 ==================== */
.summary {
    text-align: center;
    font-size: 1rem;
    color: #495057;
    margin-bottom: 1.2rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* ==================== 3. 表格容器 ==================== */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

/* ==================== 4. 表格樣式 ==================== */
.table {
    margin-bottom: 0;
    font-size: 0.95rem;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background: #e9ecef !important;
    color: black;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: none !important;
    padding: 0.75rem 0.5rem;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.table tbody td {
    text-align: center;
    vertical-align: middle;
    padding: 0.6rem 0.4rem;
    border-color: #dee2e6 !important;
    transition: all 0.2s ease;
}

/* 縣市欄 */
.table tbody td:first-child {
    font-weight: 600;
    background: #f8f9fa;
    color: #212529;
}

/* 累積數量（紅色） */
.text-danger.fw-bold {
    color: #dc3545 !important;
    font-weight: 700 !important;
    font-size: 1.05rem;
}

/* 點擊數字（藍色連結） */
.get-year-data {
    color: #0d6efd !important;
    text-decoration: none !important;
    font-weight: 600;
    cursor: pointer;
    position: relative;
    padding: 2px 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.get-year-data:hover {
    background: #e3f2fd;
    color: #0a58ca !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(13,110,253,0.2);
}
.table tbody td {
    text-align: center;
    vertical-align: middle;
    padding: 0.7rem 0.5rem;
    /* 完整內框 */
    border: 1px solid #dee2e6 !important;
    font-size: 0.95rem;
    transition: background 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa !important;
}

/* 縣市欄：左側加粗 */
.table tbody td:first-child {
    background: #f1f3f5;
    font-weight: 600;
    color: #2c3e50;
    border-left: 3px solid #6c757d !important;
}

</style>
@endsection

@section('inner_content')

<div class="container-fluid py-3">
    <h1 class="table-title">
        {{$years[0]}}-{{end($years)}}年韌性社區標章通過統計表
    </h1>

    <div class="summary">
        一星總數：{{ $rankCount['一星'] ?? 0 }}　　二星總數：{{ $rankCount['二星'] ?? 0 }}　　三星總數：{{ $rankCount['三星'] ?? 0 }}　　社區總數：{{ $totalRank }}
    </div>

    <div class="table-container">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle text-center">縣市別</th>
                    @foreach($years as $year)
                        <th colspan="2" class="text-center">{{$year}}年</th>
                    @endforeach
                    <th colspan="2" class="text-danger text-center">累積數量</th>
                </tr>
                <tr>
                    @foreach($years as $year)
                        <th class="text-center">一星</th><th class="text-center">二星</th>
                    @endforeach
                    <th class="text-center">一星</th><th class="text-center">二星</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataMaps as $county => $yearData)
                    <tr>
                        <td class="text-center fw-medium">{{$county}}</td>
                        @foreach($yearData as $year => $data)
                            <!-- 一星 -->
                            <td class="text-center">
                                @if(($data['一星'] ?? 0) > 0)
                                    @if($year != 'total')
                                        <a @click="$store.modal.show('{{$year}}', '{{$county}}', '一星')" 
                                           class="get-year-data">
                                            {{ $data['一星'] }}
                                        </a>
                                    @else
                                        <span class="text-danger fw-bold">{{ $data['一星'] }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <!-- 二星 -->
                            <td class="text-center">
                                @if(($data['二星'] ?? 0) > 0)
                                    @if($year != 'total')
                                        <a @click="$store.modal.show('{{$year}}', '{{$county}}', '二星')" 
                                           class="get-year-data">
                                            {{ $data['二星'] }}
                                        </a>
                                    @else
                                        <span class="text-danger fw-bold">{{ $data['二星'] }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Alpine Modal -->
<template x-teleport="body">
    <div x-show="$store.modal.open" 
         x-transition 
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="$store.modal.close()">
         
        <div @click.away="$store.modal.close()" 
             class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
            
            <div class="flex justify-between items-center p-4 border-b">
                <h5 class="h5 m-0" x-text="$store.modal.title"></h5>
                <button @click="$store.modal.close()" class="btn-close"></button>
            </div>

            <div class="p-4" x-html="$store.modal.content"></div>

            <div class="p-4 border-t text-end">
                <button @click="$store.modal.close()" class="btn btn-secondary btn-sm">關閉</button>
            </div>
        </div>
    </div>
</template>

@endsection

@section('scripts')
{{-- 移除 jQuery + Bootstrap JS --}}
{{-- <script src="jquery..."></script> --}}
{{-- <script src="bootstrap.bundle..."></script> --}}

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    // 全域 Modal Store
    Alpine.store('modal', {
        open: false,
        title: '',
        content: '<div class="text-center py-4">請選擇資料</div>',

        show(year, county, rank) {
            this.title = `${year}年 ${county} ${rank} 資料`;
            this.content = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">載入中...</span>
                    </div>
                    <p class="mt-2">載入資料中...</p>
                </div>`;
            this.open = true;

            fetch('{{ route('admin.dc-units.getReport') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ year, county, rank })
            })
            .then(r => r.ok ? r.json() : Promise.reject('網路錯誤'))
            .then(data => {
               // 在 Alpine.js 的 show() 裡
if (data.success && data.data?.length > 0) {
    let html = `<div class="table-responsive">
        <table class="table table-sm table-bordered align-middle"> <!-- 加上 table-bordered -->
            <thead class="table-light">
                <tr>
                    <th>社區名稱</th>
                    <th>地址</th>
                </tr>
            </thead>
            <tbody>`;
    
    data.data.forEach(d => {
        const editUrl = `/admin/dc-units/${d.id}/edit`;
        html += `<tr>
            <td>
                <a href="${editUrl}" class="text-primary text-decoration-none d-flex align-items-center">
                    ${d.name || '-'} 
                    <i class="bi bi-pencil-square ms-2"></i>
                </a>
            </td>
            <td class="text-muted small">${d.manager_address || '-'}</td>
        </tr>`;
    });
    
    html += `</tbody></table></div>`;
    this.content = html;
}
            })
            .catch(err => {
                this.content = `<div class="alert alert-danger mb-0">載入失敗</div>`;
            });
        },

        close() {
            this.open = false;
        }
    });
});
</script>


@endsection