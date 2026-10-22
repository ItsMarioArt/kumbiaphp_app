<?php

/*
 * models/ordenes.php
 */

class Ordenes extends ActiveRecord{
    public function get_ordenes($estado_orden_id = 1)
    {
        $sql = "SELECT o.id, folio, o.estado_orden_id, o.metodo_envio_id, o.usuario_id, o.total, o.creado_en,
                    eo.nombre estado_orden,
                    me.nombre metodo_envio,
                    u.email
                 FROM ordenes o
                 INNER JOIN estados_ordenes eo ON eo.id = o.estado_orden_id 
                 INNER JOIN metodos_envio me ON me.id = o.metodo_envio_id
                 INNER JOIN usuarios u ON u.id = o.usuario_id
                 WHERE o.estado_orden_id = $estado_orden_id";
        return $this->find_all_by_sql($sql);
    }
}