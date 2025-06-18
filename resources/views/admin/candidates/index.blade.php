<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Нэр дэвшигчид</h2>
            <a href="{{ route('admin.candidates.create') }}"
                class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Нэр дэвшигч нэмэх
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase">Нэр</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase">Имэйл</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase">Байгууллага</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase">Төлөв</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($candidates as $candidate)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $candidate->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $candidate->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $candidate->organization_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                    @if ($candidate->status == 'approved') bg-green-100 text-green-800
                                    @elseif($candidate->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($candidate->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('admin.candidates.edit', $candidate) }}"
                                    class="inline-block text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Засах
                                </a>
                                <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Устгах уу?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        Устгах
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
