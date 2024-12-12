<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecurringTransfer;

class DashboardController
{
    public function dashboard(Request $request)
    {
        
        $transactions = $request->user()->wallet->transactions()->with('transfer')->orderByDesc('id')->get();
        $balance = $request->user()->wallet->balance;

        return view('dashboard', compact('transactions', 'balance'));
    }

    public function recurringTransfer(Request $request)
    {   
        $balance = $request->user()->wallet->balance;
        $transfers = $request->user()->wallet->transfers()->get();

        return view('transfer', compact('balance', 'transfers'));
    }
}
