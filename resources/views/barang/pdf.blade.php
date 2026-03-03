<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>

    
  * { margin:0; padding:0; box-sizing:border-box; }

  @page {
      size: A4 portrait;
      margin: 3mm 28mm 3mm 28mm;
  }

  body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 7px;
      width: 154mm;
  }

  .label-row {
      display: table;
      width: 100%;
      table-layout: fixed;
  }

  /* Label: lebar 18mm, tinggi 36.375mm (disesuaikan agar 8 baris muat di A4) */
  .label {
      display: table-cell;
      width: 18mm;
      height: 36.375mm;
      border: 0.3px solid #ccc;
      vertical-align: middle;
      text-align: center;
      padding: 1mm 0.5mm;
      overflow: hidden;
  }

  /* Gap antar kolom */
  .label-gap {
      display: table-cell;
      width: 4mm;
  }

  .label.empty { background: #fff; }
  .label.filled { background: #fff; }

  .lbl-id {
      font-size: 4.5px;
      color: #aaa;
      letter-spacing: 0.3px;
      margin-bottom: 0.8mm;
  }

  .lbl-nama {
      font-size: 6.5px;
      font-weight: bold;
      color: #222;
      margin-bottom: 1mm;
      line-height: 1.2;
  }

  .lbl-divider {
      border: none;
      border-top: 0.4px solid #ddd;
      margin: 0 1mm 1mm 1mm;
  }

  .lbl-harga {
      font-size: 9px;
      font-weight: bold;
      color: #198754;
      margin-bottom: 0.5mm;
  }

  .lbl-footer {
      font-size: 4px;
      color: #aaa;
      letter-spacing: 1px;
      border-top: 0.8px solid #198754;
      padding-top: 0.5mm;
      margin-top: 0.5mm;
  }

  .page-break { page-break-after: always; }
</style>
</head>
<body>

@php
    $cols      = 5;
    $totalSlot = 40;
    $items     = collect($barangs)->values();
    $itemCount = $items->count();
    $itemIdx   = 0;
    $firstPageStart = $startSlot;
    $pageNum   = 0;
@endphp

@while($itemIdx < $itemCount)
    @php
        $pageNum++;
        $slotStart = ($pageNum === 1) ? $firstPageStart : 0;
    @endphp

    <div class="{{ $pageNum > 1 ? 'page-break' : '' }}">

        @for($slot = 0; $slot < $totalSlot; $slot++)
            @php
                $col          = $slot % $cols;
                $isFirstInRow = ($col === 0);
                $isLastInRow  = ($col === $cols - 1);
            @endphp

            @if($isFirstInRow)<div class="label-row">@endif

            @if($slot < $slotStart || $itemIdx >= $itemCount)
                <div class="label empty"></div>
            @else
                @php $item = $items[$itemIdx]; $itemIdx++; @endphp
                <div class="label filled">
                    <div class="lbl-id">{{ $item->id_barang }}</div>
                    <div class="lbl-nama">
                        {{ mb_strlen($item->nama) > 18 ? mb_substr($item->nama, 0, 18).'…' : $item->nama }}
                    </div>
                    <hr class="lbl-divider">
                    <div class="lbl-harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                    <div class="lbl-footer">HARGA</div>
                </div>
            @endif

            @if(!$isLastInRow)<div class="label-gap"></div>@endif

            @if($isLastInRow)</div>@endif

        @endfor

    </div>

@endwhile

</body>
</html>