<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewsletterExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly string $type) {}

    public function collection(): Collection
    {
        return $this->type === 'mobile'
            ? $this->mobileCollection()
            : $this->emailCollection();
    }

    public function headings(): array
    {
        return [$this->type];
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

    private function mobileCollection(): Collection
    {
        return User::query()
            ->whereNotNull('mobile')
            ->latest()
            ->get(['mobile'])
            ->map(fn(User $user) => [
                'mobile' => $user->mobile,
            ]);
    }
}
