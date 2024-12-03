<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;

class AccountController
{
    public function __invoke(Request $request): AccountResource
    {
        return AccountResource::make($request->user());
    }
}
