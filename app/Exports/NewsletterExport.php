<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewsletterExport implements FromCollection, WithHeadings
{
    public function __construct(
        private readonly string $type,
        private readonly bool   $includeStudentName
    )
    {
    }

    public function collection(): Collection
    {
        return match ($this->type) {
            'mobile', 'student' => $this->studentCollection(),
            'father' => $this->fatherCollection(),
            'mother' => $this->motherCollection(),
            default => $this->emailCollection(),
        };
    }

    public function headings(): array
    {
        $headings = [];

        if ($this->includeStudentName && $this->type !== 'email') {
            $headings[] = 'name';
        }

        $headings[] = match ($this->type) {
            'mobile', 'student' => 'mobile',
            'father' => 'father_mobile',
            'mother' => 'mother_mobile',
            default => 'email',
        };

        return $headings;
    }

    private function emailCollection(): Collection
    {
        return User::query()
            ->whereNotNull('email')
            ->latest()
            ->get(['email'])
            ->map(fn(User $user) => [
                'email' => $user->email,
            ]);
    }

    private function studentCollection(): Collection
    {
        return User::query()
            ->whereNotNull('mobile')
            ->latest()
            ->get(['name', 'mobile'])
            ->map(function (User $user) {
                $row = [];

                if ($this->includeStudentName) {
                    $row['name'] = $user->name;
                }

                $row['mobile'] = $user->mobile;

                return $row;
            });
    }

    private function fatherCollection(): Collection
    {
        return User::query()
            ->whereHas('personalInformation', fn($query) => $query->whereNotNull('father_mobile'))
            ->with('personalInformation')
            ->latest()
            ->get()
            ->map(function (User $user) {
                $row = [];

                if ($this->includeStudentName) {
                    $row['name'] = $user->name;
                }

                $row['father_mobile'] = $user->personalInformation?->father_mobile;

                return $row;
            });
    }

    private function motherCollection(): Collection
    {
        return User::query()
            ->whereHas('personalInformation', fn($query) => $query->whereNotNull('mother_mobile'))
            ->with('personalInformation')
            ->latest()
            ->get()
            ->map(function (User $user) {
                $row = [];

                if ($this->includeStudentName) {
                    $row['name'] = $user->name;
                }

                $row['mother_mobile'] = $user->personalInformation?->mother_mobile;

                return $row;
            });
    }
}
