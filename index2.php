<?
include_once ("backoffice/connect.php");
$yearnow = date("Y");
$index = 1;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick-theme.css">
</head>

<body class="d-flex flex-column h-100">

<? echo file_get_contents("https://thaitrade.ibusiness.co.th/what-industry/3/Bangkok+Gems+%26+Jewelry+Fair/"); ?>

</body>

</html>
