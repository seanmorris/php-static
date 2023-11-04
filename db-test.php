<?php

$get = vrzno_env('_GET');
$db  = vrzno_env('db');

if(!$get->id)
{
    $statement = $db
    ->prepare('SELECT * FROM Customers');
}
else
{
    $statement = $db
    ->prepare('SELECT * FROM Customers WHERE CustomerId = ?')
    ->bind($get->id);
}

$result = vrzno_await($statement->all());

$records = (array)$result->results;

echo "<pre>";

foreach($records as $record)
{
	var_dump($record);
}

echo "</pre>";

?>

