<?php

/*
 * file:  clientes_controller.php
 * class: ClientesController
 *
 */
class ClientesController extends AppController{
    /*
     * accion: index
     * vista: app/views/clientes/index.phtml
     * url: /clientes
     */
    public function index(){
        $this->clientes = (new Clientes)->find();
    }

    /*
     * accion: ver
     * vista: clientes/ver.phtml
     * url: /clientes/ver/:id
     */
    public function ver($id){
        $this->cliente = (new Clientes)->find((int) $id);

        if (!$this->cliente) {
            Flash::error('El cliente no existe');
            Redirect::to('clientes');
        }
    }

    /*
     * accion: editar
     * vista: clientes/editar.phtml
     * url: /clientes/editar/:id
     */
    public function editar($id){
        $this->cliente = (new Clientes)->find((int) $id);

        if (!$this->cliente) {
            Flash::error('El cliente no existe');
            Redirect::to('clientes');
            return;
        }

        $this->setPage("Editar: {$this->cliente->nombre}", 'Modifica los datos del cliente', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Clientes', 'url' => URL . 'clientes'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('cliente.nombre')) {
            $params = Input::post('cliente');

            $this->cliente->nombre         = trim($params['nombre']);
            $this->cliente->empresa        = trim($params['empresa']);
            $this->cliente->email          = trim($params['email']);
            $this->cliente->telefono       = trim($params['telefono']);
            $this->cliente->ciudad         = trim($params['ciudad']);
            $this->cliente->pais_id        = (int) $params['pais_id'];
            $this->cliente->limite_credito = trim($params['limite_credito']);

            if ($this->cliente->nombre === '' || $this->cliente->empresa === '' ||
                $this->cliente->telefono === '' || $this->cliente->ciudad === '') {
                Flash::error('Nombre, empresa, teléfono y ciudad son obligatorios');
                return;
            }

            if (!filter_var($this->cliente->email, FILTER_VALIDATE_EMAIL)) {
                Flash::error('El correo electrónico no es válido');
                return;
            }

            if (!is_numeric($this->cliente->limite_credito) || $this->cliente->limite_credito < 0) {
                Flash::error('El límite de crédito debe ser un número mayor o igual a 0');
                return;
            }

            try {
                $guardado = $this->cliente->save();
            } catch (Throwable $e) {
                $guardado = false;
            }

            if ($guardado) {
                Flash::valid('Cliente actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el cliente. Revisa que el correo no esté repetido');
            }
        }
    }
}