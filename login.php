<?php
include('inc/header.php');
include('inc/connectie.php');
?>

<div id="container">
    <h1>Inlogscherm</h1>
    <form method='POST'>
        <label for="gebruikersnaam">Gebruikersnaam:</label>
        <input type="text" id="gebruikersnaam" name="gebruikersnaam" placeholder="Gebruikersnaam"><br><br>
        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord"><br>
        <input type="submit" value="Submit">
    </form>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    $query = "SELECT inlog.gebruikersnaam, inlog.wachtwoord, functie.functie FROM inlog INNER JOIN functie ON inlog.rol_id=functie.rol_id";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['gebruikersnaam'] == $gebruikersnaam && $row['wachtwoord'] == $wachtwoord) {
                switch($row['functie']) {
                    case 'administrator':
                        $_SESSION['ingelogd'] = "administrator";
                        header("Location: admin.php");
                        break;
                    case 'kassamedewerker':
                        $_SESSION['ingelogd'] = "kassamedewerker";
                        header("Location: kassasysteem.php");
                        break;
                    case 'manager':
                        $_SESSION['ingelogd'] = "manager";
                        header("Location: verkoop.php");
                        break;
                    case 'voorraadbeheerder':
                        $_SESSION['ingelogd'] = "voorraadbeheerder";
                        header("Location: voorraad.php");
                        break;
                    default:
                        echo "Er is iets fout gegaan";
                }
            } else {
                $_SESSION['fout'] = "De gebruikersnaam of wachtwoord is onjuist";
            }
                
        }
    }
    if(isset($_SESSION['fout'])) {
        echo "<p>" . $_SESSION['fout']. "</p>";
        unset($_SESSION['fout']);
    }
} 

?>