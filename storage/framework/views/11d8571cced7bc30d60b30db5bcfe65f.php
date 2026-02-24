<?php $__env->startSection('title', '強韌臺灣計畫資訊網'); ?>

<?php $__env->startSection('beforeContainer'); ?>
<img src="/image/new_bg1.png"
    class="absolute top-0 right-0 w-[50vw] sm:w-auto h-auto sm:max-w-xl lg:max-w-2xl xl:max-w-[850px] sm:block -z-10">
<div class="relative flex flex-col justify-start w-full h-[50vh] sm:h-screen max-w-screen-2xl">

    <div
        class="flex tracking-widest flex-col items-start justify-center w-full pl-12 sm:pl-24 space-y-8 mt-[15vh] sm:mt-[20vh]">
        <span class="text-5xl sm:text-7xl text-mainTextGray">強韌臺灣</span>
        <span class="text-3xl sm:text-5xl text-mainAdminTextGrayDark">大規模風災震災整備與協作</span>
        <div class="flex flex-row items-end justify-start">
            <span class="pb-2 pr-6 text-4xl border-b-4 sm:text-6xl text-mainBlue border-mainBlue">計畫資訊網</span>
            <div class="w-3 h-3 -mb-1 rounded-full bg-mainBlue"></div>
        </div>
    </div>
    <div
        class=" bg-mainYellow z-10 absolute top-[40vh] sm:top-[70vh] left-[50vw] sm:left-[30vw] pointer-events-none w-9 h-9 rounded-full ani-floatting">
    </div>
    <div
        class="absolute z-10 hidden w-48 h-48 rounded-full pointer-events-none sm:block bg-mainBlue -bottom-14 opacity-60 left-8 ani-floatting">
    </div>
</div>
<div x-data="{
    selected: 0,
    slides:<?php echo e(json_encode($homePageCarouselItems)); ?>,
}" class="flex flex-col justify-center w-full px-4 py-4 space-x-0 space-y-8 lg:space-y-0 lg:flex-row lg:items-stretch lg:space-x-14 max-w-screen-2xl 2xl:px-0"
    x-init="$nextTick(() => {
 })">
    <div class="relative w-full lg:flex-1" :class="{'hidden':slides.length==0,'block':slides.length>0}">
        <template x-if="slides[selected].url">
            <a :href="slides[selected].url" target="_blank"
                class="overflow-hidden hover:text-transparent rounded-[3rem] lg:rounded-l-none hover:no-underline">
                <img class="object-cover object-center w-full h-full aspect-video rounded-[3rem] lg:rounded-l-none block"
                    :src="slides[selected].image_url" :alt="slides[selected].title" />
            </a>
        </template>
        <template x-if="!slides[selected].url">
            <img class="object-cover object-center w-full h-full aspect-video rounded-[3rem] lg:rounded-l-none block"
                :src="slides[selected].image_url" :alt="slides[selected].title" />
        </template>
        <!-- Prev/Next Arrows -->
        <button @click="if (selected > 0 ) {selected -= 1} else { selected = slides.length - 1 }"
            class="absolute w-12 h-12 cursor-pointer top-[calc(50%-1.5rem)] left-4 hover:text-white text-white/50">
            <span class="w-10 h-10 i-fa6-solid-circle-chevron-left">
                &larr;
            </span>
        </button>
        <button @click="if (selected < slides.length - 1  ) {selected += 1} else { selected = 0 }"
            class="absolute w-12 h-12 cursor-pointer top-[calc(50%-1.5rem)] right-4 hover:text-white text-white/50">
            <span class="w-10 h-10 i-fa6-solid-circle-chevron-right">
                &rarr;
            </span>
        </button>

        <div class="absolute bottom-0 flex justify-center w-full p-4 space-x-2">
            <template x-for="(image,index) in slides" :key="index">
                <button @click="selected = index"
                    :class="{'bg-white/70': selected == index, 'bg-white/40': selected != index}"
                    class="w-2 h-2 rounded-full hover:bg-white/40 ring-2 ring-gray-300"></button>
            </template>
        </div>
    </div>
    <div class="relative flex flex-col items-start justify-start w-full lg:w-[40%] pt-8 lg:pt-0 px-6 lg:px-10">
        <div class="absolute z-10 rounded-full w-28 h-28 opacity-30 -top-8 -left-4 bg-mainPink ani-floatting"></div>
        <div class="flex flex-row items-end justify-start pb-16 space-x-4">
            <span class="text-5xl md:text-6xl text-mainBlue whitespace-nowrap">關於我們</span>
            <div class="w-24 h-2 rounded-full sm:w-28 bg-mainBlue"></div>
        </div>
        <div class="flex flex-col flex-1 w-full space-y-6">
            <div class=" text-mainTextGray">經災害防救深耕計畫長期推動，已達到強化地方政府災害防救能量及提升民間自助及互助能量之階段性目標。</div>
            <div class=" text-mainTextGray">
                為因應大規模災害情境，內政部持續推動「強韌臺灣大規模風災震災整備與協作計畫」，以「大規模災害整備」、「跨域支援合作」及「政府持續運作」3大核心規劃；以期達到持續建立、精進相關整備工作，包括大規模災害情境假設、大規模災害脆弱度及韌性盤點、公部門持續運作機制、鄉（鎮、市、區）公所區域聯防合作機制、直轄市、縣（市）政府互相支援合作機制、民間協作管理機制等多元成效。
            </div>
        </div>
        <div class="w-full pt-8 text-right lg:pt-2">
            <a href="/page/rtp_intro"
                class="px-4 py-2 text-white rounded-md bg-mainBlue hover:text-white hover:no-underline hover:bg-mainGrayDark">Learn
                More</a>
        </div>

    </div>
