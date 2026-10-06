<?php

/*
 * file:  clientes_controller.php
 * class: ClientesController
 *
 */
class ClientesController extends AppController{
    public function items(){
        return [
            1 => (object) ['id' => 1, 'nombre' => 'Camila Flores', 'empresa' => 'ACME S.A.', 'email' => 'contacto@acmesa.com', 'telefono' => '8197646097', 'ciudad' => 'Guadalajara', 'pais_id' => 1, 'limite_credito' => 62077.24],
            2 => (object) ['id' => 2, 'nombre' => 'Andrés Gómez', 'empresa' => 'TechNova', 'email' => 'contacto@technova.com', 'telefono' => '8153818135', 'ciudad' => 'Monterrey', 'pais_id' => 1, 'limite_credito' => 158559.84],
            3 => (object) ['id' => 3, 'nombre' => 'Paola Vargas', 'empresa' => 'Grupo Andino', 'email' => 'contacto@grupoandino.com', 'telefono' => '8170730511', 'ciudad' => 'Puebla', 'pais_id' => 1, 'limite_credito' => 42912.99],
            4 => (object) ['id' => 4, 'nombre' => 'Héctor Castillo', 'empresa' => 'BlueSoft', 'email' => 'contacto@bluesoft.com', 'telefono' => '8126463034', 'ciudad' => 'Tijuana', 'pais_id' => 1, 'limite_credito' => 65464.29],
            5 => (object) ['id' => 5, 'nombre' => 'Daniela Morales', 'empresa' => 'Construmex', 'email' => 'contacto@construmex.com', 'telefono' => '8122725085', 'ciudad' => 'Mérida', 'pais_id' => 1, 'limite_credito' => 94233.83],
            6 => (object) ['id' => 6, 'nombre' => 'Martín Ortiz', 'empresa' => 'Vitalis', 'email' => 'contacto@vitalis.com', 'telefono' => '8166263253', 'ciudad' => 'León', 'pais_id' => 1, 'limite_credito' => 124280.54],
            7 => (object) ['id' => 7, 'nombre' => 'Lucía Silva', 'empresa' => 'DataCore', 'email' => 'contacto@datacore.com', 'telefono' => '8120015711', 'ciudad' => 'Querétaro', 'pais_id' => 1, 'limite_credito' => 67064.44],
            8 => (object) ['id' => 8, 'nombre' => 'Gabriel Ruiz', 'empresa' => 'Solaria', 'email' => 'contacto@solaria.com', 'telefono' => '8123070826', 'ciudad' => 'Cancún', 'pais_id' => 1, 'limite_credito' => 138930.44],
        ];
    }
    /*
     * accion: index
     * vista: app/views/clientes/index.phtml
     * url: /clientes
     */
    public function index(){
        $this->clientes = $this->items();
    }
    /*
     * accion: ver
     * vista: clientes/ver.phtml
     * url: /clientes/ver/:id
     */
    public function ver($id){


        $this->cliente = $this->items()[$id];
    }
}