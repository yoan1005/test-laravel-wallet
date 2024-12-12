<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <div class="text-base text-gray-400">@lang('Balance')</div>
                <div class="flex items-center pt-1">
                    <div class="text-2xl font-bold text-gray-900">
                        {{ \Illuminate\Support\Number::currencyCents($balance) }}
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <h2 class="text-xl font-bold mb-6">@lang('Créer un transfert récurrent')</h2>
                <form method="POST" action="{{ route('create-transfer') }}" class="space-y-4">
                    @csrf

                    @if (session('money-sent-status') === 'success')
                        <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                            <span class="font-medium">@lang('Money sent!')</span>
                            @lang(':amount were successfully sent to :name.', ['amount' => Number::currencyCents(session('money-sent-amount', 0)), 'name' => session('money-sent-recipient-name')])
                        </div>
                    @elseif (session('money-sent-status') === 'insufficient-balance')
                            <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                <span class="font-medium">@lang('Insufficient balance!')</span>
                                @lang('You can\'t send :amount to :name.', ['amount' => Number::currencyCents(session('money-sent-amount', 0)), 'name' => session('money-sent-recipient-name')])
                            </div>
                    @endif
                            
                    <div>
                        <x-input-label for="recipient_email" :value="__('Recipient email')" />
                        <x-text-input id="recipient_email"
                                      class="block mt-1 w-full"
                                      type="email"
                                      name="recipient_email"
                                      :value="old('recipient_email')"
                                      required />
                        <x-input-error :messages="$errors->get('recipient_email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="start_date" :value="__('Start Date')" />
                        <x-text-input id="start_date"
                                      class="block mt-1 w-full"
                                      type="date"
                                      name="start_date"
                                      :value="old('start_date')"
                                      required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="end_date" :value="__('End Date')" />
                        <x-text-input id="end_date"
                                      class="block mt-1 w-full"
                                      type="date"
                                      name="end_date"
                                      :value="old('end_date')"
                                      required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="interval" :value="__('Interval in days')" />
                        <x-text-input id="interval"
                                      class="block mt-1 w-full"
                                      type="number"
                                      min="1"
                                      :value="old('interval')"
                                      name="interval"
                                      required />
                        <x-input-error :messages="$errors->get('interval')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="amount" :value="__('Amount (€)')" />
                        <x-text-input id="amount"
                                      class="block mt-1 w-full"
                                      type="number"
                                      min="0"
                                      step="0.01"
                                      :value="old('amount')"
                                      name="amount"
                                      required />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="reason" :value="__('Reason')" />
                        <x-text-input id="reason"
                                      class="block mt-1 w-full"
                                      type="text"
                                      :value="old('reason')"
                                      name="reason"
                                      required />
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-4">
                        <x-primary-button>
                            {{ __('Créer le transfert récurent !') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <h2 class="text-xl font-bold mb-6">
                    Transfert programmés
                </h2>
                <table class="w-full text-sm text-left text-gray-500 border border-gray-200">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            @lang('ID')
                        </th>
                        <th scope="col" class="px-6 py-3">
                            @lang('Start date')
                        </th>
                        <th scope="col" class="px-6 py-3">
                            @lang('End date')
                        </th>
                        <th scope="col" class="px-6 py-3">
                            @lang('Frequency')
                        </th>
                        <th scope="col" class="px-6 py-3">
                            @lang('Recipient email')
                        </th>
                        <th scope="col" class="px-6 py-3">
                            @lang('Amount')
                        </th>
                        <th scope="col" class="px-6 py-3">
                            @lang('Reason')
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($transfers as $transfer)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->start_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->end_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->interval }} jours
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->target->user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->amount }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transfer->reason }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
