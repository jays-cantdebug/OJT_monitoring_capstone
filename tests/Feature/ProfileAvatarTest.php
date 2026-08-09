<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_upload_an_avatar(): void
    {
        Storage::fake('public');

        $student = User::factory()->create();

        $response = $this->actingAs($student)->put('/student/profile', [
            'avatar' => UploadedFile::fake()->image('me.jpg'),
        ]);

        $response->assertRedirect();

        $student->refresh();
        $this->assertNotNull($student->avatar_path);
        Storage::disk('public')->assertExists($student->avatar_path);
        $this->assertNotNull($student->avatarUrl());
    }

    public function test_uploading_a_new_avatar_deletes_the_old_one(): void
    {
        Storage::fake('public');

        $student = User::factory()->create();

        $this->actingAs($student)->put('/student/profile', [
            'avatar' => UploadedFile::fake()->image('first.jpg'),
        ]);
        $firstPath = $student->refresh()->avatar_path;

        $this->actingAs($student)->put('/student/profile', [
            'avatar' => UploadedFile::fake()->image('second.jpg'),
        ]);
        $secondPath = $student->refresh()->avatar_path;

        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
    }

    public function test_updating_phone_alone_does_not_touch_an_existing_avatar(): void
    {
        Storage::fake('public');

        $student = User::factory()->create();

        $this->actingAs($student)->put('/student/profile', [
            'avatar' => UploadedFile::fake()->image('me.jpg'),
        ]);
        $avatarPath = $student->refresh()->avatar_path;

        $this->actingAs($student)->put('/student/profile', [
            'phone' => '09171234567',
        ]);

        $student->refresh();
        $this->assertSame($avatarPath, $student->avatar_path);
        $this->assertSame('09171234567', $student->phone);
        Storage::disk('public')->assertExists($avatarPath);
    }

    public function test_avatar_upload_rejects_non_image_files(): void
    {
        Storage::fake('public');

        $student = User::factory()->create();

        $response = $this->actingAs($student)->put('/student/profile', [
            'avatar' => UploadedFile::fake()->create('resume.pdf', 100),
        ]);

        $response->assertSessionHasErrors('avatar');
        $this->assertNull($student->refresh()->avatar_path);
    }

    public function test_avatar_url_matches_the_actual_request_origin_not_app_url(): void
    {
        // Regression test: avatarUrl() used to hardcode config('app.url') via
        // Storage::disk('public')->url(), which broke on this machine because
        // local dev runs on a port APP_URL doesn't reflect (php -S on a
        // non-standard port). asset() must resolve against whatever host is
        // actually serving the request instead.
        Storage::fake('public');
        config(['app.url' => 'http://this-does-not-match.invalid']);

        $student = User::factory()->create();
        $this->actingAs($student)->put('/student/profile', [
            'avatar' => UploadedFile::fake()->image('me.jpg'),
        ]);
        $student->refresh();

        $response = $this->actingAs($student)->get('http://127.0.0.1:9001/student/profile');

        $response->assertOk();
        $response->assertSee('http://127.0.0.1:9001/storage/'.$student->avatar_path, false);
        $response->assertDontSee('this-does-not-match.invalid');
    }
}
