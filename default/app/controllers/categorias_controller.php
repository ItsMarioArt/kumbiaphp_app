<?php
/*
 * file:  app/controllers/categorias_controller.php
 * class: CategoriasController
 */
class CategoriasController extends AppController {

    /*
     * accion: index
     * vista: app/views/categorias/index.phtml
     * url: /categorias
     */
    public function index() {
        $this->categorias = (new Categorias)->find();
    }

    /*
     * accion: ver
     * vista: app/views/categorias/ver.phtml
     * url: /categorias/ver/:id
     */
    public function ver($id) {
        $this->categoria = (new Categorias)->find($id);
    }

    /*
     * accion: editar
     * vista: app/views/categorias/editar.phtml
     * url: /categorias/editar/:id
     */
    public function editar($id) {
        $this->categoria = (new Categorias)->find((int) $id);

        if (!$this->categoria) {
            Flash::error('La categoría no existe');
            Redirect::to('categorias');
            return;
        }

        $this->setPage("Editar: {$this->categoria->nombre}", 'Modifica los datos de la categoría', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Categorías', 'url' => URL . 'categorias'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('categoria.nombre')) {
            $params = Input::post('categoria');

            $this->categoria->nombre      = trim($params['nombre']);
            $this->categoria->slug        = trim($params['slug'] ?? $this->categoria->slug);
            $this->categoria->descripcion = trim($params['descripcion'] ?? $this->categoria->descripcion);

            if ($this->categoria->nombre === '') {
                Flash::error('El nombre es obligatorio');
                return;
            }

            try {
                $guardada = $this->categoria->save();
            } catch (Throwable $e) {
                $guardada = false;
            }

            if ($guardada) {
                Flash::valid('Categoría actualizada correctamente');
            } else {
                Flash::error('No se pudo guardar la categoría. Revisa que el slug no esté repetido');
            }
        }
    }

    /*
     * accion: eliminar
     * url: /categorias/eliminar/:id (solo por POST)
     * No borra el registro: lo pone inactivo (activo = 0).
     * Todavia no hay ningun boton conectado a esta accion.
     */
    public function eliminar($id) {
        if (!Input::is('POST')) {
            Redirect::to('categorias/ver/' . (int) $id);
            return;
        }

        $categoria = (new Categorias)->find((int) $id);

        if (!$categoria) {
            Flash::error('La categoría no existe');
            Redirect::to('categorias');
            return;
        }

        $categoria->activo = 0;

        try {
            $desactivada = $categoria->save();
        } catch (Throwable $e) {
            $desactivada = false;
        }

        if (!$desactivada) {
            Flash::error('No se pudo desactivar la categoría');
            Redirect::to('categorias/ver/' . (int) $id);
            return;
        }

        Flash::valid('Categoría desactivada correctamente');
        Redirect::to('categorias');
    }
}