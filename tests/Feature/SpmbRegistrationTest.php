<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\SpmbRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpmbRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ppdb_page_is_accessible(): void
    {
        $response = $this->get(route('ppdb.index'));

        $response->assertStatus(200);
        $response->assertSee('PPDB');
    }

    public function test_registration_form_submission_creates_record(): void
    {
        Setting::set('spmb_open', '1');
        Setting::set('spmb_form_enabled', '1');

        $payload = [
            'full_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
            'previous_school' => 'SMP Negeri 1 Bandung',
            'jalur' => 'zonasi',
        ];

        $response = $this->post(route('ppdb.store'), $payload);

        $response->assertRedirect(route('ppdb.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('spmb_registrations', [
            'full_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'jalur' => 'zonasi',
            'status' => 'pending',
        ]);
    }

    public function test_registration_fails_when_spmb_is_closed(): void
    {
        Setting::set('spmb_open', '0');

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Ani Lestari',
            'phone' => '089876543210',
            'previous_school' => 'SMP Swasta',
            'jalur' => 'prestasi',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('spmb_registrations', ['full_name' => 'Ani Lestari']);
    }

    public function test_registration_fails_when_form_is_disabled(): void
    {
        Setting::set('spmb_open', '1');
        Setting::set('spmb_form_enabled', '0');

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Dewi Rahayu',
            'phone' => '08111111111',
            'previous_school' => 'SMP Negeri 2',
            'jalur' => 'afirmasi',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('spmb_registrations', ['full_name' => 'Dewi Rahayu']);
    }

    public function test_registration_validates_required_fields(): void
    {
        Setting::set('spmb_open', '1');
        Setting::set('spmb_form_enabled', '1');

        $response = $this->post(route('ppdb.store'), []);

        $response->assertSessionHasErrors(['full_name', 'phone', 'previous_school', 'jalur']);
    }

    public function test_registration_validates_jalur_enum(): void
    {
        Setting::set('spmb_open', '1');
        Setting::set('spmb_form_enabled', '1');

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Test User',
            'phone' => '081234567890',
            'previous_school' => 'SMP Test',
            'jalur' => 'invalid_jalur',
        ]);

        $response->assertSessionHasErrors(['jalur']);
    }

    public function test_spmb_registration_factory_creates_valid_records(): void
    {
        $registration = SpmbRegistration::factory()->create();

        $this->assertDatabaseHas('spmb_registrations', ['id' => $registration->id]);
        $this->assertContains($registration->jalur, ['zonasi', 'prestasi', 'afirmasi', 'mutasi']);
        $this->assertContains($registration->status, ['pending', 'verified', 'accepted', 'rejected']);
    }
}
