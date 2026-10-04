@props(['disabled' => false])

{{-- Shared text field. Focus ring uses the brand teal so every form in the app
     matches the Career Booster palette. --}}
<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#138A9E] focus:ring-[#138A9E] rounded-md shadow-sm']) }}>
