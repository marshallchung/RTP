@extends('admin.layouts.dashboard', [
'heading' => '地圖',
'breadcrumbs' => [
'成果網功能',
['防災避難看板', route('admin.sign-location.index')],
'地圖'
]
])

@section('title', '地圖')

@section('inner_content')
<div x-data="{
    signLocations:{{ json_encode($signLocations) }},
    map:null,
    service:null,
    initMap(){
        var This = this;
        const mapOptions = {
            center: { lat: 23.69781, lng: 120.960514 },
            zoom: 7,
            streetViewControl: false
        };
        loader
        .importLibrary('maps')
        .then(({Map}) => {
            This.map = new Map(document.getElementById('map'), mapOptions);
            This.service = new google.maps.places.PlacesService(This.map);
            This.signLocations.forEach((signLocation) => {
                if (signLocation['latitude'] && signLocation['longitude']) {
                    var marker = new google.maps.Marker({
                    clickable: true,
                        position: {lat: signLocation['latitude'], lng: signLocation['longitude']},
                        map: This.map
                    });
                    var infoWindow = new google.maps.InfoWindow();
                    marker.addListener('click', (function (infoWindow) {
                        return function () {
                            infoWindow.setContent(signLocation['info']);
                            infoWindow.open(This.map, marker);
                        };
                    })(infoWindow));
                }
            });
        })
        .catch((e) => {
            console.log(e);
        });
    },
}" class="p-5 pb-16" x-init="$nextTick(() => {
    initMap();
    })">
    <div id="map" class="w-full h-[70vw]"></div>
</div>
@endsection

@section('scripts')
@endsection