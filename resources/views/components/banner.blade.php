{{--
    Banner Component
    Usage: @include('components.banner', ['type' => 'horizontal_small'])
--}}

@php
    $bannerCode = App\Helpers\AffiliateHelper::render($type);
@endphp

@if ($bannerCode)
    <div class="affiliate-banner {{ $class ?? '' }}">
        {!! $bannerCode !!}
    </div>
@endif
