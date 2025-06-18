<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Санал хураалт</h2>

        {{-- Live results container --}}
        <div id="live-results" class="space-y-3">
            @foreach ($results as $index => $c)
                <div class="flex bg-white border rounded-lg shadow-sm overflow-hidden" id="result-{{ $c->id }}">
                    <!-- Rank -->
                    <div class="w-12 bg-blue-600 text-white flex items-center justify-center text-xl font-bold">
                        {{ $index + 1 }}
                    </div>

                    <!-- Info -->
                    <div class="flex-1 p-4 flex justify-between items-center">
                        <div>
                            <div class="text-gray-900 font-semibold">{{ $c->name }}</div>
                            <div class="text-sm text-gray-900 font-semibold">{{ $c->organization_name ?? '' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-800">Санал: {{ $c->votes_count }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function updateVotes() {
            fetch('/admin/votes/live')
                .then(res => res.json())
                .then(data => {
                    // Саналын тоо ихээс бага руу эрэмбэлэх
                    data.sort((a, b) => b.votes_count - a.votes_count);

                    const container = document.getElementById('live-results');
                    container.innerHTML = ''; // Хуучин блокуудыг арилгана

                    data.forEach((c, index) => {
                        const div = document.createElement('div');
                        div.className = "flex bg-white border rounded-lg shadow-sm overflow-hidden";
                        div.id = `result-${c.id}`;
                        div.innerHTML = `
                            <div class="w-12 bg-blue-600 text-white flex items-center justify-center text-xl font-bold">
                                ${index + 1}
                            </div>
                            <div class="flex-1 p-4 flex justify-between items-center">
                                <div>
                                    <div class="text-gray-900 font-semibold">${c.name}</div>
                                    <div class="text-sm text-gray-900 font-semibold">${c.organization_name ?? ''}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-800">
                                        Санал: ${c.votes_count}
                                    </div>
                                </div>
                            </div>
                        `;
                        container.appendChild(div);
                    });
                })
                .catch(error => console.error("Алдаа:", error));
        }

        // 5 секунд тутам шинэчлэх
        setInterval(updateVotes, 5000);
    </script>
</x-app-layout>
