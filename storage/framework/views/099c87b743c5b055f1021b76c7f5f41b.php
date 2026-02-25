<nav x-data={popupMenu:false,showMenu:null,showSubMenu:null}
    class="fixed top-0 left-0 right-0 bottom-0 flex flex-col items-center justify-start w-screen h-screen z-[1000]"
    :class="{'h-screen':popupMenu,' h-fit':!popupMenu}">
    <div class="relative flex flex-col items-center justify-center w-full"
        :class="{'<?php echo e(count(request()->segments())>0?'bg-black/70 border-b-0':'bg-gradient-to-b from-white to-gray-200 border-b sm:border-b-0 border-b-gray-300'); ?> shadow-lg':scrollTop>30,'bg-transparent shadow-none':scrollTop<=30}">
        <div data-tag="背景半透明暗色"
            class="fixed top-0 left-0 w-screen h-screen transition-opacity duration-300 bg-black opacity-0 pointer-events-none -z-0"
            :class="{'opacity-50 pointer-events-auto':popupMenu,'opacity-0 pointer-events-none':!popupMenu}"></div>
        <div data-tag="選單漸層底色"
            class="absolute top-0 right-0 flex flex-row items-start justify-center w-screen h-screen pl-24 overflow-visible transition-all duration-300 origin-top-right scale-0 -translate-x-4 translate-y-4 opacity-0 sm:pl-16 pr-28 sm:pr-16 bg-gradient-to-t from-mainBlue to-mainBlueDark py-28"
            :class="{'scale-100 translate-x-0 translate-y-0 opacity-100':popupMenu,'scale-0 translate-y-4 -translate-x-4 opacity-0':!popupMenu}">
            <div data-tag="選單" @click.outside="showMenu=null;showSubMenu=null"
                class="items-start border-transparent border sm:border-white sm:p-6 rounded-r-3xl rounded-bl-3xl justify-start hidden w-auto sm:w-4/5 sm:flex-col sm:justify-center sm:items-center sm:space-y-4 space-y-0 min-w-[12rem] fixed left-[50vw-112px] top-[50vh-240px]"
                :class="{'flex':popupMenu,'hidden':!popupMenu}" id="navbarMobile">
                <div class="items-start justify-start hidden w-full -mt-[4.25rem] -ml-[3.125rem] sm:flex">
                    <div
                        class="flex items-center justify-center w-32 h-[44px] text-lg tracking-widest text-mainBlueDark bg-white rounded-t-xl">
                        Menu
                    </div>
                </div>
                <div class="flex-row items-center justify-center hidden pt-4 space-x-12 text-base font-bold"
                    :class="{'hidden sm:flex':popupMenu,'hidden':!popupMenu}">
                    <a href="/admin"
                        class="flex items-center justify-center w-48 h-12 text-white transition-all duration-300 border border-white rounded-full hover:text-mainYellow">業務人員版</a>
                    <?php if(Auth::guard('dc')->check() || Auth::guard('dp')->check()): ?>
                    <!--<a href="/logout"
                        class="flex items-center justify-center w-48 h-12 text-white transition-all duration-300 border border-white rounded-full hover:text-mainYellow">韌性社區登出</a>!-->
                    <?php else: ?>
                    <!--<a href="/login"
                        class="flex items-center justify-center w-48 h-12 text-white transition-all duration-300 border border-white rounded-full hover:text-mainYellow">韌性社區登入</a>!-->
                    <?php endif; ?>
                </div>
                <ul
                    class="z-10 flex flex-col sm:flex-row items-start justify-center sm:justify-between w-auto sm:w-full sm:ml-0 sm:max-w-[100rem] ml-auto navbar-nav text-xl min-w-[14rem] sm:min-w-0">
                    <?php echo $__env->make(config('laravel-menu.views.bootstrap-items'), array('items' =>
                    Menu::get('right')->roots()), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </ul>
            </div>
        </div>
        <div data-tag="上方logo與menu button"
            class="relative flex flex-row justify-between w-full px-4 py-3 mx-auto item-center flex-nowrap max-w-screen-2xl">
            <div class="flex flex-row justify-between w-full px-4 py-3 mx-auto item-center flex-nowrap ">
                <a class="navbar-brand py-[0.3125rem] rounded-full w-56" :class="{'-z-10':popupMenu,'z-0':!popupMenu}"
                    href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(count(request()->segments())>0?'/image/new_logo_white.svg':'/image/new_logo.svg'); ?>"
                        alt="<?php echo e(config('app.cht_name')); ?>" class="w-full h-auto">
                </a>
                <div class="flex flex-row justify-end w-56">
                    <button @click="popupMenu=!popupMenu"
                        class="relative z-10 flex flex-col items-center justify-center w-16 h-16 text-white border-white border-none rounded-full bg-mainBlue"
                        :class="{'border':popupMenu,'border-none':!popupMenu}" type="button" data-toggle="collapse"
                        data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span
                            class="w-9 h-0.5 rounded-full bg-white absolute transition-all duration-300 top-[1.125rem]"
                            :class="{'rotate-45 translate-y-[0.825rem]':popupMenu,'rotate-0 translate-y-0':!popupMenu}"></span>
                        <span
                            class="w-9 h-0.5 rounded-full bg-white absolute transition-all duration-300 top-[1.875rem]"
                            :class="{'-rotate-45 translate-y-[0.125rem]':popupMenu,'rotate-0 translate-y-0':!popupMenu}"></span>
                        <span class="absolute text-sm transition-all duration-300 top-[2.125rem]"
                            :class="{'opacity-0':popupMenu,'opacity-100':!popupMenu}">Menu</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</nav>
<?php if(Route::currentRouteName() != 'index'): ?>
<div class="relative w-full h-auto mb-16">
    <img src="/image/page_header.jpg" class="w-full h-auto -z-10 aspect-[2.54]">
    <div class="absolute w-full h-6 -bottom-4 sm:h-12 sm:-bottom-8 bg-mainYellow"></div>
    <h3 class="absolute text-3xl text-white left-12 bottom-16 sm:text-6xl sm:left-24 sm:bottom-28"><?php echo $__env->yieldContent('title'); ?></h3>
    <h5 class="absolute text-xl text-white left-12 bottom-8 sm:text-3xl sm:left-24 sm:bottom-16"><?php echo $__env->yieldContent('subtitle'); ?></h5>
</div>
<?php endif; ?><?php /**PATH /Users/Marshall/Desktop/RTP-main/resources/views/components/navbar.blade.php ENDPATH**/ ?>