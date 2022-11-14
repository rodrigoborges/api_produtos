<?php

namespace App\Http\Requests;

use App\Http\Requests\AbstractRequest;

class ProdutoUpdateRequest extends AbstractRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|unique:produtos,name,{$this->produto->id}|max:100",
            'price' => 'required|numeric',
            'description' => 'required',
            'category' => 'required|max:100',
            'image_url' => 'url|nullable',
        ];
    }

}
