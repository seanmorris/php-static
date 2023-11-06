<?php

$pdo = new PDO($connStr = 'vrzno:' . vrzno_target(vrzno_env('db')));

$update = $pdo->prepare('UPDATE WikiPages SET PageTitle = ? WHERE PageId = ?');

$update->execute(['Changed!', 3]);

$select = $pdo->prepare('SELECT * FROM WikiPages WHERE PageId = ? ORDER BY PageId DESC');

// $prepped->bindValue(1, 200, PDO::PARAM_INT);

var_dump($connStr, $pdo, $select);

$select->execute([3]);

while ($row = $select->fetchAll(PDO::FETCH_CLASS, StdClass::class))
{
	var_dump($row);
}
