@inject('assetClass', 'App\Asset')

<div class="w-full lg:w-1/2 px-2 my-2">
  <a href="{{ route('asset.show', ['asset' => $asset ]) }}">
    <article class="flex bg-white rounded shadow hover-active-darken">
      <div class="flex-shrink-0 self-center">
        <img class="object-cover w-26 h-26 bg-gray-400 rounded-l" src="{{ $asset->icon_url }}">
      </div>
      {{--
        Offset the right panel slightly on the Y axis to make tags
        appear slightly further from the bottom, which looks better
      --}}
      <div class="ml-6 py-3 pl-1 -mt-px mb-px w-full pr-3">
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
          {{ __('by :author', ['author' => $asset->author->name]) }}
          @endif
        </div>
        <div class="text-sm -ml-px mt-2">
          <span class="m-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
            <span class="fa {{ $asset->category_icon }} fa-fw mr-1 -ml-1 opacity-75"></span>
            {{ $asset->category }}
          </span>
          <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset->godot_version }}</span>
          @php
            switch (intval($asset->support_level_id)) {
              case ($assetClass::SUPPORT_LEVEL_OFFICIAL):
                $supportLevelClasses = 'bg-green-100 text-green-800';
                $supportLevelIcon = 'fa-check';
                break;
              case ($assetClass::SUPPORT_LEVEL_COMMUNITY):
                $supportLevelClasses = 'bg-gray-200';
                $supportLevelIcon = '';
                break;
              case ($assetClass::SUPPORT_LEVEL_TESTING):
                $supportLevelClasses = 'bg-yellow-200 text-yellow-800';
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
