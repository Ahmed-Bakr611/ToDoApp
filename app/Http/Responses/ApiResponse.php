<?php

namespace App\Http\Responses;

class ApiResponse
{
    public function __construct(
        public mixed $data = null,
        public string $message = '',
        public bool $status = true,
        public ?array $errors = null,
        public ?array $meta = null
    ) {}

    public function toArray(): array
    {
        $response = [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];

        if ($this->errors !== null) $response['errors'] = $this->errors;
        if ($this->meta !== null) $response['meta'] = $this->meta;

        return $response;
    }
}
