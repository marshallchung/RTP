@extends('layouts.app')

@section('title', '成果資料')
@section('subtitle', '防災避難看板')

@section('content')
<div x-data="" class="p-5 pb-16 mt-3" x-init="$nextTick(() => {
    })">
    <div id="map" class="w-full h-[70vw]"></div>
</div>
@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('google-map.api-key') }}"></script>
<script>
    var signLocations = {!! json_encode($signLocations) !!}
</script>
<script>
    function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: {lat: 23.69781, lng: 120.960514},
                streetViewControl: false
            });

            $.each(signLocations, function () {
                var signLocation = $(this)[0];
                if (signLocation['latitude'] && signLocation['longitude']) {
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
            });
        }

        google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endsection