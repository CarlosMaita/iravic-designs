<?php
/**
 * Created by PhpStorm.
 * User: smiith
 * Date: 15/7/2018
 * Time: 2:59 AM
 */

namespace App\Services\Menu;

class MenuServices
{
    /**
     * Retorna si se esta en una url base. Puede aceptar parametro para validar que la url sea exacta (Sin parametros query)
     */
    public function isActive($url, $other, $exact = false, $dropdown = false)
    {
        $result = "";
        if ($exact) {
            if ($other === $url) {
                $result = !$dropdown ? "c-active" : 'c-show';
            }
        } else {
            $url = substr($url, 1);
            $other = substr($other, 1);
            $other = str_replace("/", '\/', $other);
            $pattern = '/^' . $other . '/i';
            if (preg_match($pattern, $url)) {
                $result = !$dropdown ? "c-active" : 'c-show';
            }
        }

        return $result;
    }
}