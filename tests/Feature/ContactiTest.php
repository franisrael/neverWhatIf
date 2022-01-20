<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private $contact;

    public function setUp(): void
    {
        parent::setUp();
        $this->contact = Contact::factory()->create(['name' => 'John']);
    }

    public function test_get_contacts()
    {
        $response = $this->getJson(route('contact.index'));

        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('John', $response->json()[0]['name']);
    }

    public function test_get_single_contact()
    {
        $response = $this->getJson(route('contact.show', $this->contact->id));
        $response->assertOk();

        $this->assertEquals($response->json()['name'], $this->contact->name);
    }

    public function test_store_contact()
    {
        $contact = Contact::factory()->make();
        $response = $this->postJson(route('contact.store'), [
            'name' => $contact->name,
            'email' => $contact->email,
            'message' => $contact->message
        ])->assertCreated()
            ->json();
        $this->assertDatabaseHas('contacts', ['name' => $contact->name]);
    }

    public function test_field_name_required()
    {
        $this->withExceptionHandling();
        $response = $this->postJson(route('contact.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
