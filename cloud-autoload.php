<?php
class CloudAutoloader
{
	protected static $vrzno, $loader;
	protected $venDir, $files = [], $psr4 = []/*, $psr0 = []*/;

	public static function autoload($className)
	{
		static::$loader = static::$loader ?? new static;
		static::$vrzno  = static::$vrzno  ?? new Vrzno;

		$zipUrl = static::$loader->locateClassPackage($className);

		static::$loader->loadPackage($zipUrl);
		static::$loader->includeClass($className);
	}

	protected function __construct()
	{
		$this->venDir = '/tmp/vendor';
	}

	protected function loadPackage($url)
	{
		$buffer = $this->fetchPackage($url);

		if($pkgDir = $this->unpackBuffer($buffer))
		{
			$this->enumerateFiles($pkgDir);
			$this->registerAutoloaders($pkgDir);
		}
	}

	protected function includeClass($className)
	{
		$file = $this->locateClassFile($className);

		if(file_exists($file))
		{
			include $file;

			return $className;
		}

		return FALSE;
	}

	protected function locateClassPackage($className)
	{
		$split = explode('\\', $className);
		$url   = strtolower(sprintf('https://repo.packagist.org/p2/%s/%s.json', $split[0], $split[1]));
		$resp  = vrzno_await(static::$vrzno->fetch($url));
		$json  = vrzno_await($resp->json());

		$packageName = strtolower(sprintf('%s/%s', $split[0], $split[1]));

		return $json->packages->{ $packageName }->{0}->dist->url;
	}

	protected function fetchPackage($url)
	{
		$fetch = static::$vrzno->fetch($url, (object)['headers' => (object)[ 'User-Agent' => 'google-chrome lol' ]]);
		$resp  = vrzno_await($fetch);

		return vrzno_await($resp->arrayBuffer());
	}

	protected function unpackBuffer($buffer)
	{
		$binary = '';

		foreach($buffer as $x => $byte)
		{
			$binary .= chr((int)$byte);
		}

		$memFile = fopen('/tmp/pkg.zip', 'w');

		mkdir('/tmp/vendor');

		fwrite($memFile, $binary);

		$zip = new \ZipArchive;
		$res = $zip->open('/tmp/pkg.zip');

		$pkgDir = $zip->statIndex(0)['name'];

		if($res === TRUE)
		{
			$zip->extractTo($this->venDir);
			$zip->close();
		}
		else
		{
			// echo 'failed, code:' . $res;
			return FALSE;
		}

		return $pkgDir;
	}

	protected function enumerateFiles($pkgDir)
	{
		$directory = new \RecursiveDirectoryIterator($this->venDir);
		$iterator  = new \RecursiveIteratorIterator($directory);

		$files = [];

		foreach ($iterator as $info)
		{
			$path = $info->getPathname();
			$pathVendor  = substr($path, strlen($this->venDir));
			$pathPackage = substr($pathVendor, 1 + strlen($pkgDir));

			$this->files[$pathPackage] = $path;
		}
	}

	protected function registerAutoloaders($pkgDir)
	{
		$json     = file_get_contents('/tmp/vendor/' . $pkgDir . '/composer.json');
		$package  = json_decode($json);
		$autoload = $package->autoload;

		foreach($autoload->{'psr-4'} as $namespace => $path)
		{
			$this->psr4[$namespace] = $pkgDir . $path;
		}
	}

	protected function locateClassFile($className)
	{
		$found = FALSE;

		foreach($this->psr4 as $prefix => $directory)
		{
			if($prefix === substr($className, 0, strlen($prefix)))
			{
				$suffix = substr($className, strlen($prefix));
				$split  = explode('\\', $suffix);

				if('/' !== $directory[ -1 + strlen($directory) ])
				{
					$directory .= '/';
				}

				$found = $this->venDir . '/' . $directory . implode('/', $split) . '.php';
				break;
			}
		}

		return $found;
	}
}


// Register the autoloader
spl_autoload_register( fn($className) => CloudAutoloader::autoload($className) );

// Autoload and instantiate a class.
$bank = new \SeanMorris\Bob\Bank;

// Use it.
$value = [0,1,2,3];
$encoded = $bank->encode($value);
$decoded = $bank->decode($encoded);
?>
<pre><?php
	var_dump(['Raw Val' => $value]);
	var_dump(['Encoded' => $encoded]);
	var_dump(['Decoded' => $decoded]);
?></pre>
