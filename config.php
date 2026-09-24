<?php
    // Loeme Dockeri seaded või kasutame nende puudumisel vaikeväärtusi.
    $db_server = getenv('DB_HOST') ?: 'db';
    $db_andmebaas = getenv('DB_NAME') ?: 'car_rent';
    $db_kasutaja = getenv('DB_USER') ?: 'admin';
    $db_salasona = getenv('DB_PASS') ?: 'Passw0rd';

    // Proovime andmebaasiga ühendust luua kuni viis korda.
    $yhendus = false;
    $attempts = 0;
    
    while ($attempts < 5) {
        $yhendus = @mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);
        if ($yhendus) break;
        
        $attempts++;
        sleep(2); // Anname enne uut katset kaks sekundit aega.
    }

    // Kontrollime, kas ühendus sai lõpuks loodud.
    if (!$yhendus) {
        die('Viga: Andmebaasiga ei saanud ühendust. Kontrolli, kas DB konteiner töötab. ' . mysqli_connect_error());
    }
?>