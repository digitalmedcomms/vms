@php
    $value = $column['value'] ?? data_get($entry, $column['name']);
    $value = is_numeric($value) ? (float)$value : 0;
    $maxStars = 5;
    $fullStars = floor($value);
    $halfStar = ($value - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = $maxStars - $fullStars - $halfStar;
@endphp

<span title="{{ $value }}">
    @for ($i = 0; $i < $fullStars; $i++)
        <i class="la la-star text-warning"></i>
    @endfor
    @if ($halfStar)
        <i class="la la-star-half-alt text-warning"></i>
    @endif
    @for ($i = 0; $i < $emptyStars; $i++)
        <i class="la la-star-o text-muted"></i>
    @endfor
    @if($value > 0)
        <small class="text-muted ml-1">({{ $value }})</small>
    @endif
</span>
