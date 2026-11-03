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
     * CODIGO 2.5: quita un renglon de ESTA venta (se valida que le pertenezca).
     * VentasItems::after_delete recalcula el total en la base; aqui se vuelve a
     * llamar update_total() solo para refrescar este objeto.
     */
    public function quitar_item($item_id)
    {
        $item_id = (int) $item_id;

        $item = (new VentasItems())->find_first("conditions: venta_id = {$this->id} AND id = $item_id");

        if (!$item) {
            Flash::error("El producto no pertenece a esta venta");
            return $this;
        }

        $item->delete();
        $this->update_total();
        Flash::valid("Producto quitado de la venta #{$this->id}");

        return $this;
    }

    /*
     * CODIGO 2.6: recalcula el total con la suma de los renglones y refresca
     * este objeto con el valor guardado.
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
     * CODIGO 3.4: cierra la venta registrando el pago inicial.
     * El total NO cambia: lo pagado se resta de por_pagar.
     * $params = ['metodo_id' => ..., 'monto' => ...]. Si el monto es 0 no se
     * registra pago y todo el total queda por pagar.
     * Regresa true/false; si falla deja el mensaje con Flash::error.
     */
    public function finalizar($params)
    {
        $this->update_total();                       // refresca $this->total

        $total = (float) $this->total;
        $monto = round((float) ($params['monto'] ?? 0), 2);

        if ($monto < 0 || $monto > $total) {
            Flash::error("El monto debe estar entre 0 y el total de la venta ($" . number_format($total, 2) . ")");
            return false;
        }

        if ($monto > 0) {
            $metodo = (new PagosMetodos())->find_first((int) ($params['metodo_id'] ?? 0));

            if (!$metodo || !$metodo->activo) {
                Flash::error("Elige un método de pago válido");
                return false;
            }

            $pago = new Pagos();
            $pago->venta_id  = $this->id;
            $pago->metodo_id = $metodo->id;
            $pago->monto     = $monto;

            try {
                $guardado = $pago->save();
            } catch (Throwable $e) {
                $guardado = false;
            }

            if (!$guardado) {
                Flash::error("No se pudo registrar el pago");
                return false;
            }
        }

        $this->cerrada   = 1;
        $this->por_pagar = round($total - $monto, 2);

        return $this->save();
    }
}