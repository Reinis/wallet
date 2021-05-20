<?php

namespace App\Http\Requests;

use App\Models\Dto\StoreTransactionServiceRequest;
use App\Models\Wallet;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Wallet::find($this->get('source'))->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'source' => 'required|exists:wallets,id',
            'target' => 'required|integer|exists:wallets,id|different:source',
            'amount' => 'required|integer|gt:0',
            'currency' => 'required|string|max:3|regex:/^EUR$/',
            'notes' => 'nullable|string|max:255',
        ];
    }

    /**
     * Prepare data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            [
                'notes' => $this->get('notes') ?? '',
            ]
        );
    }

    public function validated(): StoreTransactionServiceRequest
    {
        $transaction = parent::validated();

        return new StoreTransactionServiceRequest(
            $transaction['source'],
            $transaction['target'],
            $transaction['amount'],
            $transaction['currency'],
            $transaction['notes'],
        );
    }
}
