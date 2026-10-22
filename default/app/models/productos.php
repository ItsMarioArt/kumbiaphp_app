<?php

/*
 * models/productos.php
 */

class Productos extends ActiveRecord
{

    public function initialize()
    {
        $this->belongs_to('categoria', 'model: Categorias', 'foreign_key: categoria_id');
        $this->belongs_to('marca', 'model: Marcas', 'foreign_key: marca_id');
    }
}