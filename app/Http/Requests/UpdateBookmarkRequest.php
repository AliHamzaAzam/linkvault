<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('bookmark'));
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['boolean'],
            'tags' => ['nullable', 'string'],
            'collection_ids' => ['nullable', 'array'],
            'collection_ids.*' => ['exists:collections,id'],
        ];
    }

    public function passedValidation(): void
    {
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => array_filter(array_map('trim', explode(',', $this->tags))),
            ]);
        }
    }
}