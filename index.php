<?php
    $links = [
        'Hello, world!' => '/hello-world.php',
        'php info' => '/phpinfo.php',
        'D1 SQL' => '/db-test.php',
    ];
?>
<html>
    <head></head>
    <body>
        <h1>Welcome to the first cloud php site!</h1>
        <ul>
            <?php foreach($links as $text => $url): ?>
                <li><a href = "<?=$url;?>"><?=$text;?></a></li>
            <?php endforeach; ?>
        </ul>
        <p>Check out the github:</p>
        <p><a href = "https://github.com/seanmorris/php-wasm">https://github.com/seanmorris/php-wasm</a></p>
        <p><a href = "https://github.com/seanmorris/php-cloud">https://github.com/seanmorris/php-cloud</a></p>
        <p><a href = "https://github.com/seanmorris/php-static">https://github.com/seanmorris/php-static</a></p>
    </body>
</html>
