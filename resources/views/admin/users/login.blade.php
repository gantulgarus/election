{{-- resources/views/admin/login.blade.php --}}
<x-guest-layout>
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Имэйл')" />
            <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Нууц үг')" />
            <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required />
        </div>

        <div class="mt-4">
            <x-primary-button>{{ __('Нэвтрэх') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
