<?php

namespace WPize\WPize;

class WPize
{

    private $config;
    private $realBase;

    public function __construct(Array $config = array())
    {
        if (!$config) {
            throw new \Exception('Please pass in a configuration array');
        }
        $this->config = $config;
    }

    public function process()
    {

        if (!$this->config['tempBase']) {
            $this->config['tempBase'] = tempdir();
        }
        if (is_dir($this->realBase)) {
            $this->recursivelyRemoveBase();
        }


        foreach ($this->config['pieces'] as $piece) {

            $type = 'shell';
            if (isset($piece['retrieve']['type'])) {
                $type = $piece['retrieve']['type'];
            }


            if (class_exists($type)) {
                /**
                 * @var handlers $handle
                 */
                $handle = new $type($piece, $this->realBase);
                $handle->handle();
            }


        }


    }

    public function recursivelyRemoveBase()
    {

        self::recursivelyRemoveDirectory($this->realBase);
    }

    public static function recursivelyRemoveDirectory($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") self::recursivelyRemoveDirectory($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

}