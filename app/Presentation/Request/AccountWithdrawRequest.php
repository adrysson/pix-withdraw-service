<?php

declare(strict_types=1);

namespace App\Presentation\Request;

use DateTime;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
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
        $method = $this->input('method');
        $config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $methodsConfig = $config->get('withdrawal-methods');
        $rules = [
            'method' => [
                'required',
                'string',
                'in:' . implode(',', array_keys($methodsConfig)),
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
        if ($method && isset($methodsConfig[$method]['validation'])) {
            $rules = array_merge($rules, $methodsConfig[$method]['validation']);
        }
        return $rules;
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
