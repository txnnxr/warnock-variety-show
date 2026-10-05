<?php

namespace Tests\Feature;

use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_archive_lists_past_shows_only(): void
    {
        Show::factory()->create(['name' => 'Spring Show', 'date' => now()->subMonths(2)]);
        Show::factory()->create(['name' => 'Rained Out', 'date' => now()->subMonth(), 'canceled' => true]);
        Show::factory()->create(['name' => 'Next Show', 'date' => now()->addWeek()]);

        $this->get('/shows/archive')
            ->assertOk()
            ->assertSee('Spring Show')
            ->assertDontSee('Rained Out')
            ->assertDontSee('Next Show');
    }

    public function test_admin_can_upload_and_delete_photos(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $show = Show::factory()->create(['date' => now()->subWeek()]);

        $this->actingAs($admin)->post("/shows/{$show->id}/photos", [
            'photos' => [UploadedFile::fake()->image('stage.jpg'), UploadedFile::fake()->image('crowd.jpg')],
            'caption' => 'Opening night',
        ])->assertRedirect("/shows/{$show->id}/view");

        $this->assertCount(2, $show->photos);
        $photo = $show->photos->first();
        Storage::disk('public')->assertExists($photo->path);
        $this->get("/shows/{$show->id}/view")->assertSee($photo->url)->assertSee('Opening night');
        $this->get('/shows/archive')->assertSee($photo->url);

        $this->actingAs($admin)->delete("/photos/{$photo->id}");

        Storage::disk('public')->assertMissing($photo->path);
        $this->assertCount(1, $show->fresh()->photos);
    }

    public function test_photo_gallery_uploads_and_deletes_without_reloading(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $show = Show::factory()->create(['name' => 'Fall Show']);

        $response = $this->actingAs($admin)->postJson("/shows/{$show->id}/photos", [
            'photos' => [UploadedFile::fake()->image('stage.jpg')],
        ])->assertOk();

        $photo = $show->photos()->first();
        $response->assertExactJson(['photos' => [[
            'id' => $photo->id,
            'url' => $photo->url,
            'caption' => null,
            'alt' => 'Fall Show',
            'delete_url' => route('photos.destroy', $photo),
        ]]]);

        $this->actingAs($admin)->deleteJson("/photos/{$photo->id}")->assertNoContent();

        Storage::disk('public')->assertMissing($photo->path);
        $this->assertCount(0, $show->photos()->get());
    }

    public function test_upload_validation_errors_come_back_as_json(): void
    {
        Storage::fake('public');
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->postJson("/shows/{$show->id}/photos", ['photos' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')]])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('photos.0');
    }

    public function test_admins_get_an_empty_gallery_ready_for_uploads(): void
    {
        $show = Show::factory()->create();

        $this->get("/shows/{$show->id}/view")->assertDontSee('photoGallery');
        $this->actingAs(User::factory()->admin()->create())
            ->get("/shows/{$show->id}/view")->assertSee('photoGallery');
    }

    public function test_only_images_can_be_uploaded(): void
    {
        Storage::fake('public');
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post("/shows/{$show->id}/photos", ['photos' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')]])
            ->assertSessionHasErrors('photos.0');
    }

    public function test_non_admins_cannot_upload_photos(): void
    {
        Storage::fake('public');
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post("/shows/{$show->id}/photos", ['photos' => [UploadedFile::fake()->image('stage.jpg')]])
            ->assertForbidden();
    }
}
