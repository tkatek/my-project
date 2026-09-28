<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFlowTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Curious Traveler',
            'email' => 'curious@example.com',
            'phone' => '+212611111111',
            'subject' => 'experience',
            'message' => 'Which experience suits a family of four?',
            'consent' => '1',
            'request_id' => 'contact-0001',
        ], $overrides);
    }

    public function test_contact_message_is_saved(): void
    {
        $response = $this->postJson('/contacts', $this->validPayload());

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertStringStartsWith('MSG-', $response->json('reference'));

        $this->assertDatabaseHas('contacts', [
            'name' => 'Curious Traveler',
            'email' => 'curious@example.com',
            'phone' => '+212611111111',
            'subject' => 'experience',
            'message' => 'Which experience suits a family of four?',
            'consent' => 1,
            'read_at' => null,
        ]);
    }

    public function test_duplicate_contact_request_id_is_ignored(): void
    {
        $payload = $this->validPayload();

        $this->postJson('/contacts', $payload)->assertOk();
        $this->postJson('/contacts', $payload)->assertOk();

        $this->assertSame(1, Contact::count());
    }

    public function test_invalid_subject_is_rejected(): void
    {
        $response = $this->postJson('/contacts', $this->validPayload([
            'subject' => 'not-a-topic',
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Contact::count());
    }

    public function test_missing_consent_is_rejected(): void
    {
        $response = $this->postJson('/contacts', $this->validPayload([
            'consent' => null,
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Contact::count());
    }

    public function test_honeypot_is_rejected(): void
    {
        $response = $this->postJson('/contacts', $this->validPayload([
            'company_website' => 'http://spam.example',
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Contact::count());
    }

    public function test_short_message_is_rejected(): void
    {
        $response = $this->postJson('/contacts', $this->validPayload([
            'message' => 'Hi',
        ]));

        $response->assertStatus(422);
    }
}
