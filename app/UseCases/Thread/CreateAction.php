<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Collection;

/**
 * 指定資格配下の Part 一覧を、配下 Chapter の Section 件数付きで Eager Load して返すユースケース。
 */
final class CreateAction
{
    /**
     * @return Collection<int, Part>
     */
    public function __invoke(Certification $certifications): Collection
    {
        return $certifications->with('certificates')
                       -> where('status', 'published')
                       -> get();
    }
}