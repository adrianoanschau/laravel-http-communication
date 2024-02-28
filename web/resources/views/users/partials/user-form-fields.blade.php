<!-- First Name -->
<div>
    <x-input-label for="firstname" :value="__('First Name')" />
    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
</div>

<!-- Last Name -->
<div class="mt-4">
    <x-input-label for="lastname" :value="__('Last Name')" />
    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
</div>

<!-- Email Address -->
<div class="mt-4">
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
