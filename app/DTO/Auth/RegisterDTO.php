<?php

namespace App\DTO\Auth;

use Spatie\DataTransferObject\DataTransferObject;

class RegisterDTO extends DataTransferObject
{
    public string $nom;
    public string $email;
    public string $password;
    public string $password_confirmation;
}
