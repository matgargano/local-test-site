#!/usr/local/bin/php
<?php

use WPize\WPize;


$config = array(
    'tempBase' => null,
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
                    'rm wp-config-sample.php'
                )

            ),
            'keep' => array(
                array(
                    'pathMap' => array('.' => '.'),
                    'recursive' => false
                ),
                array(
                    'pathMap' => array('wp-includes' => 'wp-admin')
                ),
                array(
                    'pathMap' => array('wp-admin' => 'wp-admin')
                ),

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

                array(
                    'pathMap' => array('web/app/plugins' => 'wp-content/plugins'),

                ),
                array(
                    'pathMap' => array('web/app/themes' => 'wp-content/themes'),

                ),

            )
        ),

        array(
            'retrieve' => array(
                'type' => 'git',
                'repo' => 'ssh://codeserver.dev.7b33070f-d376-4663-807d-d5283bae2d06@codeserver.dev.7b33070f-d376-4663-807d-d5283bae2d06.drush.in:2222/~/repository.git',
            ),
            'keep' => array(

                array(
                    'pathMap' => array('.' => '.'),
                    'recursive' => false
                ),
                array(
                    'pathMap' => array('wp-includes' => 'wp-includes')
                ),
                array(
                    'pathMap' => array('wp-admin' => 'wp-admin')
                ),
                array(
                    'pathMap' => array('wp-content/mu-plugins' => 'wp-content/mu-plugins')
                ),


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





//
//class git extends handlers
//{
//
//    public function __construct($data, $base = null)
//    {
//        parent::__construct($data, $base);
//    }
//
//    public function grab()
//    {
//        $repo = $this->data['retrieve']['repo'];
//        shell_exec('git clone ' . $repo . ' .');
//        chdir($this->dir);
//        if (is_array($this->data['postCmd'])) {
//            foreach ($this->data['postCmd'] as $command) {
//                shell_exec($command);
//            }
//        }
//
//
//    }
//
//}



$wpizer = new WPize($config);
$wpizer->process();


function tempdir()
{
    $tempfile = tempnam(sys_get_temp_dir(), '');
    if (file_exists($tempfile)) {
        unlink($tempfile);
    }
    mkdir($tempfile);
    if (is_dir($tempfile)) {
        return $tempfile;
    }
}

