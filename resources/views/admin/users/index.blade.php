<x-app-layout>
    <x-slot name="header">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('Хэрэглэгчийн жагсаалт') }}</h2>
            <a href="{{ route('admin.users.create') }}"
                class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Шинэ хэрэглэгч нэмэх
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                            <tr>
                                <th class="px-6 py-3">{{ __('Нэр') }}</th>
                                <th class="px-6 py-3">{{ __('Имэйл') }}</th>
                                <th class="px-6 py-3">{{ __('Утас') }}</th>
                                <th class="px-6 py-3">{{ __('Баталгаажсан') }}</th>
                                <th class="px-6 py-3">{{ __('Хандах эрх') }}</th>
                                <th class="px-6 py-3">{{ __('Үйлдлүүд') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->phone }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $user->is_verified ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $user->is_verified ? 'Тийм' : 'Үгүй' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $user->is_admin ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $user->is_admin ? 'Админ' : 'Хэрэглэгч' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex items-center space-x-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-blue-600 hover:underline text-sm font-medium">
                                            {{ __('Засах') }}
                                        </a>
                                        <a href="{{ route('admin.users.votes', $user) }}"
                                            class="text-indigo-600 hover:underline text-sm font-medium">
                                            {{ __('Саналын түүх') }}
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Устгахдаа итгэлтэй байна уу?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline text-sm font-medium">
                                                {{ __('Устгах') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('Хэрэглэгч олдсонгүй.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
