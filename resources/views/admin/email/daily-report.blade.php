<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
</head>

<body style="background-color:#f8f9fa;padding:12px;">
    <div style="width: 100%; max-width: 56rem; padding:1rem;">
        <div
            style="width: 100%; padding-left: 1rem;padding-right: 1rem; padding-top: 1.5rem;padding-bottom: 1.5rem; background-color: white;border-width: 1px;border-color: #65676b; border-radius: .125rem;">
            <div style="width: 100%; margin-bottom: 2.5rem;">
                <div style="width: 100%; font-size: 1.5rem;line-height: 2rem; font-weight: 700; text-align: right;">
                    歡迎，{{ $user->name }}</div>
                <div style="">
                    <div style="margin-right: 2rem; font-size: 1.5rem;line-height: 2rem; font-weight: 700;">計畫管考項目狀態通知
                    </div>
                </div>
                <div style="padding-left: 6rem; font-size: 1.5rem;line-height: 2rem; font-weight: 700;">{{ $user->name
                    }}您好：</div>
                <div style="padding-left: 6rem; font-size: 1.5rem;line-height: 2rem; font-weight: 700;">今天是{{
                    intval(date("Y"))-1911 }}年{{ date("n") }}月{{ date("d")
                    }}日，計畫管考項目狀態通知如下：</div>
            </div>
            <div style="margin-bottom: 2.5rem;">
                <div style="margin-bottom: 0.5rem; font-weight: 700;">管考項目</div>
                <table border
                    style="width: 100%; text-align:  center;text-indent: 0;border-color: #65676b;border-collapse: collapse;border-width: 1px;">
                    <tr style="font-weight: 700; border-bottom-width: 1px;border-color: #65676b;">
                        <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                            項目</th>
                        <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                            截止日期</th>
                        <th style="padding: 0.5rem;">
                            狀態</th>
                    </tr>
                    <tr style="border-bottom-width: 1px;border-color: #65676b;">
                        <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                            成果資料</td>
                        <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                            {{
                            ($report_public_dates &&
                            array_key_exists('reports',$report_public_dates))?$report_public_dates['reports']['c_expire_date']:''
                            }}
                        </td>
                        <td style="padding: 0.5rem; text-align: center;">
                            @if ($user->type == 'county' && count($report_data)>0)
                            @if ($report_data[0]['topic_count']==$report_data[0]['report_count'])
                            <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #83cc16;">
                                已繳交</div>
                            @elseif (date("Y-m-d")>=$report_public_dates['reports']['expire_soon_date'] &&
                            date("Y-m-d")<=$report_public_dates['reports']['expire_date'] &&
                                $report_data[0]['report_count']<=$report_data[0]['topic_count']) <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                                即將逾期
            </div>
            @elseif ($report_public_dates['reports']['expire_date']<=date("Y-m-d") &&
                $report_data[0]['report_count']<=$report_data[0]['topic_count']) <div
                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                逾期
        </div>
        @elseif ($report_data[0]['report_count']==0)
        <div
            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
            尚未繳交
        </div>
        @endif
        @elseif ($user->type != 'county' && count($report_data)>0)
        @foreach ($report_data as $one_data)
        @if (date("Y-m-d")>=$report_public_dates['reports']['expire_soon_date'] &&
        date("Y-m-d")<=$report_public_dates['reports']['expire_date'] &&
            $one_data['report_count']<=$one_data['topic_count']) <div
            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
            {{ $one_data['name'] }}
    </div>
    @elseif ($report_public_dates['reports']['expire_date']<=date("Y-m-d") &&
        $one_data['report_count']<=$one_data['topic_count']) <div
        style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
        {{ $one_data['name'] }}</div>
        @endif
        @endforeach
        @endif
        </td>
        </tr>
        <tr style="border-bottom-width: 1px;border-color: #65676b;">
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                執行計畫書</td>
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                {{
                ($report_public_dates &&
                array_key_exists('plan',$report_public_dates))?$report_public_dates['plan']['c_expire_date']:''
                }}
            </td>
            <td style="padding: 0.5rem;">
                @if ($user->type == 'county' && count($plan_data)>0)
                @if ($plan_data[0]['plan_count']>0)
                <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #83cc16;">
                    已繳交</div>
                @elseif (date("Y-m-d")>=$report_public_dates['plan']['expire_soon_date'] &&
                date("Y-m-d")<=$report_public_dates['plan']['expire_date'] && $plan_data[0]['plan_count']==0) <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                    即將逾期</div>
                    @elseif ($report_public_dates['plan']['expire_date']<=date("Y-m-d") &&
                        $plan_data[0]['plan_count']==0) <div
                        style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                        逾期</div>
                        @elseif ($plan_data[0]['plan_count']==0)
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            尚未繳交</div>
                        @endif
                        @elseif ($user->type != 'county' && count($plan_data)>0)
                        @foreach ($plan_data as $one_data)
                        @if (date("Y-m-d")>=$report_public_dates['plan']['expire_soon_date'] &&
                        date("Y-m-d")<=$report_public_dates['plan']['expire_date'] && $one_data['plan_count']==0) <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_data['name'] }}</div>
                            @elseif ($report_public_dates['plan']['expire_date']<=date("Y-m-d") &&
                                $one_data['plan_count']==0) <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                                {{ $one_data['name'] }}</div>
                                @endif
                                @endforeach
                                @endif
            </td>
        </tr>
        <tr style="border-bottom-width: 1px;border-color: #65676b;">
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                期末簡報</td>
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                {{
                ($report_public_dates &&
                array_key_exists('presentation',$report_public_dates))?$report_public_dates['presentation']['c_expire_date']:''
                }}
            </td>
            <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                @if ($user->type == 'county' && count($presentation_data)>0)
                @if ($presentation_data[0]['presentation_count']>0)
                <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #83cc16;">
                    已繳交</div>
                @elseif (date("Y-m-d")>=$report_public_dates['presentation']['expire_soon_date'] &&
                date("Y-m-d")<=$report_public_dates['presentation']['expire_date'] &&
                    $presentation_data[0]['presentation_count']==0) <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                    即將逾期</div>
                    @elseif ($report_public_dates['presentation']['expire_date']<=date("Y-m-d") &&
                        $presentation_data[0]['presentation_count']==0) <div
                        style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                        逾期</div>
                        @elseif ($presentation_data[0]['presentation_count']==0)
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            尚未繳交</div>
                        @endif
                        @elseif ($user->type != 'county' && count($presentation_data)>0)
                        @foreach ($presentation_data as $one_data)
                        @if (date("Y-m-d")>=$report_public_dates['presentation']['expire_soon_date'] &&
                        date("Y-m-d")<=$report_public_dates['presentation']['expire_date'] &&
                            $one_data['presentation_count']==0) <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_data['name'] }}</div>
                            @elseif ($report_public_dates['presentation']['expire_date']<=date("Y-m-d") &&
                                $one_data['presentation_count']==0) <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                                {{ $one_data['name'] }}</div>
                                @endif
                                @endforeach
                                @endif
            </td>
        </tr>
        <tr style="border-bottom-width: 1px;border-color: #65676b;">
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                優選範本</td>
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                {{
                ($report_public_dates &&
                array_key_exists('sample',$report_public_dates))?$report_public_dates['sample']['c_expire_date']:''
                }}
            </td>
            <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                @if ($user->type == 'county' && count($sample_report_data)>0)
                @if ($sample_report_data[0]['topic_count']==$sample_report_data[0]['report_count'])
                <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #83cc16;">
                    已繳交</div>
                @elseif (date("Y-m-d")>=$report_public_dates['sample']['expire_soon_date'] &&
                date("Y-m-d")<=$report_public_dates['sample']['expire_date'] &&
                    $sample_report_data[0]['report_count']<=$sample_report_data[0]['topic_count']) <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                    即將逾期</div>
                    @elseif ($report_public_dates['sample']['expire_date']<=date("Y-m-d") &&
                        $sample_report_data[0]['report_count']<=$sample_report_data[0]['topic_count']) <div
                        style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                        逾期</div>
                        @elseif ($sample_report_data[0]['report_count']==0)
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            尚未繳交</div>
                        @endif
                        @elseif ($user->type != 'county' && count($sample_report_data)>0)
                        @foreach ($sample_report_data as $one_data)
                        @if (date("Y-m-d")>=$report_public_dates['sample']['expire_soon_date'] &&
                        date("Y-m-d")<=$report_public_dates['sample']['expire_date'] &&
                            $one_data['report_count']<=$one_data['topic_count']) <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_data['name'] }}</div>
                            @elseif ($report_public_dates['sample']['expire_date']<=date("Y-m-d") &&
                                $one_data['report_count']<=$one_data['topic_count']) <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                                {{ $one_data['name'] }}</div>
                                @endif
                                @endforeach
                                @endif
            </td>
        </tr>
        <tr style="border-bottom-width: 1px;border-color: #65676b;">
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                執行進度管制表-期中</td>
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                {{
                ($report_public_dates &&
                array_key_exists('seasonal1',$report_public_dates))?$report_public_dates['seasonal1']['c_expire_date']:''
                }}
            </td>
            <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                @if ($user->type == 'county' && count($seasonal_report_2_data)>0)
                @if ($seasonal_report_2_data[0]['topic_count']==$seasonal_report_2_data[0]['report_count'])
                <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #83cc16;">
                    已繳交</div>
                @elseif (date("Y-m-d")>=$report_public_dates['seasonal1']['expire_soon_date'] &&
                date("Y-m-d")<=$report_public_dates['seasonal1']['expire_date'] &&
                    $seasonal_report_2_data[0]['report_count']<=$seasonal_report_2_data[0]['topic_count']) <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                    即將逾期</div>
                    @elseif ($report_public_dates['seasonal1']['expire_date']<=date("Y-m-d") &&
                        $seasonal_report_2_data[0]['report_count']<=$seasonal_report_2_data[0]['topic_count']) <div
                        style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                        逾期</div>
                        @elseif ($seasonal_report_2_data[0]['report_count']==0)
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            尚未繳交</div>
                        @endif
                        @elseif ($user->type != 'county' && count($seasonal_report_2_data)>0)
                        @foreach ($seasonal_report_2_data as $one_data)
                        @if (date("Y-m-d")>=$report_public_dates['seasonal1']['expire_soon_date'] &&
                        date("Y-m-d")<=$report_public_dates['seasonal1']['expire_date'] &&
                            $one_data['report_count']<=$one_data['topic_count']) <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_data['name'] }}</div>
                            @elseif ($report_public_dates['seasonal1']['expire_date']<=date("Y-m-d") &&
                                $one_data['report_count']<=$one_data['topic_count']) <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                                {{ $one_data['name'] }}</div>
                                @endif
                                @endforeach
                                @endif
            </td>
        </tr>
        <tr style="border-bottom-width: 1px;border-color: #65676b;">
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                執行進度管制表-期末</td>
            <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                {{
                ($report_public_dates &&
                array_key_exists('seasonal2',$report_public_dates))?$report_public_dates['seasonal2']['c_expire_date']:''
                }}
            </td>
            <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                @if ($user->type == 'county' && count($seasonal_report_3_data)>0)
                @if ($seasonal_report_3_data[0]['topic_count']==$seasonal_report_3_data[0]['report_count'])
                <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #83cc16;">
                    已繳交</div>
                @elseif (date("Y-m-d")>=$report_public_dates['seasonal2']['expire_soon_date'] &&
                date("Y-m-d")<=$report_public_dates['seasonal2']['expire_date'] &&
                    $seasonal_report_3_data[0]['report_count']<=$seasonal_report_3_data[0]['topic_count']) <div
                    style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                    即將逾期</div>
                    @elseif ($report_public_dates['seasonal2']['expire_date']<=date("Y-m-d") &&
                        $seasonal_report_3_data[0]['report_count']<=$seasonal_report_3_data[0]['topic_count']) <div
                        style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                        逾期</div>
                        @elseif ($seasonal_report_3_data[0]['report_count']==0)
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            尚未繳交</div>
                        @endif
                        @elseif ($user->type != 'county' && count($seasonal_report_3_data)>0)
                        @foreach ($seasonal_report_3_data as $one_data)
                        @if (date("Y-m-d")>=$report_public_dates['seasonal2']['expire_soon_date'] &&
                        date("Y-m-d")<=$report_public_dates['seasonal2']['expire_date'] &&
                            $one_data['report_count']<=$one_data['topic_count']) <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_data['name'] }}</div>
                            @elseif ($report_public_dates['seasonal2']['expire_date']<=date("Y-m-d") &&
                                $one_data['report_count']<=$one_data['topic_count']) <div
                                style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; border-radius: 0.5rem; height: 1.75rem; width: 6rem; background-color: #e11d48;">
                                {{ $one_data['name'] }}</div>
                                @endif
                                @endforeach
                                @endif
            </td>
        </tr>
        </table>
        </div>
        <div style="margin-bottom: 2.5rem;">
            <div style="margin-bottom: 0.5rem; font-weight: 700;">績效評估自評表</div>
            <table border
                style="width: 100%; text-align: center; border-width:1px;text-indent: 0;border-color: #65676b;border-collapse: collapse;">
                <tr style="font-weight: 700; border-bottom-width: 1px;border-color: #65676b;">
                    <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        項目</th>
                    <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        填報日期</th>
                    <th style="padding: 0.5rem;">
                        狀態</th>
                </tr>
                @foreach ($questionnaire_data as $one_questionnaire)
                <tr style="border-bottom-width: 1px;border-color: #65676b;">
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{ $one_questionnaire['title'] }}
                    </td>
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{
                        $one_questionnaire['c_date_from'] . "～" . $one_questionnaire['c_date_to']
                        }}
                    </td>
                    <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                        @if (date("Y-m-d")<$one_questionnaire['date_from']) <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #6b7280; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            尚未開始
        </div>
        @elseif (date("Y-m-d")>$one_questionnaire['date_to'])
        <div
            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #e11d47; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
            已結束</div>
        @else
        <div
            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
            進行中</div>
        @endif
        </td>
        </tr>
        @endforeach
        </table>
        </div>
        <div style="margin-bottom: 2.5rem;">
            <div style="margin-bottom: 0.5rem; font-weight: 700;">進階防災士逾期狀況</div>
            <table border
                style="width: 100%; text-align: center; text-indent: 0;border-color: #65676b;border-collapse: collapse;border-width: 1px;">
                <tr style="font-weight: 700; border-bottom-width: 1px;border-color: #65676b;">
                    <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        姓名</th>
                    <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        進階防災士受訓中狀態到期日</th>
                    <th style="padding: 0.5rem;">
                        狀態</th>
                </tr>
                @foreach ($dp_advance_soon_expire_data as $one_student)
                <tr style="border-bottom-width: 1px;border-color: #65676b;">
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{ $one_student['name'] }}
                    </td>
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{
                        date("Y-m-d",strtotime($one_student['date_first_finish'] . " +{$DP_student_valid_year} year"))
                        }}
                    </td>
                    <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_student['expire_state'] }}
                        </div>
                    </td>
                </tr>
                @endforeach
                @foreach ($dp_advance_expire_data as $one_student)
                <tr style="border-bottom-width: 1px;border-color: #65676b;">
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{ $one_student['name'] }}
                    </td>
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{
                        date("Y-m-d",strtotime($one_student['date_first_finish'] . " +{$DP_student_valid_year} year"))
                        }}
                    </td>
                    <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #e11d47; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            {{ $one_student['expire_state'] }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div style="margin-bottom: 2.5rem;">
            <div style="margin-bottom: 0.5rem; font-weight: 700;">韌性社區逾期狀況</div>
            <table border
                style="width: 100%; text-align: center; text-indent: 0;border-color: #65676b;border-collapse: collapse;border-width: 1px;">
                <tr style="font-weight: 700; border-bottom-width: 1px;border-color: #65676b;">
                    <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        社區名稱</th>
                    <th style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        星等</th>
                    <th style="padding: 0.5rem;">
                        狀態</th>
                </tr>
                @foreach ($dc_unit_soon_expire_data as $one_unit)
                <tr style="border-bottom-width: 1px;border-color: #65676b;">
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{ $one_unit['county']['name'] .$one_unit['name'] }}
                    </td>
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        <div>{{ $one_unit['rank'] }}</div>
                        <div style="font-size: 0.875rem;line-height: 1.25rem;">
                            {{
                            "(有效期限： {$one_unit['rank_expired_date']})"
                            }}
                        </div>
                    </td>
                    <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #eab308; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            即將逾期
                        </div>
                    </td>
                </tr>
                @endforeach
                @foreach ($dc_unit_expire_data as $one_unit)
                <tr style="border-bottom-width: 1px;border-color: #65676b;">
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        {{ $one_unit['county']['name'] .$one_unit['name'] }}
                    </td>
                    <td style="padding: 0.5rem; border-right-width: 1px;border-color: #65676b;">
                        <div>{{ $one_unit['rank'] }}</div>
                        <div style="font-size: 0.875rem;line-height: 1.25rem;">
                            {{
                            "(有效期限： {$one_unit['rank_expired_date']})"
                            }}
                        </div>
                    </td>
                    <td style="padding: 0.5rem; text-align: center; vertical-align: middle;">
                        <div
                            style="text-align: center; vertical-align: middle; margin-bottom: 0.25rem; padding-top: 0.5rem; margin-left: auto;margin-right: auto; font-size: 0.875rem;line-height: 1.25rem; color: white; background-color: #e11d47; border-radius: 0.5rem; height: 1.75rem; width: 6rem;">
                            逾期
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        </div>
        </div>
</body>

</html>