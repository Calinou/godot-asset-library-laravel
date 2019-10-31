@php
// Set the variables' default values
$type = $type ?? 'text';
$required = $required ?? false;
$requiredImplicit = $requiredImplicit ?? false;
$placeholder = $placeholder ?? '';
$minlength = $minlength ?? 0;
$maxlength = $maxlength ?? 10000;
$autofocus = $autofocus ?? false;
$autocomplete = $autocomplete ?? 'on';
$class = $class ?? '';
// Will be displayed at the right of a label (optional slot variable)
$labelSuffix = $labelSuffix ?? '';

// Set the placeholder automatically for password fields
if ($type === 'password') {
  $placeholder = '****************';
}
@endphp

<div class="mb-6">
  @if ($labelSuffix)
  <div class="flex items-center justify-between">
  @endif

  <label for="{{ $name }}" class="form-label @if ($required && !$requiredImplicit) form-required @endif">
    {{ $label }}
  </label>

  @if ($labelSuffix)
    {{ $labelSuffix }}
  </div>
  @endif

  @php
  // Determine the HTML tag to use
  if ($type === 'textarea') {
    $tag = 'textarea';
  } else {
    $tag = 'input';
  }
  @endphp
  <{{ $tag }}
    @if ($required) required @endif
    @if ($autofocus) autofocus @endif
    id="{{ $name }}"
    type="{{ $type }}"
    name="{{ $name }}"
    @if ($type !== 'textarea') value="{{ $value ?? old($name) }}" @endif
    @if ($minlength) minlength="{{ $minlength }}" @endif
    maxlength="{{ $maxlength }}"
    autocomplete="{{ $autocomplete }}"
    placeholder="{{ $placeholder }}"
    class="form-input-text {{ $class }}"
  >@if ($type === 'textarea'){{ $value ?? old($name) }}</textarea> @endif

  @error($name)
  <div role="alert" class="form-error">
    {{ $message }}
  </div>
  @enderror

  {{-- Display form help if available --}}
  @if ($slot)
  <div class="mt-2 text-sm text-gray-600">
    {{ $slot }}
  </div>
  @endif
</div>
