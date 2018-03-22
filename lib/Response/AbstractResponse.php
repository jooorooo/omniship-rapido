<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 29.6.2017 г.
 * Time: 13:40 ч.
 */

use Omniship\Helper\Helper;

class AbstractResponse implements JsonSerializable
{

    /**
     * @return array
     */
    public function toArray() {
        $reflection = new ReflectionClass($this);
        $return = [];
        foreach($reflection->getProperties() AS $property) {
            $value = $this->{$property->getName()};
            if(is_object($value) && method_exists($value, 'toArray')) {
                $value = $value->toArray();
            } else if(is_array($value)) {
                $tmp = [];
                foreach ($value AS $key => $v) {
                    $tmp[$key] = is_object($v) && method_exists($v, 'toArray') ? $v->toArray() : $v;
                }
                $value = $tmp;
            }
            $return[substr(Helper::snake($property->getName()), 1)] = $value;

        }
        return $return;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif (is_object($value) && method_exists($value, 'toArray')) {
                return $value->toArray();
            } else {
                return $value;
            }
        }, $this->toArray());
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

}