</div>
<div x-data="{
    selected: 0,
    slides:<?php echo e(json_encode($homePageCarouselItems)); ?>,
}" class="flex flex-col justify-center w-full px-4 py-4 pt-24 space-x-0 space-y-8 lg:space-y-0 lg:flex-row lg:items-stretch lg:space-x-4 max-w-screen-2xl 2xl:px-0"
    x-init="$nextTick(() => {
 })">
    <div class="relative flex flex-col items-start justify-start w-full lg:w-[40%] pt-8 lg:pt-0 px-6 lg:px-10">
        <div class="flex flex-row items-end justify-center w-full pb-8 space-x-4">
            <span class="text-4xl md:text-5xl text-mainBlue whitespace-nowrap">最新消息</span>
        </div>
        <div class="flex flex-col items-start justify-start w-full sm:flex-1">
            <?php $__empty_1 = true; $__currentLoopData = $news_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div
                class="relative flex flex-col w-full px-4 py-2 my-2 space-y-2 border rounded bg-mainLight text-mainAdminTextGrayDark">
                <div class="flex flex-row items-center justify-start">
                    <span class="p-1 text-xs text-white rounded-full bg-mainGrayDark whitespace-nowrap"><?php echo e($item->sort); ?></span>
                    <span class="flex-1 px-2 text-sm text-right text-mainAdminTextGrayDark"><?php echo e($item->author->name); ?>

                        發表於 <?php echo e($item->created_at->format('Y-m-d')); ?></span>
                    <span class="flex items-center justify-center ml-1 space-x-1 text-sm"><i
                            class="i-fa6-solid-eye"></i>
                        <span>
                            <?php echo e($item->counter_count); ?> 瀏覽
                        </span>
                    </span>
                </div>
                <span class="text-left text-mainBlueDark">
                    <?php echo e(link_to_route('introduction.public-news.show', $item->title, $item)); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div
                class="relative flex flex-col w-full px-4 py-2 my-2 border rounded bg-mainLight text-mainAdminTextGrayDark">
                無消息</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="relative flex flex-col items-start justify-start w-full lg:flex-1"
        :class="{'hidden':slides.length==0,'block':slides.length>0}">
        <div class="flex flex-row items-end justify-center w-full pb-8 space-x-4">
            <span class="text-4xl md:text-5xl text-mainBlue whitespace-nowrap">防災士培訓課程</span>
        </div>
        <?php if($course_data->count()>0): ?>
        <table class="w-full text-sm bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    <th class="w-1/4 p-2 font-bold border-r last:border-r-0">主辦單位</th>
                    <th class="w-1/4 p-2 font-bold border-r last:border-r-0">課程名稱</th>
                    <th class="p-2 font-bold border-r last:border-r-0">連絡電話</th>
                    <th class="p-2 font-bold border-r last:border-r-0">E-mail</th>
                    <th class="p-2 font-bold border-r last:border-r-0">開課日期</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $course_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                    <td class="p-2 text-center border-r last:border-r-0">
                        <?php echo e($item->organizer=='消防署'?'內政部消防署':(preg_match('/(市|縣)$/',$item->organizer)?$item->organizer.'政府':$item->organizer)); ?>

                    </td>
                    <td class="p-2 text-center border-r last:border-r-0"><?php echo Html::linkroute('dp.courseShow',
                        $item->name, $item->id); ?></td>
                    <td class="p-2 text-center border-r last:border-r-0"><?php echo e($item->phone); ?></td>
                    <td class="p-2 text-center border-r last:border-r-0"><?php echo e($item->email); ?></td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        <div class="flex flex-col items-start justify-start space-y-1">
                            <span class="whitespace-nowrap">
                                <?php echo e($item->date_from); ?> ~ <?php echo e($item->date_to); ?>

                            </span>
                            <div class="flex flex-row items-center justify-between w-full">
                                <span
                                    class="p-1 text-xs text-white rounded-md <?php echo e($item->advance?'bg-amber-600':'bg-mainBlue'); ?>"><?php echo e($item->advance?'進階課程':'一般課程'); ?></span>
                                <?php if((new Carbon\Carbon($item->date_from))->gt(today())): ?>
                                <span class="p-1 text-xs text-white rounded-md bg-mainBlue">尚未開始</span>
                                <?php elseif((new Carbon\Carbon($item->date_to))->lt(today())): ?>
                                <span class="p-1 text-xs text-white rounded-md bg-mainGrayDark">已結束</span>
                                <?php else: ?>
                                <span class="p-1 text-xs text-white rounded-md bg-lime-600">進行中</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div
            class="flex items-center justify-center flex-1 w-full min-h-[250px] lg:min-h-0 my-2 text-2xl bg-white border shadow text-mainBlue">
            <a href="/dp/training-institution">近期開課資訊請洽各大防災士培訓機構</a>
        </div>
        <?php endif; ?>

    </div>
