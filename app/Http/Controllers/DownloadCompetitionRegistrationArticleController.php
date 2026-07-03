<?php

namespace App\Http\Controllers;

use App\Models\CompetitionRegistration;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadCompetitionRegistrationArticleController extends Controller
{
    public function __invoke(CompetitionRegistration $registration): StreamedResponse
    {
        Gate::authorize('view', $registration);

        abort_if(blank($registration->article_file), 404);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        abort_unless($disk->exists($registration->article_file), 404);

        return $disk->download(
            $registration->article_file,
            basename($registration->article_file),
        );
    }
}
