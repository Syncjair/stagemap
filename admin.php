<?php 
include('./inc/header.php');

if (!$_SESSION['ingelogd'] == "administrator") {
    header('refresh:0;./login.php');
}
?>

<h1>Administrator pagina</h1>

<!-- <div class="uitlogknop">
<a href="?action=uitloggen" class="action">
        <div id="uitloggen">
            <p>Uitloggen</p>
        </div>
    </a>
</div> -->

<div id="klikpagina">
    <a href='kassasysteem.php' class='pagina1'>
        <div id="kassa" >
        <p>Kassasysteem</p>
        <img src="./img/kassa.jpg" class="adminfoto"> 
        </div>
    </a>

    <a href='voorraad.php' class='pagina2'>
        <div id="voorraad">
        <p>Voorraad</p>
        <img src="./img/voorraad.webp" class="adminfoto"> 
        </div>
    </a>

    <a href='verkoop.php' class='pagina3'>
        <div id="verkoop">
        <p>Verkoop</p>
        <img src="./img/verkoop.jpg" class="adminfoto"> 
        </div>
    </a>
</div>


