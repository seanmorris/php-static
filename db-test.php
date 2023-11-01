DB Test:

<?php

$vrzno = new Vrzno;

$db = $vrzno->_env->db;

$statement = $db
    ->prepare('SELECT * FROM Customers WHERE CompanyName = ?')
    ->bind('Bs Beverages')
    ->all();

$statement->then(function($result){
    echo json_encode($result);
});
