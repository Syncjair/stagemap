<?php
include('inc/header.php');
include('inc/connectie.php');


if ($_SESSION['ingelogd'] != "manager" && $_SESSION['ingelogd'] != "administrator") {
    if ($_SESSION['ingelogd'] == "kassamedewerker") {
        header('refresh:0;./kassasysteem.php');
    } elseif ($_SESSION['ingelogd'] == "voorraadbeheerder") {
        header('refresh:0;./voorraad.php');
    } else {
        header('refresh:0;./login.php');
}
}
?>
<h1>Verkoop</h1>

<div class="menu">
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
    $query = "SELECT omschrijving, aantalverkocht, artikel.artikelgroep FROM verkoop INNER JOIN artikel ON verkoop.Artikelnummer=artikel.Artikelnummer";

$result = $mysqli->query($query);
if ($result->num_rows > 0) {
    echo "<table class='verkoop'>
    <tr>
    <th>Product</th>
    <th>Artikelgroep</th>
    <th>Aantalverkocht</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row['omschrijving'] . "</td>
        <td>" . $row['artikelgroep'] . "</td>
        <td>" . $row['aantalverkocht'] . "</td>
        <tr>";
    }
}


?>