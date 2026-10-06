<?php

/*
 * file:  productos_controller.php
 * class: ProductosController
 *
 */
class ProductosController extends AppController{
    public function items(){
        return [
            1 => (object) ['id' => 1, 'nombre' => 'Laptop Lenovo 15', 'descripcion' => 'Computadora portátil 15.6 pulgadas', 'precio' => 3537.97, 'stock' => 71, 'categoria_id' => 1, 'marca' => 'HP', 'sku' => 'PRD-0001'],
            2 => (object) ['id' => 2, 'nombre' => 'Mouse inalámbrico', 'descripcion' => 'Mouse óptico 1600 DPI', 'precio' => 12751.97, 'stock' => 18, 'categoria_id' => 1, 'marca' => 'Logitech', 'sku' => 'PRD-0002'],
            3 => (object) ['id' => 3, 'nombre' => 'Teclado mecánico', 'descripcion' => 'Switches azules RGB', 'precio' => 15550.32, 'stock' => 13, 'categoria_id' => 1, 'marca' => 'Razer', 'sku' => 'PRD-0003'],
            4 => (object) ['id' => 4, 'nombre' => 'Monitor 24"', 'descripcion' => 'Full HD 75Hz', 'precio' => 5010.07, 'stock' => 101, 'categoria_id' => 1, 'marca' => 'Samsung', 'sku' => 'PRD-0004'],
            5 => (object) ['id' => 5, 'nombre' => 'Audífonos Bluetooth', 'descripcion' => 'Cancelación de ruido', 'precio' => 12446.55, 'stock' => 13, 'categoria_id' => 1, 'marca' => 'Sony', 'sku' => 'PRD-0005'],
            6 => (object) ['id' => 6, 'nombre' => 'Silla ergonómica', 'descripcion' => 'Soporte lumbar ajustable', 'precio' => 11255.17, 'stock' => 4, 'categoria_id' => 7, 'marca' => 'Herman Miller', 'sku' => 'PRD-0006'],
            7 => (object) ['id' => 7, 'nombre' => 'Mesa de centro', 'descripcion' => 'Madera de roble', 'precio' => 5053.77, 'stock' => 77, 'categoria_id' => 7, 'marca' => 'IKEA', 'sku' => 'PRD-0007'],
            8 => (object) ['id' => 8, 'nombre' => 'Balón de fútbol', 'descripcion' => 'Tamaño oficial profesional', 'precio' => 14973.43, 'stock' => 102, 'categoria_id' => 3, 'marca' => 'Adidas', 'sku' => 'PRD-0008'],
            9 => (object) ['id' => 9, 'nombre' => 'Raqueta de tenis', 'descripcion' => 'Grafito ligero', 'precio' => 774.73, 'stock' => 55, 'categoria_id' => 3, 'marca' => 'Wilson', 'sku' => 'PRD-0009'],
            10 => (object) ['id' => 10, 'nombre' => 'Camiseta deportiva', 'descripcion' => 'Dry-fit talla M', 'precio' => 14114.46, 'stock' => 0, 'categoria_id' => 5, 'marca' => 'Nike', 'sku' => 'PRD-0010'],
        ];
    }
    /*
     * accion: index
     * vista: app/views/productos/index.phtml
     * url: /productos
     */
    public function index(){
        $this->title = 'Productos';
        $this->productos = (new Productos) -> find();
    }
    /*
     * accion: ver
     * vista: productos/ver.phtml
     * url: /productos/ver/:id
     */
    public function ver($id){
        $this->producto = (new Productos) -> find($id);
        $this->title = "Producto: {$this->producto->nombre}";
    }

    public function editar($id){
        $this->producto = (new Productos) -> find($id);
        $this->title = "Editar: {$this->producto->nombre}";

        if(Input::hasPost('producto.nombre')){
            $params = Input::post('producto');
            $this->producto->nombre = $params['nombre'];
            $this->producto->stock = $params['stock'];
            $this->producto->categoria_id = $params['categoria_id'];
            $this->producto->marca_id = $params['marca_id'];

            if ($this->producto->save()) {
                Flash::valid('Producto actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el producto');
            }

        }
    }

    public function crear(){
        if (Input::hasPost('producto')){
            $params = Input::post('producto');
            $this->producto = new Productos($params);
            $this->producto->categoria_id = 1;
            $this->producto->marca_id = 1;
            $this->producto->sku = uniqid();
            $this->producto->slug = uniqid();

            if ($this->producto->create()) {
                Flash::valid('Producto creado correctamente');
            } else {
                Flash::error('No se pudo crear el producto');
            }

        }
    }

}