<?
$top_menu_active = "why-visit";
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
                            Style Bangkok 10-14 MAR 2022 / For Visitor / <span class="active">Why Visit</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">WHY VISIT</h1>
                        </div>
                    </div>

                    <div class="row mt-4 px-4">
                        <div class="col-12 mb-4">
                            <img src="assets/images/ex-content-why-visit.png" class="w-100 rounded-4" />
                        </div>
                        <div class="col-12 mb-4">
                            <h2 class="title p-0 m-0">An Ultimate Trade Fair to Maximize Your Travel Costs!</h2>
                        </div>
                        <ul class="col-12 px-0 px-md-5 list-text">
                            <li class="mb-5"> A perfect opportunity to shop for a wide selection of finest lifestyle quality products
                                from
                                more than 1,000 companies. These products appeal to customers of different ages and
                                demands,
                                and most importantly, they come in unique and outstanding designs.</li>

                            <li class="mb-5">The Expo is bringing the top exhibitors from “Niche Markets” including elderly care
                                products,
                                baby, kids and mum products, pet products, the “Demark” award-winning products and “I+D
                                Style Café” Prototype Café.</li>

                            <li class="mb-5">Networking opportunities with the participants from ASEAN nations in CLMV zone and
                                International Pavilion.</li>

                            <li class="mb-5">Up-and-coming topic Seminars / Business Matching services / Lifestyle product Fashion
                                Shows
                            </li>
                        </ul>
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
    .list-text li {
        font-size: 16px;
        font-weight: light;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
    }
    </style>

</body>


</html>
