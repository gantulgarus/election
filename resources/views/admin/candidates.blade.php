<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Бүх нэр дэвшигчид</h2>



        <div class="space-y-4">
            @foreach ($candidates as $c)
                <div class="bg-white shadow rounded-lg p-5 border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-lg font-semibold text-gray-900">{{ $c->user->name }}</p>
                            <p class="text-gray-700 mt-1">{{ $c->description }}</p>
                            <p class="mt-2 text-sm text-gray-500">Төлөв:
                                <span
                                    class="@if ($c->status === 'approved') text-green-600 @elseif($c->status === 'rejected') text-red-600 @else text-yellow-600 @endif font-medium">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </p>
                        </div>

                        @if ($c->status === 'pending')
                            <div class="flex flex-col gap-2">
                                <form method="POST" action="/admin/candidates/{{ $c->id }}/approve">
                                    @csrf
                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-1.5 rounded text-sm">
                                        Зөвшөөрөх
                                    </button>
                                </form>
                                <form method="POST" action="/admin/candidates/{{ $c->id }}/reject">
                                    @csrf
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-sm">
                                        Татгалзах
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
