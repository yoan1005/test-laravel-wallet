<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransferExecuted;
use App\Models\RecurringTransfer;
use App\Actions\PerformWalletTransfer;

class executeRecurringTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:execute-recurring-transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PerformWalletTransfer $performWalletTransfer)
    {
        // Get all recurring transfers that should be executed
        $transfers = RecurringTransfer::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();


        foreach($transfers as $transfer) {
            $check = TransferExecuted::where('transfer_id', $transfer->id)->whereDate('executed_at', now()->subDays($transfer->interval))->where('is_executed', true)->first();
            if($check) {
                continue;
            }
            try {
                $performWalletTransfer->execute(
                    sender: $transfer->source->user,
                    recipient: $transfer->target->user,
                    amount: $transfer->amount * 100,
                    reason: $transfer->reason
                );

                TransferExecuted::create([
                    'source_id' =>  $transfer->source_id,
                    'target_id' =>  $transfer->target_id,
                    'amount'=>  $transfer->amount * 100,
                    'transfer_id' => $transfer->id,
                    'is_executed' => true,
                    'executed_at' => now()
                ]);

                // notify the user
                // ...

            } catch (\Exception $e) {
                // Log the error
                TransferExecuted::create([
                    'source_id' =>  $transfer->source_id,
                    'target_id' =>  $transfer->target_id,
                    'amount'=>  $transfer->amount * 100,
                    'transfer_id' => $transfer->id,
                    'is_executed' => false,
                    'executed_at' => now(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
