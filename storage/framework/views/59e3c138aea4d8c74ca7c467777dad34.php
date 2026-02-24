<?php $__env->startSection('title', '計畫管考項目狀態通知'); ?>

<?php $__env->startSection('inner_content'); ?>
<div class="w-full max-w-4xl p-4">
    <div class="w-full px-4 py-6 bg-white border border-gray-200 rounded-sm">
        <div class="w-full mb-10">
            <div class="w-full text-2xl font-bold text-right">歡迎，<?php echo e($user->name); ?></div>
            <div class="flex flex-row">
                <div class="mr-8 text-2xl font-bold">計畫管考項目狀態通知</div>
                <a target="_blank" href="/admin/export" class="inline-block mb-4 text-xl text-mainBlueDark">匯出</a>
            </div>
            <div class="pl-24 text-2xl font-bold"><?php echo e($user->name); ?>您好：</div>
            <div class="pl-24 text-2xl font-bold">今天是<?php echo e(intval(date("Y"))-1911); ?>年<?php echo e(date("n")); ?>月<?php echo e(date("d")); ?>日，計畫管考項目狀態通知如下：</div>
        </div>
        <div class="mb-10">
            <div class="mb-2 font-bold">管考項目</div>
            <table class="w-full text-center border">
                <tr class="font-bold border-b last:border-b-0">
                    <th class="p-2 border-r last:border-r-0">項目</th>
                    <th class="p-2 border-r last:border-r-0">截止日期</th>
                    <th class="p-2 border-r last:border-r-0">狀態</th>
                </tr>
                <tr class="border-b last:border-b-0">
                    <td class="p-2 border-r last:border-r-0">成果資料</td>
                    <td class="p-2 border-r last:border-r-0">
                        <?php echo e(($report_public_dates &&
                        array_key_exists('reports',$report_public_dates))?$report_public_dates['reports']['c_expire_date']:''); ?>

                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        <?php if($user->type == 'county' && count($report_data)>0): ?>
                        <?php if($report_data[0]['topic_count']==$report_data[0]['report_count']): ?>
                        <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-lime-500">
                            已繳交</div>
                        <?php elseif(date("Y-m-d")>=$report_public_dates['reports']['expire_soon_date'] &&
                        date("Y-m-d")<=$report_public_dates['reports']['expire_date'] &&
                            $report_data[0]['report_count']<=$report_data[0]['topic_count']): ?> <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                            即將逾期
        </div>
        <?php elseif($report_public_dates['reports']['expire_date']<=date("Y-m-d") &&
            $report_data[0]['report_count']<=$report_data[0]['topic_count']): ?> <div
            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
            逾期
    </div>
    <?php elseif($report_data[0]['report_count']==0): ?>
    <div class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
        尚未繳交
    </div>
    <?php endif; ?>
    <?php elseif($user->type != 'county' && count($report_data)>0): ?>
    <?php $__currentLoopData = $report_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(date("Y-m-d")>=$report_public_dates['reports']['expire_soon_date'] &&
    date("Y-m-d")<=$report_public_dates['reports']['expire_date'] &&
        $one_data['report_count']<=$one_data['topic_count']): ?> <div
        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
        <?php echo e($one_data['name']); ?>

