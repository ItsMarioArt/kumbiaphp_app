<?php

/*
 * file:  pagos_controller.php
 * class: PagosController
 *
 */
class PagosController extends AppController{

    /*
     * accion: index
     * vista: app/views/pagos/index.phtml
     * url: /pagos
     */
    public function index(){
        $this->pagos = (new Pagos) -> find();
    }
    /*
     * accion: ver
     * vista: pagos/ver.phtml
     * url: /pagos/ver/:id
     */
    public function ver($id){
        $this->pago = (new Pagos) -> find($id);
    }

    /*
     * accion: editar
     * vista: pagos/editar.phtml
     * url: /pagos/editar/:id
     * Solo se edita el estado; orden, metodo, transaccion y monto son de lectura.
     */
    public function editar($id){
        $this->pago = (new Pagos)->find((int) $id);

        if (!$this->pago) {
            Flash::error('El pago no existe');
            Redirect::to('pagos');
            return;
        }

        $this->setPage("Editar pago: {$this->pago->transaccion_id}", 'Modifica el estado del pago', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Pagos', 'url' => URL . 'pagos'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('pago.estado')) {
            $params = Input::post('pago');

            $this->pago->estado = trim($params['estado']);

            if ($this->pago->estado === '') {
                Flash::error('El estado es obligatorio');
                return;
            }

            try {
                $guardado = $this->pago->save();
            } catch (Throwable $e) {
                $guardado = false;
            }

            if ($guardado) {
                Flash::valid('Pago actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el pago');
            }
        }
    }
}