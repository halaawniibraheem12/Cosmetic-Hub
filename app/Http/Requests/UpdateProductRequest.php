<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Security: ownership must not come from request
            'user_id' => ['prohibited'],

            // Product basic fields
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->route('product')->id),
            ],
            'price'       => 'required|numeric|min:0.01|decimal:0,2',
            'category_id' => 'required|exists:categories,id',

            // ✅ Image validation (optional)
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Suppliers (Many-to-Many)
            'suppliers' => ['required', 'array'],

            // checkbox
            'suppliers.*.selected' => ['nullable', 'in:1,on'],

            'suppliers.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'suppliers.*.lead_time_days' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $suppliers = $this->input('suppliers', []);
            $selectedIds = [];

            foreach ($suppliers as $supplierId => $data) {
                if (!empty($data['selected'])) {
                    $selectedIds[] = $supplierId;

                    if (!isset($data['cost_price']) || $data['cost_price'] === '') {
                        $validator->errors()->add(
                            "suppliers.$supplierId.cost_price",
                            'Cost price is required for selected supplier.'
                        );
                    }

                    if (!isset($data['lead_time_days']) || $data['lead_time_days'] === '') {
                        $validator->errors()->add(
                            "suppliers.$supplierId.lead_time_days",
                            'Lead time days is required for selected supplier.'
                        );
                    }
                }
            }

            if (count($selectedIds) === 0) {
                $validator->errors()->add(
                    'suppliers',
                    'Please select at least one supplier.'
                );
                return;
            }

            $existsCount = \App\Models\Supplier::whereIn('id', $selectedIds)->count();
            if ($existsCount !== count($selectedIds)) {
                $validator->errors()->add(
                    'suppliers',
                    'One or more selected suppliers do not exist.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'You cannot change the product owner.',

            'name.required'  => 'Product name is required.',
            'name.unique'    => 'This product name already exists.',
            'price.required' => 'Price is required.',
            'price.numeric'  => 'Price must be a valid number.',
            'price.min'      => 'Price must be greater than 0.',
            'price.decimal'  => 'Price must have up to 2 decimal places.',
            'category_id.required' => 'Category is required.',
            'category_id.exists'   => 'Selected category does not exist.',

            // Image messages
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Allowed formats: jpg, jpeg, png, webp.',
            'image.max'   => 'Image size must not exceed 2MB.',

            'suppliers.required' => 'Please select at least one supplier.',
            'suppliers.array'    => 'Suppliers data format is invalid.',

            'suppliers.*.cost_price.numeric' => 'Cost price must be a number.',
            'suppliers.*.cost_price.min'     => 'Cost price must be 0 or more.',
            'suppliers.*.lead_time_days.integer' => 'Lead time must be an integer.',
            'suppliers.*.lead_time_days.min'     => 'Lead time must be 0 or more.',
        ];
    }
}
