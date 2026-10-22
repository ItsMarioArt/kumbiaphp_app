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

    /*
     * accion: editar
     * vista: usuarios/editar.phtml
     * url: /usuarios/editar/:id
     * Se editan correo, rol y estado. La contraseña no se toca.
     */
    public function editar($id){
        $this->usuario = (new Usuarios)->find((int) $id);

        if (!$this->usuario) {
            Flash::error('El usuario no existe');
            Redirect::to('usuarios');
            return;
        }

        $this->setPage("Editar: {$this->usuario->email}", 'Modifica los datos del usuario', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Usuarios', 'url' => URL . 'usuarios'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('usuario.email')) {
            $params = Input::post('usuario');

            $this->usuario->email  = trim($params['email']);
            $this->usuario->rol_id = (int) $params['rol_id'];
            $this->usuario->activo = (int) $params['activo'] ? 1 : 0;

            if (!filter_var($this->usuario->email, FILTER_VALIDATE_EMAIL)) {
                Flash::error('El correo electrónico no es válido');
                return;
            }

            try {
                $guardado = $this->usuario->save();
            } catch (Throwable $e) {
                $guardado = false;
            }

            if ($guardado) {
                Flash::valid('Usuario actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el usuario. Revisa que el correo no esté repetido');
            }
        }
    }
}