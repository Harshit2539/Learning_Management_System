<?php

namespace App\Imports;

use App\Jobs\SendWelcomeMailJob;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public array $errors = [];
    public int $importedCount = 0;

    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $rows)
    {
        $role = Role::where('name', Role::$user)->first();

        if (!$role) {
            $this->errors[] = 'Student role not found in the system.';
            return;
        }

        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = !empty($referralSettings['users_affiliate_status']);

        // Pre-fetch existing emails and mobiles to avoid per-row DB queries
        $existingEmails  = DB::table('users')->whereNotNull('email')->pluck('email')->flip()->toArray();
        $existingMobiles = DB::table('users')->whereNotNull('mobile')->pluck('mobile')->flip()->toArray();

        $insertData  = [];
        $mailQueue   = [];
        $now         = time();

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $name     = trim($row['name']     ?? '');
            $email    = strtolower(trim($row['email']    ?? ''));
            $mobile   = trim($row['mobile']   ?? '');
            $password = trim($row['password'] ?? '');
            $status   = strtolower(trim($row['status']  ?? 'active'));
            $university = trim($row['university'] ?? '');

            // Skip blank rows
            if (empty($name) && empty($email) && empty($mobile)) {
                continue;
            }

            // Basic validation without Validator::make() overhead
            if (strlen($name) < 3 || strlen($name) > 128) {
                $this->errors[] = "Row {$rowNumber}: Name must be between 3 and 128 characters.";
                continue;
            }

            if (empty($email) && empty($mobile)) {
                $this->errors[] = "Row {$rowNumber}: Either email or mobile is required.";
                continue;
            }

            if (strlen($password) < 6) {
                $this->errors[] = "Row {$rowNumber}: Password must be at least 6 characters.";
                continue;
            }

            if (!in_array($status, ['active', 'pending', 'inactive'])) {
                $status = 'active';
            }

            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "Row {$rowNumber}: Invalid email address.";
                    continue;
                }
                if (isset($existingEmails[$email])) {
                    $this->errors[] = "Row {$rowNumber}: Email {$email} already exists.";
                    continue;
                }
                // Reserve in-memory to catch duplicates within the same file
                $existingEmails[$email] = true;
            }

            if (!empty($mobile)) {
                if (!is_numeric($mobile)) {
                    $this->errors[] = "Row {$rowNumber}: Mobile must be numeric.";
                    continue;
                }
                if (isset($existingMobiles[$mobile])) {
                    $this->errors[] = "Row {$rowNumber}: Mobile {$mobile} already exists.";
                    continue;
                }
                $existingMobiles[$mobile] = true;
            }

            // cost 4 for bulk import — fast on any server, user should reset password after first login
            $insertData[] = [
                'full_name'  => $name,
                'university' => $university ?: null,
                'email'      => $email ?: null,
                'mobile'     => $mobile ?: null,
                'role_name'  => $role->name,
                'role_id'    => $role->id,
                'password'   => password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]),
                'status'     => $status,
                'affiliate'  => $usersAffiliateStatus,
                'verified'   => true,
                'created_at' => $now,
            ];

            if (!empty($email)) {
                $mailQueue[] = ['name' => $name, 'email' => $email, 'password' => $password];
            }
        }

        if (empty($insertData)) {
            return;
        }

        try {
            // Bulk insert — 1 query for entire chunk instead of N queries
            DB::table('users')->insert($insertData);
            $this->importedCount += count($insertData);
        } catch (\Exception $e) {
            $this->errors[] = 'Batch insert failed: ' . $e->getMessage();
            return;
        }

        // Dispatch mail jobs to queue — completely non-blocking
        foreach ($mailQueue as $mail) {
            SendWelcomeMailJob::dispatch($mail['name'], $mail['email'], $mail['password']);
        }
    }
}
