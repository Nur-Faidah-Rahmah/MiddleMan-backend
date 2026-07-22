<?php

namespace App\Services;

use Exception;
use App\Models\Application;
use App\Models\VerificationDocument;
use App\Models\Job;
use App\Services\Base\BaseService;
use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ApplicationService extends BaseService
{
    /**
     * Worker melamar quest.
     */
    public function apply(Job $job, int $workerId): Application
    {
        if ($job->status !== JobStatus::Open->value) {
            $this->fail('Quest tidak tersedia.', 400);
        }

        $verified = VerificationDocument::where('user_id', $workerId)
            ->where('status', 'approved')
            ->exists();

        if (! $verified) {
            $this->fail(
                'Akun Anda belum terverifikasi.',
                403
            );
        }

        if ($job->requester_id === $workerId) {
            $this->fail(
                'Anda tidak dapat melamar quest milik sendiri.',
                400
            );
        }

        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('worker_id', $workerId)
            ->exists();

        if ($alreadyApplied) {
            $this->fail(
                'Anda sudah pernah melamar quest ini.',
                409
            );
        }

        return DB::transaction(function () use ($job, $workerId) {

            return Application::create([
                'job_id' => $job->id,
                'worker_id' => $workerId,
                'status' => 'pending',
                'terms_accepted_at' => now(),
                'terms_accepted_ip' => request()->ip(),
                'applied_at' => now(),
            ]);

        });
    }

    /**
     * Semua pelamar pada suatu job.
     */
    public function applicants(Job $job): Collection
    {
        if ($job->requester_id !== auth()->id()) {

            $this->fail(
                'Anda tidak memiliki akses ke daftar pelamar.',
                403
            );

        }

        return Application::with('worker.profile')
            ->where('job_id', $job->id)
            ->latest()
            ->get();
    }

    /**
     * Riwayat lamaran worker.
     */
    public function myApplications(int $workerId): Collection
    {
        return Application::with([
                'job.category',
                'job.requester'
            ])
            ->where('worker_id', $workerId)
            ->latest()
            ->get();
    }

    /**
     * Requester menerima worker.
     */
    public function accept(Application $application): Application
    {
        if ($application->job->selected_worker_id !== null) {

            $this->fail(
                'Worker untuk quest ini sudah dipilih.',
                409
            );

        }

        if ($application->job->requester_id !== auth()->id()) {

            $this->fail(
                'Anda bukan pemilik quest ini.',
                403
            );

        }

        if ($application->job->status !== JobStatus::Open->value) {

            $this->fail(
                'Quest sudah tidak tersedia.',
                400
            );

        }

        if ($application->status !== 'pending') {

            $this->fail(
                'Lamaran sudah diproses.',
                400
            );

        }

        DB::transaction(function () use ($application) {

            Application::where('job_id', $application->job_id)
                ->update([
                    'status' => 'rejected',
                ]);

            $application->update([
                'status' => 'accepted',
                'responded_at' => now(),
            ]);

            $application->job->update([

                'selected_worker_id' => $application->worker_id,

                'status' => JobStatus::Taken->value,

                'taken_at' => now(),

            ]);

        });

        return $application->fresh([
            'worker',
            'job'
        ]);
    }

    /**
     * Tolak satu lamaran.
     */
    public function reject(Application $application): Application
    {
        if ($application->job->requester_id !== auth()->id()) {

            $this->fail(
                'Anda bukan pemilik quest ini.',
                403
            );

        }

        if ($application->status !== 'pending') {

            $this->fail(
                'Lamaran sudah diproses.',
                400
            );

        }

        $application->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return $application;
    }
}