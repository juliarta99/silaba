<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan {{ $report->code }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }

/* ── Header ── */
.header { background: #C01818; color: #fff; padding: 20px 28px 16px; }
.header-top { display: flex; justify-content: space-between; align-items: flex-start; }
.logo-area { display: flex; align-items: center; gap: 10px; }
.logo-text { font-size: 18px; font-weight: 700; letter-spacing: -0.3px; }
.logo-sub { font-size: 9px; opacity: 0.85; margin-top: 2px; }
.doc-label { text-align: right; }
.doc-label .label { font-size: 9px; opacity: 0.75; text-transform: uppercase; letter-spacing: 0.5px; }
.doc-label .code { font-size: 16px; font-weight: 700; font-family: 'Courier New', monospace; letter-spacing: 1px; }
.header-divider { border-top: 1px solid rgba(255,255,255,0.25); margin-top: 14px; padding-top: 10px;
                  display: flex; justify-content: space-between; font-size: 9px; opacity: 0.8; }

/* ── Status badge ── */
.status-bar { padding: 8px 28px; background: #f9f9f9; border-bottom: 1px solid #e5e5e5;
              display: flex; align-items: center; gap: 12px; }
.status-badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 9px;
                font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
.status-pending            { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }
.status-in_progress        { background: #DBEAFE; color: #1E40AF; border: 1px solid #BFDBFE; }
.status-under_review       { background: #EDE9FE; color: #5B21B6; border: 1px solid #DDD6FE; }
.status-waiting_for_materials { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
.status-completed          { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
.status-rejected           { background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; }
.priority-badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 9px;
                  font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
.priority-low      { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.priority-medium   { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
.priority-high     { background: #FFF7ED; color: #9A3412; border: 1px solid #FDBA74; }
.priority-critical { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }

/* ── Content ── */
.content { padding: 20px 28px; }
.section { margin-bottom: 18px; }
.section-title { font-size: 9px; font-weight: 700; color: #C01818; text-transform: uppercase;
                 letter-spacing: 0.8px; padding-bottom: 5px; border-bottom: 1.5px solid #C01818;
                 margin-bottom: 10px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px 16px; }
.field { margin-bottom: 2px; }
.field-label { font-size: 8px; font-weight: 600; color: #6b7280; text-transform: uppercase;
               letter-spacing: 0.4px; margin-bottom: 2px; }
.field-value { font-size: 11px; color: #111827; font-weight: 400; }
.field-value.mono { font-family: 'Courier New', monospace; font-size: 10px; }
.text-block { background: #f8f8f8; border: 1px solid #e5e5e5; border-radius: 4px;
              padding: 10px 12px; font-size: 10.5px; line-height: 1.65; color: #1f2937; }

/* ── Progress timeline ── */
.timeline { border-left: 2px solid #e5e5e5; margin-left: 8px; padding-left: 14px; }
.timeline-item { margin-bottom: 12px; position: relative; }
.timeline-dot { position: absolute; left: -19px; top: 2px; width: 8px; height: 8px;
                border-radius: 50%; background: #C01818; border: 2px solid #fff;
                box-shadow: 0 0 0 1px #C01818; }
.timeline-date { font-size: 8.5px; color: #6b7280; margin-bottom: 2px; }
.timeline-title { font-size: 10.5px; font-weight: 600; color: #111827; margin-bottom: 2px; }
.timeline-officer { font-size: 9px; color: #6b7280; margin-bottom: 4px; }
.timeline-note { font-size: 10px; color: #374151; background: #f9f9f9; border-left: 2px solid #e5e5e5;
                 padding: 5px 8px; border-radius: 0 4px 4px 0; }

/* ── Assignments ── */
.officer-chip { display: inline-block; background: #f3f4f6; border: 1px solid #e5e7eb;
                border-radius: 4px; padding: 4px 8px; font-size: 9.5px; color: #374151;
                margin-right: 6px; margin-bottom: 4px; }
.officer-pos { font-size: 8px; color: #6b7280; display: block; margin-top: 1px; }

/* ── Evidence ── */
.evidence-note { font-size: 9.5px; color: #6b7280; font-style: italic; }

/* ── Review ── */
.stars { font-size: 13px; color: #F59E0B; letter-spacing: 1px; }
.empty-state { font-size: 10px; color: #9ca3af; font-style: italic; padding: 8px 0; }

/* ── Footer ── */
.footer { margin-top: 24px; border-top: 1px solid #e5e5e5; padding: 10px 28px 0;
          display: flex; justify-content: space-between; font-size: 8.5px; color: #9ca3af; }

/* ── Map coords ── */
.coords-box { display: inline-flex; align-items: center; gap: 6px; background: #f3f4f6;
              border: 1px solid #e5e5e5; border-radius: 4px; padding: 4px 8px;
              font-family: 'Courier New', monospace; font-size: 9.5px; color: #374151; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="header">
    <div class="header-top">
        <div class="logo-area">
            <div>
                <div class="logo-text">SILABA</div>
                <div class="logo-sub">Sistem Lapor Badung — Kabupaten Badung</div>
            </div>
        </div>
        <div class="doc-label">
            <div class="label">Nomor Laporan</div>
            <div class="code">{{ $report->code }}</div>
        </div>
    </div>
    <div class="header-divider">
        <span>Dokumen Resmi Laporan Pengaduan Masyarakat</span>
        <span>Diunduh: {{ $downloaded_at }} oleh {{ $downloaded_by->name }}</span>
    </div>
</div>

{{-- ── Status bar ── --}}
<div class="status-bar">
    @php
    $statusLabel = [
        'pending'              => 'Menunggu Verifikasi',
        'in_progress'          => 'Sedang Diproses',
        'under_review'         => 'Dalam Peninjauan',
        'waiting_for_materials'=> 'Menunggu Material',
        'completed'            => 'Selesai',
        'rejected'             => 'Ditolak',
    ][$report->status] ?? $report->status;

    $priorityLabel = [
        'low'      => 'Rendah',
        'medium'   => 'Sedang',
        'high'     => 'Tinggi',
        'critical' => 'Kritis',
    ][$report->priority] ?? $report->priority;
    @endphp

    <span class="status-badge status-{{ $report->status }}">{{ $statusLabel }}</span>
    <span class="priority-badge priority-{{ $report->priority }}">Prioritas: {{ $priorityLabel }}</span>
    @if ($report->sla_deadline)
    <span style="font-size:9px; color:#6b7280;">
        SLA: {{ \Carbon\Carbon::parse($report->sla_deadline)->translatedFormat('j M Y') }}
    </span>
    @endif
</div>

<div class="content">

    {{-- ══ 1. INFORMASI LAPORAN ══ --}}
    <div class="section">
        <div class="section-title">Informasi Laporan</div>
        <div class="grid-2" style="margin-bottom:10px">
            <div class="field">
                <div class="field-label">Judul Laporan</div>
                <div class="field-value" style="font-weight:600">{{ $report->title }}</div>
            </div>
            <div class="field">
                <div class="field-label">Kategori</div>
                <div class="field-value">{{ $report->category?->name ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="field-label">OPD Penanggung Jawab</div>
                <div class="field-value">{{ $report->category?->department?->name ?? 'Belum dipetakan' }}</div>
            </div>
            <div class="field">
                <div class="field-label">Kecamatan</div>
                <div class="field-value">{{ $report->district?->name ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="field-label">Tanggal Dilaporkan</div>
                <div class="field-value">{{ $report->created_at?->translatedFormat('j F Y, H:i') }}</div>
            </div>
            <div class="field">
                <div class="field-label">Terakhir Diperbarui</div>
                <div class="field-value">{{ $report->updated_at?->translatedFormat('j F Y, H:i') }}</div>
            </div>
        </div>
        <div class="field" style="margin-bottom:8px">
            <div class="field-label">Lokasi</div>
            <div class="field-value">{{ $report->location }}</div>
        </div>
        @if ($report->latitude && $report->longitude)
        <div class="field">
            <div class="field-label">Koordinat GPS</div>
            <div class="coords-box">
                {{ number_format($report->latitude, 7) }},&nbsp;{{ number_format($report->longitude, 7) }}
            </div>
        </div>
        @endif
    </div>

    {{-- ══ 2. PELAPOR ══ --}}
    <div class="section">
        <div class="section-title">Data Pelapor</div>
        <div class="grid-3">
            @if ($report->user)
            <div class="field">
                <div class="field-label">Nama</div>
                <div class="field-value">{{ $report->user->name }}</div>
            </div>
            <div class="field">
                <div class="field-label">No. HP</div>
                <div class="field-value mono">{{ $report->user->citizen?->phone ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value">{{ $report->user->citizen?->email ?? '—' }}</div>
            </div>
            @else
            <div class="field">
                <div class="field-label">Nama (Tamu)</div>
                <div class="field-value">{{ $report->guest_name ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="field-label">No. HP (Tamu)</div>
                <div class="field-value mono">{{ $report->guest_phone ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="field-label">Jenis Akun</div>
                <div class="field-value" style="color:#6b7280">Tamu (tanpa akun)</div>
            </div>
            @endif
        </div>
    </div>

    {{-- ══ 3. DESKRIPSI ══ --}}
    <div class="section">
        <div class="section-title">Deskripsi Laporan</div>
        <div class="text-block">{{ $report->description }}</div>
    </div>

    {{-- ══ 4. PETUGAS YANG DITUGASKAN ══ --}}
    <div class="section">
        <div class="section-title">Petugas yang Ditugaskan</div>
        @if ($report->assignments->count() > 0)
        @foreach ($report->assignments as $assign)
        <span class="officer-chip">
            {{ $assign->employee?->user?->name ?? '—' }}
            <span class="officer-pos">{{ ucfirst(str_replace('_', ' ', $assign->employee?->position ?? '')) }}</span>
        </span>
        @endforeach
        @else
        <div class="empty-state">Belum ada petugas yang ditugaskan.</div>
        @endif
    </div>

    {{-- ══ 5. RIWAYAT PROGRESS ══ --}}
    <div class="section">
        <div class="section-title">Riwayat Progress Penanganan</div>
        @if ($report->progresses->count() > 0)
        <div class="timeline">
            @foreach ($report->progresses->sortBy('created_at') as $prog)
            @php
            $statusLbl = [
                'pending'              => 'Menunggu Verifikasi',
                'in_progress'          => 'Sedang Diproses',
                'under_review'         => 'Dalam Peninjauan',
                'waiting_for_materials'=> 'Menunggu Material',
                'completed'            => 'Selesai',
                'rejected'             => 'Ditolak',
            ][$prog->status] ?? $prog->status;
            @endphp
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-date">{{ $prog->created_at?->translatedFormat('j F Y, H:i') }}</div>
                <div class="timeline-title">
                    {{ $prog->title }}
                    <span class="status-badge status-{{ $prog->status }}" style="font-size:8px;padding:2px 7px;margin-left:4px">
                        {{ $statusLbl }}
                    </span>
                </div>
                <div class="timeline-officer">
                    Petugas: {{ $prog->employee?->user?->name ?? '—' }}
                    ({{ ucfirst(str_replace('_', ' ', $prog->employee?->position ?? '')) }})
                </div>
                @if ($prog->description)
                <div class="timeline-note">{{ $prog->description }}</div>
                @endif
                @if ($prog->notes)
                <div class="timeline-note" style="margin-top:4px">Catatan: {{ $prog->notes }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">Belum ada update progress.</div>
        @endif
    </div>

    {{-- ══ 7. ULASAN & RATING ══ --}}
    @if ($report->review)
    <div class="section">
        <div class="section-title">Ulasan Pelapor</div>
        <div class="grid-2">
            <div class="field">
                <div class="field-label">Rating</div>
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                    {{ $i <= $report->review->rating ? '★' : '☆' }}
                    @endfor
                </div>
                <div style="font-size:9px;color:#6b7280;margin-top:2px">{{ $report->review->rating }}/5</div>
            </div>
            <div class="field">
                <div class="field-label">Tanggal Ulasan</div>
                <div class="field-value">{{ $report->review->created_at?->translatedFormat('j F Y') }}</div>
            </div>
        </div>
        @if ($report->review->comment)
        <div class="text-block" style="margin-top:8px">{{ $report->review->comment }}</div>
        @endif
    </div>
    @endif

    {{-- ══ 8. LAPORAN TERKAIT ══ --}}
    @if ($report->parentReport)
    <div class="section">
        <div class="section-title">Laporan Terkait</div>
        <div class="field">
            <div class="field-label">Duplikat dari Laporan</div>
            <div class="field-value mono">{{ $report->parentReport->code }}</div>
        </div>
        <div class="field" style="margin-top:4px">
            <div class="field-label">Judul Laporan Asli</div>
            <div class="field-value">{{ $report->parentReport->title }}</div>
        </div>
    </div>
    @endif

</div>

{{-- ══ FOOTER ══ --}}
<div class="footer">
    <span>SILABA — Sistem Lapor Badung &copy; {{ date('Y') }} Kabupaten Badung</span>
    <span>{{ $report->code }} | Halaman <span class="pagenum"></span></span>
</div>

</body>
</html>