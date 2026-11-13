<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Models\User;
use App\Models\QaThread;
use App\Models\Certification;
use App\Enums\QaThreadStatus;
use Illuminate\Database\Eloquent\Collection;

final class IndexAction
{
    /**
     * @return Collection<int, QuestionCategory>
     */
    public function __invoke(User $viewer, array $filters = []): array
    {
        $keyword = $filters['keyword'] ?? null;
        $status = $filters['status'] ?? null;
        $certification_id = $filters['certification_id'] ?? null;

        $threads = QaThread::with(['certification.certificates', 'user', 'replies'])
                -> withCount('replies')
                -> whereHas(
                    'certification.certificates',
                    fn ($q) => $q->where('status', 'published'),
                )
                -> whereHas(
                    'certification',
                    fn ($q) => $q->where('status', 'published'),
                )
                ->when(
                        $status ?? null,
                        fn ($q, string $status) => $q->where('status', $status),
                    )
                -> keyword($keyword)
                -> certificationSearch($certification_id)
                -> orderBy('title')
                -> paginate(10);

        $certifications = Certification::with('certificates')
                       -> where('status', 'published')
                       -> get();

        $status = $threads->pluck('certification.certificate');

        $segments = [
            '' => '全て',
            'unresolved' => '未解決',
            'resolved' => '解決済',
        ];

        return [
            'threads' => $threads,
            'filters' => $filters,
            'certifications' => $certifications,
            'status' => $status,
            'segments' => $segments
        ];
    }
}

