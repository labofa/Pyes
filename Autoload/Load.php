<?php  
    class Autoload
    {
        private static function loadFile(String $file) {
            if (file_exists($file)) {
                require_once $file;
                return True;
            }
            return False;
        }
        public function autoLoad(String $class)
        {
            $success = False;
            $fn = str_replace('//', DIRECTORY_SEPARATOR, $class) . '.php';
            foreach (self::$dirs as $start) {
                $file = $start . DIRECTORY_SEPARATOR . $fn;
                if (self::loadFile($file)) {
                    $success = True;
                    break;
                }
            }
            if (!$success) {
                if (!self::loadFile(__DIR__ . DIRECTORY_SEPARATOR . $fn)) {
                    throw new \Exception(
                        self::UNABLE_TO_LOAD . ' ' . $class
                    );
                }
            }
            return $success;
        }
        public function addDirs($dirs) {
            if (is_array($dirs)) {
                self::$dirs = array_merge(self::$dirs, $dirs);
            } else {
                self::$dirs[] = $dirs;
            }
        }
        public static function init($dirs = array())
        {
            if ($dirs) {
                self::addDirs($dirs);
            }
            if (self::$registered == 0) {
                spl_autoload_register(__CLASS__ . '::autoload');
                self::$registered++;
            }
        }
    }
    
    $autoloader = new Autoload();
    $autoloader::loadFile();
