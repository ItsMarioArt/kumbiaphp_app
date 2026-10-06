<?php

/*
 * file:  marcas_controller.php
 * class: MarcasController
 *
 */
class MarcasController extends AppController{

    /*
     * accion: index
     * vista: app/views/marcas/index.phtml
     * url: /marcas
     */
    public function index(){
        $this->marcas = (new Marcas) -> find();
    }
    /*
     * accion: ver
     * vista: marcas/ver.phtml
     * url: /marcas/ver/:id
     */
    public function ver($id){
        $this->marca = (new Marcas) -> find($id);
    }
}