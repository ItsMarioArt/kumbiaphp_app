<?php

/*
 * file:  usuarios_controller.php
 * class: UsuariosController
 *
 */
class UsuariosController extends AppController{

    /*
     * accion: index
     * vista: app/views/usuarios/index.phtml
     * url: /usuarios
     */
    public function index(){
        $this->usuarios = (new Usuarios) -> find();
    }
    /*
     * accion: ver
     * vista: usuarios/ver.phtml
     * url: /usuarios/ver/:id
     */
    public function ver($id){
        $this->usuario = (new Usuarios) -> find($id);
    }
}