<?php 

class Autoload {
    
    private $baseDir;

    public function __construct($baseDir) {
        $this->baseDir = $baseDir;
    }

    public function register() {
        spl_autoload_register([$this, 'autoload']);
    }

    private function autoload($classe) {
        $this->buscarClaseRecursivamente($this->baseDir, $classe);
    }

    private function buscarClaseRecursivamente($baseDir, $classe) {
        
        $elementos = scandir($baseDir);

        foreach ($elementos as $elemento) {
            if ($elemento === "." || $elemento === "..") {
                continue;
            }

            $path = $baseDir . $elemento;

            if (is_file($path) && basename($path) === "$classe.php") {
                include $path;
                return true;
            }

            if (is_dir($path)) {
                if ($this->buscarClaseRecursivamente($path . '/', $classe)) {
                    return true;
                }
            }
        }

        return false;
    }
}