@extends('layouts.app')
@inject('assetClass', 'App\Asset')

@section('title', __('Submit an asset'))

@section('content')
<div class="container">
    <form method="POST" action="{{ route('asset.store') }}">
      @csrf

      <div class="text-center text-xl font-medium">
        {{ __('Submit an asset to Godot Asset Library') }}
      </div>

      <section class="w-full max-w-md mx-auto mt-8">
        <div class="mb-6">
          <label for="title" class="form-label form-required">{{ __('Asset name') }}</label>
          <input
            required
            autofocus
            id="title"
            name="title"
            value="{{ old('title') }}"
            autocomplete="off"
            placeholder="{{ __("My Own Asset") }}"
            class="form-input-text"
          >
          @error('title')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>

        <div class="mb-6">
          <label for="blurb" class="form-label">{{ __('Blurb') }}</label>
          <input
            id="blurb"
            name="blurb"
            value="{{ old('blurb') }}"
            autocomplete="off"
            placeholder="{{ __('One-line description of the asset') }}"
            class="form-input-text"
          >
          @error('blurb')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>

        <div class="mb-6">
          <label for="description" class="form-label form-required">{{ __('Description') }}</label>
          <textarea
            required
            id="description"
            name="description"
            autocomplete="off"
            class="form-input-text h-64"
            placeholder="{{ __('Full description that spans multiple lines…') }}"
          >{{ old('description') }}</textarea>
          @error('description')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>

        <div class="mb-6 sm:flex sm:justify-between">
          <div class="mb-6 sm:mb-0">
            <label for="category" class="form-label form-required">{{ __('Category') }}</label>
            <select
              required
              id="category"
              name="category_id"
            >
              <option disabled selected>{{ __('Select a category') }}</option>
              @foreach (range(0, $assetClass::CATEGORY_MAX - 1) as $categoryId)
                <option value="{{ $categoryId }}">{{ $assetClass::getCategoryName($categoryId) }}</option>
              @endforeach
            </select>
            @error('category_id')
            <div role="alert" class="form-error">
              {{ $message }}
            </div>
            @enderror
          </div>

          <div>
            <label for="license" class="form-label form-required">{{ __('License') }}</label>
            <select
              required
              id="license"
              name="cost"
            >
            <option disabled selected>{{ __('Select a license') }}</option>
              @foreach ($assetClass::LICENSES as $licenseSpdx => $licenseName)
                <option value="{{ $licenseSpdx }}">{{ $licenseName }}</option>
              @endforeach
            </select>
            @error('cost')
            <div role="alert" class="form-error">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        <div class="mb-6 sm:flex sm:justify-between">
          <div class="mb-6 sm:mb-0">
            <label for="version-string" class="form-label form-required">{{ __('Asset version') }}</label>
            <input
              required
              id="version-string"
              name="versions[0][version_string]"
              value="{{ old('version_string') }}"
              autocomplete="off"
              placeholder="{{ __('1.0.0') }}"
              class="form-input-text"
            >
            @error('versions[0][version_string]')
            <div role="alert" class="form-error">
              {{ $message }}
            </div>
            @enderror
          </div>
          <div>
            <label for="godot-version" class="form-label form-required">{{ __('Godot version') }}</label>
            <select
              required
              id="godot-version"
              name="versions[0][godot_version]"
            >
              <option disabled selected>{{ __('Select a Godot version') }}</option>
              <option value="3.2">Godot 3.2</option>
              <option value="3.1">Godot 3.1</option>
              <option value="3.0">Godot 3.0</option>
            </select>
            @error('versions[0][godot_version]')
            <div role="alert" class="form-error">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        <div class="mb-6">
          <label for="browse-url" class="form-label form-required">{{ __('Repository URL') }}</label>
          <input
            required
            id="browse-url"
            name="browse_url"
            value="{{ old('browse_url') }}"
            autocomplete="off"
            placeholder="{{ __('https://github.com/user/asset') }}"
            class="form-input-text"
          >
          @error('browse_url')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
          <div class="form-help">
            {{ __('This must be an URL to a public GitHub, GitLab or Bitbucket repository.') }}
          </div>
        </div>

        <div class="mb-6">
          <label for="download-url" class="form-label">{{ __('Download URL') }}</label>
          <input
            id="download-url"
            name="versions[0][download_url]"
            value="{{ old('download_url') }}"
            autocomplete="off"
            placeholder="{{ __('https://github.com/user/asset/archive/v1.0.0.zip') }}"
            class="form-input-text"
          >
          @error('versions[0][download_url]')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
          <div class="form-help">
            {{ __('If you leave this field empty, the download URL will be inferred from the repository URL and the asset version.') }}<br>
            {{ __('For example, if the asset version is "1.0.0", the ZIP archive corresponding to the Git tag "v1.0.0" will be used (note the leading "v").') }}
          </div>
        </div>

        <div class="mb-6">
          <label for="issues-url" class="form-label">{{ __('Issues URL') }}</label>
          <input
            id="issues-url"
            name="issues_url"
            value="{{ old('issues_url') }}"
            autocomplete="off"
            placeholder="{{ __('https://github.com/user/asset/issues') }}"
            class="form-input-text"
          >
          @error('issues_url')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
          <div class="form-help">
            {{ __('If you leave this field empty, the issue reporting URL will be inferred from the repository URL.') }}
          </div>
        </div>

        <div class="mb-6">
          <label for="icon-url" class="form-label">{{ __('Icon URL') }}</label>
          <input
            id="icon-url"
            name="icon_url"
            value="{{ old('icon_url') }}"
            autocomplete="off"
            placeholder="{{ __('https://raw.githubusercontent.com/user/asset/master/icon.png') }}"
            class="form-input-text"
          >
          @error('icon_url')
          <div role="alert" class="form-error">
            {{ $message }}
          </div>
          @enderror
          <div class="form-help">
            {{ __('The recommended size is 256×256, but lower sizes are allowed.') }}<br>
            {{ __('If you leave this field empty, the icon must be committed to the repository as "icon.png" in the root directory.') }}
          </div>
        </div>

        <button class="button button-primary w-full mt-6" type="submit">
          {{ __('Submit asset') }}
        </button>
      </section>
    </form>
  </div>
@endsection
