<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class ProductRequest extends Request
{
    public function commonRules() : array
    {
        return [
            'name' => 'required|min:3',
            'description' => 'nullable|max:2000',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ];
    }
}