<?php

declare(strict_types=1);

namespace App\UseCases\MeetingPack;

use App\Enums\MeetingPackStatus;
use App\Models\User;
use App\Models\MeetingPack;
use Illuminate\Database\Eloquent\Collection;

final class IndexAction
{
    /**
     * @return Collection<int, CertificationCategory>
     */
    public function __invoke(User $admin, array $filters = []): array
    {
        $keyword = $filters['keyword'] ?? null;
        $status = $filters['status'] ?? null;

        $plans = MeetingPack::query()
               ->when(
                        $status ?? null,
                        fn ($q, string $status) => $q->where('status', $status),
                    )
               ->keyword($keyword)
               ->ordered()
               ->paginate(10);

        $selectStatus = [
            'draft' => '下書き',
            'published' => '公開中',
            'archived' => 'アーカイブ',
        ];

        return [
            'plans' => $plans,
            'keyword' => $keyword,
            'status' => $selectStatus,
        ];
    }
}