<?php
class Clientes extends ActiveRecord
{
    protected function initialize()
    {
        $this->has_many('ventas');
    }

    public function nombre_completo()
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellidos ?? ''));
    }

    public function __toString()
    {
        return $this->nombre_completo();
    }

    public function excede_credito($extra = 0)
    {
        $linea = $this->linea_credito ?? '';

        if ($linea === '') {
            return false;
        }

        return ((float) ($this->por_pagar ?? 0) + (float) $extra) > (float) $linea;
    }

    public function update_por_pagar()
    {
        $id = (int) $this->id;

        $this->sql("UPDATE clientes
                       SET por_pagar = (SELECT COALESCE(SUM(por_pagar), 0)
                                          FROM ventas
                                         WHERE cliente_id = $id AND cerrada = 1)
                     WHERE id = $id");

        $this->find_first($id);
    }
}