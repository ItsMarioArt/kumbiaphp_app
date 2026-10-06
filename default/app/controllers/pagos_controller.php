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
}