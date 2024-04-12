<?php
include('inc/header.php');
include('inc/connectie.php');


if ($_SESSION['ingelogd'] != "kassamedewerker" && $_SESSION['ingelogd'] != "administrator") {
    if ($_SESSION['ingelogd'] == "voorraadbeheerder") {
        header('refresh:0;./voorraad.php');
    } elseif ($_SESSION['ingelogd'] == "manager") {
        header('refresh:0;./verkoop.php');
    } else {
        header('refresh:0;./login.php');
    }
}

if (isset($_POST['type']) && $_POST['type'] == 'toevoegen') {
    if (isset($_SESSION['kassabon'][$_POST['omschrijving']])) {
        $_SESSION['kassabon'][$_POST['omschrijving']]['aantal']++;
    } else {
        $omschrijving = $_POST['omschrijving'];
        $prijs = $_POST['prijs'];
        $eenheid = $_POST['eenheid'];
        $artikelnummer = $_POST['artikelnummer'];
        $aantal = 1;
        $_SESSION['kassabon'][$omschrijving] = array('prijs' => $prijs, 'artikelnummer' => $artikelnummer, 'eenheid' => $eenheid, 'aantal' => $aantal);
    }
}

if (isset($_POST['omschrijving'])) {
    if (isset($_SESSION['kassabon'][$_POST['omschrijving']])) {
        if (isset($_POST['type']) && $_POST['type'] == '+') {
            $_SESSION['kassabon'][$_POST['omschrijving']]['aantal'] += 1;
        } else if (isset($_POST['type']) && $_POST['type'] == '-') {
            $_SESSION['kassabon'][$_POST['omschrijving']]['aantal'] -= 1;
            if ($_SESSION['kassabon'][$_POST['omschrijving']]['aantal'] == 0) {
                unset($_SESSION['kassabon'][$_POST['omschrijving']]);
            }
        }
    }
}

if (isset($_POST['afrekenen']) && $_POST['afrekenen'] == 'afrekenen') {
    foreach ($_SESSION['kassabon'] as $artikel => $aantal) {
        $query = "UPDATE artikel SET aantal = aantal  - " . $aantal['aantal'] . " WHERE Artikelnummer = '" . $aantal['artikelnummer'] . "'";
        $result = $mysqli->query($query);
        $query = "UPDATE verkoop SET aantalverkocht = aantalverkocht + " . $aantal['aantal'] . " WHERE Artikelnummer = '" . $aantal['artikelnummer'] . "'";
        $result = $mysqli->query($query);
        unset($_SESSION['kassabon']);
    }
}
?>

<div class="menu">

    <a href='?action=aardappels' class="action">
        <div id="categorie1">
            <p>Aardappelen, groente en fruit</p>
        </div>
    </a>

    <a href='?action=kaas' class="action">
        <div id="categorie2">
            <p>Kaas en Vleeswaren</p>
        </div>
    </a>

    <a href='?action=broodbeleg' class="action">
        <div id="categorie3">
            <p>Broodbeleg</p>
        </div>
    </a>

    <a href='?action=dranken' class="action">
        <div id="categorie4">
            <p>Dranken</p>
        </div>
    </a>

    <a href='?action=chips' class="action">
        <div id="categorie5">
            <p>Chips</p>
        </div>
    </a>

    <a href='?action=koek' class="action">
        <div id="categorie6">
            <p>Koek</p>
        </div>
    </a>

    <a href="./uitloggen.php" class="action">
        <div id="uitloggen">
            <p>Uitloggen</p>
        </div>
    </a>

    <?php
    if ($_SESSION['ingelogd'] == 'administrator') {
        echo "<a href='admin.php' class='action'>
            <div class='admin'>
                <p>Admin</p>
            </div>
        </a>";
    }
    ?>
</div>

<form id="productzoeker" method="POST">
    <input type="text" id="barcodeinput" name="barcode" placeholder="Barcode zoeken">
    <div class="barcodeknoppen">
        <button type="button" class="barcodeknop" onclick="barcodecijfer('1')">
            <p class="barcodecijfer">1</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('2')">
            <p class="barcodecijfer">2</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('3')">
            <p class="barcodecijfer">3</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('4')">
            <p class="barcodecijfer">4</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('5')">
            <p class="barcodecijfer">5</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('6')">
            <p class="barcodecijfer">6</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('7')">
            <p class="barcodecijfer">7</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('8')">
            <p class="barcodecijfer">8</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('9')">
            <p class="barcodecijfer">9</p>
        </button>
        <button type="button" class="barcodeknop" onclick="barcodecijfer('0')">
            <p class="barcodecijfer">0</p>
        </button>
        <button type="button" class="barcodeknop" onclick="leegmaken()">
            <p class="barcodecijfer">CE</p>
        </button>
        <button type="submit" class="barcodeknop">
            <p class="barcodecijfer">OK</p>
    </div>
</form>

<?php

if (isset($_GET['action']) && $_GET['action'] == 'aardappels') {
    $query = "SELECT * FROM artikel WHERE artikelgroep = 'Aardappels, groente en fruit'";
} elseif (isset($_GET['action']) && $_GET['action'] == 'kaas') {
    $query = "SELECT * FROM artikel WHERE artikelgroep = 'Kaas,Vleeswaren'";
} elseif (isset($_GET['action']) && $_GET['action'] == 'broodbeleg') {
    $query = "SELECT * FROM artikel WHERE artikelgroep = 'Broodbeleg'";
} elseif (isset($_GET['action']) && $_GET['action'] == 'dranken') {
    $query = "SELECT * FROM artikel WHERE artikelgroep IN ('Koffie','Frisdrank')";
} elseif (isset($_GET['action']) && $_GET['action'] == 'chips') {
    $query = "SELECT * FROM artikel WHERE artikelgroep = 'Chips'";
} elseif (isset($_GET['action']) && $_GET['action'] == 'koek') {
    $query = "SELECT * FROM artikel WHERE artikelgroep = 'Koek'";
} else {
    $query = "SELECT * FROM artikel";
}

