<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\{User, Program, Student, Course, Enrollment, Exam, Grade, Invoice, Payment, Scholarship};

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

//        // Create a Program
//        $program = Program::create([
//            'name' => 'Computer Science',
//        ]);
//        // Create a User (e.g., a student user)
//        $user = User::create([
//            'name' => 'John Doe',
//            'email' => 'john@example.com',
//            'password' => Hash::make('secret'),
//        ]);
//        // Create a Student linked to the User and Program
//        $student = Student::create([
//            'user_id' => $user->id,
//            'program_id' => $program->id,
//            'student_number' => 'S12345',
//        ]);
//        // Create a Course in the Program
//        $course = Course::create([
//            'program_id' => $program->id,
//            'title' => 'Introduction to Programming',
//        ]);
//        // Enroll the student in the course
//        $enrollment = Enrollment::create([
//            'student_id' => $student->id,
//            'course_id' => $course->id,
//            'enrolled_at' => now(),
//        ]);
//        // Create an Exam for the course
//        $exam = Exam::create([
//            'course_id' => $course->id,
//            'exam_type' => 'Midterm',
//            'scheduled_at' => now()->addWeeks(8),
//        ]);
//        // Record a Grade for the student on that exam
//        $grade = Grade::create([
//            'student_id' => $student->id,
//            'exam_id' => $exam->id,
//            'score' => 87.5,
//        ]);
//        // Create an Invoice for the student
//        $invoice = Invoice::create([
//            'student_id' => $student->id,
//            'amount' => 1000.00,
//            'status' => 'unpaid',
//        ]);
//        // Record a Payment for that invoice
//        $payment = Payment::create([
//            'invoice_id' => $invoice->id,
//            'amount' => 1000.00,
//            'paid_at' => now(),
//        ]);
//        // Award a Scholarship to the student
//        $scholarship = Scholarship::create([
//            'student_id' => $student->id,
//            'name' => 'Merit Scholarship',
//            'amount' => 5000.00,
//        ]);
        $this->call([
            RbacSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
