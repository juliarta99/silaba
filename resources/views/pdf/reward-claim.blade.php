<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tiket Reward {{ $ticketNo }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 11px;
    color: #1a1a1a;
    background: #fff;
    width: 100%;
}

/* ── Header merah ── */
.header {
    background: #C01818;
    color: #fff;
    padding: 18px 22px 14px;
}
.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}
.app-name    { font-size: 17px; font-weight: 700; letter-spacing: -0.3px; }
.app-sub     { font-size: 8.5px; opacity: .75; margin-top: 1px; }
.ticket-label { font-size: 8px; opacity: .7; text-transform: uppercase; letter-spacing: .5px; text-align: right; }
.ticket-no   { font-size: 14px; font-weight: 700; font-family: 'Courier New', monospace;
               letter-spacing: 2px; text-align: right; margin-top: 2px; }
.header-divider {
    border-top: 1px solid rgba(255,255,255,.2);
    padding-top: 8px;
    display: flex;
    justify-content: space-between;
    font-size: 8.5px;
    opacity: .75;
}

/* ── Status badge ── */
.status-row {
    padding: 7px 22px;
    background: #f9f9f9;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 8px;
}
.badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 99px;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.badge-active  { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
.badge-expired { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }

/* ── Reward info ── */
.reward-row {
    padding: 12px 22px;
    border-bottom: 1px dashed #e0e0e0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.reward-icon {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: #FEE2E2;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
    color: #C01818;
    font-weight: 700;
    text-align: center;
    line-height: 40px;
}
.reward-name { font-size: 13px; font-weight: 700; color: #111; }
.reward-type { font-size: 9px; color: #9ca3af; margin-top: 2px; }

/* ── QR + Kode tengah ── */
.qr-section {
    padding: 18px 22px 14px;
    text-align: center;
    border-bottom: 1px dashed #e0e0e0;
}
.qr-wrap {
    display: inline-block;
    padding: 10px;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    background: #fff;
    margin-bottom: 8px;
}
.qr-wrap img { display: block; }
.qr-wrap svg {
    width: 160px;
    height: 160px;
    display: block;
}
.qr-hint  { font-size: 8.5px; color: #9ca3af; margin-bottom: 14px; }
.voucher-box {
    background: #FFF5F5;
    border: 2px dashed #C01818;
    border-radius: 12px;
    padding: 12px 18px;
    margin: 0 auto;
    max-width: 280px;
}
.voucher-label { font-size: 8px; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.voucher-code  {
    font-size: 20px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    letter-spacing: 3px;
    color: #C01818;
    word-break: break-all;
}

/* ── Masa berlaku ── */
.validity-row {
    display: flex;
    gap: 8px;
    padding: 12px 22px;
    border-bottom: 1px dashed #e0e0e0;
}
.validity-cell {
    flex: 1;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px 10px;
}
.validity-cell.expired { background: #FEF2F2; border-color: #FECACA; }
.v-label { font-size: 8px; color: #9ca3af; margin-bottom: 3px; }
.v-value { font-size: 10.5px; font-weight: 600; color: #111; }
.v-value.red { color: #C01818; }

/* ── Poin info ── */
.points-row {
    padding: 10px 22px;
    background: #FFF5F5;
    border-bottom: 1px solid #FEE2E2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.points-label { font-size: 10px; color: #6b7280; }
.points-val   { font-size: 11px; font-weight: 700; color: #C01818; }

/* ── Cara penggunaan ── */
.section { padding: 12px 22px; border-bottom: 1px solid #f0f0f0; }
.section-title { font-size: 9.5px; font-weight: 700; color: #111; margin-bottom: 6px; }
.section-body  { font-size: 9.5px; color: #374151; line-height: 1.6; }
.note-box {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-radius: 6px;
    padding: 7px 10px;
    margin-top: 7px;
    font-size: 9px;
    color: #1D4ED8;
    line-height: 1.5;
}

/* ── S&K ── */
.sk-list { list-style: none; margin: 0; padding: 0; }
.sk-list li { padding-left: 10px; position: relative; font-size: 9px; color: #6b7280; line-height: 1.6; }
.sk-list li::before { content: '•'; position: absolute; left: 0; color: #C01818; font-weight: 700; }

/* ── Footer ── */
.footer {
    padding: 10px 22px;
    display: flex;
    justify-content: space-between;
    font-size: 8px;
    color: #9ca3af;
    border-top: 1px solid #f0f0f0;
}
</style>
</head>
<body>

{{-- ── Header ── --}}
<div class="header">
    <div class="header-top">
        <div>
            <div class="app-name">SILABU</div>
            <div class="app-sub">Sistem Lapor Badung — Kabupaten Badung</div>
        </div>
        <div>
            <div class="ticket-label">Nomor Tiket</div>
            <div class="ticket-no">{{ $ticketNo }}</div>
        </div>
    </div>
    <div class="header-divider">
        <span>Tiket Penukaran Reward</span>
        <span>Diklaim: {{ $rewardClaim->created_at->translatedFormat('j F Y, H:i') }}</span>
    </div>
</div>

{{-- ── Status ── --}}
<div class="status-row">
    @if ($isExpired)
    <span class="badge badge-expired">Kadaluarsa</span>
    <span style="font-size:9px;color:#9ca3af">Voucher ini sudah melewati masa berlakunya</span>
    @else
    <span class="badge badge-active">Aktif</span>
    <span style="font-size:9px;color:#6b7280">Voucher masih berlaku, tunjukkan kepada petugas</span>
    @endif
</div>

{{-- ── Nama Reward ── --}}
<div class="reward-row">
    <div class="reward-icon">🎁</div>
    <div>
        <div class="reward-name">{{ $rewardClaim->reward->name }}</div>
        <div class="reward-type">{{ $rewardClaim->reward->type }}</div>
    </div>
</div>

{{-- ── QR + Kode ── --}}
<div class="qr-section">
    <div class="qr-wrap">
        @if ($qr['type'] === 'matrix')
        @php $size = count($qr['matrix']); $cell = round(160 / $size, 2); @endphp
        <table style="border-collapse:collapse;width:160px;height:160px;margin:0 auto;
                    border:4px solid #fff;background:#fff;">
            @foreach ($qr['matrix'] as $row)
            <tr style="height:{{ $cell }}px;">
                @foreach ($row as $module)
                <td style="width:{{ $cell }}px;height:{{ $cell }}px;padding:0;margin:0;
                            background:{{ $module ? '#000000' : '#ffffff' }};
                            font-size:0;line-height:0;"></td>
                @endforeach
            </tr>
            @endforeach
        </table>
        @else
        <img src="{{ $qr['content'] }}" width="160" height="160" alt="QR Code">
        @endif
    </div>
    <div class="qr-hint">Scan QR code ini kepada petugas</div>

    <div class="voucher-box">
        <div class="voucher-label">Kode Voucher</div>
        <div class="voucher-code">{{ $voucher->code }}</div>
    </div>
</div>

{{-- ── Masa Berlaku ── --}}
<div class="validity-row">
    @if ($voucher->valid_from)
    <div class="validity-cell">
        <div class="v-label">Berlaku Mulai</div>
        <div class="v-value">{{ \Carbon\Carbon::parse($voucher->valid_from)->translatedFormat('j M Y') }}</div>
    </div>
    @endif

    @if ($voucher->valid_until)
    <div class="validity-cell {{ $isExpired ? 'expired' : '' }}">
        <div class="v-label">Berlaku Hingga</div>
        <div class="v-value {{ $isExpired ? 'red' : '' }}">
            {{ \Carbon\Carbon::parse($voucher->valid_until)->translatedFormat('j M Y') }}
            @if ($isExpired) (Kadaluarsa) @endif
        </div>
    </div>
    @else
    <div class="validity-cell">
        <div class="v-label">Berlaku Hingga</div>
        <div class="v-value">Tidak Kadaluarsa</div>
    </div>
    @endif
</div>

{{-- ── Poin ── --}}
<div class="points-row">
    <span class="points-label">Poin yang digunakan</span>
    <span class="points-val">-{{ number_format($rewardClaim->points_used) }} Poin</span>
</div>

{{-- ── Cara Penggunaan ── --}}
<div class="section">
    <div class="section-title">Cara Menggunakan</div>
    <div class="section-body">
        {{ $rewardClaim->reward->description ?? 'Tunjukkan kode atau QR kepada petugas terkait.' }}
    </div>
    @if ($rewardClaim->notes)
    <div class="note-box">
        <strong>Catatan:</strong> {{ $rewardClaim->notes }}
    </div>
    @endif
</div>

{{-- ── Syarat & Ketentuan ── --}}
<div class="section">
    <div class="section-title">Syarat &amp; Ketentuan</div>
    <ul class="sk-list">
        <li>Kode hanya dapat digunakan satu kali</li>
        <li>Tidak dapat ditukarkan dengan uang tunai</li>
        <li>Tidak dapat digabungkan dengan promo lain</li>
        <li>Tunjukkan kode atau QR sebelum bertransaksi</li>
        <li>SILABU tidak bertanggung jawab atas kode yang hilang atau disalahgunakan</li>
    </ul>
</div>

{{-- ── Footer ── --}}
<div class="footer">
    <span>SILABU — Sistem Lapor Badung &copy; {{ date('Y') }} Kabupaten Badung</span>
    <span>{{ $ticketNo }}</span>
</div>

</body>
</html>