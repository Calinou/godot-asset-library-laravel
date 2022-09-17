<div class="w-full lg:w-1/2 px-2 my-2">
  <a
    href="{{ route('asset.show', ['asset' => $asset ]) }}"
    title="Latest version: {{ $asset->version_string }} (released {{ $asset->versions->last()->created_at->diffForHumans() }})&#10;Last page update: {{ $asset->modify_date->diffForHumans() }}&#10;License: {{ $asset->license_name }}&#10;Tags: {{ implode(', ', $asset->tags) }}"
  >
    <article class="flex bg-white dark:bg-gray-800 rounded shadow hover-active-darken">
      <div class="flex-shrink-0 self-center">
        {{--
          Use smaller icons on mobile displays. Do not round the right side of
          the icon on larger displays, as its height will match the card height.
        --}}
        <img class="object-cover w-20 h-20 sm:w-26 sm:h-26 bg-gray-400 dark:bg-gray-700 rounded sm:rounded-r-none" src="{{ $asset->icon_url ? $asset->icon_url : '/android-chrome-192x192.png' }}">
      </div>
      {{--
        Offset the right panel slightly on the Y axis to make tags
        appear slightly further from the bottom, which looks better
      --}}
      <div class="ml-3 md:ml-6 lg:ml-3 xl:ml-6 py-3 pl-1 -mt-px mb-px w-full pr-3">
        <div class="flex space-between">
          <div class="leading-relaxed font-medium">{{ $asset->title }}</div>
          <div class="flex-grow text-right text-sm {{ $asset->score_color }}">
            <span class="fa mr-1 opacity-50 @if ($asset->score >= 0) fa-thumbs-up @else fa-thumbs-down @endif"></span>
            {{ $asset->score }}
          </div>
        </div>
        <div class="leading-relaxed text-gray-600 text-sm my-px">
          @if ($asset->blurb)
          {{ $asset->blurb }}
          @else
          {{ __('by :author', ['author' => $asset->author->username]) }}
          @endif
        </div>
        <div class="text-sm -ml-px mt-2">
          <span class="m-1 px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
            <span class="fa {{ $asset->category_icon }} fa-fw mr-1 -ml-1 opacity-75"></span>
            {{ $asset->category }}
          </span>
          <span class="m-1 px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-full">{{ $asset->godot_version }}</span>
          @php
            switch (intval($asset->support_level_id)) {
              case (App\Asset::SUPPORT_LEVEL_OFFICIAL):
                $supportLevelClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                $supportLevelIcon = 'fa-check';
                break;
              case (App\Asset::SUPPORT_LEVEL_COMMUNITY):
                $supportLevelClasses = 'bg-gray-200 dark:bg-gray-700';
                $supportLevelIcon = '';
                break;
              case (App\Asset::SUPPORT_LEVEL_TESTING):
                $supportLevelClasses = 'bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                $supportLevelIcon = 'fa-exclamation-circle';
                break;
              default:
                throw new \Exception("Invalid support level: $asset->support_level_id");
            }
          @endphp
          <span class="m-1 px-3 py-1 rounded-full {{ $supportLevelClasses }}">
            @if ($supportLevelIcon)
              <span class="fa {{ $supportLevelIcon }} fa-fw mr-1 -ml-1 opacity-75"></span>
            @endif
            {{ $asset->support_level }}
          </span>
        </div>
      </div>
    </article>
  </a>
</div>
