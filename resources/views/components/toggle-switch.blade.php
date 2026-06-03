@props([
    'name',
    'id' => $name,
    'checked' => false,
    'label' => null,
    'variant' => 'default',
    'disabled' => false,
])

<div class="toggle-wrapper">
    <input 
        type="checkbox" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        value="1" 
        class="toggle-input {{ $attributes->get("class") }}"
        {{ $checked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except("class") }}
    >
    <label for="{{ $id }}" class="toggle-switch toggle-switch--{{ $variant }} {{ $disabled ? 'toggle-switch--disabled' : '' }}">
        <span class="toggle-knob"></span>
    </label>
    @if($label)
        <label for="{{ $id }}" class="toggle-label">{{ $label }}</label>
    @endif
</div>

<style>
.toggle-wrapper {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

.toggle-input {
    display: none;
}

.toggle-label {
    font-size: 14px;
    color: #212121;
    user-select: none;
    cursor: pointer;
    margin-bottom: 0;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 58px;
    height: 24px;
    background: #c5ccd6;
    border-radius: 4px;
    border: none;
    padding: 0 3px;
    cursor: pointer;
    transition: all 0.3s ease-in;
    outline: none;
    box-sizing: border-box;
}

.toggle-switch:hover {
    background: #8f9aa5;
}

.toggle-input:checked + .toggle-switch {
    background: #1274e6;
}

.toggle-input:checked + .toggle-switch:hover {
    background: #0a45b5;
}

.toggle-knob {
    position: absolute;
    width: 18px;
    height: 18px;
    background: #ffffff;
    border-radius: 3px;
    display: block;
    top: 3px;
    left: 3px;
    transition: all 0.1s ease-in;
}

.toggle-input:checked + .toggle-switch .toggle-knob {
    transform: translateX(34px);
}

.toggle-switch--outlined {
    background: #fff;
    border: 1px solid #8f9aa5;
    padding: 0 4px;
}

.toggle-switch--outlined:hover {
    border: 1px solid #444d56;
}

.toggle-switch--outlined:hover .toggle-knob {
    background: #444d56;
}

.toggle-switch--outlined .toggle-knob {
    width: 16px;
    height: 16px;
    background: #c5ccd6;
    top: 4px;
    left: 4px;
}

.toggle-input:checked + .toggle-switch--outlined {
    border: 1px solid #1274e6;
    background: #1274e6;
}

.toggle-input:checked + .toggle-switch--outlined:hover {
    border: 1px solid #0a45b5;
    background: #0a45b5;
}

.toggle-input:checked + .toggle-switch--outlined .toggle-knob {
    background: #fff;
    transform: translateX(32px);
}

.toggle-switch--rounded {
    border-radius: 20px;
}

.toggle-switch--rounded .toggle-knob {
    border-radius: 20px;
}

.toggle-switch--disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.toggle-input:disabled + .toggle-switch {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
