<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Department, Program, Professor, Course, CourseOffering, ClassMeeting, Student, Enrollment};

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dept = Department::firstOrCreate(['code' => 'CS'], ['name' => 'Computer Science']);

        $program = Program::firstOrCreate(
            ['code' => 'CS-BSC'],
            ['name' => 'BSc Computer Science', 'credits_required' => 180, 'department_id' => $dept->id]
        );

        $admin = User::firstOrCreate(['email' => 'admin@polis.local'], [
            'name' => 'System Admin', 'password' => Hash::make('secret123')
        ]);
        $admin->assignRole('system_admin');

        $profUser = User::firstOrCreate(['email' => 'prof@polis.local'], [
            'name' => 'Dr. Luca Lezzerini', 'password' => Hash::make('secret123')
        ]);
        $prof = Professor::firstOrCreate(
            ['user_id' => $profUser->id],
            ['department_id' => $dept->id, 'title' => 'Professor']
        );
        $profUser->assignRole('professor');

        $studentUser = User::firstOrCreate(['email' => 'student@polis.local'], [
            'name' => 'Aron Bazini', 'password' => Hash::make('secret123')
        ]);
        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            ['program_id' => $program->id, 'student_number' => 'S0001'] // <- corrected key
        );
        $studentUser->assignRole('student');

        $course = Course::firstOrCreate(
            ['code' => 'CS101'],
            [
                'department_id' => $dept->id,   // keep if your schema has it
                'program_id' => $program->id, // <-- add this line
                'title' => 'Intro to Programming',
                'credits' => 6,
                'description' => 'Basics of programming.',
            ]
        );

        $offering = CourseOffering::firstOrCreate(
            ['course_id' => $course->id, 'professor_id' => $prof->id, 'term' => '2025-FALL', 'section' => 'A'],
            ['schedule_meta' => ['days' => ['Mon', 'Wed'], 'time' => '10:00'], 'is_active' => true]
        );

        ClassMeeting::firstOrCreate([
            'course_offering_id' => $offering->id,
            'starts_at' => now()->addDay()->setTime(10, 0),
            'ends_at' => now()->addDay()->setTime(12, 0),
            'room' => 'A1',
        ]);

        Enrollment::firstOrCreate(
            ['student_id' => $student->id, 'course_offering_id' => $offering->id],
            ['status' => 'enrolled', 'enrolled_at' => now()]
        );
    }
}
