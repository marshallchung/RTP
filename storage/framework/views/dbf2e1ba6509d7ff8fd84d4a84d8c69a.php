<div x-data="{
            nowFloatMenu:null,
            nowClickMenu:null,
        }" class="text-base transition-all duration-500" :class="{'w-[240px]':openMMC,'w-[56px]':!openMMC}"
    :class="{'':openMMC,'overflow-visible':!openMMC}">
    <div class="pb-6 transition-all duration-300" :class="{'w-[240px]':openMMC,'w-[56px]':!openMMC}">
        <ul class="w-full">
            <?php if(Auth::user()->hasPermission('admin-permissions') || Auth::user()->hasPermission('NFA-permissions') ||
            Auth::user()->hasPermission('County-permissions')): ?>
            <li x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" @mouseenter="setFloatMenu('首頁')" @mouseleave="unsetFloatMenu('首頁')"
                class="flex flex-col items-center justify-center  transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark h-[44px]"
                :class="{'w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC,'bg-mainCyanDark text-white':(!openMMC && floatMenu)}">
                <a href="<?php echo e(route('admin.dashboard.index')); ?>"
                    class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full">
                    <i class="w-4 h-4 i-clarity-dashboard-solid"></i>
                    <span x-show="openMMC" class=" whitespace-nowrap">首頁</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('sidebar-news')): ?>
            <li class="relative flex flex-col items-center justify-start" @click.away="unsetClickMenu('近期重點工作')"
                @mouseenter="setFloatMenu('近期重點工作')" @mouseleave="unsetFloatMenu('近期重點工作')"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }">
                <button type="button" @click="setClickMenu('近期重點工作')"
                    class="flex flex-row items-center w-full justify-start space-x-2 text-gray-100 hover:text-white p-[14px] border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-file-lines"></i>
                    <span x-show="openMMC">近期重點工作</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">近期重點工作</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('admin-permissions') ||
                    Auth::user()->hasPermission('NFA-permissions') || 
					Auth::user()->origin_role==6 ): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.news.create')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">新增</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.news.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">所有</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('admin-permissions') || Auth::user()->hasPermission('NFA-permissions') ||
            Auth::user()->hasPermission('County-permissions')): ?>
            <li @click.away="unsetClickMenu('計畫規範與相關資料')" @mouseenter="setFloatMenu('計畫規範與相關資料')"
                @mouseleave="unsetFloatMenu('計畫規範與相關資料')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('計畫規範與相關資料')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-upload"></i>
                    <span x-show="openMMC">計畫規範與相關資料</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">計畫規範與相關資料</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('create-uploads')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.uploads.create')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">上傳</span>
                        </a>
                    </li>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.uploads.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">修改</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.uploads.view')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">所有</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li @click.away="unsetClickMenu('成果資料')" @mouseenter="setFloatMenu('成果資料')"
                @mouseleave="unsetFloatMenu('成果資料')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('成果資料')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-user"></i>
                    <span x-show="openMMC">成果資料</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">成果資料</span>
                    </li>
                    <?php if(Auth::user()->isAbleTo('create-report-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.date.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">資料公開時間</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->isAbleTo('create-reports')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.submit', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                        p-[14px] space-x-2">
                            <span class="pl-6">資料上傳</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.index', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                    p-[14px] space-x-2">
                            <span class="pl-6">資料展示</span></a>
                    </li>
                    <?php if(Auth::user()->isAbleTo('create-report-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.inquire', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                        p-[14px] space-x-2">
                            <span class="pl-6">管理與查詢功能</span></a>
                    </li>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.evaluationCommission.index', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                            p-[14px] space-x-2">
                            <span class="pl-6">管考作業</span></a>
                    </li>
                    <?php else: ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.inquireByCounty', ['year' => date('Y')])); ?>?category_id=&year=2023"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                            p-[14px] space-x-2">
                            <span class="pl-6">管理與查詢功能</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-report-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.distribute.index')); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                                            p-[14px] space-x-2">
                            <span class="pl-6">縣市政府歷年統計</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('create-plans') || Auth::user()->hasPermission('create-plans-for-county')): ?>
            <li @click.away="unsetClickMenu('執行計畫書')" @mouseenter="setFloatMenu('執行計畫書')"
                @mouseleave="unsetFloatMenu('執行計畫書')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('執行計畫書')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-user"></i>
                    <span x-show="openMMC">執行計畫書</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">執行計畫書</span>
                    </li>
                    <?php if(Auth::user()->isAbleTo('create-plans-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.plans.date.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">資料公開時間</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-plans') ||
                    Auth::user()->hasPermission('create-plans-for-county')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.plans.create', [
                            'year' => date('Y'),
                            ])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                            p-[14px] space-x-2">
                            <span class="pl-6">資料上傳</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('view-plans')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.plans.inquire', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                        p-[14px] space-x-2">
                            <span class="pl-6">資料展示</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('create-plans') || Auth::user()->hasPermission('create-plans-for-county')
            || Auth::user()->hasPermission('view-plans') ): ?>
            <li @click.away="unsetClickMenu('期末簡報')" @mouseenter="setFloatMenu('期末簡報')"
                @mouseleave="unsetFloatMenu('期末簡報')" x-data="{
                            showSubMenu:false,
                            floatMenu:false,
                            setFloatMenu(id){
                                if(this.nowClickMenu===null){
                                    this.floatMenu=true;
                                    this.nowFloatMenu=id;
                                }
                            },
                            unsetFloatMenu(id){
                                this.floatMenu=false;
                                if(this.nowFloatMenu===id){
                                    this.nowFloatMenu=null;
                                }
                            },
                            setClickMenu(id){
                                this.showSubMenu=this.nowClickMenu===id?false:true;
                                this.nowClickMenu=this.nowClickMenu===id?null:id;
                            },
                            unsetClickMenu(id){
                                this.floatMenu=false;
                                this.showSubMenu=false;
                                if(this.nowClickMenu===id){
                                    this.nowClickMenu=null;
                                }
                            }
                        }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('期末簡報')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-user"></i>
                    <span x-show="openMMC">期末簡報</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">期末簡報</span>
                    </li>
                    <?php if(Auth::user()->isAbleTo('create-presentation-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.presentation.date.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">資料公開時間</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-plans') ||
                    Auth::user()->hasPermission('County-permissions')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.presentation.create', [
                                        'year' => date('Y'),
                                        ])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                                        p-[14px] space-x-2">
                            <span class="pl-6">資料上傳</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('view-plans')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.presentation.inquire', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                                    p-[14px] space-x-2">
                            <span class="pl-6">資料展示</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('sidebar-samplereport')): ?>
            <li @click.away="unsetClickMenu('優選範本')" @mouseenter="setFloatMenu('優選範本')"
                @mouseleave="unsetFloatMenu('優選範本')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('優選範本')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-thumbs-up"></i>
                    <span x-show="openMMC">優選範本</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">優選範本</span>
                    </li>
                    <?php if(Auth::user()->isAbleTo('create-sample-report-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.sample-report.date.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">資料公開時間</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.sample-report.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">優選範本</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('create-seasonalReports') ||
            Auth::user()->hasPermission('view-all-seasonalReports')): ?>
            <li @click.away="unsetClickMenu('執行進度管制表')" @mouseenter="setFloatMenu('執行進度管制表')"
                @mouseleave="unsetFloatMenu('執行進度管制表')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('執行進度管制表')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-user"></i>
                    <span x-show="openMMC">執行進度管制表</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">執行進度管制表</span>
                    </li>
                    <?php if(Auth::user()->isAbleTo('create-seasonal-report-public-date')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.seasonal-report.date.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">資料公開時間</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-seasonalReports')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.seasonalReports.submit', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                            p-[14px] space-x-2">
                            <span class="pl-6">資料上傳<?php echo e(Auth::user()->role); ?></span></a>
                    </li>
                    <?php endif; ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.seasonalReports.index', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                            p-[14px] space-x-2">
                            <span class="pl-6">資料展示</span></a>
                    </li>
                    <?php if(Auth::user()->hasPermission('view-all-seasonalReports')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.seasonalReports.inquire', ['year' => date('Y')])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                            p-[14px] space-x-2">
                            <span class="pl-6">管理與查詢功能</span></a>
                    </li>
                    <?php elseif(Auth::user()->hasPermission('create-seasonalReports')): ?>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.seasonalReports.inquireByCounty')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">管理與查詢功能</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('sidebar-address')): ?>
            <li @click.away="unsetClickMenu('通訊錄')" @mouseenter="setFloatMenu('通訊錄')"
                @mouseleave="unsetFloatMenu('通訊錄')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('通訊錄')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-phone"></i>
                    <span x-show="openMMC">通訊錄</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">通訊錄</span>
                    </li>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.address.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">通訊錄查詢</span></a>
                    </li>
                    <?php if(Auth::check() && (in_array(Auth::user()->type, ['county', 'district']) ||
                    empty(Auth::user()->type))): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.address.manage')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">資料更新</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('admin-permissions')||
            Auth::user()->hasPermission('NFA-permissions')||
            Auth::user()->hasPermission('DEP-permissions')||
            Auth::user()->hasPermission('County-permissions')): ?>
            <li @click.away="unsetClickMenu('民眾版')" @mouseenter="setFloatMenu('民眾版')"
                @mouseleave="unsetFloatMenu('民眾版')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('民眾版')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-globe"></i>
                    <span x-show="openMMC">民眾版</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5   z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">民眾版</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('admin-permissions') ||
                    Auth::user()->hasPermission('NFA-permissions')): ?> <li
                        class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.reports.submit', ['year' => 2017, 'title' => '成果網資料上傳'])); ?>" class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white
                                p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">資料上傳</span></a>
                    </li>

                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="/admin/static-page/rtp_intro/edit"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">強韌臺灣計畫簡介</span></a>
                    </li>

                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.sign-location.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">防災避難看板</span></a>
                    </li>

                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.public-news.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">最新消息</span></a>
                    </li>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.home-page-carousel-image.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">首頁輪播設定</span></a>
                    </li>
                    <?php endif; ?>

                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.video.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">宣導影片及文宣</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('modify-static-page')): ?>
            <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                <a href="<?php echo e(route('admin.static-page.index')); ?>"
                    class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                    <i class="w-4 h-4 i-mdi-web-box" :class="{'w-4 h-4':openMMC,'w-5 h-5':!openMMC}"></i>
                    <span x-show="openMMC" class="transition-transform duration-300">靜態頁面</span></a>
            </li>
            <?php endif; ?>

            <li @click.away="unsetClickMenu('防災士培訓')" @mouseenter="setFloatMenu('防災士培訓')"
                @mouseleave="unsetFloatMenu('防災士培訓')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('防災士培訓')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-plus"></i>
                    <span x-show="openMMC">防災士培訓</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">防災士培訓</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('admin-permissions')||
                    Auth::user()->hasPermission('NFA-permissions')||
                    Auth::user()->hasPermission('DEP-permissions')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.front-introduction.edit', 1)); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">編輯防災士簡介(民眾版)</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('admin-permissions')||
                    Auth::user()->hasPermission('NFA-permissions')||
                    Auth::user()->hasPermission('County-permissions')||
                    Auth::user()->hasPermission('DEP-permissions')||
                    Auth::user()->hasPermission('DP-Training-permissions')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dp-teachers.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">師資資料庫管理</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('DP-students-manage') ||
                    Auth::user()->hasPermission('DP-Training-permissions')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dp-students.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">防災士資料管理</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('DP-courses-manage') ||
                    Auth::user()->hasPermission('DP-Training-permissions')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dp-courses.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">培訓課程管理</span></a>
                    </li>
                    <?php endif; ?>

                    

                    <?php if(Auth::user()->hasPermission('DP-experiences-manage')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dp-experiences.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">參與防災工作</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('DP-news-manage')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dpDownload.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">相關資料下載(民眾版)</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('DP-training-institution-manage')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dp-training-institution.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">防災士培訓機構(民眾版)</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>

            <li @click.away="unsetClickMenu('進階防災士培訓')" @mouseenter="setFloatMenu('進階防災士培訓')"
                @mouseleave="unsetFloatMenu('進階防災士培訓')" x-data="{
                        showSubMenu:false,
                        floatMenu:false,
                        setFloatMenu(id){
                            if(this.nowClickMenu===null){
                                this.floatMenu=true;
                                this.nowFloatMenu=id;
                            }
                        },
                        unsetFloatMenu(id){
                            this.floatMenu=false;
                            if(this.nowFloatMenu===id){
                                this.nowFloatMenu=null;
                            }
                        },
                        setClickMenu(id){
                            this.showSubMenu=this.nowClickMenu===id?false:true;
                            this.nowClickMenu=this.nowClickMenu===id?null:id;
                        },
                        unsetClickMenu(id){
                            this.floatMenu=false;
                            this.showSubMenu=false;
                            if(this.nowClickMenu===id){
                                this.nowClickMenu=null;
                            }
                        }
                    }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('進階防災士培訓')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-plus"></i>
                    <span x-show="openMMC">進階防災士培訓</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">進階防災士資料管理</span>
                    </li>
                    <li class="border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dp-advanced-students.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="pl-6">進階防災士資料管理</span></a>
                    </li>
                </ul>
            </li>


            <?php if(Auth::user()->hasPermission('admin-permissions')||
            Auth::user()->hasPermission('NFA-permissions')||
            Auth::user()->hasPermission('DEP-permissions')||
            Auth::user()->hasPermission('County-permissions')): ?>
            <li @click.away="unsetClickMenu('推動韌性社區')" @mouseenter="setFloatMenu('推動韌性社區')"
                @mouseleave="unsetFloatMenu('推動韌性社區')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('推動韌性社區')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-magnet"></i>
                    <span x-show="openMMC">推動韌性社區</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">推動韌性社區</span>
                    </li>
                    <?php if(!Auth::user()->hasPermission('County-permissions')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.front-introduction.edit', 2)); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">編輯韌性社區簡介(民眾版)</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dc-units.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">查詢與管理韌性社區資料</span></a>
                    </li>

                    <!-- TODO: -->
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dc-stages.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">社區防災計畫書上傳</span></a>
                    </li>

                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dc-stages-list.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">社區防災計畫書清單</span></a>
                    </li>
					
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dc-certifications.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">韌性社區標章申請表填寫及上傳</span></a>
                    </li>
                    <?php if(!Auth::user()->hasPermission('County-permissions')): ?>
                    
                    
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.dcDownload.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">相關資料下載(民眾版)</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <?php if(!Auth::user()->hasPermission('DEP-permissions') &&
            !Auth::user()->hasPermission('DP-Training-permissions')): ?>
            <li @click.away="unsetClickMenu('操作教學說明文件')" @mouseenter="setFloatMenu('操作教學說明文件')"
                @mouseleave="unsetFloatMenu('操作教學說明文件')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('操作教學說明文件')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-book"></i>
                    <span x-show="openMMC">操作教學說明文件</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">操作教學說明文件</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('create-guidance')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.guidance.create')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">新增</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.guidance.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">所有</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('admin-permissions') || Auth::user()->hasPermission('NFA-permissions') ||
            Auth::user()->hasPermission('County-permissions')): ?>
            <li @click.away="unsetClickMenu('績效評估指標')" @mouseenter="setFloatMenu('績效評估指標')"
                @mouseleave="unsetFloatMenu('績效評估指標')" x-data="{
                                showSubMenu:false,
                                floatMenu:false,
                                setFloatMenu(id){
                                    if(this.nowClickMenu===null){
                                        this.floatMenu=true;
                                        this.nowFloatMenu=id;
                                    }
                                },
                                unsetFloatMenu(id){
                                    this.floatMenu=false;
                                    if(this.nowFloatMenu===id){
                                        this.nowFloatMenu=null;
                                    }
                                },
                                setClickMenu(id){
                                    this.showSubMenu=this.nowClickMenu===id?false:true;
                                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                                },
                                unsetClickMenu(id){
                                    this.floatMenu=false;
                                    this.showSubMenu=false;
                                    if(this.nowClickMenu===id){
                                        this.nowClickMenu=null;
                                    }
                                }
                            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('績效評估指標')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-book"></i>
                    <span x-show="openMMC">績效評估指標</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">績效評估指標</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('create-questionnaires')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.questionnaire.create')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">新增自評表</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.questionnaire.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">列表&填寫</span></a>
                    </li>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.questionnaire.panel')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">檢視</span></a>
                    </li>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.questionnaire.statistic')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">分數統計表</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('admin-permissions') || Auth::user()->hasPermission('NFA-permissions')): ?> <li
                @click.away="unsetClickMenu('後台修正管理')" @mouseenter="setFloatMenu('後台修正管理')"
                @mouseleave="unsetFloatMenu('後台修正管理')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('後台修正管理')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-wrench"></i>
                    <span x-show="openMMC">後台修正管理</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">後台修正管理</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('topic-manage')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.admin.reportTerms')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">工作項目管理</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-publicTerms')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.frontDownload.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">相關資源連結(民眾版)</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-publicTerms')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.admin.publicTerms')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">民眾版簡介分類項目管理</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('create-publicUrls')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.admin.countyOrder')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">縣市順序管理</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('activity-log.access')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.activity-log.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">日誌紀錄</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->hasPermission('admin-permissions') || Auth::user()->hasPermission('NFA-permissions') ||
            Auth::user()->hasPermission('County-permissions') || Auth::user()->hasPermission('DP-Training-permissions')): ?>
            <li @click.away="unsetClickMenu('QA專區')" @mouseenter="setFloatMenu('QA專區')"
                @mouseleave="unsetFloatMenu('QA專區')" x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" class="relative flex flex-col items-center justify-start"
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC}">
                <button type="button" @click="setClickMenu('QA專區')"
                    class="flex flex-row items-center justify-start border-l-4 border-l-transparent hover:border-l-mainCyanDark transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full h-[44px]"
                    :class="{'bg-mainCyanDark text-white':(!openMMC && (showSubMenu || floatMenu))}">
                    <i class="w-4 h-4 i-fa6-solid-question"></i>
                    <span x-show="openMMC">QA專區</span>
                    <i class="absolute w-2.5 h-2.5 transition-all duration-300 i-fa6-solid-chevron-right right-4"
                        :class="{'rotate-90':showSubMenu && openMMC,'rotate-0':!showSubMenu,'right-4 left-auto':openMMC,' left-8 right-auto':!openMMC}"></i>
                </button>
                <ul class="flex-col flex mt-1.5 bg-mainMenuOpenBG z-10 max-h-0 overflow-hidden"
                    :class="{'block w-full pr-4':openMMC,'absolute left-[56px] -top-2 w-[240px] pr-0':!openMMC,'overflow-hidden max-h-0 transition-all duration-50':!(showSubMenu || (floatMenu && !openMMC)),'overflow-auto max-h-screen transition-all duration-500 ease-in opacity-100':(showSubMenu || (floatMenu && !openMMC))}">
                    <li class="bg-mainCyanDark text-white px-[14px] h-[46px] flex flex-row items-center justify-start w-full"
                        :class="{'overflow-hidden max-h-0 transition-all duration-100 ease-in-out':!((showSubMenu || floatMenu) && !openMMC),'overflow-auto max-h-screen transition-all duration-100 ease-in':((showSubMenu || floatMenu) && !openMMC)}">
                        <span class="pl-6">QA專區</span>
                    </li>
                    <?php if(Auth::user()->hasPermission('create-QAs')): ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.qas.create')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">新增</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="transition-all duration-300 border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                        <a href="<?php echo e(route('admin.qas.index')); ?>"
                            class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2">
                            <span class="transition-transform duration-300"
                                :class="{'opacity-0 transition-all duration-500':!(showSubMenu || (floatMenu && !openMMC)),' translate-x-6 opacity-100 transition-all duration-500':(showSubMenu || (floatMenu && !openMMC))}">所有</span></a>
                    </li>
                </ul>
            </li>
            <li x-data="{
                showSubMenu:false,
                floatMenu:false,
                setFloatMenu(id){
                    if(this.nowClickMenu===null){
                        this.floatMenu=true;
                        this.nowFloatMenu=id;
                    }
                },
                unsetFloatMenu(id){
                    this.floatMenu=false;
                    if(this.nowFloatMenu===id){
                        this.nowFloatMenu=null;
                    }
                },
                setClickMenu(id){
                    this.showSubMenu=this.nowClickMenu===id?false:true;
                    this.nowClickMenu=this.nowClickMenu===id?null:id;
                },
                unsetClickMenu(id){
                    this.floatMenu=false;
                    this.showSubMenu=false;
                    if(this.nowClickMenu===id){
                        this.nowClickMenu=null;
                    }
                }
            }" @mouseenter="setFloatMenu('意見交流專區')" @mouseleave="unsetFloatMenu('意見交流專區')"
                class="flex flex-col items-center justify-center "
                :class="{' w-[240px] items-start':openMMC,'w-[56px] items-center':!openMMC,'bg-mainCyanDark text-white':(!openMMC && floatMenu)}">
                <a href="https://www.facebook.com/groups/428143137374563/" target="_blank"
                    class="flex flex-row items-center justify-start transition-all duration-300 text-gray-100 hover:text-white p-[14px] space-x-2 w-full border-l-4 border-l-transparent hover:border-l-mainCyanDark">
                    <i class="w-4 h-4 i-fa6-brands-facebook"></i>
                    <span x-show="openMMC">意見交流專區</span>
                </a>
            </li>
            <?php endif; ?>

        </ul>
        <div x-show="openMMC" class="w-full py-4 pr-12 text-center text-mainAdminTextGray">
            客服專線：<br>02-81966123<br>02-81966122
        </div>
        <div x-show="openMMC" class="w-full pb-4 pr-12 text-center text-mainAdminTextGray">
            客服信箱：<br>hsuyaya@nfa.gov.tw<br>eric@nfa.gov.tw
        </div>
        <div x-show="openMMC" class="w-full pb-4 pr-12 text-center text-mainAdminTextGray">
            防災士客服專線：<br>02-81966118
        </div>
        <div x-show="openMMC" class="w-full pb-4 pr-12 text-center text-mainAdminTextGray">
            防災士客服信箱：<br>tdrvtiedp@gmail.com
        </div>
    </div>
</div><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>