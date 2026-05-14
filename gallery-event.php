<?
$top_menu_active = "gallery";
?>

<!doctype html>
<html lang="en">

<head>
    <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="assets/css/carousel.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <?php include('components/top_slide.php'); ?>
                </div>
            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container">
                    <?php include('components/top_menu.php'); ?>

                    <div class="row mt-4 px-4">
                        <div class="col-12 col-md-7 navigator-text pt-2">
                            Style Bangkok 10-14 MAY 2022 / Gallery / <span class="active">Album</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">Gallery</h1>
                        </div>
                    </div>

                    <div class="row mt-4 mb-5 px-4">
                        <div class="col-12 mb-2">
                            <h2 class="title p-0 m-0">Photo Gallery</h2>
                        </div>
                        <div id="gallery" class="col-12 mb-2">
                            <div class="row row-cols-1 row-cols-md-2 gallery-list">
                                <div class="col px-1 pb-2" onclick="window.location.href='gallery-event-photo.php'">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('assets/images/ex-gallery/ex-gallery-1.png') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-12">
                                                        <span class="event-name d-block">Style Bangkok Fair
                                                            Opening</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3">
                                                <a href="#" class="d-block see-all-fair-gallery">
                                                    8 images
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col px-1 pb-2" onclick="window.location.href='gallery-event-photo.php'">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('assets/images/ex-gallery/ex-gallery-2.png') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-12">
                                                        <span class="event-name d-block">STYLE De Bangkok Hotel
                                                            Showcase</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3">
                                                <a href="#" class="d-block see-all-fair-gallery">
                                                    8 images
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col px-1 pb-2" onclick="window.location.href='gallery-event-photo.php'">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('assets/images/ex-gallery/ex-gallery-3.png') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-12">
                                                        <span class="event-name d-block">Design Hall</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3">
                                                <a href="#" class="d-block see-all-fair-gallery">
                                                    8 images
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col px-1 pb-2" onclick="window.location.href='gallery-event-photo.php'">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('assets/images/ex-gallery/ex-gallery-4.png') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-12">
                                                        <span class="event-name d-block">Troop Show Chatuchak</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3">
                                                <a href="#" class="d-block see-all-fair-gallery">
                                                    8 images
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col px-1 pb-2" onclick="window.location.href='gallery-event-photo.php'">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('assets/images/ex-gallery/ex-gallery-3.png') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-12">
                                                        <span class="event-name d-block">Grand Opening October
                                                            2019</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3">
                                                <a href="#" class="d-block see-all-fair-gallery">
                                                    8 images
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col px-1 pb-2" onclick="window.location.href='gallery-event-photo.php'">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('assets/images/ex-gallery/ex-gallery-4.png') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-12">
                                                        <span class="event-name d-block">The Niche showcase</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3">
                                                <a href="#" class="d-block see-all-fair-gallery">
                                                    8 images
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-0">
                            <div class="col-6 page-txt">1 - 8 of 16</div>
                            <div class="col-6 text-end">
                                <i class="bi bi-chevron-left page-txt page-icon  page-icon-disable"></i>
                                <input class="input-page" type="text" value="1" /> <span class="page-txt">/ 2<span>
                                        <i class="bi bi-chevron-right page-txt page-icon  page-icon"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="assets/js/script.js" type="text/javascript"></script>


    <style>
    .navigator-text {
        font-size: 18px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #111;
    }

    .navigator-text .active {
        font-weight: bold;
    }


    h1.title {
        font-size: 40px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.34;
        letter-spacing: normal;
    }

    h2.title {
        font-size: 26px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        /* text-align: justify; */
        color: #111;
    }

    #gallery .gallery-list>.col {
        min-height: 250px;
        cursor: pointer;
    }


    #gallery .gallery-list .event-area img {
        width: 40px;
        height: 40px;

    }

    #gallery .gallery-list .event-area .event-name {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    #gallery .gallery-list .lastest-album {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.27;
        letter-spacing: normal;
        text-align: right;
        color: #fff;
    }

    #gallery .gallery-list .see-all-fair-gallery {
        font-size: 12px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: right;
        color: #fff;
        text-decoration: none;
    }

    #gallery .gallery-list .bg-cover {
        min-height: 300px;
        background-repeat: no-repeat;
        background-position: center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    .see-all-fair-gallery {
        text-decoration: underline !important;
    }

    .view-more-area .bi {
        color: #111;
    }

    .view-more {
        font-size: 15px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #111;
    }


    .continer-content-list .card .card-image {
        height: 217px;
        border-radius: 1rem;
        overflow: hidden;
    }

    .continer-content-list .card .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .continer-content-list .card .card-title {
        height: 55px;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .continer-content-list .card .card-body h5 {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.06;
        letter-spacing: normal;
    }

    .bg-yt-play {
        background: url('assets/images/icon-youtube-play.png') no-repeat center center;
        background-size: 60px auto;
        -moz-background-size: 60px auto;
        -o-background-size: 60px auto;
        background-size: 60px auto;
    }

    .page-txt {
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #111;
    }

    .input-page {
        padding: 0 25px;
        border-radius: 6px;
        border: solid 1px #378dd7;
        background-color: #fff;
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #378dd7;
        width:60px;
    }

    .page-icon {
        font-weight: bold;
        color: #111;
    }

    .page-icon-disable {
        opacity: 0.14;
    }
    </style>
</body>


</html>
