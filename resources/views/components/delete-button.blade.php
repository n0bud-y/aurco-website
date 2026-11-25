@props(['action', 'label' => 'Delete', 'confirm' => 'Delete this item? This cannot be undone.'])
<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $confirm }}')" class="inline">
    @csrf
    @method('DELETE')
    <button class="text-sm font-medium text-red-600 hover:text-red-700">{{ $label }}</button>
</form>
