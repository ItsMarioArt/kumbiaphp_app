<?php

/*
 * file:  app/extensions/helpers/Myhtml.php
 * class: Myhtml
 *
 * Helper con utilidades para generar etiquetas html rápidas
 * desde las vistas (img, enlaces, etc). Pensado para no
 * estar escribiendo URL a mano todo el rato.
 *
 * Uso típico en una vista .phtml:
 *   <?= Myhtml::img("productos/1.jpg", "Teclado", ["width" => "64px"]) ?>
 *   <?= Myhtml::enlace("productos/ver/1", "Ver detalle", ["class" => "btn btn-info"]) ?>
 */
class Myhtml{

    /*
     * Mezcla los atributos pasados por el usuario con los que
     * vienen por defecto. Si el atributo ya existe no lo reemplaza,
     * solo le agrega el valor default al final (para no perder
     * clases css que ya trajera).
     */
    protected static function attrsdefaut($attrs, $defaults)
    {
        foreach ($defaults as $k => $v) {
            if (isset($attrs[$k])) {
                if (strpos($attrs[$k], $v) === false) {
                    $attrs[$k] .= ' ' . $v;
                }
            } else {
                $attrs[$k] = $v;
            }
        }
        return $attrs;
    }

    /*
     * Crea una etiqueta <img> apuntando a la carpeta storage.
     * El $src es relativo a storage/, no hace falta poner la ruta completa.
     *
     * ejemplo:
     *   Myhtml::img("frutas/1.jpg", "Fruta", ["width" => "64px"])
     * devuelve:
     *   <img src="/storage/frutas/1.jpg" alt="Fruta" width="64px"/>
     */
    public static function img($src, $alt = '', $attrs = [])
    {
        $attrs = Myhtml::attrsdefaut($attrs, ["class" => ""]);
        $attrs = Tag::getAttrs($attrs);
        return '<img src="' . URL . "storage/$src\" alt=\"$alt\" " . $attrs. '/>';
    }

    /*
     * Crea un enlace interno de la aplicación. Le agrega un
     * emoji 🔗 al texto para distinguirlo de un link normal.
     *
     * ejemplo:
     *   Myhtml::enlace("productos", "Listado de productos")
     * devuelve algo como:
     *   <a href="http://localhost/kumbiaphp/productos">🔗Listado de productos</a>
     */
    public static function enlace($action,$text, $attrs = [])
    {
        $text = "" . $text;
        $attrs = Myhtml::attrsdefaut($attrs, ["class" => ""]);
        $attrs = Tag::getAttrs($attrs);
        return Html::link($action,$text, $attrs);
    }

    /*
     * Boton "Editar": enlace interno con apariencia de boton
     * (amarillo de Bootstrap por defecto).
     *
     * ejemplo:
     *   Myhtml::btn_editar("productos/editar/1")
     *   Myhtml::btn_editar("productos/editar/1", "Editar ✏️", ["class" => "btn-lg"])
     */
    public static function btn_editar($action, $text = "Editar ✏️", $attrs = [])
    {
        $attrs = Myhtml::attrsdefaut($attrs, ["class" => "btn btn-warning"]);
        return Html::link($action, $text, Tag::getAttrs($attrs));
    }

    /*
     * Boton "Regresar": enlace interno con apariencia de boton
     * (gris con borde, discreto).
     *
     * ejemplo:
     *   Myhtml::btn_regresar("productos")
     *   Myhtml::btn_regresar("productos", "Volver", ["class" => "btn-lg"])
     */
    public static function btn_regresar($action, $text = "Regresar", $attrs = [])
    {
        $attrs = Myhtml::attrsdefaut($attrs, ["class" => "btn btn-outline-secondary"]);
        return Html::link($action, $text, Tag::getAttrs($attrs));
    }

    /*
     * Boton "Eliminar": por ahora solo diseno. Es un <button type="button">
     * sin enlace ni formulario, asi que no ejecuta ninguna accion.
     *
     * ejemplo:
     *   Myhtml::btn_eliminar()
     *   Myhtml::btn_eliminar("Eliminar 🗑️", ["class" => "btn-lg"])
     */
    public static function btn_eliminar($text = "Eliminar 🗑️", $attrs = [])
    {
        $attrs = Myhtml::attrsdefaut($attrs, ["class" => "btn btn-outline-danger"]);
        return '<button type="button" ' . Tag::getAttrs($attrs) . ">$text</button>";
    }

    /*
     * Diseno de funciones para
     * boton guardar
     * boton cancelar
     * boton eliminar
     * boton actualizar
     *
     * enlaces
     */
}
