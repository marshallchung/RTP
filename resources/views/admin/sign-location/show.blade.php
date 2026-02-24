@extends('admin.layouts.dashboard', [
'heading' => '檢視防災避難看板',
'breadcrumbs' => [
'成果網功能',
['防災避難看板', route('admin.sign-location.index')],
'檢視防災避難看板'
]
])

@section('title', '檢視防災避難看板')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <div class="md:w-67/100 xl:w-75/100">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">檢視
            </div>
            <div class="p-5 m-0 bg-white">
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('user_id', '單位') !!}
                    <p class="form-control-static">{{ $signLocation->user->name }}</p>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('lat_long', '緯度 / 經度') !!}
                    <p class="form-control-static">{{ $signLocation->latitude }}
                        / {{ $signLocation->longitude }}</p>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('description', '簡介') !!}
                    <p class="form-control-static">{{ $signLocation->description }}</p>
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    <label>檔案</label>

                    @if (isset($signLocation))
                    <div class="xl:w-full">
                        @foreach($signLocation->files as $file)
                        <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                            data-id="{{ $file->id }}">
                            <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                                $file->name }}</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    <div id="map" style="width: 100%; height: 70vh;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative xl:w-25/100 xl:float-left md:w-1/3">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯
            </div>
            <div class="p-5 m-0 bg-white">
                @if($hasPermission)
                <a href="{{ route('admin.sign-location.edit', $signLocation) }}" class="btn btn-lg btn-primary"
                    style="width: 100%;">
                    編輯
                </a>
                @else
                <a href="javascript:void(0)" class="btn btn-lg btn-primary disabled" style="width: 100%;">
                    編輯
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('google-map.api-key') }}"></script>
<script>
    var signLocation = {!! json_encode($signLocationData) !!}
</script>
<script>
    function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 18,
                center: {lat: signLocation['latitude'], lng: signLocation['longitude']},
                streetViewControl: false
            });

            var marker = new google.maps.Marker({
                clickable: true,
                position: {lat: signLocation['latitude'], lng: signLocation['longitude']},
                map: map
            });
            var infoWindow = new google.maps.InfoWindow();
            marker.addListener('click', (function (infoWindow) {
                return function () {
                    infoWindow.setContent(signLocation['info']);
                    infoWindow.open(map, marker);
                };
            })(infoWindow));

        }

        google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endsection