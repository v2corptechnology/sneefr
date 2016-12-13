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
            panorama: '{{ asset('images/R0010025.jpeg') }}',
            container: 'photosphere',
            caption: 'Roots Beverly Hills • 1505 Abbot Kinney Blvd, Venice, CA 90291, USA',
            loading_img: 'http://photo-sphere-viewer.js.org/assets/photosphere-logo.gif',
            navbar: 'zoom caption fullscreen',
            default_fov: 70,
            default_long: 3.618199,
            default_lat:  -0.25,
            mousewheel: false,
            time_anim: false,
            gyroscope: true,
            size: {
                height: 600
            },
            markers: [{
                id: 'id1',
                polygon_px: [[1446,1584], [1689,1514], [1884,1606], [1569,1742]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'MACKINAW FLANNEL SHIRT',
                content: document.getElementById('id1').innerHTML
            },{
                id: 'id2',
                polygon_px: [[32,1044], [40,1008], [115,1001], [110,1047]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'MENS CABIN ONESIE',
                content: document.getElementById('id2').innerHTML
            },{
                id: 'id3',
                polygon_px: [[619,1435], [668,1456], [677,1450], [707,1443], [688,1423], [670,1399], [644,1402], [624,1406]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'RAYMOND SNEAKER SUEDE',
                content: document.getElementById('id3').innerHTML
            },{
                id: 'id4',
                polygon_px: [[2261,1678], [2330,1630], [2541,1618], [2568,1641], [2604,1640], [2758,1764], [2923,1814], [2186,1909]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'BRAEBURN FLANNEL SHIRT',
                content: document.getElementById('id4').innerHTML
            },{
                id: 'id5',
                polygon_px: [[754,1310], [820,1342], [814,1281], [839,1251], [814,1133], [792,1116], [770,1118], [791,1134], [802,1287], [758,1284]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'MONARCH SWEATER DRESS',
                content: document.getElementById('id5').innerHTML
            },{
                id: 'id6',
                polygon_px: [[77,1103], [53,1157], [40,1378], [70,1370], [83,1231], [119,1125], [122,1106]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'NORDIC CARDIGAN',
                content: document.getElementById('id6').innerHTML
            },{
                id: 'id7',
                polygon_px: [[442,1143], [480,1150], [503,1135], [530,1162], [549,1332], [525,1332], [513,1349], [483,1334], [484,1315], [438,1285], [427,1272], [406,1271], [415,1213], [432,1147]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'ROYAL OAK SWEATER',
                content: document.getElementById('id7').innerHTML
            },{
                id: 'id8',
                polygon_px: [[1984,1468], [2019,1487], [2065,1480], [2107,1504], [2163,1498], [2182,1444], [2196,1393], [2161,1366], [2117,1368], [2099,1355], [2061,1364], [2017,1367], [2002,1409]],
                svgStyle: {
                    fill: 'rgba(0, 125, 195, 0.2)',
                    stroke: 'rgba(0, 125, 195, 0.8)',
                    'stroke-width': '2px'
                },
                tooltip: 'MENS CHUKKA BOOT SUEDE',
                content: document.getElementById('id8').innerHTML
            }]
        });

        var poly = '';
        PSV.on('click', function (e) {
            poly += '[' + e.texture_x + ',' + e.texture_y + '], ';
            console.log(poly);
        });
    };
</script>

<div class="hidden">
    <div id="id1">
        <div class="box">

            <h1 class="item__heading">MACKINAW FLANNEL SHIRT</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1248/2d48285fd0a4eb550a5401e6908d2d42.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">3 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">


                <span class="item__price">$74.00</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="https://www.sidewalks.city/payments/create?ad=1248-mackinaw-flannel-shirt" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="https://www.sidewalks.city/items/1248-mackinaw-flannel-shirt/edit" title=""><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
                        <li>
                            <form action="https://www.sidewalks.city/items/1248-mackinaw-flannel-shirt" method="post">
                                <input name="_token" value="HQ0dELYISSFkCIsDPuAERIS0aWZQTsKUU46L8OCC" type="hidden">
                                <input name="_method" value="delete" type="hidden">

                                <button class="btn btn-link" type="submit"><i class="fa fa-fw fa-trash"></i> Remove</button>

                            </form>
                        </li>

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1248-mackinaw-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>

    <div id="id2">
        <div class="box">

            <h1 class="item__heading">MENS CABIN ONESIE</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1250/b7d9520ce719195c1064fd0908e01591.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">3 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">


                <span class="item__price">$124.00</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="https://www.sidewalks.city/payments/create?ad=1248-mackinaw-flannel-shirt" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="https://www.sidewalks.city/items/1248-mackinaw-flannel-shirt/edit" title=""><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
                        <li>
                            <form action="https://www.sidewalks.city/items/1248-mackinaw-flannel-shirt" method="post">
                                <input name="_token" value="HQ0dELYISSFkCIsDPuAERIS0aWZQTsKUU46L8OCC" type="hidden">
                                <input name="_method" value="delete" type="hidden">

                                <button class="btn btn-link" type="submit"><i class="fa fa-fw fa-trash"></i> Remove</button>

                            </form>
                        </li>

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1248-mackinaw-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>


    <div id="id3">
        <div class="box">

            <h1 class="item__heading">RAYMOND SNEAKER SUEDE</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1251/df15af2d256ad0127968c2b4173466d0.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">3 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">

                <del class="item__tagprice text-muted">$126.00</del>
                <span class="item__price">$71.98</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="https://www.sidewalks.city/payments/create?ad=1248-mackinaw-flannel-shirt" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="https://www.sidewalks.city/items/1248-mackinaw-flannel-shirt/edit" title=""><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
                        <li>
                            <form action="https://www.sidewalks.city/items/1248-mackinaw-flannel-shirt" method="post">
                                <input name="_token" value="HQ0dELYISSFkCIsDPuAERIS0aWZQTsKUU46L8OCC" type="hidden">
                                <input name="_method" value="delete" type="hidden">

                                <button class="btn btn-link" type="submit"><i class="fa fa-fw fa-trash"></i> Remove</button>

                            </form>
                        </li>

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1248-mackinaw-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>


    <div id="id4">
        <div class="box">

            <h1 class="item__heading">BRAEBURN FLANNEL SHIRT</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1249/de2630fff98eef288737a65ff0e87438.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">4 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">

                <del class="item__tagprice text-muted">$78.00</del>

                <span class="item__price">$58.99</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="#LoginBefore" data-toggle="modal" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1249-braeburn-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>




    <div id="id5">
        <div class="box">

            <h1 class="item__heading">MONARCH SWEATER DRESS</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1247/34400f4d7b11d71b64b554223fd9ca7d.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">4 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">

                <del class="item__tagprice text-muted">$142.00</del>

                <span class="item__price">$108.99</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="#LoginBefore" data-toggle="modal" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1249-braeburn-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>


    <div id="id7">
        <div class="box">

            <h1 class="item__heading">ROYAL OAK SWEATER</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1245/5a63f5dc35fdc99f1a3017a84f1872d8.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">3 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">

                <span class="item__price">$88.00</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="#LoginBefore" data-toggle="modal" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1249-braeburn-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>

    <div id="id6">
        <div class="box">

            <h1 class="item__heading">NORDIC CARDIGAN</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1246/fb0045dbeca28f95b2cfc404123bbbcd.jpg" alt="">

            <div class="item__meta">
                <span class="item__stock">3 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">

                <del class="item__tagprice text-muted">$116.00</del>

                <span class="item__price">$86.99</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="#LoginBefore" data-toggle="modal" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1249-braeburn-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>

    <div id="id8">
        <div class="box">

            <h1 class="item__heading">MENS CHUKKA BOOT SUEDE</h1>

            <img class="ad__images__preview img-responsive" src="https://eazkmue.cloudimg.io/crop/500x500/q75.tjpg/_originals_/1252/13c87ac3f8c3ad590e5f9953c872fbcf.JPG" alt="">

            <div class="item__meta">
                <span class="item__stock">3 in stock</span>
            </div>

            <hr class="item__separator">

            <div class="item__buttons">

                <span class="item__price">$176.99</span>

                <div class="btn-group btn-group-lg pull-right">

                    <a href="#LoginBefore" data-toggle="modal" title="" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy</a>

                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">

                        <li><a href="#reportAd" title="" data-toggle="modal"><i class="fa fa-fw fa-warning"></i> Report item</a></li>
                    </ul>
                </div>

            </div>

            <hr class="item__separator">

            <div class="item__social">
                <a href="https://www.sidewalks.city/share/ad/1249-braeburn-flannel-shirt" class="btn btn-link" title="" data-toggle="modal" data-remote="false" data-target="#shareModal">
                    <i class="icon fa fa-lg fa-share-alt"></i> Share
                </a>

                <a href="#writeTo" class="btn btn-link" title="" data-toggle="modal" data-remote="false">
                    <i class="icon fa fa-lg fa-comment-o"></i> Contact
                </a>
            </div>
        </div>
    </div>

</div>


<div class="timeline">
    <div class="row">

        <div class="col-md-4">
            <ul class="summary">
                <li class="summary__item text-center summary__item--header" style="background-color: #FFFFFF;padding-top: 1rem;">

                    <a href="#" data-toggle="modal" data-target="#profilePicture">
                        <img class="profile__image" alt="Roots - Beverly Hills" src="https://eazkmue.cloudimg.io/crop/80x80/q75/https://s3-media1.fl.yelpcdn.com/bphoto/FnKU5yMeYwKKyLED6716ag/o.jpg" width="40" height="40">
                    </a>

                    <h2 class="summary__head" style="color:#000000; padding-left: 0;padding-top: 1rem;">
                        Roots - Beverly Hills
                    </h2>
                </li>
                <li class="summary__item">
                    <p class="text-muted js-summary__item--expandable" style="font-size:1.3rem; margin-bottom: 0;">
                        +13108588343
                    </p>
                    <a class="summary__toggle js-summary__toggle" href="#"><i class="fa fa-chevron-down"></i></a>
                </li>

                <li class="summary__item--selected">
                    <h2 class="summary__head">
                        <i class="fa fa-globe summary__icon"></i>
                        <a href="https://www.sidewalks.city/shops/roots-beverly-hills-beverly-hills">
                            8 ads
                        </a>
                    </h2>
                    <p class="summary__content summary__content--extra">
                        Details
                    </p>
                </li>
                <!-- evaluation section -->
                <li class="summary__item">
                    <h2 class="summary__head">
                        <i class="fa fa-trophy summary__icon"></i>
                        <a href="https://www.sidewalks.city/shops/roots-beverly-hills-beverly-hills/evaluations" title=":name has no review yet, [
                                'ratio' => $shop->evaluations->ratio()),
                                'name' => $shop->getName()])">
                            profile.sidebar.evaluations                            </a>
                    </h2>
                </li>


                <li>
                    <form action="https://www.sidewalks.city/claims" method="post">
                        <input name="_token" value="VRLvENqUkUh3MoQofgoOUTapheuTy7yo6JUxbm4x" type="hidden">
                        <input name="shop_id" value="26060" type="hidden">
                        <button class="btn btn-block btn-primary btn-primary2" type="submit">
                            <i class="fa fa-handshake-o"></i>
                            Claim my shop
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="col-md-8">


            <div class="row">

                <div class="col-sm-8">
                    <h1 class="content-head" id="common">
                        8 ads at Roots - Beverly Hills            </h1>
                </div>


                <div class="col-sm-4">
                    <form class="" action="https://www.sidewalks.city/shops/roots-beverly-hills-beverly-hills/search" method="get">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input class="form-control" name="q" placeholder="Search…" value="" type="text">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
                            </div>
                        </div>
                    </form>
                </div>

            </div>




            <div class="row">
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1252-mens-chukka-boot-suede" title="MENS CHUKKA BOOT SUEDE">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1252/13c87ac3f8c3ad590e5f9953c872fbcf.JPG" alt="MENS CHUKKA BOOT SUEDE" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1252/13c87ac3f8c3ad590e5f9953c872fbcf.JPG 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$176.00</span>
                                MENS CHUKKA BOOT SUEDE
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1251-raymond-sneaker-suede" title="RAYMOND SNEAKER SUEDE">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1251/df15af2d256ad0127968c2b4173466d0.jpg" alt="RAYMOND SNEAKER SUEDE" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1251/df15af2d256ad0127968c2b4173466d0.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$71.98</span>
                                RAYMOND SNEAKER SUEDE
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1250-mens-cabin-onesie" title="MENS CABIN ONESIE">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1250/b7d9520ce719195c1064fd0908e01591.jpg" alt="MENS CABIN ONESIE" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1250/b7d9520ce719195c1064fd0908e01591.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$124.00</span>
                                MENS CABIN ONESIE
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1249-braeburn-flannel-shirt" title="BRAEBURN FLANNEL SHIRT">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1249/de2630fff98eef288737a65ff0e87438.jpg" alt="BRAEBURN FLANNEL SHIRT" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1249/de2630fff98eef288737a65ff0e87438.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$58.99</span>
                                BRAEBURN FLANNEL SHIRT
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1248-mackinaw-flannel-shirt" title="MACKINAW FLANNEL SHIRT">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1248/2d48285fd0a4eb550a5401e6908d2d42.jpg" alt="MACKINAW FLANNEL SHIRT" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1248/2d48285fd0a4eb550a5401e6908d2d42.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$74.00</span>
                                MACKINAW FLANNEL SHIRT
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1247-monarch-sweater-dress" title="MONARCH SWEATER DRESS">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1247/34400f4d7b11d71b64b554223fd9ca7d.jpg" alt="MONARCH SWEATER DRESS" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1247/34400f4d7b11d71b64b554223fd9ca7d.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$108.99</span>
                                MONARCH SWEATER DRESS
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1246-nordic-cardigan" title="NORDIC CARDIGAN">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1246/fb0045dbeca28f95b2cfc404123bbbcd.jpg" alt="NORDIC CARDIGAN" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1246/fb0045dbeca28f95b2cfc404123bbbcd.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$86.99</span>
                                NORDIC CARDIGAN
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="card" href="https://www.sidewalks.city/ad/1245-royal-oak-sweater" title="ROYAL OAK SWEATER">
                        <figure class="card__gallery">
                            <img class="card__image" src="https://eazkmue.cloudimg.io/crop/360x250/q75.tjpg/_originals_/1245/5a63f5dc35fdc99f1a3017a84f1872d8.jpg" alt="ROYAL OAK SWEATER" srcset="https://eazkmue.cloudimg.io/crop/720x500/q75.tjpg/_originals_/1245/5a63f5dc35fdc99f1a3017a84f1872d8.jpg 2x" itemprop="image" width="360" height="250">

                            <figcaption class="card__title card__title--small">
                                <span class="card__price">$88.00</span>
                                ROYAL OAK SWEATER
                            </figcaption>
                        </figure>
                    </a>
                </div>
            </div>


        </div>
    </div>
</div>



@endsection
