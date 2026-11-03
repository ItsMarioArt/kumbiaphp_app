<?php

class VentasController extends AppController
{
    /*
     * accion: nuevo
     * vista: ventas/nuevo.phtml
     * url: /ventas/nuevo              -> elegir cliente
     *      /ventas/nuevo/:cliente_id  -> venta abierta de ese cliente
     */
    public function nuevo($cliente_id = null)
    {
        $this->title = "Nueva Venta";
        $this->cliente_id = $cliente_id;

        // 1) Se eligio el cliente en el formulario: crear/recuperar su venta y ir a ella
        if (Input::hasPost('venta')) {
            $cliente = $this->cargar_cliente(Input::post('venta.cliente_id'));

            if ($cliente) {
                $venta = (new Ventas())->get_abierta($cliente->id);
                Flash::valid("Venta #{$venta->id} lista para el cliente {$cliente->nombre}");
                Redirect::to("ventas/nuevo/{$cliente->id}");   // CODIGO 6
            }
            return;
        }

        // 2) Sin cliente: formulario para elegir uno
        if ($cliente_id === null) {
            $this->clientes = (new Clientes())->find("order: nombre");
            return;
        }

        // 3) Con cliente: su venta abierta
        $this->cliente = $this->cargar_cliente($cliente_id);
        if (!$this->cliente) {
            return;
        }
        $this->venta = (new Ventas())->get_abierta($this->cliente->id);

        // Agregar un producto (CODIGO 2)
        if (Input::hasPost('item')) {
            $this->venta->agregar_item(Input::post('item'));
            Redirect::to("ventas/nuevo/{$this->cliente->id}");
            return;
        }

        $this->productos = (new Productos())->get_activos();
        $this->items = $this->venta->getItems();
    }

    /*
     * CODIGO 2.1: finalizar la venta abierta del cliente (solo por POST).
     * Sustituye al bloque "finalizar" del CODIGO 7, que ya no va en nuevo().
     */
    public function cerrar($cliente_id = null)
    {
        if (Input::hasPost('finalizar')) {
            $cliente = $this->cargar_cliente($cliente_id);
            if (!$cliente) {
                return;
            }

            $venta = (new Ventas())->get_abierta($cliente->id);

            if (!$venta->getItems()) {
                Flash::error("Agrega al menos un producto antes de finalizar la venta");
                Redirect::to("ventas/nuevo/{$cliente->id}");
                return;
            }

            if ($venta->finalizar()) {
                Flash::valid("Venta #{$venta->id} finalizada");
            } else {
                Flash::error("No se pudo finalizar la venta");
            }
        }

        Redirect::to("ventas/nuevo");
    }

    /*
     * CODIGO 2.4: quitar un renglon de la venta abierta (solo por POST).
     */
    public function quitar_item($cliente_id = null, $item_id = null)
    {
        if (Input::is('POST')) {
            $cliente = $this->cargar_cliente($cliente_id);
            if (!$cliente) {
                return;
            }

            $venta = (new Ventas())->get_abierta($cliente->id);
            $venta->quitar_item($item_id);

            Redirect::to("ventas/nuevo/{$cliente->id}");
            return;
        }

        Redirect::to("ventas/nuevo/" . (int) $cliente_id);
    }

    /*
     * Busca el cliente; si no existe avisa y regresa al formulario de seleccion.
     */
    protected function cargar_cliente($cliente_id)
    {
        $cliente = (new Clientes())->find_first((int) $cliente_id);

        if (!$cliente) {
            Flash::error("El cliente no existe");
            Redirect::to("ventas/nuevo");
        }

        return $cliente;
    }
}