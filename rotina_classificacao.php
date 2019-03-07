<?php
error_reporting( E_ALL );

$json_file = file_get_contents("tickets.json");   
$json = json_decode($json_file, true);

foreach($json as $ticket){
    $abertura = new DateTime($ticket['DateCreate']);
    $update = new DateTime($ticket['DateUpdate']);
    $interval = $abertura->diff($update);
    if ($interval->days > 5) {
        $alta = 33.33;
    }
    foreach($ticket['Interactions'] as $interacao) {
        if (preg_match('Reclamação', $interacao['Subject'])) {        
            echo'<pre>';
            var_dump($interacao);
            die;
        }
    }
}
echo'<pre>';
var_dump($json[1]['Interactions'][0]['Subject']);
var_dump(preg_match('/Reclamação/', $json[1]['Interactions'][0]['Subject']));
die;
