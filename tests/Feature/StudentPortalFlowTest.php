<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPortalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login(): void
    {
        $user = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_student_can_submit_enrollment_application(): void
    {
        $response = $this->post('/enroll', [
            'first_name' => 'Maria',
            'middle_name' => 'B',
            'last_name' => 'Dela Cruz',
            'dob' => '2008-05-10',
            'gender' => 'Female',
            'civil_status' => 'Single',
            'nationality' => 'Filipino',
            'email' => 'maria@example.com',
            'contact_number' => '09171234567',
            'address' => 'Manila',
            'student_type' => 'New Student',
            'course_id' => 1,
            'year_level' => '1',
            'previous_school' => 'St. Joseph Academy',
            'emergency_full_name' => 'Pedro Dela Cruz',
            'emergency_relationship' => 'Father',
            'emergency_contact_number' => '09180000000',
            'emergency_address' => 'Manila',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('enrollment_applications', [
            'email' => 'maria@example.com',
            'status' => 'submitted',
        ]);
    }
}
