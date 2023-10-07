


<div>
    <span style="display:none;">
        @php
            $bgColor = match ($getRecord()->status()) {
                'placed_at' => '#afafff',
                'packaged_at' => '#ECF783',
                'shipped_at' => '#1111'
            }
        @endphp
    </span>

    <span
        style="padding:2px 6px; font-size:13px;
        background-color:{{ $bgColor }}; color:white;border:1px solid transparent; border-radius:10%;">{{ $getRecord()->presenter()->present() }}</span>

</div>
