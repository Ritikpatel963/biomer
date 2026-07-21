@php
    $isCurrentForm = old('form_type') === 'create' && $formKey === 'create'
        || old('form_type') === 'edit' && old('link_id') == optional($link)->id;

    $value = function (string $field, $fallback = '') use ($isCurrentForm, $link) {
        return $isCurrentForm ? old($field) : ($link->{$field} ?? $fallback);
    };
@endphp

<div class="row gy-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="section{{ $formKey }}">Section</label>
        <input class="form-control @if($isCurrentForm) @error('section') is-invalid @enderror @endif"
            id="section{{ $formKey }}"
            name="section"
            list="footerSections{{ $formKey }}"
            value="{{ $value('section', 'Products') }}"
            placeholder="Products, Company, Policies, Support"
            required>
        <datalist id="footerSections{{ $formKey }}">
            @foreach($sections as $section)
                <option value="{{ $section }}">
            @endforeach
        </datalist>
        @if($isCurrentForm)
            @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold" for="position{{ $formKey }}">Position</label>
        <input type="number"
            min="0"
            class="form-control @if($isCurrentForm) @error('position') is-invalid @enderror @endif"
            id="position{{ $formKey }}"
            name="position"
            value="{{ $value('position', 1) }}"
            required>
        @if($isCurrentForm)
            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold" for="label{{ $formKey }}">Label</label>
        <input class="form-control @if($isCurrentForm) @error('label') is-invalid @enderror @endif"
            id="label{{ $formKey }}"
            name="label"
            value="{{ $value('label') }}"
            placeholder="About Us"
            required>
        @if($isCurrentForm)
            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold" for="url{{ $formKey }}">URL or Route Name</label>
        <input class="form-control @if($isCurrentForm) @error('url') is-invalid @enderror @endif"
            id="url{{ $formKey }}"
            name="url"
            value="{{ $value('url') }}"
            placeholder="/about or frontend.blog.index"
            required>
        @if($isCurrentForm)
            @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold" for="target{{ $formKey }}">Target</label>
        <select id="target{{ $formKey }}" name="target" class="form-select">
            @php($target = $value('target', '_self'))
            <option value="_self" {{ $target === '_self' ? 'selected' : '' }}>Same Tab</option>
            <option value="_blank" {{ $target === '_blank' ? 'selected' : '' }}>New Tab</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold" for="status{{ $formKey }}">Status</label>
        <select id="status{{ $formKey }}" name="is_active" class="form-select">
            @php($status = (string) $value('is_active', 1))
            <option value="1" {{ $status === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>
