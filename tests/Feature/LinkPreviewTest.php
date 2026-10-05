<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LinkPreviewTest extends TestCase
{
    use RefreshDatabase;

    private function meta(string $html, string $property): ?string
    {
        preg_match('/<meta (?:property|name)="'.preg_quote($property, '/').'" content="([^"]*)"/', $html, $match);

        return isset($match[1]) ? html_entity_decode($match[1], ENT_QUOTES) : null;
    }

    public function test_home_page_has_site_previews(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertSame('Warnock Variety Show', $this->meta($html, 'og:title'));
        $this->assertSame(url('/images/background.jpg'), $this->meta($html, 'og:image'));
        $this->assertNotEmpty($this->meta($html, 'og:description'));
    }

    public function test_show_page_previews_the_show(): void
    {
        $show = Show::factory()->create(['name' => 'The Autumn Spectacular', 'date' => '2026-10-17 20:00:00', 'description' => "Tap dancing & fire poetry.\nBring a chair."]);

        $html = $this->get("/shows/{$show->id}/view")->getContent();

        $this->assertSame('The Autumn Spectacular', $this->meta($html, 'og:title'));
        $this->assertSame('Saturday, October 17 · 8:00pm · Tap dancing & fire poetry. Bring a chair.', $this->meta($html, 'og:description'));
        $this->assertSame(url("/shows/{$show->id}/view"), $this->meta($html, 'og:url'));
        $this->assertStringNotContainsString($show->address, $html);
    }

    public function test_show_preview_uses_the_first_photo(): void
    {
        Storage::fake('public');
        $show = Show::factory()->create(['date' => now()->subWeek()]);
        $photo = $show->photos()->create(['path' => UploadedFile::fake()->image('stage.jpg')->store('shows', 'public')]);

        $this->assertSame($photo->url, $this->meta($this->get("/shows/{$show->id}/view")->getContent(), 'og:image'));
    }

    public function test_invitation_links_preview_with_the_guests_name(): void
    {
        $invite = Invite::factory()->create(['first_name' => "D'Angelo"]);

        $html = $this->get("/shows/{$invite->show_id}/invite/respond/{$invite->key}")->getContent();

        $this->assertSame("{$invite->show->name} · D'Angelo's invitation", $this->meta($html, 'og:title'));
        $this->assertSame(1, substr_count($html, 'property="og:title"'));
    }
}
