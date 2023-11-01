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
    $records = (array)$all->results;
    var_dump($records);
    foreach($records as $record)
    {
        var_dump($record);
    }
});