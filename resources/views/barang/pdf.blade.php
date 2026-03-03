<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  /* ============================================================
     KERTAS A4 PORTRAIT: 210mm × 297mm
     Layout TnJ No.108: 5 kolom × 8 baris = 40 label
     ============================================================ */

  * { margin:0; padding:0; box-sizing:border-box; }

  @page {
      size: A4 portrait;
      margin: 13.5mm 4.5mm 13.5mm 4.5mm;
  }

  body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 8px;
      width: 201mm; /* 210 - 4.5 - 4.5 */
  }

  /* Grid wrapper */
  .grid-wrap {
      width: 100%;
  }

  /* Satu baris = 5 label */
  .label-row {
      display: table;
      width: 100%;
      table-layout: fixed;
  }

  /* Satu label */
  .label {
      display: table-cell;
      width: 20%;              /* 100% / 5 kolom */
      height: 33.75mm;         /* (297 - 13.5 - 13.5) / 8 baris */
      border: 0.3px solid #ccc;
      vertical-align: middle;
      text-align: center;
      padding: 2mm 1mm;
      overflow: hidden;
  }

  /* Label kosong (slot sebelum koordinat awal / slot sisa) */
  .label.empty {
      background: #fff;
  }

  /* Label berisi data */
  .label.filled {
      background: #fff;
  }

  /* ID barang */
  .lbl-id {
      font-size: 5px;
      color: #aaa;
      letter-spacing: 0.3px;
      margin-bottom: 1mm;
  }

  /* Nama barang */
  .lbl-nama {
      font-size: 7.5px;
      font-weight: bold;
      color: #222;
      margin-bottom: 1.5mm;
      line-height: 1.2;
  }

  /* Garis pemisah */
  .lbl-divider {
      border: none;
      border-top: 0.5px solid #ddd;
      margin: 0 2mm 1.5mm 2mm;
  }

  /* Harga */
  .lbl-harga {
      font-size: 11px;
      font-weight: bold;
      color: #198754;
      margin-bottom: 1mm;
  }

  /* Aksen bawah */
  .lbl-footer {
      font-size: 5px;
      color: #aaa;
      letter-spacing: 1px;
      border-top: 1px solid #198754;
      padding-top: 1mm;
      margin-top: 1mm;
  }

  /* Page break antar halaman (jika data > 40) */
  .page-break {
      page-break-after: always;
  }
</style>
</head>
<body>

@php
    $cols      = 5;
    $totalSlot = 40;  // 5 × 8 per halaman
    $items     = collect($barangs)->values();
    $itemCount = $items->count();
    $itemIdx   = 0;   // pointer ke data barang

    // Absolute slot number (0-based, terus bertambah lintas halaman)
    $absSlot   = 0;

    // Halaman pertama: slot awal sesuai koordinat user
    $firstPageStart = $startSlot; // 0-based

    $pageNum = 0;
@endphp

@while($itemIdx < $itemCount)
    @php
        $pageNum++;
        $slotStart = ($pageNum === 1) ? $firstPageStart : 0;
    @endphp

    <div class="grid-wrap {{ $pageNum > 1 ? 'page-break' : '' }}">

        @for($slot = 0; $slot < $totalSlot; $slot++)
            @php
                $col = $slot % $cols; // 0-based
                $isFirstInRow = ($col === 0);
                $isLastInRow  = ($col === $cols - 1);
            @endphp

            @if($isFirstInRow)
                <div class="label-row">
            @endif

            @if($slot < $slotStart || $itemIdx >= $itemCount)
                {{-- Label kosong --}}
                <div class="label empty"></div>
                @php if($slot >= $slotStart && $itemIdx < $itemCount) {} @endphp
            @else
                @php $item = $items[$itemIdx]; $itemIdx++; @endphp
                <div class="label filled">
                    <div class="lbl-id">{{ $item->id_barang }}</div>
                    <div class="lbl-nama">
                        {{ mb_strlen($item->nama) > 22 ? mb_substr($item->nama, 0, 22).'…' : $item->nama }}
                    </div>
                    <hr class="lbl-divider">
                    <div class="lbl-harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                    <div class="lbl-footer">HARGA</div>
                </div>
            @endif

            @if($isLastInRow)
                </div>{{-- /label-row --}}
            @endif

        @endfor

    </div>{{-- /grid-wrap --}}

@endwhile

</body>
</html>