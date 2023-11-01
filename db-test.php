<?php

$vrzno = new Vrzno;

$db = $vrzno->env->db;

//*/
$statement = $db->prepare('SELECT * FROM Customers');
/*/
$statement = $db
->prepare('SELECT * FROM Customers WHERE CompanyName = ?')
->bind('Bs Beverages');
//*/

$all = vrzno_await($statement->all());

$out = fopen('php://stdout', 'w');
$headers = false;

$all->then(function($all) use($out, &$headers) {
    foreach($all->results as $record)
    {
        if(!$headers)
        {
            fputcsv($out, array_keys($record), "\t");
            $headers = true;
        }
        
        fputcsv($out, $record, "\t");
    }
});