<?php

/*
 * file:  productos_controller.php
 * class: ProductosController
 */
class ProductosController extends AppController
{
    /*
     * accion: index
     * vista: productos/index.phtml
     * url: /productos
     */
    public function index()
    {
        $this->setPage('Productos', 'Listado de productos registrados', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Productos']
        ]);

        $this->productos = (new Productos)->find();
    }

    /*
     * accion: ver
     * vista: productos/ver.phtml
     * url: /productos/ver/:id
     */
    public function ver($id)
    {
        $this->producto = $this->cargarProducto($id);

        $this->setPage("Producto: {$this->producto->nombre}", "SKU {$this->producto->sku}", [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Productos', 'url' => URL . 'productos'],
            ['label' => $this->producto->nombre]
        ]);
    }

    /*
     * accion: editar
     * vista: productos/editar.phtml
     * url: /productos/editar/:id
     */
    public function editar($id)
    {
        $this->producto = $this->cargarProducto($id);

        $this->setPage("Editar: {$this->producto->nombre}", 'Modifica los datos del producto', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Productos', 'url' => URL . 'productos'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('producto.nombre')) {
            $params = Input::post('producto');

            $this->producto->nombre       = $params['nombre'];
            $this->producto->stock        = (int) ($params['stock'] ?? $this->producto->stock);
            $this->producto->categoria_id = $params['categoria_id'] ?? $this->producto->categoria_id;
            $this->producto->marca_id     = $params['marca_id'] ?? $this->producto->marca_id;

            if ($this->producto->save()) {
                Flash::valid('Producto actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el producto');
            }
        }
    }

    /*
     * accion: crear
     * vista: productos/crear.phtml
     * url: /productos/crear
     */
    public function crear()
    {
        $this->setPage('Nuevo producto', 'Registra un producto en el catálogo', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Productos', 'url' => URL . 'productos'],
            ['label' => 'Nuevo']
        ]);

        // Evita que la vista falle en GET por variable no definida
        $this->producto = new Productos;

        if (Input::hasPost('producto')) {
            $params = Input::post('producto');

            $this->producto = new Productos($params);
            $this->producto->categoria_id = $params['categoria_id'] ?? 1;
            $this->producto->marca_id     = $params['marca_id'] ?? 1;
            $this->producto->sku          = uniqid();
            $this->producto->slug         = uniqid();

            if ($this->producto->create()) {
                Flash::valid('Producto creado correctamente');
            } else {
                Flash::error('No se pudo crear el producto');
            }
        }
    }

    /**
     * Busca el producto o redirige al listado si no existe.
     * Al ser protected, Kumbia no lo expone como acción.
     */
    protected function cargarProducto($id)
    {
        $producto = (new Productos)->find((int) $id);

        if (!$producto) {
            Flash::error('El producto no existe');
            Redirect::to('productos');
        }

        return $producto;
    }
}