<?php

namespace App\Http\Requests;

use App\Models\Wallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TransactionPostRequest extends FormRequest
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
            'toWallet' => 'required|boolean',
            'amount' => 'required|integer|gt:0',
            'currency' => 'required|string|max:3|regex:/^EUR$/',
            'notes' => 'nullable|string|max:255',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->sometimes(
            'target',
            'required|string|max:255',
            fn($input) => !$input->toWallet
        );
        $validator->sometimes(
            'target',
            'required|integer|exists:wallets,id|different:source',
            fn($input) => $input->toWallet
        );
    }

    /**
     * Prepare data for validation.
     */
    public function prepareForValidation(): void
    {
        if ($this->get('toWallet')) {
            $this->merge(
                [
                    'target' => filter_var($this->get('target'), FILTER_VALIDATE_INT),
                ]
            );
        }
    }
}
