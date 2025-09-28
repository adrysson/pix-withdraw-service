<?php

declare(strict_types=1);

namespace App\Presentation\Request;

use App\Domain\Enum\WithdrawalMethodType;
use Hyperf\Validation\Request\FormRequest;

class AccountWithdrawRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'method' => [
                'required',
                'string',
            ],
            'pix' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'array',
            ],
            'pix.type' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'string',
            ],
            'pix.key' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'string',
            ],
            'amount' => [
                'required',
                'numeric', 'min:0.01',
            ],
            'schedule' => [
                'nullable',
                'date_format:Y-m-d H:i',
            ],
        ];
    }
}
