<?php

class Ventas
{
    public function get_abierta($cliente_id)
    {
        $venta = $this->find_first("cliente_id: $cliente_id, cerrada: 0");
        if (!$venta) {
            $venta = new Ventas();
            $venta->cliente_id = $cliente_id;
            $venta->usuario_id = $cliente_id;
            $venta->cerrada = 0;
            $venta->fecha = date("Y-m-d");
            $venta->save();
        }

        return $venta;
    }
}
