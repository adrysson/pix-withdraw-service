<?php

declare(strict_types=1);

namespace App\Presentation\Request;

use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Enum\PixKeyType;
use DateTime;
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
                'in:' . implode(',', array_map(fn($e) => $e->value, WithdrawalMethodType::cases())),
            ],
            'pix' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'array',
            ],
            'pix.type' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'string',
                'in:' . implode(',', array_map(fn($e) => $e->value, PixKeyType::cases())),
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

    public function accountId(): ?string
    {
        return $this->route('accountId');
    }

    public function methodType(): string
    {
        return $this->input('method');
    }

    public function methodData(): array
    {
        return $this->input(strtolower($this->methodType())) ?? [];
    }

    public function amount(): float
    {
        return (float) $this->input('amount');
    }

    public function schedule(): ?DateTime
    {
        $schedule = $this->input('schedule');
        if ($schedule) {
            return new DateTime($schedule);
        }
        return null;
    }
}
