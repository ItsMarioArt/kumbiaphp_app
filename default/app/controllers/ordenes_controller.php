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
}