$result = $mysqli->query($query);
if ($result->num_rows > 0) {
    echo "<div class= 'productcontainer'>";
    while ($row = $result->fetch_assoc()) {
        $row['prijs'] = str_replace(',', '.', $row['prijs']);
        $row['prijs'] = floatval($row['prijs']);
        echo "      <form method='POST' action='' class='product'>
                    <input type= 'hidden' name='type' value='toevoegen'>
                    <input type='hidden' name='artikelnummer' value='" . $row['Artikelnummer'] . "'>
                    <input type='hidden' name='omschrijving' value='" . $row['omschrijving'] . "'>
                    <input type='hidden' name='prijs' value='" . $row['prijs'] . "'>
                    <input type='hidden' name='eenheid' value='" . $row['eenheid'] . "'>
                        <button type='submit' class='product_button'>
                            <p>" . $row['omschrijving'] . "</p>
                            <p> €" . $row['prijs'] . "</p>
                            <p>" . $row['eenheid'] . "</p>
                        </button>
                    </form>
                ";
    }
    echo "</div>";
}
?>

<div id='productscherm'>
    <table class='kassa_bon'>
        <tr>
            <th>Omschrijving</th>
            <th>Prijs</th>
            <th>Aantal</th>
        </tr>
        <?php
        if (isset($_SESSION['kassabon'])) {
            foreach ($_SESSION['kassabon'] as $omschrijving => $product) {
                if (isset($product['prijs'])) {
                    echo "<tr>
                             <td>" . $omschrijving . "</td>
                             <td> €" . $product['prijs'] . "</td>
                             <td>" . $product['aantal'] . "</td>
                             <td>
                                <form method='POST' action=''>
                                    <input class='plus_min' type='hidden' name='type' value='+'>
                                    <input class='plus_min' type='hidden' name='omschrijving' value='$omschrijving'>
                                    <input class='plus_min' type='submit' name='type' value='+'>
                                </form>
                                <form method='POST' action=''>
                                    <input class='plus_min' type='hidden' name='omschrijving' value='$omschrijving'>
                                    <input class='plus_min' type='hidden' name='type' value='-'>
                                    <input class='plus_min' type='submit' name='type' value='-'>
                                </form>
                                </td>
                         </tr>";
                }
            }
        }
        ?>
    </table>
</div>

<div id="totaal">
    <table class="totaal">
        <tr>
            <th>Totaal</th>
            <th>BTW</th>
        </tr>

        <tr>
            <td>
                <?php
                if (isset($_SESSION['kassabon'])) {
                    $totaal = 0;
                    $producttotaal = 0;
                    $btw_percentage = 0.09;
                    foreach ($_SESSION['kassabon'] as $product) {
                        if (isset($product['prijs'])) {
                            $producttotaal = floatval($product['prijs']) * floatval($product['aantal']);
                            $totaal += $producttotaal;
                        }
                    }
                    $totaal_met_btw = $totaal + ($totaal * $btw_percentage);
                    echo "€" . round($totaal_met_btw, 2);
                }
                ?>
            </td>
            <td>
                <?php
                if (isset($_SESSION['kassabon'])) {
                    $btw = 0;
                    foreach ($_SESSION['kassabon'] as $product) {
                        if (isset($product['prijs'])) {
                            $btw = floatval($product['prijs']) * floatval($product['aantal']) * 0.09;
                        }
                    }
                    echo "€" . round($btw, 2);
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<form id="afrekenen" method="POST" action="">
    <input type="hidden" name="type" value="afrekenen">
    <input type="submit" name="afrekenen" id="afrekenenbtn" value="afrekenen" onclick="alert('Afrekenen succesvol!')">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['barcode'])) {
        $barcode = $_POST['barcode'];

        $query = "SELECT omschrijving, Artikelnummer, leverancier, eenheid, prijs, aantal FROM artikel WHERE Artikelnummer = '$barcode'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['prijs'] = str_replace(',', '.', $row['prijs']);
                $row['prijs'] = floatval($row['prijs']);
                echo "<div class='productcontainer'>
                    <form method='POST' action='' class='product'>
                    <input type= 'hidden' name='type' value='toevoegen'>
                    <input type='hidden' name='artikelnummer' value='" . $row['Artikelnummer'] . "'>
                    <input type='hidden' name='omschrijving' value='" . $row['omschrijving'] . "'>
                    <input type='hidden' name='prijs' value='" . $row['prijs'] . "'>
                    <input type='hidden' name='eenheid' value='" . $row['eenheid'] . "'>
                        <button type='submit' class='product_button'>
                            <p>" . $row['omschrijving'] . "</p>
                            <p> €" . $row['prijs'] . "</p>
                            <p>" . $row['eenheid'] . "</p>
                        </button>
                    </form>
                </div>";
            }
        } else {
            $_SESSION['foutbarcode'] = "Barcode is niet gevonden";
        }
    }
}

if (isset($_SESSION['foutbarcode'])) {
    echo "<p id='foutbarcode'>" . $_SESSION['foutbarcode'] . "</p>";
    unset($_SESSION['foutbarcode']);
}


?>