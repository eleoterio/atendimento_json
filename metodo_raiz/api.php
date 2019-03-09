<?php
error_reporting( E_ALL );
$arquivo = "tickets.json";
$json_file = file_get_contents($arquivo);   
$json = json_decode($json_file, true);
$newarr = [];
if (isset($_GET['dtIni']) && isset($_GET['dtFim'])) {
    $newarr = array_filter($json, function($item) {
        return ($item["DateCreate"] > $_GET['dtIni'] && $item["DateCreate"] < $_GET['dtFim']);
    });
}
if (isset($_GET['prioridade'])) {
    $newarr = array_filter($newarr, function($item) {
        return strtolower($item["priority"]) == strtolower($_GET['prioridade']);
    });
}
if (isset($_GET['ordenacao'])) {
    if ($_GET['ordenacao'] == 'data_criacao') {
        $newarr = arraySort($newarr, 'DateCreate');
    }
    if ($_GET['ordenacao'] == 'data_alterado') {
        $newarr = arraySort($newarr, 'DateUpdate');
    }
    if ($_GET['ordenacao'] == 'prioridade') {
        $newarr = arraySort($newarr, 'priority');
    }
}
if (isset($_GET['por_pagina'])) {
    $pagina = isset($_GET['pagina_atual']) ? $_GET['pagina_atual'] : 1;
    $por_pag = $_GET['por_pagina'];
    if (count($newarr) > ($por_pag*$pagina)) {
        $paginar = [];
        $pagina = ($pagina == 1 )? 0 : $pagina;
        foreach ($newarr as $arr) {
            $paginar[] = $arr;
        }
        $pag = [];
        foreach ($paginar as $k => $v) {
            if ($k >= ($pagina*$por_pag) && $k < (($pagina*$por_pag)+$por_pag) ) {
                $pag[] = $v; 
            }
        }
        $newarr = $pag;
    }
    
}
function arraySort($array, $on){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }
        asort($sortable_array);
        
        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

