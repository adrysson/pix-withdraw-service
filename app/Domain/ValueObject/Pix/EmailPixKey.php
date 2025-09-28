<?php

namespace App\Domain\ValueObject\Pix;

use App\Domain\Enum\PixKeyType;

class EmailPixKey extends PixKey
{
    public function keyType(): PixKeyType
    {
        return PixKeyType::EMAIL;
    }

    public function isValid(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function errorMessage(): string
    {
        return 'O valor da chave pix deve ser um e-mail válido';
    }
}
