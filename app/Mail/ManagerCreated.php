<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ManagerCreated extends Mailable
{
    public $email;
    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this
            ->subject('Nuevo Administrador: Credenciales de Acceso')
            ->view('emails.manager_created')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
            ]);
    }
}
