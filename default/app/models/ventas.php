<?php
class Ventas extends ActiveRecord
{
    protected function initialize()
    {
        $this->belongs_to('usuarios');
        $this->belongs_to('clientes');
        $this->has_many('items', 'model: VentasItems', 'fk: venta_id');
        $this->has_many('pagos');
    }

    public function get_abierta($usuario_id, $cliente_id = null)
    {
        $usuario_id = (int) $usuario_id;
        $cliente_id = (int) $cliente_id;
        $filtro     = $cliente_id ? "cliente_id = $cliente_id" : "cliente_id IS NULL";

        $venta = $this->find_first("conditions: usuario_id = $usuario_id AND $filtro AND cerrada = 0");

        if (!$venta) {
            $venta = new Ventas();
            $venta->usuario_id = $usuario_id;
            if ($cliente_id) {
                $venta->cliente_id = $cliente_id;
            }
            $venta->cerrada = '0';
            $venta->save();
        }

        return $venta;
    }

    public function buscar_abierta($id)
    {
        return $this->find_first("conditions: id = " . (int) $id . " AND cerrada = 0");
    }

    public function obtener_cliente()
    {
        $id = (int) ($this->cliente_id ?? 0);
        return $id ? (new Clientes())->find_first($id) : null;
    }

    public function obtener_vendedor()
    {
        $id = (int) ($this->usuario_id ?? 0);
        return $id ? (new Usuarios())->find_first($id) : null;
    }

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

    public function finalizar($params)
    {
        $this->update_total();

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

        if (!$this->save()) {
            return false;
        }

        $cliente = $this->obtener_cliente();

        if ($cliente) {
            $cliente->update_por_pagar();

            if ($cliente->excede_credito()) {
                Flash::warning("El cliente {$cliente->nombre_completo()} excede su línea de crédito (por pagar $"
                    . number_format((float) $cliente->por_pagar, 2) . " de $"
                    . number_format((float) $cliente->linea_credito, 2) . ")");
            }
        }

        return true;
    }

    public function get_abiertas()
    {
        $sql = "SELECT v.id AS venta_id, v.total, v.fecha,
                       c.id AS cliente_id, c.nombre, c.apellidos,
                       COALESCE(CONCAT_WS(' ', c.nombre, c.apellidos), 'Público General') AS nombre_completo,
                       u.email AS vendedor
                FROM ventas v
                LEFT JOIN clientes c ON c.id = v.cliente_id
                INNER JOIN usuarios u ON u.id = v.usuario_id
                WHERE v.cerrada = 0
                ORDER BY v.fecha DESC";

        return $this->find_all_by_sql($sql);
    }

    public function descartar()
    {
        (new VentasItems())->delete_all("venta_id = {$this->id}");
        return $this->delete();
    }
}