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

$all->then(function($all){
    $headers = false;
    $records = (array)$all->results;
    $stdout  = fopen('php://stdout', 'w');

    foreach($records as $record)
    {
        if(!$headers)
        {
            fputcsv($stdout, array_keys($record), "\t");
        }

        fputcsv($stdout, $record, "\t");

        var_dump($record);
    }
});