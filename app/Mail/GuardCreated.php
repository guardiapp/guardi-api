<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GuardCreated extends Mailable
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
            ->subject('Nuevo Usuario: Credenciales de Acceso')
            ->view('emails.guard_created')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
            ]);
    }
}
