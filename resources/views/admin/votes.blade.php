<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Санал хураалтын үр дүн</h2>
                @isset($setting)
                    @if (now()->between($setting->voting_start, $setting->voting_end))
                        <p class="text-green-600 font-semibold mt-1">Санал хураалт <span class="underline">идэвхтэй</span></p>
                    @else
                        <p class="text-red-600 font-semibold mt-1">Санал хураалт <span class="underline">идэвхгүй</span></p>
                    @endif
                @else
                    <p class="text-yellow-600 font-semibold mt-1">Тохиргоо хийгдээгүй байна</p>
                @endisset
            </div>

            <div class="flex flex-wrap gap-2">
                {{-- Цэвэрлэх товч --}}
                <form method="POST" action="{{ route('voting.reset') }}"
                    onsubmit="return confirm('Та санал хураалтыг шинээр эхлүүлэхдээ итгэлтэй байна уу?')">
                    @csrf
                    <button class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                        Цэвэрлэх
                    </button>
                </form>

                {{-- Эхлүүлэх эсвэл Дуусгах товчийг харуулах --}}
                @isset($setting)
                    @if (now()->between($setting->voting_start, $setting->voting_end))
                        {{-- Дуусгах товч --}}
                        <form method="POST" action="{{ route('voting.end') }}"
                            onsubmit="return confirm('Та санал хураалтыг дуусгахдаа итгэлтэй байна уу?')">
                            @csrf
                            <button
                                class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-4 py-2 rounded-lg shadow">
                                Санал хураалт дуусгах
                            </button>
                        </form>
                    @else
                        {{-- Эхлүүлэх товч --}}
                        <form method="POST" action="{{ route('voting.start') }}"
                            onsubmit="return confirm('Санал хураалтыг эхлүүлэх үү?')">
                            @csrf
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                                Санал хураалт эхлүүлэх
                            </button>
                        </form>
                    @endif
                @endisset
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="px-4 py-3 border-b">Нэр</th>
                        <th class="px-4 py-3 border-b">Байгууллага</th>
                        <th class="px-4 py-3 border-b">Саналын тоо</th>
                    </tr>
                </thead>
                <tbody id="results-body" class="text-gray-800 divide-y divide-gray-200">
                    @foreach ($results as $c)
                        <tr id="row-{{ $c->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2">{{ $c->name }}</td>
                            <td class="px-4 py-2">{{ $c->organization_name }}</td>
                            <td class="px-4 py-2 font-semibold" id="votes-{{ $c->id }}">{{ $c->votes_count }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</x-app-layout>

<script>
    function updateVotes() {
        fetch('/admin/votes/live')
            .then(res => res.json())
            .then(data => {
                // Саналын тоо ихээс бага руу эрэмбэлэх
                data.sort((a, b) => b.votes_count - a.votes_count);

                const tbody = document.getElementById('results-body');
                tbody.innerHTML = ''; // Хуучин мөрүүдийг цэвэрлэх

                // Шинэчилсэн өгөгдлөөр мөр нэмэх
                data.forEach(c => {
                    const row = document.createElement('tr');
                    row.id = `row-${c.id}`;
                    row.className = 'hover:bg-gray-50 transition-colors';
                    row.innerHTML = `
                        <td class="px-4 py-2">${c.name}</td>
                        <td class="px-4 py-2">${c.organization_name}</td>
                        <td class="px-4 py-2 font-semibold" id="votes-${c.id}">${c.votes_count}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error("Алдаа:", error));
    }

    // 5 секунд тутам шинэчлэх
    setInterval(updateVotes, 5000);
</script>
