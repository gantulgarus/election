<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ $user->name }} - {{ __('саналын түүх') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="p-6">
                @if ($votes->isEmpty())
                    <p class="text-gray-500">{{ __('Саналын бүртгэл алга байна.') }}</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3">{{ __('Нэр дэвшигч') }}</th>
                                <th class="px-6 py-3">{{ __('Огноо') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($votes as $vote)
                                <tr>
                                    <td class="px-6 py-4">{{ $vote->candidate->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $vote->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
