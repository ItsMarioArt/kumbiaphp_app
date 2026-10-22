<?php

/*
 * file:  ordenes_controller.php
 * class: OrdenesController
 *
 */
class OrdenesController extends AppController{

    /*
     * accion: index
     * vista: app/views/ordenes/index.phtml
     * url: /ordenes
     */
    public function index(){
        $this->ordenes = (new Ordenes) -> find();
    }
    /*
     * accion: ver
     * vista: ordenes/ver.phtml
     * url: /ordenes/ver/:id
     */
    public function ver($id){
        $this->orden = (new Ordenes) -> find($id);
    }

    /*
     * accion: editar
     * vista: ordenes/editar.phtml
     * url: /ordenes/editar/:id
     * Solo se editan el estado y la direccion de envio; folio y totales son de lectura.
     */
    public function editar($id){
        $this->orden = (new Ordenes)->find((int) $id);

        if (!$this->orden) {
            Flash::error('La orden no existe');
            Redirect::to('ordenes');
            return;
        }

        $this->setPage("Editar orden: {$this->orden->folio}", 'Modifica el estado y la dirección de envío', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Órdenes', 'url' => URL . 'ordenes'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('orden.estado_orden_id')) {
            $params = Input::post('orden');

            $this->orden->estado_orden_id      = (int) $params['estado_orden_id'];
            $this->orden->direccion_envio_snap = trim($params['direccion_envio_snap']);

            if ($this->orden->direccion_envio_snap === '') {
                Flash::error('La dirección de envío es obligatoria');
                return;
            }

            try {
                $guardada = $this->orden->save();
            } catch (Throwable $e) {
                $guardada = false;
            }

            if ($guardada) {
                Flash::valid('Orden actualizada correctamente');
            } else {
                Flash::error('No se pudo guardar la orden');
            }
        }
    }
}