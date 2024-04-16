<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case PIX = 'P';
    case CREDIT_CARD = 'C';
    case DEBIT_CARD = 'D';
}
