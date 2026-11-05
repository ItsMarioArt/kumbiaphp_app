<?php
class Usuarios extends ActiveRecord
{
    protected function initialize()
    {
        $this->has_many('ventas');
    }

    // Asumiendo que tu método get_activos() está implementado aquí o en un trait
    public function get_activos()
    {
        return $this->find("conditions: activo = 1", "order: email ASC");
    }
}