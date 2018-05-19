<?php

namespace app\services;

class IoCContainer
{
    private static $binds = [];
    private static $singletons = [];

    /**
     * 
     */
    public static function bind($objectName, $bindObjectName)
    {
        
        if(interface_exists($objectName) && !(new \ReflectionClass($bindObjectName))->implementsInterface($objectName)) {
            throw new \Exception("error");
        }

        if((class_exists($objectName) || interface_exists($objectName)) && (class_exists($bindObjectName))) {
            self::$binds[$objectName] = $bindObjectName;
        }

    }

    /**
     * 
     */
    public static function make($name)
    {
        
        if(!empty(self::$singletons[$name])) {
            return empty(self::$singletons[$name]['instance']) ? call_user_func(self::$singletons[$name]['maker']) : self::$singletons[$name]['instance'];
        }

        if(!empty(self::$binds[$name])) {
            $name = self::$binds[$name];
        }

        $reflector = new \ReflectionClass($name);

        if(!$reflector->isInstantiable()) {
            throw new \Exception("error");
        }

        if(empty($reflector->getConstructor())) {
            return $reflector->newInstance();
        }

        foreach($reflector->getConstructor()->getParameters() as $param) {
            
            $dependency = $param->getClass();
           
            if(empty($dependency)) {
                
                if(!$param->isDefaultValueAvailable()){
                    throw new \Exception("error");
                }
                $dependencies[] = $param->getDefaultValue();

            } else {
                $dependencies[] = self::make($dependency->name);
            }
        }
        return $reflector->newInstanceArgs($dependencies);

    }

    /**
     * 
     */
    public static function singleton($name, $maker, $instance = null)
    {

        if(is_callable($maker)) {
            self::$singletons[$name] = [ 'maker' => $maker, 'instance' => $instance];
        }

    }
    
}