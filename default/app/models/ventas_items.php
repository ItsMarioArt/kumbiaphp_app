<?php

/*
 * file:  models/ventas_items.php
 * tabla: ventas_items
 */
class VentasItems extends ActiveRecord
{
    /*
     * Relacion: $item->getVenta() devuelve la venta a la que pertenece el renglon.
     */
    protected function initialize()
    {
        $this->belongs_to('venta', 'model: Ventas', 'fk: venta_id');
    }

    /*
     * Antes de guardar (insert o update) se calcula el importe.
     */
    public function before_save()
    {
        $this->importe = $this->cantidad * $this->unitario;
    }

    /*
     * El total de la venta se recalcula DESPUES de guardar o borrar el renglon,
     * cuando ya existe en la base (si fuera antes, el SUM no lo incluiria).
     */
    public function after_save()
    {
        $this->getVenta()->update_total();
    }

    public function after_delete()
    {
        $this->getVenta()->update_total();
    }
}