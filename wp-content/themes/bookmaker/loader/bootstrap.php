<?php 

class Bootstrap {
    /**
     * Наименования классов
     * 
     * @var array
     */
    public $classes;

    public function register () {
        foreach ($this->classes as $class) {
            require_once __DIR__ . '/../classes/' . $class;
        }
    }
}
