<?php

namespace App\Http\Requests\Reserva;

use Illuminate\Foundation\Http\FormRequest;

class ImplementoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "nombre_ubicacion"=>["required"],
            "categoria"=>["required"],
            "nombre_estado"=>["required","max:30"],
            "nombre"=>["required","max:30","unique:reservas,nombre"],
            "cantidad"=>["required","max:11","integer"]
            // 'email' => 'required|email|unique:users,email_address'
        ];
    }
    public function messages()
    {
        return[
            "nombre.required"=>"El campo :attribute es obligatorio.",
            "nombre_estado.required"=>"El campo :attribute es obligatorio.",
            "categoria.required"=>"El campo :attribute es obligatorio.",
            "nombre.required"=>"El campo :attribute es obligatorio.",
            'nombre.unique' => 'El nombre ya existe en la tabla.',
            "cantidad.required"=>"El campo :attribute es obligatorio.",
            "nombre.max"=>'El campo :attribute no puede tener más de :max caracteres.',
            "nombre_estado.max"=>'El campo :attribute no puede tener más de :max caracteres.',
            "cantidad.integer"=>'El campo :attribute debe ser un número entero.'
        ];
    }
}
