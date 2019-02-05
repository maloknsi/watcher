<?php

namespace common\traits\access;

trait Allow
{

    public function rules()
    {
        return [
            [
                'allow' => true,
            ]
        ];
    }
}
