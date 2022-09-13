@props(['name', 'label', 'fieldType'])

<div class="form-group">
    <input type="{{ $fieldType }}" class="form-control form-control-user" id="{{ $name }}"
        name="{{ $name }}" aria-describedby="{{ $name }}" placeholder="{{ $label }}">
    {{ $slot }}
</div>
