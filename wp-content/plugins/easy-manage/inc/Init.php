<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc;


 class Init{
        /**
        * Store all the classes inside an array
        * @return array Full list of classes
        */
        public static function get_services(){
            return [
                Pages\AdminRoutes::class,
                Pages\TraineeRoutes::class,
                Pages\TrainerRoutes::class,
                Pages\PmRoutes::class,
                Pages\Tables::class,
                Pages\CohortRoutes::class,
                // Pages\Login::class,
            ];
        }
    
        /**
        * Loop through the classes, initialize them,
        * and call the register() method if it exists
        * @return
        */
        public static function register_services(){
            foreach(self::get_services() as $class){
                $service = self::instantiate($class);
                if(method_exists($service, 'register')){
                    $service->register();
                }
            }
        }
    
        /**
        * Initialize the class from the services array and instance new instance of the class
        */
        private static function instantiate($class){
            $service = new $class();
            return $service;
        }
 }

?>