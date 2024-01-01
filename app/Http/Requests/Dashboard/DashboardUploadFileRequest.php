<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardUploadFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:png,jpg,jpeg,gif,mp4',
            'type' => 'required'
        ];
    }
}
