<?php

namespace Tests\Feature;

use App\Services\AlumniImportHistory;
use App\Services\AlumniImportResult;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumniImportHistoryTest extends TestCase
{
    private AlumniImportHistory $history;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->history = new AlumniImportHistory;
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function sampleResult(): AlumniImportResult
    {
        return new AlumniImportResult(
            created: 1,
            updated: 0,
            imported: [
                ['row' => 2, 'action' => 'created', 'attributes' => ['full_name' => 'Andi Wijaya', 'certificate_number' => 'IJ-1']],
            ],
            failures: [
                ['row' => 3, 'reason' => 'Kolom Nama Lengkap wajib diisi.', 'attributes' => ['full_name' => null]],
            ],
        );
    }

    public function test_records_an_import_run_to_local_storage(): void
    {
        $entry = $this->history->record($this->sampleResult(), 'data-alumni.xlsx');

        Storage::disk('local')->assertExists('alumni-import-history/'.$entry['id'].'.json');

        $this->assertSame('data-alumni.xlsx', $entry['filename']);
        $this->assertSame(1, $entry['created']);
        $this->assertSame(1, $entry['failed']);
        $this->assertSame(2, $entry['total']);
    }

    public function test_all_returns_full_data_newest_first(): void
    {
        Carbon::setTestNow('2026-06-21 10:00:00');
        $this->history->record($this->sampleResult(), 'lama.xlsx');

        Carbon::setTestNow('2026-06-21 11:00:00');
        $newest = $this->history->record($this->sampleResult(), 'baru.xlsx');

        $all = $this->history->all();

        $this->assertCount(2, $all);
        $this->assertSame($newest['id'], $all[0]['id']);
        $this->assertSame('baru.xlsx', $all[0]['filename']);
        $this->assertSame('Andi Wijaya', $all[0]['imported'][0]['attributes']['full_name']);
        $this->assertSame('Kolom Nama Lengkap wajib diisi.', $all[0]['failures'][0]['reason']);
    }

    public function test_keeps_only_the_last_50_entries(): void
    {
        for ($i = 0; $i < 55; $i++) {
            Carbon::setTestNow(Carbon::parse('2026-06-21 00:00:00')->addMinutes($i));
            $this->history->record($this->sampleResult(), "import-{$i}.xlsx");
        }

        $this->assertCount(50, $this->history->all());
        // The five oldest runs must have been pruned.
        $this->assertSame('import-54.xlsx', $this->history->all()[0]['filename']);
        $this->assertSame('import-5.xlsx', $this->history->all()[49]['filename']);
    }

    public function test_clear_removes_all_history(): void
    {
        $this->history->record($this->sampleResult(), 'data.xlsx');

        $this->history->clear();

        $this->assertSame([], $this->history->all());
    }
}
