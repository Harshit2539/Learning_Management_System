<?php

namespace App\Imports;

use App\Mail\WelcomeUser;
use App\Models\Role;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $importedCount = 0;

    public function collection(Collection $rows)
    {
        $role = Role::where('name', Role::$user)->first();

        if (empty($role)) {
            $this->errors[] = 'Student role not found in the system.';
            return;
        }

        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = (!empty($referralSettings) && !empty($referralSettings['users_affiliate_status']));

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because row 1 is heading

            $data = [
                'full_name' => trim($row['name'] ?? ''),
                'university' => trim($row['university'] ?? ''),
                'mobile'    => trim($row['mobile'] ?? ''),
                'email'     => trim($row['email'] ?? ''),
                'status'    => strtolower(trim($row['status'] ?? 'active')),
                'password'  => trim($row['password'] ?? ''),
            ];

            // Skip completely empty rows
            if (empty($data['full_name']) && empty($data['email']) && empty($data['mobile'])) {
                continue;
            }

            $validator = Validator::make($data, [
                'full_name' => 'required|min:3|max:128',
                'email'     => 'nullable|email|unique:users,email',
                'mobile'    => 'nullable|numeric|unique:users,mobile',
                'status'    => 'required|in:active,pending,inactive',
                'password'  => 'required|min:6',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->errors[] = "Row {$rowNumber}: {$error}";
                }
                continue;
            }

            if (empty($data['email']) && empty($data['mobile'])) {
                $this->errors[] = "Row {$rowNumber}: Either email or mobile is required.";
                continue;
            }

            $userData = [
                'full_name'  => $data['full_name'],
                'university' => $data['university'] ?: null,
                'role_name'  => $role->name,
                'role_id'    => $role->id,
                'password'   => User::generatePassword($data['password']),
                'status'     => $data['status'],
                'affiliate'  => $usersAffiliateStatus,
                'verified'   => true,
                'created_at' => time(),
            ];

            if (!empty($data['email'])) {
                $userData['email'] = $data['email'];
            }

            if (!empty($data['mobile'])) {
                $userData['mobile'] = $data['mobile'];
            }

            User::create($userData);
            $this->importedCount++;

            // Send welcome email with credentials
            if (!empty($data['email'])) {
                try {
                    Mail::to($data['email'])->send(new WelcomeUser(
                        $data['full_name'],
                        $data['email'],
                        $data['password']
                    ));
                } catch (\Exception $e) {
                    // mail failure should not block import
                }
            }
        }
    }
}
