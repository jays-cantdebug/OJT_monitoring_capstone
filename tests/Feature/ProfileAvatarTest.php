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
}
