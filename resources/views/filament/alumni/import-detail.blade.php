@php
    /** @var array<string, mixed> $entry */
    $imported = $entry['imported'] ?? [];
    $failures = $entry['failures'] ?? [];
@endphp

<div>
    {{-- Ringkasan --}}
    <div class="aih-badges">
        <x-filament::badge color="success" icon="heroicon-m-plus-circle">
            {{ $entry['created'] ?? 0 }} ditambahkan
        </x-filament::badge>
        <x-filament::badge color="info" icon="heroicon-m-arrow-path">
            {{ $entry['updated'] ?? 0 }} diperbarui
        </x-filament::badge>
        <x-filament::badge :color="($entry['failed'] ?? 0) > 0 ? 'danger' : 'gray'" icon="heroicon-m-x-circle">
            {{ $entry['failed'] ?? 0 }} gagal
        </x-filament::badge>
    </div>

    {{-- Data berhasil masuk --}}
    <div class="aih-subhead">
        <span class="aih-ico-ok"><x-filament::icon icon="heroicon-m-check-circle" /></span>
        <h3>Data Berhasil Masuk</h3>
        <span class="aih-count">{{ count($imported) }}</span>
    </div>

    @if (! empty($imported))
        <div class="aih-table-wrap">
            <div class="aih-table-scroll">
                <table class="aih-table">
                    <thead>
                        <tr>
                            <th class="aih-right">#</th>
                            <th>Nama Lengkap</th>
                            <th>No. Ijazah</th>
                            <th class="aih-center">Tahun Lulus</th>
                            <th>Masuk PTN</th>
                            <th class="aih-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($imported as $row)
                            @php $attr = $row['attributes'] ?? []; @endphp
                            <tr>
                                <td class="aih-num">{{ $row['row'] ?? '—' }}</td>
                                <td class="aih-name">{{ ($attr['full_name'] ?? null) ?: '—' }}</td>
                                <td class="aih-mono">{{ ($attr['certificate_number'] ?? null) ?: '—' }}</td>
                                <td class="aih-center">{{ ($attr['graduation_year'] ?? null) ?: '—' }}</td>
                                <td>
                                    @if ($attr['entered_ptn'] ?? false)
                                        <x-filament::badge color="success">{{ ($attr['ptn_name'] ?? null) ?: 'PTN' }}</x-filament::badge>
                                    @else
                                        <span class="aih-muted">Tidak</span>
                                    @endif
                                </td>
                                <td class="aih-right">
                                    <x-filament::badge :color="($row['action'] ?? '') === 'updated' ? 'info' : 'success'">
                                        {{ ($row['action'] ?? '') === 'updated' ? 'Diperbarui' : 'Ditambahkan' }}
                                    </x-filament::badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="aih-empty">Tidak ada baris yang berhasil masuk.</div>
    @endif

    {{-- Data gagal masuk --}}
    <div class="aih-subhead aih-section">
        <span class="aih-ico-bad"><x-filament::icon icon="heroicon-m-x-circle" /></span>
        <h3>Data Gagal Masuk</h3>
        <span class="aih-count aih-count--danger">{{ count($failures) }}</span>
    </div>

    @if (! empty($failures))
        <div class="aih-table-wrap aih-table-wrap--danger">
            <div class="aih-table-scroll">
                <table class="aih-table aih-table--danger">
                    <thead>
                        <tr>
                            <th class="aih-right">#</th>
                            <th>Nama Lengkap</th>
                            <th>No. Ijazah</th>
                            <th>Alasan Gagal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($failures as $row)
                            @php $attr = $row['attributes'] ?? []; @endphp
                            <tr>
                                <td class="aih-num">{{ $row['row'] ?? '—' }}</td>
                                <td class="aih-name">{{ ($attr['full_name'] ?? null) ?: '—' }}</td>
                                <td class="aih-mono">{{ ($attr['certificate_number'] ?? null) ?: '—' }}</td>
                                <td class="aih-reason">{{ $row['reason'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="aih-empty aih-empty--ok">Semua baris berhasil diimpor. 🎉</div>
    @endif
</div>
