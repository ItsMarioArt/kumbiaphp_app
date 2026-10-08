<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todos las controladores heredan de esta clase en un nivel superior
 * por lo tanto los métodos aquí definidos están disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
abstract class AppController extends Controller
{
    /**
     * Se ejecuta antes de cada acción de cualquier controlador.
     * Define el template y los valores por defecto de la página.
     */
    final protected function initialize()
    {
        View::template('sparkadmin');

        // Valores por defecto (cada acción puede sobrescribirlos)
        $this->title       = 'Mi Sistema';
        $this->subtitle    = '';
        $this->description = 'Panel de administración';
        $this->breadcrumb  = [];
    }

    final protected function finalize()
    {
    }

    /**
     * Atajo para definir los datos de la página desde una acción.
     *
     * @param string $title      Título (h1 y <title>)
     * @param string $subtitle   Subtítulo opcional
     * @param array  $breadcrumb Migas de pan: [['label' => '...', 'url' => '...'], ...]
     */
    protected function setPage($title, $subtitle = '', array $breadcrumb = [])
    {
        $this->title      = $title;
        $this->subtitle   = $subtitle;
        $this->breadcrumb = $breadcrumb;
    }
}