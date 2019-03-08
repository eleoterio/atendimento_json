<?php
error_reporting( E_ALL );
$arquivo = "tickets.json";
$json_file = file_get_contents($arquivo);   
$json = json_decode($json_file, true);

foreach($json as $key => $ticket){
    $abertura = new DateTime($ticket['DateCreate']);
    $update = new DateTime($ticket['DateUpdate']);
    $interval = $abertura->diff($update);
    $pontos = 0;
    if (count($ticket['Interactions']) <= 1) {
        $pontos += 20;
    }
    if ($interval->days > 5) 
    {
        $pontos += 20;
    }
    if ( preg_match('/reclamação/', strtolower($ticket['Interactions'][0]['Subject']) ) || preg_match('/reclamação/', strtolower($ticket['Interactions'][0]['Message']) ) ) 
    {        
         $pontos += 20;
    } 
    if ( preg_match('/troca/', strtolower($ticket['Interactions'][0]['Subject']) ) || preg_match('/troca/', strtolower($ticket['Interactions'][0]['Message']) ) )           
    {         
        $pontos += 20;
    }
 if ( preg_match('/procon/', strtolower($ticket['Interactions'][0]['Message']) ) || preg_match('/reclame/', strtolower($ticket['Interactions'][0]['Message']) ) )           
    {         
        $pontos += 20;
    }
    $json[$key]['ponctuation'] = $pontos;
    if ($pontos > 20) {
        $json[$key]['priority'] = "Alta";
    } else {
        $json[$key]['priority'] = "Normal";
    }
}
$json_format = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents($arquivo, $json_format);
