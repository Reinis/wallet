<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Dto\StoreWalletServiceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => 'integer|gte:0',
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
        ];
    }

    /**
     * Prepare data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            [
                'description' => $this->get('description') ?? '',
            ]
        );
    }

    public function validated(): StoreWalletServiceRequest
    {
        $wallet = parent::validated();

        return new StoreWalletServiceRequest(
            $wallet['name'],
            $wallet['description'],
            $wallet['id'],
        );
    }
}
