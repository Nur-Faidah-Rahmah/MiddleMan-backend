<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Submission;
use App\Models\Application;
use App\Enums\JobStatus;
use App\Services\Base\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmissionService extends BaseService
{
    public function submit(
        Job $job,
        int $workerId,
        array $data
    ): Submission {

        $accepted = Application::where('job_id', $job->id)
            ->where('worker_id', $workerId)
            ->where('status', 'accepted')
            ->exists();

        if (! $accepted) {

            $this->fail(
                'Anda bukan worker yang dipilih.',
                403
            );

        }

        if ($job->selected_worker_id !== $workerId) {

            $this->fail(
                'Anda bukan worker pada quest ini.',
                403
            );

        }

        if ($job->submission()->exists()) {

            $this->fail(
                'Quest ini sudah pernah disubmit.',
                409
            );

        }

        if ($job->status !== JobStatus::Taken->value) {

            $this->fail(
                'Quest belum dapat disubmit.',
                400
            );

        }

        /** @var UploadedFile|null $file */
        $file = $data['attachment'] ?? null;

        $attachmentPath = null;

        if ($file) {

            $attachmentPath = $file->store(
                'submissions',
                'public'
            );

        }

        return DB::transaction(function () use (
            $job,
            $workerId,
            $data,
            $attachmentPath,
            $file
        ) {

            $submission = Submission::create([

                'job_id'=>$job->id,

                'worker_id'=>$workerId,

                'note'=>$data['note'] ?? null,

                'attachment_path'=>$attachmentPath,

                'attachment_type' => $file?->getMimeType(),

                'submitted_at'=>now(),

                'status'=>JobStatus::Submitted->value

            ]);

            $job->update([

                'status'=>JobStatus::Submitted->value

            ]);

            return $submission;

        });

    }

    public function show(Job $job): ?Submission
    {
        if (

            $job->requester_id !== auth()->id()

            &&

            $job->selected_worker_id !== auth()->id()

        ) {

            $this->fail(

                'Anda tidak memiliki akses.',

                403

            );

        }

        return Submission::with([

            'worker.profile',

            'job.category',

            'job.requester'

        ])
        ->where('job_id', $job->id)
        ->first();

    }
}