</div>
<?php elseif($report_public_dates['reports']['expire_date']<=date("Y-m-d") &&
    $one_data['report_count']<=$one_data['topic_count']): ?> <div
    class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
    <?php echo e($one_data['name']); ?></div>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    </td>
    </tr>
    <tr class="border-b last:border-b-0">
        <td class="p-2 border-r last:border-r-0">執行計畫書</td>
        <td class="p-2 border-r last:border-r-0">
            <?php echo e(($report_public_dates &&
            array_key_exists('plan',$report_public_dates))?$report_public_dates['plan']['c_expire_date']:''); ?>

        </td>
        <td class="p-2 border-r last:border-r-0">
            <?php if($user->type == 'county' && count($plan_data)>0): ?>
            <?php if($plan_data[0]['plan_count']>0): ?>
            <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-lime-500">
                已繳交</div>
            <?php elseif(array_key_exists('plan',$report_public_dates) &&
            date("Y-m-d")>=$report_public_dates['plan']['expire_soon_date'] &&
            date("Y-m-d")<=$report_public_dates['plan']['expire_date'] && $plan_data[0]['plan_count']==0): ?> <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                即將逾期</div>
                <?php elseif(array_key_exists('plan',$report_public_dates) && $report_public_dates['plan']['expire_date']
                <=date("Y-m-d") && $plan_data[0]['plan_count']==0): ?> <div
                    class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                    逾期</div>
                    <?php elseif($plan_data[0]['plan_count']==0): ?>
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
                        尚未繳交</div>
                    <?php endif; ?>
                    <?php elseif($user->type != 'county' && count($plan_data)>0): ?>
                    <?php $__currentLoopData = $plan_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(array_key_exists('plan',$report_public_dates) &&
                    date("Y-m-d")>=$report_public_dates['plan']['expire_soon_date'] &&
                    date("Y-m-d")<=$report_public_dates['plan']['expire_date'] && $one_data['plan_count']==0): ?> <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_data['name']); ?></div>
                        <?php elseif(array_key_exists('plan',$report_public_dates) &&
                        $report_public_dates['plan']['expire_date']<=date("Y-m-d") && $one_data['plan_count']==0): ?> <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                            <?php echo e($one_data['name']); ?></div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
        </td>
    </tr>
    <tr class="border-b last:border-b-0">
        <td class="p-2 border-r last:border-r-0">期末簡報</td>
        <td class="p-2 border-r last:border-r-0">
            <?php echo e(($report_public_dates &&
            array_key_exists('presentation',$report_public_dates))?$report_public_dates['presentation']['c_expire_date']:''); ?>

        </td>
        <td class="p-2 text-center align-middle border-r last:border-r-0">
            <?php if($user->type == 'county' && count($presentation_data)>0): ?>
            <?php if($presentation_data[0]['presentation_count']>0): ?>
            <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-lime-500">
                已繳交</div>
            <?php elseif(array_key_exists('presentation',$report_public_dates) &&
            date("Y-m-d")>=$report_public_dates['presentation']['expire_soon_date'] &&
            date("Y-m-d")<=$report_public_dates['presentation']['expire_date'] &&
                $presentation_data[0]['presentation_count']==0): ?> <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                即將逾期</div>
                <?php elseif(array_key_exists('presentation',$report_public_dates) &&
                $report_public_dates['presentation']['expire_date']<=date("Y-m-d") &&
                    $presentation_data[0]['presentation_count']==0): ?> <div
                    class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                    逾期</div>
                    <?php elseif($presentation_data[0]['presentation_count']==0): ?>
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
                        尚未繳交</div>
                    <?php endif; ?>
                    <?php elseif($user->type != 'county' && count($presentation_data)>0): ?>
                    <?php $__currentLoopData = $presentation_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(array_key_exists('presentation',$report_public_dates) &&
                    date("Y-m-d")>=$report_public_dates['presentation']['expire_soon_date'] &&
                    date("Y-m-d")<=$report_public_dates['presentation']['expire_date'] &&
                        $one_data['presentation_count']==0): ?> <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_data['name']); ?></div>
                        <?php elseif(array_key_exists('presentation',$report_public_dates) &&
                        $report_public_dates['presentation']['expire_date']<=date("Y-m-d") &&
                            $one_data['presentation_count']==0): ?> <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                            <?php echo e($one_data['name']); ?></div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
        </td>
    </tr>
    
    <tr class="border-b last:border-b-0">
        <td class="p-2 border-r last:border-r-0">執行進度管制表-期初</td>
        <td class="p-2 border-r last:border-r-0">
            <?php echo e(($report_public_dates &&
            array_key_exists('seasonal0',$report_public_dates))?$report_public_dates['seasonal0']['c_expire_date']:''); ?>

        </td>
        <td class="p-2 text-center align-middle border-r last:border-r-0">
            <?php if($user->type == 'county' && count($seasonal_report_2_data)>0): ?>
            <?php if($seasonal_report_2_data[0]['report_count']>0): ?>
            <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-lime-500">
                已繳交</div>
            <?php elseif(array_key_exists('seasonal0',$report_public_dates) &&
            date("Y-m-d")>=$report_public_dates['seasonal0']['expire_soon_date'] &&
            date("Y-m-d")<=$report_public_dates['seasonal0']['expire_date'] &&
                $seasonal_report_2_data[0]['report_count']==0): ?> <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                即將逾期</div>
                <?php elseif(array_key_exists('seasonal0',$report_public_dates) &&
                $report_public_dates['seasonal0']['expire_date']<=date("Y-m-d") &&
                    $seasonal_report_2_data[0]['report_count']==0): ?> <div
                    class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                    逾期</div>
                    <?php elseif($seasonal_report_2_data[0]['report_count']==0): ?>
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
                        尚未繳交</div>
                    <?php endif; ?>
                    <?php elseif($user->type != 'county' && count($seasonal_report_2_data)>0): ?>
                    <?php $__currentLoopData = $seasonal_report_2_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(array_key_exists('seasonal0',$report_public_dates) &&
                    date("Y-m-d")>=$report_public_dates['seasonal0']['expire_soon_date'] &&
                    date("Y-m-d")<=$report_public_dates['seasonal0']['expire_date'] && $one_data['report_count']==0): ?>
                        <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_data['name']); ?></div>
                        <?php elseif(array_key_exists('seasonal0',$report_public_dates) &&
                        $report_public_dates['seasonal0']['expire_date']<=date("Y-m-d") && $one_data['report_count']==0): ?>
                            <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                            <?php echo e($one_data['name']); ?></div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
        </td>
    </tr>
    <tr class="border-b last:border-b-0">
        <td class="p-2 border-r last:border-r-0">執行進度管制表-期中</td>
        <td class="p-2 border-r last:border-r-0">
            <?php echo e(($report_public_dates &&
            array_key_exists('seasonal1',$report_public_dates))?$report_public_dates['seasonal1']['c_expire_date']:''); ?>

        </td>
        <td class="p-2 text-center align-middle border-r last:border-r-0">
            <?php if($user->type == 'county' && count($seasonal_report_2_data)>0): ?>
            <?php if($seasonal_report_2_data[0]['report_count']>0): ?>
            <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-lime-500">
                已繳交</div>
            <?php elseif(array_key_exists('seasonal1',$report_public_dates) &&
            date("Y-m-d")>=$report_public_dates['seasonal1']['expire_soon_date'] &&
            date("Y-m-d")<=$report_public_dates['seasonal1']['expire_date'] &&
                $seasonal_report_2_data[0]['report_count']==0): ?> <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                即將逾期</div>
                <?php elseif(array_key_exists('seasonal1',$report_public_dates) &&
                $report_public_dates['seasonal1']['expire_date']<=date("Y-m-d") &&
                    $seasonal_report_2_data[0]['report_count']==0): ?> <div
                    class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                    逾期</div>
                    <?php elseif($seasonal_report_2_data[0]['report_count']==0): ?>
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
                        尚未繳交</div>
                    <?php endif; ?>
                    <?php elseif($user->type != 'county' && count($seasonal_report_2_data)>0): ?>
                    <?php $__currentLoopData = $seasonal_report_2_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(array_key_exists('seasonal1',$report_public_dates) &&
                    date("Y-m-d")>=$report_public_dates['seasonal1']['expire_soon_date'] &&
                    date("Y-m-d")<=$report_public_dates['seasonal1']['expire_date'] && $one_data['report_count']==0): ?>
                        <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_data['name']); ?></div>
                        <?php elseif(array_key_exists('seasonal1',$report_public_dates) &&
                        $report_public_dates['seasonal1']['expire_date']<=date("Y-m-d") && $one_data['report_count']==0): ?>
                            <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                            <?php echo e($one_data['name']); ?></div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
        </td>
    </tr>
    <tr class="border-b last:border-b-0">
        <td class="p-2 border-r last:border-r-0">執行進度管制表-期末</td>
        <td class="p-2 border-r last:border-r-0">
            <?php echo e(($report_public_dates &&
            array_key_exists('seasonal2',$report_public_dates))?$report_public_dates['seasonal2']['c_expire_date']:''); ?>

        </td>
        <td class="p-2 text-center align-middle border-r last:border-r-0">
            <?php if($user->type == 'county' && count($seasonal_report_3_data)>0): ?>
            <?php if($seasonal_report_3_data[0]['report_count']>0): ?>
            <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-lime-500">
                已繳交</div>
            <?php elseif(array_key_exists('seasonal2',$report_public_dates) &&
            date("Y-m-d")>=$report_public_dates['seasonal2']['expire_soon_date'] &&
            date("Y-m-d")<=$report_public_dates['seasonal2']['expire_date'] &&
                $seasonal_report_3_data[0]['report_count']==0): ?> <div
                class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                即將逾期</div>
                <?php elseif(array_key_exists('seasonal2',$report_public_dates) &&
                $report_public_dates['seasonal2']['expire_date']<=date("Y-m-d") &&
                    $seasonal_report_3_data[0]['report_count']==0): ?> <div
                    class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                    逾期</div>
                    <?php elseif($seasonal_report_3_data[0]['report_count']==0): ?>
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
                        尚未繳交</div>
                    <?php endif; ?>
                    <?php elseif($user->type != 'county' && count($seasonal_report_3_data)>0): ?>
                    <?php $__currentLoopData = $seasonal_report_3_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(array_key_exists('seasonal2',$report_public_dates) &&
                    date("Y-m-d")>=$report_public_dates['seasonal2']['expire_soon_date'] &&
                    date("Y-m-d")<=$report_public_dates['seasonal2']['expire_date'] && $one_data['report_count']==0): ?>
                        <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_data['name']); ?></div>
                        <?php elseif(array_key_exists('seasonal2',$report_public_dates) &&
                        $report_public_dates['seasonal2']['expire_date']<=date("Y-m-d") && $one_data['report_count']==0): ?>
                            <div
                            class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white rounded-lg h-7 w-[6rem] bg-rose-600">
                            <?php echo e($one_data['name']); ?></div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
        </td>
    </tr>
    </table>
    </div>
    <div class="mb-10">
        <div class="mb-2 font-bold">績效評估自評表</div>
        <table class="w-full text-center border">
            <tr class="font-bold border-b last:border-b-0">
                <th class="p-2 border-r last:border-r-0">項目</th>
                <th class="p-2 border-r last:border-r-0">填報日期</th>
                <th class="p-2 border-r last:border-r-0">狀態</th>
            </tr>
            <?php $__currentLoopData = $questionnaire_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b last:border-b-0">
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e($one_questionnaire['title']); ?>

                </td>
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e($one_questionnaire['c_date_from'] . "～" . $one_questionnaire['c_date_to']); ?>

                </td>
                <td class="p-2 text-center align-middle border-r last:border-r-0">
                    <?php if(date("Y-m-d")<$one_questionnaire['date_from']): ?> <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-gray-500 rounded-lg h-7 w-[6rem]">
                        尚未開始
    </div>
    <?php elseif(date("Y-m-d")>$one_questionnaire['date_to']): ?>
    <div class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-rose-500 rounded-lg h-7 w-[6rem]">
        已結束</div>
    <?php else: ?>
    <div class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
        進行中</div>
    <?php endif; ?>
    </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    </div>
    <div class="mb-10">
        <div class="mb-2 font-bold">進階防災士逾期狀況</div>
        <table class="w-full text-center border">
            <tr class="font-bold border-b last:border-b-0">
                <th class="p-2 border-r last:border-r-0">姓名</th>
                <th class="p-2 border-r last:border-r-0">進階防災士受訓中狀態到期日</th>
                <th class="p-2 border-r last:border-r-0">狀態</th>
            </tr>
            <?php $__currentLoopData = $dp_advance_soon_expire_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b last:border-b-0">
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e($one_student['name']); ?>

                </td>
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e(date("Y-m-d",strtotime($one_student['date_first_finish'] . " +{$DP_student_valid_year} year"))); ?>

                </td>
                <td class="p-2 text-center align-middle border-r last:border-r-0">
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_student['expire_state']); ?>

                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $dp_advance_expire_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b last:border-b-0">
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e($one_student['name']); ?>

                </td>
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e(date("Y-m-d",strtotime($one_student['date_first_finish'] . " +{$DP_student_valid_year} year"))); ?>

                </td>
                <td class="p-2 text-center align-middle border-r last:border-r-0">
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-rose-500 rounded-lg h-7 w-[6rem]">
                        <?php echo e($one_student['expire_state']); ?>

                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
    <div class="mb-10">
        <div class="mb-2 font-bold">韌性社區逾期狀況</div>
        <table class="w-full text-center border">
            <tr class="font-bold border-b last:border-b-0">
                <th class="p-2 border-r last:border-r-0">社區名稱</th>
                <th class="p-2 border-r last:border-r-0">星等</th>
                <th class="p-2 border-r last:border-r-0">狀態</th>
            </tr>
            <?php $__currentLoopData = $dc_unit_soon_expire_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b last:border-b-0">
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e($one_unit['county']['name'] .$one_unit['name']); ?>

                </td>
                <td class="p-2 border-r last:border-r-0">
                    <div><?php echo e($one_unit['rank']); ?></div>
                    <div class="text-sm">
                        <?php echo e("(有效期限： {$one_unit['rank_expired_date']})"); ?>

                    </div>
                </td>
                <td class="p-2 text-center align-middle border-r last:border-r-0">
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-yellow-500 rounded-lg h-7 w-[6rem]">
                        即將逾期
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $dc_unit_expire_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one_unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b last:border-b-0">
                <td class="p-2 border-r last:border-r-0">
                    <?php echo e($one_unit['county']['name'] .$one_unit['name']); ?>

                </td>
                <td class="p-2 border-r last:border-r-0">
                    <div><?php echo e($one_unit['rank']); ?></div>
                    <div class="text-sm">
                        <?php echo e("(有效期限： {$one_unit['rank_expired_date']})"); ?>

                    </div>
                </td>
                <td class="p-2 text-center align-middle border-r last:border-r-0">
                    <div
                        class="text-center align-middle mb-1 pt-1 mx-auto text-sm text-white bg-rose-500 rounded-lg h-7 w-[6rem]">
                        逾期
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
    </div>
    </div>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', [
'heading' => '計畫管考項目狀態通知',
'breadcrumbs' => [
'首頁'
]
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/dashboard/report.blade.php ENDPATH**/ ?>