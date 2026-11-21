<?php
return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
        'string' => 'El campo :attribute no debe exceder :max caracteres.',
    ],
    'after_or_equal' => 'La fecha del evento debe ser igual o posterior a :date.',
    'attributes' => [
        'invitados' => 'cantidad de invitados',
        'tipo_evento' => 'tipo de evento',
        'fecha_evento' => 'fecha del evento',
        'nombre' => 'nombre completo',
        'celular' => 'celular',
        'correo' => 'correo electrónico',
        'motivo' => 'motivo del rechazo',
    ],
];
