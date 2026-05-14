<?
$top_menu_active = "why-exhibit";
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
                            Style Bangkok 10-14 MAR 2022 / For Visitor / <span class="active">Why Exhibit</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">WHY EXHIBIT</h1>
                        </div>
                    </div>

                    <div class="row mt-4 px-4">
                        <div class="col-12 mb-4">
                            <img src="assets/images/ex-content-why-exhibit.png" class="w-100 rounded-4" />
                        </div>
                        <div class="col-12 mb-4">
                            <h2 class="title p-0 m-0">Reap Benefits of the Region with the Value-For-Money Trade Fair!
                            </h2>
                        </div>
                        <div class="col-12 px-0 px-md-5">
                            <p> A perfect opportunity to meet up with 52,000 visitors from more than 69 countries around
                                the
                                world, including all kinds of potential buyers ranging from importers, manufacturers,
                                trading companies, buying agents and more.</p>

                            <p>Exhibitors can build their business network with the potential partners, diverse traders
                                and
                                international buyers, brought together by the Department of International Trade
                                Promotion.
                            </p>

                            <p>The trade fair provides a one-stop service for the exhibitors including business
                                matching,
                                shuttle bus, interpreters, security system and many others.</p>

                            <p>Get up-to-date insights about lifestyle product trends and export with various Export
                                Clinics
                                and special seminars.
                            </p>
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

    p {
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
    }
    </style>

</body>


</html>
