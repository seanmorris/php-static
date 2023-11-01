DB Test:

<?php

$vrzno = new Vrzno;

$db = $vrzno->env->db;

$statement = $db
->prepare('SELECT * FROM Customers WHERE CompanyName = ?')
->bind('Bs Beverages');

$all = $vrzno->all($statement);

$all->then(function($all){
    
    var_dump($all->results);

});