<?php
include('inc/header.php');
include('inc/connectie.php');

if ($_SESSION['ingelogd'] != "voorraadbeheerder" && $_SESSION['ingelogd'] != "administrator") {
    if ($_SESSION['ingelogd'] == "kassamedewerker") {
        header('refresh:0;./kassasysteem.php');
    } elseif ($_SESSION['ingelogd'] == "manager") {
        header('refresh:0;./verkoop.php');
    } else {
        header('refresh:0;./login.php');
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

    <a href='?action=csv' class="action">
        <div id="csv">
            <p>CSV bestand importeren</p>
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


<?php

if (isset($_GET['action']) && $_GET['action'] == 'csv') {
    echo "<form id='importeren' action='' method='POST' enctype='multipart/form-data'>
                    <input type='file' name='file'>
                    <input type='submit' name='submit' value='Import'>
                </form>";
}

function uitlezenbestand($bestand)
{
    $info = array();
    if (($handle = fopen($bestand, "r")) == TRUE) {
        fgetcsv($handle, 1000, ";");
        while (($data = fgetcsv($handle, 1000, ";")) == TRUE) {
            $info[] = $data;
        }
        fclose($handle);
    }
    return $info;
}

if (isset($_FILES["file"]["name"])) {

    $target_dir = "./";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($imageFileType != "csv") {
        echo "Sorry, alleen CSV bestanden zijn toegestaan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "file is niet geupload.";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        } else {
            echo "er ging iets fout met het uploaden van het bestand.";
        }
    }

    $info = uitlezenbestand($_FILES["file"]["name"]);
    $query = "SELECT Artikelnummer, aantal FROM artikel";
    $result = $mysqli->query($query);

    while ($verwerk = mysqli_fetch_assoc($result)) {
        foreach ($info as $key => $value) {
            if ($verwerk['Artikelnummer'] == $value[0]) {
                $aantal = intval($verwerk['aantal']) + intval($value[6]);
                $query = "UPDATE artikel SET aantal = " . $aantal . " WHERE Artikelnummer = '$value[0]'";
                $mysqli->query($query);
            }
        }
    }
    unlink($_FILES["file"]["name"]);
}

echo "<table class='voorraad'>
        <tr>
            <th>Artikelnummer</th>
            <th>Omschrijving</th>
            <th>Leverancier</th>
            <th>Artikelgroep</th>
            <th>Eenheid</th>
            <th>Prijs</th>
            <th>Aantal</th>
        </tr>";

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
    while ($row = $result->fetch_assoc()) {
        echo
        "<tr>
            <td>" . $row['Artikelnummer'] . "</td>
            <td>" . $row['omschrijving'] . "</td>
            <td>" . $row['leverancier'] . "</td>
            <td>" . $row['artikelgroep'] . "</td>
            <td>" . $row['eenheid'] . "</td>
            <td> €" . $row['prijs'] . "</td>
            <td>" . $row['aantal'] . "</td> 
         </tr>";
    }
}
echo "</table>";
?>