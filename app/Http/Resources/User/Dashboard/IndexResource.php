<?php

declare(strict_types=1);

namespace App\Http\Resources\User\Dashboard;

use App\Models\AcademicYear;
use App\Models\AcademicYearUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    protected ?AcademicYear $activeYear = null;

    public function withActiveYear(AcademicYear $year): self
    {
        $this->activeYear = $year;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $academicYearUser = AcademicYearUser::where([
            ['academic_year_id', '=', $this->activeYear->id],
            ['user_id', '=', $this->id]
        ])->firstOrFail();

        return [
            'id' => $this->id,
            'name' => $this->getFullName(),
            'email' => $this->auth_step === 2 ? $this->email : '-',
            'role' => $academicYearUser->role->label(),
            'is_active' => $this->is_active,
            'edit_link' => route('dashboard.academic_year_user.edit', [
                'academicYear' => $this->activeYear->slug,
                'academic_year_user' => $academicYearUser->id,
            ])
        ];
    }
}
