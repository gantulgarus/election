<x-guest-layout>
    <form method="POST" action="{{ route('verifyOtp') }}">
        @csrf

        <input type="hidden" name="phone" value="{{ old('phone', $phone ?? '') }}" />

        <div>
            <x-input-label for="otp_code" :value="__('OTP код оруулах')" />
            <x-text-input id="otp_code" class="block mt-1 w-full" type="text" name="otp_code" required autofocus />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('OTP баталгаажуулах') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
