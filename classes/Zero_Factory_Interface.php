<?php

class Zero_Factory_Interface
{
    // public function create(ContainerInterface $container)
    // {
    //     return 
    // }

    private $_config = [
        'name' => 'info'
    ];

    public function get( $id )
    {
        return $this->_config[$id];
    }

    public function has($id)
    {
        return array_key_exists( $id, $this->_config );
    }
}
