<?php

namespace App\Http\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $contrasena;

    public function __construct($usuario, $contrasena)
    {
        $this->usuario    = $usuario;
        $this->contrasena = $contrasena;
    }

    public function build()
    {
        return $this->subject('Tus credenciales de acceso - E&M Laboratorio')
                    ->view('paciente.credenciales');
    }
}