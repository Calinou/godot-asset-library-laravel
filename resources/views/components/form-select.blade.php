<div class="mb-6">
  <label for="{{ $name }}" class="form-label @if ($required) form-required @endif">
    {{ $label }}
  </label>

  <div class="inline-block relative">
    <select
      @if ($required) required @endif
      id="{{ $name }}"
      name="{{ $name }}"
      class="block appearance-none w-full bg-white shadow border rounded px-3 py-2 pr-8 leading-tight text-sm hover:border-gray-500 focus:outline-none focus:shadow-outline"
    >
      <option disabled>
        {{ $placeholder }}
      </option>
      @foreach ($choices as $key => $label)
      <option value="{{ $key }}" @if ($key === ($value ?? old('name'))) selected @endif>
        {{ $label }}
      </option>
      @endforeach
    </select>

    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
      </svg>
    </div>
  </div>

  @error($name)
  <div role="alert" class="form-error">
    {{ $message }}
  </div>
  @enderror
</div>
