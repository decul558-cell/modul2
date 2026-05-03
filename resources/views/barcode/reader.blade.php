@extends('layouts.app')

@section('title', 'Barcode Reader')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">📦 Barcode Reader - Tag Harga</h4>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Arahkan kamera ke barcode pada label barang</p>

                {{-- Area kamera scanner --}}
                <div id="reader" style="width: 100%;"></div>

                {{-- Suara beep --}}
                <audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

                {{-- Hasil scan --}}
                <div id="result-box" class="mt-3" style="display:none;">
                    <div class="alert alert-success">
                        <h5>✅ Barang Ditemukan!</h5>
                    </div>
                    <table class="table table-bordered text-start">
                        <tr>
                            <td width="40%"><strong>ID Barang</strong></td>
                            <td id="res-id">-</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Barang</strong></td>
                            <td id="res-nama">-</td>
                        </tr>
                        <tr>
                            <td><strong>Harga</strong></td>
                            <td id="res-harga">-</td>
                        </tr>
                    </table>
                </div>

                {{-- Tidak ditemukan --}}
                <div id="not-found-box" class="mt-3" style="display:none;">
                    <div class="alert alert-danger">
                        ❌ Barang tidak ditemukan!
                    </div>
                </div>

                <button id="btn-scan" class="btn btn-primary mt-3" onclick="mulaiScan()" style="display:none;">
                    🔄 Scan Lagi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner  = null;
let sudahScan = false;

function mulaiScan() {
    sudahScan = false;
    document.getElementById('result-box').style.display    = 'none';
    document.getElementById('not-found-box').style.display = 'none';
    document.getElementById('btn-scan').style.display      = 'none';
    document.getElementById('reader').innerHTML            = '';

    scanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: { width: 300, height: 150 }
    });
    scanner.render(onScanSuccess, () => {});
}

function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    // Beep
    document.getElementById('beep-sound').play();

    // Stop scanner
    scanner.clear();

    // Cari barang ke server
    fetch(`{{ url('/barcode-reader/cari') }}/${decodedText}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'found') {
                document.getElementById('res-id').innerText    = data.id_barang;
                document.getElementById('res-nama').innerText  = data.nama_barang;
                document.getElementById('res-harga').innerText = 'Rp ' + parseInt(data.harga).toLocaleString('id-ID');
                document.getElementById('result-box').style.display = 'block';
            } else {
                document.getElementById('not-found-box').style.display = 'block';
            }
            document.getElementById('btn-scan').style.display = 'inline-block';
        })
        .catch(() => {
            alert('Gagal menghubungi server!');
            document.getElementById('btn-scan').style.display = 'inline-block';
        });
}

window.onload = mulaiScan;
</script>
@endpush