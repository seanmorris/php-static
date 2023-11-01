<?php

$db  = (new Vrzno)->env->db;
$get = vrzno_env('_GET');

if(!$get->id)
{
    $statement = $db->prepare('SELECT * FROM Customers');
}
else
{
    $statement = $db->prepare('SELECT * FROM Customers WHERE CustomerId = ?')
    ->bind($get->id);
}

$statement->all()->then(function($result){
    $headers = false;
    $records = (array)$result->results;
    $stdout  = fopen('php://stdout', 'w');

    foreach($records as $record)
    {
        var_dump($record);
        // if(!$headers)
        // {
        //     fputcsv($stdout, array_keys($record), "\t");
        // }

        // fputcsv($stdout, $record, "\t");
    }
});
