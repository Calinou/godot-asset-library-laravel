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
        @component('components/form-input', [
          'name' => 'title',
          'label' => __('Asset name'),
          'placeholder' => __('My Own Asset'),
          'required' => true,
          'autofocus' => true,
          'maxlength' => 50,
          'autocomplete' => 'off',
        ])
        @endcomponent

        @component('components/form-input', [
          'name' => 'blurb',
          'label' => __('Blurb'),
          'placeholder' => __('One-line description of the asset'),
          'maxlength' => 60,
          'autocomplete' => 'off',
        ])
        @endcomponent


        @component('components/form-input', [
          'type' => 'textarea',
          'name' => 'description',
          'label' => __('Description'),
          'placeholder' => __('Full description that spans multiple lines…'),
          'required' => true,
          'autocomplete' => 'off',
          'class' => 'h-64',
        ])
        @endcomponent

        <div class="sm:flex sm:justify-between">
          @php
          $categories = [];
          foreach (range(0, $assetClass::CATEGORY_MAX - 1) as $categoryId) {
            $categories[] = $assetClass::getCategoryName($categoryId);
          }
          @endphp

          @component('components/form-select', [
            'name' => 'category_id',
            'label' => __('Category'),
            'placeholder' => __('Select a category'),
            'required' => true,
            'choices' =>  $categories,
          ])
          @endcomponent

          @component('components/form-select', [
            'name' => 'cost',
            'label' => __('License'),
            'placeholder' => __('Select a license'),
            'required' => true,
            'choices' =>  $assetClass::LICENSES,
          ])
          @endcomponent
        </div>

        <div class="sm:flex sm:justify-between">
          @component('components/form-input', [
            'name' => 'versions[0][version_string]',
            'label' => __('Asset version'),
            'placeholder' => '1.0.0',
            'required' => true,
            'autocomplete' => 'off',
          ])
          @endcomponent

          @component('components/form-select', [
            'name' => 'versions[0][godot_version]',
            'label' => __('Godot version'),
            'placeholder' => __('Select a Godot version'),
            'required' => true,
            'choices' => [
              '3.2' => 'Godot 3.2',
              '3.1' => 'Godot 3.1',
              '3.0' => 'Godot 3.0',
            ],
          ])
          @endcomponent
        </div>

        @component('components/form-input', [
          'name' => 'browse_url',
          'label' => __('Repository URL'),
          'placeholder' => 'https://github.com/user/asset',
          'required' => true,
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('This must be a URL to a public GitHub, GitLab or Bitbucket repository.') }}
        @endcomponent

        @component('components/form-input', [
          'name' => 'versions[0][download_url]',
          'label' => __('Download URL'),
          'placeholder' => 'https://github.com/user/asset/archive/v1.0.0.zip',
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('If you leave this field empty, the download URL will be inferred from the repository URL and the asset version.') }}<br>
        {{ __('For example, if the asset version is "1.0.0", the ZIP archive corresponding to the Git tag "v1.0.0" will be used (note the leading "v").') }}
        @endcomponent

        @component('components/form-input', [
          'name' => 'issues_url',
          'label' => __('Issues URL'),
          'placeholder' => 'https://github.com/user/asset/issues',
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('If you leave this field empty, the issue reporting URL will be inferred from the repository URL.') }}
        @endcomponent

        @component('components/form-input', [
          'name' => 'icon_url',
          'label' => __('Icon URL'),
          'placeholder' => 'https://raw.githubusercontent.com/user/asset/master/icon.png',
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('The recommended size is 256×256, but lower sizes are allowed.') }}<br>
        {{ __('If you leave this field empty, the icon must be committed to the repository as "icon.png" in the root directory.') }}
        @endcomponent

        <button class="button button-primary w-full mt-6" type="submit">
          {{ __('Submit asset') }}
        </button>
      </section>
    </form>
  </div>
@endsection
