<x-guest-layout>
    <form method="POST" action="{{ route('send.otp') }}">
        @csrf

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Утасны дугаар')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required
                autofocus />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('OTP илгээх') }}
            </x-primary-button>
        </div>
    </form>OTP илгээх
</x-guest-layout>
