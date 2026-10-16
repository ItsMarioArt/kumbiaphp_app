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
     * Diseno de funciones para
     * boton guardar
     * boton cancelar
     * boton eliminar
     * boton actualizar
     *
     * enlaces
     */
}
