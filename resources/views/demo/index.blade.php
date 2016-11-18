@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="//rawgit.com/mistic100/Photo-Sphere-Viewer/master/dist/photo-sphere-viewer.min.css">

<script src="//rawgit.com/mrdoob/three.js/dev/build/three.min.js"></script>
<script src="//rawgit.com/malko/D.js/master/lib/D.min.js"></script>
<script src="//rawgit.com/mistic100/uEvent/master/uevent.min.js"></script>
<script src="//rawgit.com/olado/doT/master/doT.min.js"></script>
<script src="//rawgit.com/mrdoob/three.js/master/examples/js/controls/DeviceOrientationControls.js"></script>
<script src="//rawgit.com/mistic100/Photo-Sphere-Viewer/master/dist/photo-sphere-viewer.min.js"></script>

<div id="photosphere"></div>

<script>
    window.onload = function() {
        var PSV = new PhotoSphereViewer({
            panorama: '{{ asset('images/R0010025.jpg') }}',
            container: 'photosphere',
            caption: 'Tu étais sous le bureau ou derrière le rideau ? <b>&copy; Arthur Ab</b>',
            loading_img: 'http://photo-sphere-viewer.js.org/assets/photosphere-logo.gif',
            navbar: 'zoom caption fullscreen',
            default_fov: 70,
            mousewheel: false,
            time_anim: false,
            gyroscope: true,
            size: {
                height: 600
            },
            markers: [{
                id: 'MBP',
                polygon_px: [[1515, 946], [1883, 908], [1783, 1278], [1043, 1490], [1055, 1169], [1377, 1149]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: '(almost) Brand new MBP',
                content: document.getElementById('details-mbp').innerHTML
            }, {
                id: 'iPad',
                polygon_px: [[1252, 1017], [1403, 1022], [1340, 1160], [1190, 1122]],
                svgStyle: {
                    fill: 'rgba(200, 0, 0, 0.2)',
                    stroke: 'rgba(200, 0, 50, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'iPad 007 version',
                content: document.getElementById('details-ipad').innerHTML
            }, {
                id: 'paper',
                polygon_px: [[2399, 1123], [2596, 1155], [1940, 1190], [2029, 1122]],
                svgStyle: {
                    fill: 'rgba(0, 150, 0, 0.2)',
                    stroke: 'rgba(0, 150, 50, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'Paper with places yet to write',
                content: document.getElementById('details-paper').innerHTML
            }, {
                id: 'cow',
                polygon_px: [[3919, 1214], [3804, 1322], [3832, 1358], [3746, 1383], [3943, 1480], [196, 1480], [235, 1388], [383, 1323], [271, 1208], [4045, 1216]],
                svgStyle: {
                    fill: 'rgba(255, 255, 0, 0.2)',
                    stroke: 'rgba(255, 255, 50, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'Véritable bison chassé par Phile, roulé sous les aisselles de Douglas',
                content: document.getElementById('details-cow').innerHTML
            }, {
                id: 'text',
                x: 3084,
                y: 1172,
                html: document.getElementById('details-cow').innerHTML,
                anchor: 'bottom left',
            }
            ]
        });

        var poly = '';
        PSV.on('click', function (e) {
            poly += '[' + e.texture_x + ',' + e.texture_y + '], ';
            console.log(poly);
        });
    };
</script>

<div class="hidden">
    <?php $ads = \Sneefr\Models\Ad::take(10)->get();?>
    <div id="details-mbp">
        @include ('items.show._heading', ['ad' => $ads->first(), 'shop' => $ads->first()->shop])
    </div>

    <div id="details-ipad">
        @include ('items.show._heading', ['ad' => $ads->get(2), 'shop' => $ads->get(2)->shop])
    </div>

    <div id="details-paper">
        @include ('items.show._heading', ['ad' => $ads->get(3), 'shop' => $ads->get(3)->shop])
    </div>

    <div id="details-cow">
        @include ('items.show._heading', ['ad' => $ads->get(4), 'shop' => $ads->get(4)->shop])
    </div>
</div>

@endsection
