<?php

namespace App\Enums;

enum AuditAction: string
{
    case LoggedIn = 'logged_in';
    case CreatedDeanAccount = 'created_dean_account';
    case CreatedStudentAccount = 'created_student_account';
    case SelfRegisteredAccount = 'self_registered_account';
    case ApprovedStudentAccount = 'approved_student_account';
    case RejectedStudentAccount = 'rejected_student_account';
    case ResetStudentPassword = 'reset_student_password';
    case UpdatedProfile = 'updated_profile';

    public function label(): string
    {
        return match ($this) {
            self::LoggedIn => 'Logged In',
            self::CreatedDeanAccount => 'Created Dean Account',
            self::CreatedStudentAccount => 'Created Student Account',
            self::SelfRegisteredAccount => 'Self-Registered Account',
            self::ApprovedStudentAccount => 'Approved Student Account',
            self::RejectedStudentAccount => 'Rejected Student Account',
            self::ResetStudentPassword => 'Reset Student Password',
            self::UpdatedProfile => 'Updated Profile',
        };
    }
}
