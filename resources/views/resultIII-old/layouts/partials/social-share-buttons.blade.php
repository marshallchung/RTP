{{--
$shareText：分享的文字
$shareUrl：分享的網址
--}}
@php($defaultShareUrl = url()->current())
@php($shareUrl = isset($shareUrl) ? $shareUrl : $defaultShareUrl)
@php($shareText = isset($shareText) ? $shareText : null)
<div style="display: flex; align-items: center" class="mb-2">
    {{-- Line --}}
    <div style="background-color: rgb(0, 185, 0); border-radius: 6px; display: inline-block;"><a
            href="javascript:void(0)"
            onclick="window.open('https://social-plugins.line.me/lineit/share?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}', '_blank', 'toolbar=0,status=0')"
            style="font-size: 13px; text-align: center; color: rgb(255, 255, 255); border: 1px solid rgb(255, 255, 255); padding: 2px 10px; cursor: pointer; text-decoration: none; display: block;">
            <i class="fab fa-line mr-2"></i>分享至 LINE</a></div>
    {{-- Facebook --}}
    <div style="background-color: rgb(93, 125, 174); border-radius: 6px; display: inline-block;"><a
            href="javascript:void(0)"
            onclick="window.open('https://www.facebook.com/sharer/sharer.php?quote={{ urlencode($shareText) }}&u={{ urlencode($shareUrl) }}%2F', '_blank', 'toolbar=0,status=0')"
            style="font-size: 13px; text-align: center; color: rgb(255, 255, 255); border: 1px solid rgb(255, 255, 255); padding: 2px 10px; cursor: pointer; text-decoration: none; display: block;">
            <i class="fab fa-facebook-square mr-2"></i>分享至 Facebook</a></div>
    {{-- Twitter --}}
    <div style="background-color: rgb(0, 172, 238); border-radius: 6px; display: inline-block;"><a
            href="javascript:void(0)"
            onclick="window.open('http://twitter.com/share?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}', '_blank', 'toolbar=0,status=0')"
            style="font-size: 13px; text-align: center; color: rgb(255, 255, 255); border: 1px solid rgb(255, 255, 255); padding: 2px 10px; cursor: pointer; text-decoration: none; display: block;">
            <i class="fab fa-twitter-square mr-2"></i>Tweet</a></div>
</div>
