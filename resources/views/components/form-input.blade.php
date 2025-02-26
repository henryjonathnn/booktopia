@props([
    'label',
    'type' => 'text',
    'error' => null,
    'isValidating' => false
])

<div class="space-y-1">
    <label class="text-sm text-gray-400">{{ $label }}</label>
    <div class="relative">
        <input {{ $attributes->merge([
            'class' => 'w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border ' . 
                      ($error ? 'border-red-500' : 'border-purple-500/10')
        ]) }}>
        
        @if($isValidating)
            <div class="absolute right-4 top-3.5">
                <div class="animate-spin rounded-full h-5 w-5 border-2 border-purple-500 border-t-transparent"></div>
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="text-sm text-red-500">{{ $error }}</p>
    @endif
</div> 