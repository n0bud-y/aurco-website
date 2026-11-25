@props(['name', 'label', 'checked' => false])
<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) class="rounded border-slate-300 text-brand focus:ring-brand">
    {{ $label }}
</label>