</div>
<div class="w-full px-10 pt-24 pb-8">
    <div x-data="{
        pageList:[
            {
                'glyphicon': '/image/icon_project.svg',
                'title': '計畫簡介',
                'content': '介紹強韌臺灣計畫是什麼、推動各項工作的介紹，以及推動計畫期間的成效、動人故事等，並提供計畫檔案下載。',
            },
            {
                'glyphicon': '/image/icon_achievement.svg',
                'title': '成果資料',
                'content': '提供深耕1、2、3期計畫工作成果下載，以及內政部編撰的成果書冊，這些資料都是各縣市、鄉鎮市區以及協力團隊努力的結晶。',
            },
            {
                'glyphicon': '/image/icon_disaster_prevention.svg',
                'title': '防災士培訓',
                'content': '提供防災士的介紹、最新消息、各單位培訓課程開課狀況及報名方式，並陸續提供教材等資料下載，歡迎想加入防災士的夥伴瀏覽。',
            },
            {
                'glyphicon': '/image/icon_community.svg',
                'title': '推動韌性社區',
                'content': '提供韌性社區的介紹、最新消息，並陸續提供操作手冊等資料下載，歡迎想申請韌性社區推動標章的社區夥伴瀏覽，已取得標章的社區，也可於登入後查詢上傳社區的各種防災資料。',
            },
            {
                'glyphicon': '/image/icon_resource.svg',
                'title': '相關資源連結',
                'content': '提供強韌臺灣計畫相關參考檔案及資源。',
            },
            {
                'glyphicon': '/image/icon_operator.svg',
                'title': '業務人員版',
                'content': '提供執行強韌臺灣計畫的縣市、鄉鎮市區業務人員，各種資料下載及管考作業功能，需有登入帳號密碼才可使用。',
            }
        ],
    }" class="w-full pl-4 pr-4 mx-auto max-w-screen-2xl" x-init="$nextTick(() => {
    })">
        <div class="relative flex flex-col items-center justify-center w-full pb-8">
            <div class="absolute z-10 rounded-full w-28 h-28 opacity-30 -top-12 bg-mainPink ani-floatting">
            </div>
            <h4 class="pb-4 text-5xl md:text-6xl text-mainBlue">頁面簡介</h4>
            <div class="h-2 rounded-full w-28 bg-mainBlue"></div>
        </div>
        <div class="flex flex-row flex-wrap items-stretch justify-center w-full">
            <template x-for="(item,index) in pageList" :key="'page'+index">
                <div class="flex flex-col items-center justify-start w-full px-4 py-8 md:w-1/2 lg:w-1/3">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <img :src="item.glyphicon" class="h-auto w-36">
                        <span class="text-4xl text-mainAdminTextGrayDark" x-text="item.title"></span>
                        <span class="w-3/4 text-base md:w-1/2 text-mainTextGray" x-text="item.content"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<div class="w-full px-4 pt-24 sm:px-10 pb-28 sm:pb-20">
    <div class="flex flex-col items-start justify-start w-full pl-4 pr-4 mx-auto max-w-screen-2xl" x-init="$nextTick(() => {
    })">
        <div class="relative flex flex-row items-end justify-start w-full pb-12">
            <div
                class="absolute z-10 w-24 h-24 rounded-full opacity-30 -top-14 left-[23rem] hidden sm:block bg-mainPink ani-floatting">
            </div>
            <div
                class="absolute z-10 w-20 h-20 rounded-full opacity-70 top-4 right-[12rem]  hidden lg:block bg-mainBlue ani-floatting">
            </div>
            <div
                class="absolute z-10 w-8 h-8 rounded-full -top-[4.25rem] right-[22rem] hidden lg:block bg-mainYellow ani-floatting">
            </div>
            <div class="w-24 h-2 mr-10 rounded-full sm:w-28 bg-mainBlue"></div>
            <h4 class="text-5xl md:text-6xl text-mainBlue whitespace-nowrap">歷年成果</h4>
        </div>
        <div
            class="flex flex-col items-center justify-center w-full space-x-0 space-y-4 sm:flex-row sm:space-x-6 sm:space-y-0 sm:items-stretch">
            <a href="/dp/statistics"
                class="flex flex-col items-center justify-center flex-1 w-full px-4 py-8 space-x-2 border-2 md:flex-1 border-mainBlue rounded-2xl">
                <div class="flex flex-row items-center justify-center space-x-2">
                    <img src="/image/icon_disaster_prevention_number.svg" class="w-auto h-16">
                    <div class="flex flex-col items-center justify-center">
                        <span class="pt-1 text-sm text-gray-400 sm:text-lg">防災士認證總人數</span>
                        <span class="text-sm text-gray-400 sm:text-lg">（截至 <?php echo e($endDate); ?> ）</span>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center space-x-2">
                    <span class="font-sans text-[3rem] sm:text-[6.5rem] text-mainYellow font-extrabold">
                                       <?php echo e(number_format($dpStudentStatistics['normal_total'])); ?>

                    </span>
                    <span class="-mb-12 text-lg text-gray-400">名</span>
                </div>
            </a>
            <a href="/dp/advanced-statistics"
                class="flex flex-col items-center justify-center flex-1 w-full px-4 py-8 space-x-2 border-2 md:flex-1 border-mainBlue rounded-2xl">
                <div class="flex flex-row items-center justify-center space-x-2">
                    <img src="/image/icon_disaster_prevention_number.svg" class="w-auto h-16">
                    <div class="flex flex-col items-center justify-center">
                        <span class="pt-1 text-sm text-gray-400 sm:text-lg">進階防災士認證總人數</span>
                        <span class="text-sm text-gray-400 sm:text-lg">（截至 <?php echo e($end_year); ?> 年 <?php echo e($end_month); ?> 月）</span>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center space-x-2">
                    <span class="font-sans text-[3rem] sm:text-[6.5rem] text-mainYellow font-extrabold">
                        <?php echo e(number_format($dpStudentStatistics['advanced_total'])); ?>

                    </span>
                    <span class="-mb-12 text-lg text-gray-400">名</span>
                </div>
            </a>
            <a href="/dc/show-unit"
                class="flex flex-col items-center justify-center flex-1 w-full px-4 py-8 border-2 md:flex-1 border-mainBlue rounded-2xl">
                <div class="flex flex-row items-center justify-center space-x-2">
                    <img src="/image/icon_community_number.svg" class="w-auto h-[4.5rem]">
                    <span class="pt-1 text-lg text-gray-400">韌性社區認證總數</span>
                </div>
                <div class="flex flex-row items-center justify-center space-x-2">
                    <span class="font-sans text-[3rem] sm:text-[6.5rem] text-mainYellow font-extrabold">
                        <?php echo e(number_format((array_key_exists('一星',$rankCount)?$rankCount['一星']:0)+(array_key_exists('二星',$rankCount)?$rankCount['二星']:0)+(array_key_exists('三星',$rankCount)?$rankCount['三星']:0))); ?>

                    </span>
                    <span class="-mb-12 text-lg text-gray-400">處</span>
                </div>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/index.blade.php ENDPATH**/ ?>