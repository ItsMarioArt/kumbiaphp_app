<?php
class VentasController extends AppController
{
    public function index()
    {
        $this->title = "Listado de Ventas";
        $this->ventas = (new Ventas())->find('order: id DESC');
    }

    public function nuevo($venta_id = null)
    {
        $this->title = "Nueva Venta";

        if (Input::hasPost('venta')) {
            $this->iniciar_venta(Input::post('venta'));
            return;
        }

        if ($venta_id === null) {
            $this->usuarios = (new Usuarios())->get_activos();
            $this->clientes = $this->lista_clientes();
            return;
        }

        $this->venta = (new Ventas())->buscar_abierta($venta_id);

        if (!$this->venta) {
            Flash::error("La venta no existe o ya está cerrada");
            Redirect::to("ventas/nuevo");
            return;
        }

        $this->vendedor = $this->venta->obtener_vendedor();
        $this->cliente  = $this->venta->obtener_cliente();

        if (Input::hasPost('item')) {
            $this->venta->agregar_item(Input::post('item'));
            Redirect::to("ventas/nuevo/{$this->venta->id}");
            return;
        }

        $this->productos = (new Productos())->get_activos(); // Asumiendo que existe get_activos() en Productos
        $this->metodos   = (new PagosMetodos())->get_activos(); // Asumiendo que existe get_activos() en PagosMetodos
        $this->items     = $this->venta->getItems();
    }

    public function cerrar($venta_id = null)
    {
        if (Input::hasPost('finalizar')) {
            $venta = (new Ventas())->buscar_abierta($venta_id);

            if (!$venta) {
                Flash::error("La venta no existe o ya está cerrada");
                Redirect::to("ventas/nuevo");
                return;
            }

            if (!$venta->getItems()) {
                Flash::error("Agrega al menos un producto antes de finalizar la venta");
                Redirect::to("ventas/nuevo/{$venta->id}");
                return;
            }

            if ($venta->finalizar(Input::post('finalizar'))) {
                Flash::valid("Venta #{$venta->id} finalizada. Por pagar: $" . number_format((float) $venta->por_pagar, 2));
                Redirect::to("ventas/nuevo");
            } else {
                Redirect::to("ventas/nuevo/{$venta->id}");
            }
            return;
        }

        Redirect::to("ventas/nuevo");
    }

    public function quitar_item($venta_id = null, $item_id = null)
    {
        if (Input::is('POST')) {
            $venta = (new Ventas())->buscar_abierta($venta_id);

            if (!$venta) {
                Flash::error("La venta no existe o ya está cerrada");
                Redirect::to("ventas/nuevo");
                return;
            }

            $venta->quitar_item($item_id);
            Redirect::to("ventas/nuevo/{$venta->id}");
            return;
        }

        Redirect::to("ventas/nuevo");
    }

    public function abiertas()
    {
        $this->title = "Ventas Abiertas";
        $this->ventas_abiertas = (new Ventas())->get_abiertas();
    }

    public function descartar($venta_id)
    {
        if (Input::is('POST')) {
            $venta = (new Ventas())->find_first((int) $venta_id);

            if ($venta) {
                $venta->descartar();
                Flash::valid("La venta ha sido descartada exitosamente.");
            } else {
                Flash::error("No se pudo encontrar la venta a descartar.");
            }
        }

        return Redirect::to("ventas/abiertas");
    }

    protected function iniciar_venta($datos)
    {
        $vendedor = (new Usuarios())->find_first((int) ($datos['usuario_id'] ?? 0));

        if (!$vendedor || !$vendedor->activo) {
            Flash::error("Elige un vendedor válido");
            Redirect::to("ventas/nuevo");
            return;
        }

        $cliente    = null;
        $cliente_id = (int) ($datos['cliente_id'] ?? 0);

        if ($cliente_id) {
            $cliente = (new Clientes())->find_first($cliente_id);

            if (!$cliente || !$cliente->activo) {
                Flash::error("El cliente no existe o está inactivo");
                Redirect::to("ventas/nuevo");
                return;
            }
        }

        $venta = (new Ventas())->get_abierta($vendedor->id, $cliente_id);
        $nombre = $cliente ? $cliente->nombre_completo() : "Público General";

        Flash::valid("Venta #{$venta->id} lista ({$nombre}), vendedor {$vendedor->email}");
        Redirect::to("ventas/nuevo/{$venta->id}");
    }

    protected function lista_clientes()
    {
        $lista = [(object) ['id' => '', 'nombre' => 'Público General']];

        $clientes = (new Clientes())->find("conditions: activo = 1", "order: nombre, apellidos") ?: [];

        foreach ($clientes as $c) {
            $lista[] = (object) ['id' => $c->id, 'nombre' => $c->nombre_completo()];
        }

        return $lista;
    }
}