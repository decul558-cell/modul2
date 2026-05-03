@extends('layouts.app')

@section('title', 'Vendor - Scan QR Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">🏪 Vendor - Scan QR Code Pesanan</h4>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Arahkan kamera ke QR Code milik customer</p>

                {{-- Area kamera scanner --}}
                <div id="reader" style="width: 100%;"></div>

                {{-- Suara beep --}}
                <audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

                {{-- Hasil scan --}}
                <div id="result-box" class="mt-3" style="display:none;">
                    <div class="alert alert-success">
                        <h5>✅ Pesanan Ditemukan!</h5>
                    </div>
                    <table class="table table-bordered text-start">
                        <tr>
                            <td width="40%"><strong>ID Pesanan</strong></td>
                            <td id="res-id">-</td>
                        </tr>
                        <tr>
                            <td><strong>Kode Order</strong></td>
                            <td id="res-code">-</td>
                        </tr>
                        <tr>
                            <td><strong>Status Bayar</strong></td>
                            <td id="res-status">-</td>
                        </tr>
                    </table>

                    <h6 class="text-start mt-3">🍽️ Detail Item:</h6>
                    <table class="table table-sm table-bordered text-start">
                        <thead class="table-primary">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="res-menu"></tbody>
                    </table>
                </div>

                {{-- Tidak ditemukan --}}
                <div id="not-found-box" class="mt-3" style="display:none;">
                    <div class="alert alert-danger">
                        ❌ Pesanan tidak ditemukan!
                    </div>
                </div>

                <button id="btn-scan" class="btn btn-success mt-3" onclick="mulaiScan()" style="display:none;">
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

    scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    scanner.render(onScanSuccess, () => {});
}

function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    // Beep
    document.getElementById('beep-sound').play();

    // Stop scanner
    scanner.clear();

    // Ambil data pesanan
    fetch(`{{ url('/vendor/scan-qr/cek') }}/${decodedText}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'found') {
                document.getElementById('res-id').innerText   = data.id_pesanan;
                document.getElementById('res-code').innerText = data.order_code ?? '-';

                const statusEl = document.getElementById('res-status');
                statusEl.innerText = data.status_bayar;
                statusEl.className = (data.status_bayar === 'paid' || data.status_bayar === 'lunas')
                    ? 'text-success fw-bold'
                    : 'text-danger fw-bold';

                // Isi tabel menu
                const tbody = document.getElementById('res-menu');
                tbody.innerHTML = '';
                data.menu.forEach(m => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${m.nama_menu}</td>
                            <td>${m.qty}</td>
                            <td>Rp ${parseInt(m.harga).toLocaleString('id-ID')}</td>
                        </tr>`;
                });

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