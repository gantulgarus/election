<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Нэр дэвшигчид</h2>

        {{-- Амжилт / алдааны мессежүүд --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @php
            $setting = \App\Models\Setting::first();
            $canVote = $setting && now()->between($setting->voting_start, $setting->voting_end);
            $index = 1;
        @endphp

        <div class="space-y-4">
            @forelse ($candidates as $candidate)
                <div class="bg-white border rounded-lg shadow-sm p-4 md:flex md:items-center md:justify-between">
                    {{-- Дугаар --}}
                    <div class="text-2xl font-bold text-gray-700 md:w-12 md:text-center mb-2 md:mb-0">
                        {{ $index++ }}.
                    </div>

                    {{-- Нэр, тайлбар --}}
                    <div class="flex-1 md:px-4">
                        <p class="text-lg font-semibold text-gray-800">{{ $candidate->name }}</p>
                        <p class="text-gray-600 text-sm mt-1">{{ $candidate->organization_name ?? '' }}</p>
                    </div>

                    {{-- Санал өгөх хэсэг --}}
                    <div class="mt-3 md:mt-0">
                        @if (!$canVote)
                            <span class="inline-block bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded">
                                Санал хураалт идэвхгүй байна
                            </span>
                        @elseif (!in_array($candidate->id, $userVotes) && $voteCount < 30)
                            <form method="POST" action="{{ route('vote', $candidate->id) }}">
                                @csrf
                                <button
                                    class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded">
                                    Санал өгөх
                                </button>
                            </form>
                        @else
                            <span class="inline-block bg-gray-100 text-gray-500 text-sm px-3 py-1 rounded">
                                Санал өгсөн
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-600">Одоогоор нэр дэвшигч алга байна.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
