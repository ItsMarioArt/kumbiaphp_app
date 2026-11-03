<?php

/*
 * file:  models/ventas.php
 * tabla: ventas
 */
class Ventas extends ActiveRecord
{
    /*
     * Relaciones: $venta->getItems() devuelve los renglones (ventas_items)
     * y $venta->cliente el registro del cliente.
     */
    protected function initialize()
    {
        $this->has_many('items', 'model: VentasItems', 'fk: venta_id');
        $this->belongs_to('cliente', 'model: Clientes', 'fk: cliente_id');
    }

    /*
     * Devuelve la venta abierta (cerrada = 0) del cliente; si no tiene, la crea.
     */
    public function get_abierta($cliente_id)
    {
        $cliente_id = (int) $cliente_id;

        $venta = $this->find_first("conditions: cliente_id = $cliente_id AND cerrada = 0");

        if (!$venta) {
            // fecha y created_at salen de los DEFAULT de la tabla. cerrada se asigna aqui:
            // KumbiaPHP no reconoce "DEFAULT 0" como valor por defecto y lo marca como
            // nulo ("El campo cerrada no puede ser nulo"); asi tampoco queda en NULL.
            $venta = new Ventas();
            $venta->cliente_id = $cliente_id;
            $venta->cerrada    = '0';
            $venta->save();
        }

        return $venta;
    }

    /*
     * CODIGO 3: agrega un renglon (producto + cantidad) a la venta.
     * El importe y el total los calcula VentasItems.
     */
    public function agregar_item($params)
    {
        $producto = (new Productos())->find_first((int) ($params['producto_id'] ?? 0));
        $cantidad = (float) ($params['cantidad'] ?? 0);

        if (!$producto || $cantidad <= 0) {
            Flash::error("No se pudo agregar el producto a la venta");
            return $this;
        }

        $item = new VentasItems();
        $item->venta_id    = $this->id;
        $item->producto_id = $producto->id;
        $item->cantidad    = $cantidad;
        $item->unitario    = $producto->precio;
        $item->descripcion = $producto->nombre;

        if ($item->save()) {
            Flash::valid("Producto '{$producto->nombre}' agregado a la venta #{$this->id}");
        } else {
            Flash::error("No se pudo agregar el producto a la venta");
        }

        return $this;
    }

    /*
     * CODIGO 2.6 (adelantado, porque VentasItems lo necesita): recalcula el total
     * con la suma de los renglones y refresca este objeto con el valor guardado.
     */
    public function update_total()
    {
        $id = (int) $this->id;

        $this->sql("UPDATE ventas
                       SET total = (SELECT COALESCE(SUM(importe), 0)
                                      FROM ventas_items
                                     WHERE venta_id = $id)
                     WHERE id = $id");

        $this->find_first($id);
    }

    /*
     * Cierra la venta. Version simple de CAMBIO 1 (sin pagos): todo el total
     * queda por pagar. En el CAMBIO 3 pasa a recibir los datos del pago.
     */
    public function finalizar()
    {
        $this->update_total();
        $this->cerrada   = 1;
        $this->por_pagar = $this->total;

        return $this->save();
    }
}