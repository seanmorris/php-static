<?php
    $links = [
        'Hello, world!' => '/hello-world.php',
        'php info' => '/phpinfo.php',
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
        <a href = "https://github.com/seanmorris/php-wasm">https://github.com/seanmorris/php-wasm</a>
    </body>
</html>
