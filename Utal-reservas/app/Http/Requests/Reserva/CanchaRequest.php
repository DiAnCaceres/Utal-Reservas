<?php

namespace App\Http\Requests\Reserva;

use Illuminate\Foundation\Http\FormRequest;

class CanchaRequest extends FormRequest
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
        // return [
        //     "nombre_ubicacion"=>["required",],
        //     "nombre"=>["required","max:30","unique:reservas,nombre"],
        // ];
        switch ($this->route()->getActionMethod()) {
            case 'post_reservar':
                return [
                    'fecha' => 'required|string',
                    'bloques' => 'required'
                ];
            case 'post_registrar':
                return [
                    "nombre_ubicacion"=>["required"],
                    "nombre"=>["required","max:30","unique:reservas,nombre"]
                ];
            default:
                return [];
        }
    }
    public function messages()
    {
        return[
            "nombre.required"=>"El campo :attribute es obligatorio.",
            'nombre.unique' => 'El nombre ya existe en la tabla.',
            "nombre.required"=>"El campo :attribute es obligatorio.",
            "nombre.max"=>'El campo :attribute no puede tener más de :max caracteres.',
            "fecha.required" => "El campo :attribute es obligatorio.",
            "bloques.required" => "El campo :attribute es obligatorio.",
        ];
    }
}
