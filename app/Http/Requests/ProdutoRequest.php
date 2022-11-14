<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class ProdutoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:produtos|max:100',
            'price' => 'required|numeric',
            'description' => 'required',
            'category' => 'required|max:100',
            'image_url' => 'url|nullable',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Erros de validação',
            'data'      => $validator->errors()
        ], 400));
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo name é obrigatório.',
            'name.max' => 'O campo name não deve ser maior que 100 caracteres.',
            'name.unique' => 'O campo name deve ser único. Já existe registro com esse valor.',
            'price.required' => 'O campo price é obrigatório.',
            'price.numeric' => 'O campo price deve ser um número.',
            'description.required' => 'O campo description é obrigatório.',
            'category.required' => 'O campo category é obrigatório.',
            'category.max' => 'O campo category não deve ser maior que 100 caracteres.',
            'image_url.url' => 'O campo image_url deve ser uma URL válida.'
        ];
    }

}
