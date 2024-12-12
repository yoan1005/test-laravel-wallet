<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Api\V1\TransferRequest;
use Illuminate\Http\RedirectResponse;

class TransferController
{
    public function __invoke(TransferRequest $request): RedirectResponse
    {
        $recipientWallet = $request->getRecipient()->wallet;
        $myWallet = $request->user()->wallet;

        $myWallet->transfers()->create([
            'target_id' => $recipientWallet->id,
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'interval' => $request->input('interval'),
        ]);

        return redirect()->back()
        ->with('money-sent-status', 'success');
    }
}
