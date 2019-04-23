<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class StripTags extends TransformsRequest {

    /**
     * The attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'models', 
        'model',
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value) {
        if (in_array($key, $this->except, true)) {
            return $value;
        }
        $result = $value;
        if (is_string($value)) {
            $result = strip_tags($value);
        }
        if (is_array($value)) {
            $result = [];
            foreach ($value as $k => $val) {
                $result[$k] = $this->transform($key . '.' . $k, $val);
            }
        }
        return $result;
    }

}
