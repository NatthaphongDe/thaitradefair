<?
$top_menu_active = "exhibitor-list";
?>
<!doctype html>
<html lang="en">

<head>
    <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="assets/css/carousel.css" rel="stylesheet">

    <link href="assets/dist/scrollbar/jquery.scrollbar.css" rel="stylesheet">
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
                            Style Bangkok 10-14 MAR 2022 / For Visitor / <span class="active">Exhibitor List</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">Exhibitor List</h1>
                        </div>
                    </div>


                    <div id="search-exhibitor-list" class="row mt-3 rounded-4">
                        <div class="col align-self-center py-3 _exspad">
                            <div id="search" class="input-group my-4">
                                <select type="text" class="form-select type-exhibitors w-20">
                                    <option>
                                        Search All
                                    </option>
                                </select>
                                <input type="text" class="form-control w-50"
                                    placeholder="Type Company, Brand, Product or Keyword">

                                <button class="btn btn-search rounded-end text-center pt-2" type="button">
                                    <i class="bi bi-search text-white "></i>
                                </button>

                            </div>
                        </div>
                    </div>
                    <div id="company-list" class="mt-5 mt-sm-3 mb-5 px-0 px-md-4">
                        <ul class="nav nav-tabs border-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Company </a>
                            </li>
                        </ul>


                        <div class="float-end w-auto search-area pt-2" style="margin-top: -55px;">
                            <button class="btn w-auto float-end bg-green">
                                <img src="assets/images/anticon-local-printshop-material.png" style="height:15px;" />
                                Print
                            </button>
                            <select class="form-select selectpicker w-auto float-end"
                                style="padding-right:2rem; margin-right:0.5rem;">
                                <option selected>All Categories</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="tab-content px-0 " id="myTabContent">
                            <div class="tab-pane fade show active pb-3" id="company-tab-pane" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
                                    <div class="col-12 col-sm-6 _exleftpad" style="padding-left: 2rem;">
                                        <input class="form-check-input " type="checkbox" value=""
                                            id="titleCheckDefault">
                                        <label class="form-check-label" for="titleCheckDefault">
                                            Company Name
                                        </label>
                                        <span class="total-1">(6121)</span>
                                    </div>
                                    <div class="col-12 col-sm-6 text-white total-right text-end">
                                        <span class="">
                                            1 - 100 of 1612
                                        </span>
                                        <i
                                            class="icon-navigator-page disable bi bi-chevron-left text-white disable"></i>
                                        <input type="text" value="1" />
                                        <span class=""> / 1612 </span>
                                        <i class="icon-navigator-page bi bi-chevron-right text-white"></i>
                                    </div>
                                </div>
                                <div class="scrollbar-inner">
                                    <ul class="list-group checkbox-list-company border-0 px-4">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">
                                                        1 VISION COMPANY LIMITED
                                                    </a>
                                                </div>
                                                SUPER CALLING CARD
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">1 ST SILK DESIGNS LIMITED
                                                        PARTNERSHIP</a>
                                                </div>
                                                ARTIFICIAL ORCHID, ARTIFICIAL FLOWER
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">10 SEPTEMBER COMPANY LIMITED</a>
                                                </div>
                                                DRINKING STRAW, DOOR AND WINDOW, ELECTRICAL SAFETY PRODUCT, WATER
                                                BOILER
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">110 PRECIOUS CO.LTD.</a>
                                                </div>
                                                JEWELRY-PRECIOUS/SEMI-PRECIOUS
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">127 NORH LOUDSPEAKERS CO., LTD.</a>
                                                </div>
                                                AMPLIFIER, SPEAKER, หม้อแปลง TUBE AMPLIFIER
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">1 VISION COMPANY LIMITED</a>
                                                </div>
                                                SUPER CALLING CARD
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">1 ST SILK DESIGNS LIMITED
                                                        PARTNERSHIP</a>
                                                </div>
                                                ARTIFICIAL ORCHID, ARTIFICIAL FLOWER
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">10 SEPTEMBER COMPANY LIMITED</a>
                                                </div>
                                                DRINKING STRAW, DOOR AND WINDOW, ELECTRICAL SAFETY PRODUCT, WATER
                                                BOILER
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">110 PRECIOUS CO.LTD.</a>
                                                </div>
                                                JEWELRY-PRECIOUS/SEMI-PRECIOUS
                                            </div>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <input class="form-check-input mx-2" type="checkbox" value="">
                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="exhibitor-detail.php">127 NORH LOUDSPEAKERS CO., LTD.</a>
                                                </div>
                                                AMPLIFIER, SPEAKER, หม้อแปลง TUBE AMPLIFIER
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include('components/footer.php') ?>
    </div>

    <script src="assets/dist/scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="assets/js/script.js" type="text/javascript"></script>


    <script>
    $(document).ready(function() {
        $('.scrollbar-inner').scrollbar();
    });
    </script>

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



    #search-exhibitor-list {

        background-blend-mode: multiply;
        background-image: linear-gradient(to bottom, #378dd7, #378dd7);
    }

    #search-exhibitor-list .input-group {
        border-radius: 6px;
        border: solid 1px #fff;
        background-color: #fff;
    }


    #search-exhibitor-list #search .form-control,
    #search-exhibitor-list #search .form-select {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;

    }

    #search-exhibitor-list #search .form-select {
        background-image: url('assets/images/icon-chevron-down-blue.svg');
    }

    #search-exhibitor-list #search .btn-search {
        background-color: #378dd7 !important;
        border-color: #378dd7 !important;
    }

    #search-exhibitor-list #search .btn-search .bi {
        font-size: 20px;
    }

    #search-exhibitor-list #search .btn-search .bi:before {
        font-weight: bold !important;
    }

    @media (max-width: 575px) {
        .search-area {
            margin-top: -105px !important;
        }
    }

    .search-area .form-select {
        border: none !important;
        background: none !important;
        padding: 0;
        margin-right: 0.5rem;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
    }

    #search-exhibitor-list #search .form-select.type-exhibitors {
        font-size: 20px;
    }

    .search-area .dropdown-toggle {
        padding-top: 4px !important;
        padding-bottom: 2px !important;
        height: auto;
        border-radius: 7px;
        background-color: #fba91e !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        /* padding: 0.3rem 1rem 0.3rem 1rem; */
        padding-left: 11px !important;
        padding-right: 11px !important;
        outline: none !important;

    }

    .bootstrap-select .dropdown-menu li a span.text {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;

    }

    .bootstrap-select .dropdown-toggle:focus,
    .bootstrap-select>select.mobile-device:focus+.dropdown-toggle {
        outline: none !important;
        outline-offset: inherit;
    }

    .search-area .bg-green {
        height: 28px;
        border-radius: 7px;
        background-color: none !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        padding: 0.1rem 1rem 0.3rem 1rem;
        background-color: #659a83 !important;
    }


    #company-list {
        position: relative;
    }

    #company-list .nav-link {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border: none;
        font-size: 20px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: justify;
    }

    #company-list .nav-link.active {
        background-color: #378dd7;
        color: #fff;
    }

    #company-list .nav-tabs .nav-item {
        z-index: 2;
    }

    #myTabContent {
        border-top-right-radius: 0.75rem;
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
        z-index: 1;
        margin-top: 0px;
        position: relative;
    }

    #myTabContent .tab-title {
        background-color: #378dd7;
    }

    #myTabContent .tab-title .form-check-label {
        font-size: 16px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        margin-left: 1.5rem;
    }

    #myTabContent .tab-title .total-1 {
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    #myTabContent .tab-title .total-right {
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    #myTabContent .tab-title .total-right input {
        border-radius: 6px;
        border: solid 1px #378dd7 !important;
        background-color: #fff;
        width: 50px;
        text-align: center;
        outline: none;
    }

    #myTabContent .tab-title .total-right .bi {
        font-size: 14px;
        line-height: 1;
    }

    #myTabContent .tab-title .total-right .bi.disable {
        opacity: 0.14;
    }

    #myTabContent .tab-title .total-right .bi:before {
        font-weight: bold;
    }

    .checkbox-list-company .list-group-item {
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #000;
    }

    .checkbox-list-company .list-group-item .title {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #000;
    }

    .checkbox-list-company .list-group-item .form-check-input {
        border: solid 1px #378dd7;
    }

    #titleCheckDefault {
        width: 0.8em;
        height: 0.8em;
        border: solid 1px #378dd7;
    }



    .btn-all-category {
        font-size: 20px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff !important;
        border-radius: 14px;
        border: none !important;
    }
    </style>

</body>


</html>
