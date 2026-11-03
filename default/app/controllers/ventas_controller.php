<?php

class VentasController extends AppController
{

    public function nuevo($cliente_id = null)
    {
        $this->title = "Nueva Venta";
        $this->cliente_id = $cliente_id;

        if (Input::hasPost('venta')) {
            $cliente_id = Input::post('venta.cliente_id');
            $this->venta = (new Ventas())->get_abierta($cliente_id);
            Flash::valid("Venta #{$this->venta->id} lista para el cliente {$cliente_id}");
            return;
        }

        if ($cliente_id === null) {
            $this->clientes = (new Usuarios())->find();
        } else {
            $this->venta = (new Ventas())->get_abierta($cliente_id);
            Flash::info("Venta abierta del cliente {$cliente_id}");
        }
    }

    public function cerrar($cliente_id = null){
        if (Input::hasPost('finalizar')) {
            $this->venta->finalizar();
        }
        Redirect::to('ventas/nuevo');
    }
}
