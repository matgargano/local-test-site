#!/usr/local/bin/php
<?php
$defaults = array(
    'tempBase' => $_SERVER['HOME'] . '/temp',
    'pieces' => array(
        array(
            'retrieve' => array(
                'type' => 'shell',
                'commands' => array(

                    'curl -O https://wordpress.org/latest.tar.gz',
                    'tar -zxvf latest.tar.gz',
                    'cp -R wordpress/* .',
                    'rm -rf wordpress',
                    'rm -rf latest.tar.gz',
                    'cp wp-config-sample.php wp-config.php'
                )

            ),
            'keep' => array(
                './' => './',
                'wp-includes' => 'wp-includes',
                'wp-admin' => 'wp-admin'
            )
        ),
        array(
            'retrieve' => array(
                'type' => 'git',
                'repo' => 'git@github.com:matgargano/local-test-site.git',
            ),
            'postCmd' => array(
                'composer install'
            ),
            'keep' => array(
                'web/app/plugins' => 'wp-content/plugins',
                'web/app/themes' => 'wp-content/themes',

            )
        ),
        array(
            'retrieve' => array(
                'type' => 'git',
                'repo' => 'ssh://codeserver.dev.7b33070f-d376-4663-807d-d5283bae2d06@codeserver.dev.7b33070f-d376-4663-807d-d5283bae2d06.drush.in:2222/~/repository.git',
            ),
            'keep' => array(
                'wp-content/mu-plugins' => 'wp-content/mu-plugins',
                './' => './',
                'wp-includes' => 'wp-includes',
                'wp-admin' => 'wp-admin'
            )
        )

    ),

    'interim' => array(
        'repo' => 'git@github.com:matgargano/interim-pantheon-wp.git'
    ),
    'destination' => array(
        'repo' => 'ssh://codeserver.dev.7b33070f-d376-4663-807d-d5283bae2d06@codeserver.dev.7b33070f-d376-4663-807d-d5283bae2d06.drush.in:2222/~/repository.git'
    )

);


// todo allow passing custom config
$config = $defaults;


function removeDirectoryIfExists($dir)
{
    if (is_dir($dir)) {
        rrmdir($dir);
    }
}


function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function removeDirectories($dir)
{

    removeDirectoryIfExists($dir . '/wpizer');

}

removeDirectories($config['tempBase']);

mkdir($config['tempBase'] . '/wpizer/final', 0777, true);


foreach ($config['pieces'] as $piece) {

    $type = 'shell';
    if (isset($piece['retrieve']['type'])) {
        $type = $piece['retrieve']['type'];
    }


    if (class_exists($type)) {
        $handle = new $type($piece, $config['tempBase']);
        $handle->handle();
    }


}

abstract class handlers
{
    protected $data;
    protected $base;
    protected $hash;

    public function __construct($data, $base = null)
    {
        $this->data = $data;
        $this->base = $base;
        $this->hash = md5(microtime(true));

        if (!$this->base) {
            $this->base = $_SERVER['HOME'];
        }
    }

    abstract public function handle();


}

class git extends handlers
{

    public function __construct($data, $base = null)
    {
        parent::__construct($data, $base);
    }

    public function handle()
    {
        $repo = $this->data['retrieve']['repo'];
        $dir = $this->base . '/wpizer/' . $this->hash;
        mkdir($dir, 0777, true);
        chdir($dir);
        shell_exec('git clone ' . $repo . ' .');
        chdir($dir);
        if ( is_array($this->data['postCmd'])) {
            foreach($this->data['postCmd'] as $command ) {
                shell_exec($command);
            }
        }



        if (is_array($this->data['keep'])) {
            foreach ($this->data['keep'] as $source => $destination) {

                $actualSource = $dir . '/' . $source;
                $actualDestination = $this->base . '/wpizer/final/' . $destination;
                mkdir($actualDestination, 0777, true);
                recurse_copy($actualSource, $actualDestination);




            }
        }

    }

}

class shell extends handlers
{
    public function __construct($data, $base = null)
    {
        parent::__construct($data, $base);
    }

    public function handle()
    {

    }

}

