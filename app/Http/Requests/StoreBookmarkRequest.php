<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth handled by middleware
    }

    public function rules(): array
    {
        return [
            'url' => [
                'required', 
                'url', 
                'max:2048',
                // Unique per user
                'unique:bookmarks,url,NULL,id,user_id,' . auth()->id()
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['boolean'],
            'tags' => ['nullable', 'string'],           // comma-separated string
            'collection_ids' => ['nullable', 'array'],
            'collection_ids.*' => ['exists:collections,id'],
        ];
    }

    // Transform comma-separated tags into array
    public function passedValidation(): void
    {
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => array_filter(array_map('trim', explode(',', $this->tags))),
            ]);
        }
    }
}