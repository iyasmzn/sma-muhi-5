<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SpmbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpmbController extends Controller
{
    public function index(): View
    {
        $procedures = json_decode(Setting::get('spmb_procedures', ''), true) ?: $this->defaultProcedures();
        $fees = json_decode(Setting::get('spmb_fees', ''), true) ?: [];
        $siteName = setting('site_name', config('app.name'));

        $seo = [
            'title' => 'PPDB / SPMB '.setting('spmb_year', date('Y').'/'.(date('Y') + 1))." | {$siteName}",
            'description' => "Informasi Penerimaan Peserta Didik Baru (PPDB) {$siteName}. Prosedur pendaftaran, biaya, dan formulir online SPMB.",
            'canonical' => route('ppdb.index'),
        ];

        return view('ppdb.index', compact('procedures', 'fees', 'seo'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! (bool) Setting::get('spmb_form_enabled', true)) {
            return back()->with('error', Setting::get('spmb_closed_message', 'Form pendaftaran saat ini sedang ditutup.'));
        }

        if (! (bool) Setting::get('spmb_open', true)) {
            return back()->with('error', 'SPMB saat ini tidak dalam masa penerimaan.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'previous_school' => ['required', 'string', 'max:100'],
            'previous_school_city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'jalur' => ['required', 'in:zonasi,prestasi,afirmasi,mutasi'],
            'parent_name' => ['nullable', 'string', 'max:100'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        SpmbRegistration::create($validated);

        return redirect()->route('ppdb.index')
            ->with('success', 'Pendaftaran berhasil dikirim! Kami akan segera menghubungi Anda untuk proses verifikasi.');
    }

    /** @return array<int, array<string, mixed>> */
    private function defaultProcedures(): array
    {
        return [
            ['icon' => '📝', 'title' => 'Isi Formulir Online', 'description' => 'Kunjungi halaman PPDB dan isi formulir pendaftaran secara lengkap dan benar.'],
            ['icon' => '📁', 'title' => 'Siapkan Berkas', 'description' => 'Persiapkan dokumen yang diperlukan: ijazah/SHUN, rapor, dan pas foto terbaru.'],
            ['icon' => '✅', 'title' => 'Verifikasi Berkas', 'description' => 'Datang ke sekolah untuk verifikasi berkas pada tanggal yang telah ditentukan.'],
            ['icon' => '🎉', 'title' => 'Pengumuman Hasil', 'description' => 'Hasil seleksi diumumkan melalui halaman resmi sekolah dan via WhatsApp/email.'],
        ];
    }
}
