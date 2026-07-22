<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Application;
use App\Enums\JobStatus;
use App\Services\Base\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class JobService extends BaseService
{
    public function createJob(array $data, int $requesterId): Job
    {
        $data['requester_id'] = $requesterId;
        $data['status'] = 'draft';

        return DB::transaction(function () use ($data){

            return Job::create($data);

        });
    }

    public function getRequesterJobs(int $requesterId): Collection
    {
        return Job::with([
                'category',
                'selectedWorker'
            ])
            ->where('requester_id', $requesterId)
            ->latest()
            ->get();
    }

    public function getOpenJobs(): Collection
    {
        return Job::with([
                'category',
                'requester'
            ])
            ->open()
            ->latest()
            ->get();
    }

    // public function publish(Job $job): Job
    // {
    //     if ($job->status !== 'waiting_payment') {
    //         $this->fail('Quest belum siap dipublikasikan.', 400);
    //     }

    //     $job->update([
    //         'status' => 'open',
    //         'opened_at' => now(),
    //     ]);

    //     return $job->fresh();
    // }

    public function selectWorker(Job $job, int $applicationId): Job
    {
        DB::transaction(function () use ($job, $applicationId) {

            $application = Application::where('job_id', $job->id)
                ->findOrFail($applicationId);

            Application::where('job_id', $job->id)
                ->update([
                    'status' => 'rejected'
                ]);

            $application->update([
                'status' => 'accepted'
            ]);

            $job->update([
                'selected_worker_id' => $application->worker_id,
                'status' => 'taken',
                'taken_at' => now(),
            ]);
        });

        return $job->fresh([
            'selectedWorker',
            'applications'
        ]);
    }

    public function approve(Job $job): Job
    {
        if ($job->status !== 'submitted') {

            $this->fail(
                'Submission belum tersedia.',
                400
            );

        }

        DB::transaction(function () use ($job){

            $job->submission()->update([

                'status'=>'approved'

            ]);

            $job->update([

                'status' => JobStatus::Open->value,

            ]);

        });

        return $job->fresh();
    }

    public function cancel(Job $job): Job
    {
        if (in_array($job->status,[
            'completed',
            'cancelled'
        ])) {

            $this->fail(
                'Quest tidak dapat dibatalkan.',
                400
            );

        }

        $job->update([
            'status'=>'cancelled'
        ]);

        return $job->fresh();
    }

    public function show(Job $job): Job
    {
        $job->load([
            'category',
            'requester',
            'selectedWorker',
        ]);

        if (
            auth()->user()->role->name !== 'admin'
            && $job->requester_id !== auth()->id()
            && $job->selected_worker_id !== auth()->id()
            && $job->status === JobStatus::Draft->value
        ) {
            $this->fail('Anda tidak memiliki akses.', 403);
        }

        return $job;
    }

    public function update(
        Job $job,
        array $data,
        int $userId
    ): Job {

        if ($job->requester_id !== $userId) {

            $this->fail(
                'Anda bukan pemilik quest.',
                403
            );

        }

        if ($job->status !== JobStatus::Draft->value) {

            $this->fail(
                'Quest yang sudah dipublikasikan tidak dapat diubah.',
                400
            );

        }

        $job->update($data);

        return $job->fresh([
            'category',
            'requester',
        ]);

    }

    public function destroy(
        Job $job,
        int $userId
    ): void {

        if ($job->requester_id !== $userId) {

            $this->fail(
                'Anda bukan pemilik quest.',
                403
            );

        }

        if ($job->status !== JobStatus::Draft->value) {

            $this->fail(
                'Quest yang sudah dipublikasikan tidak dapat dihapus.',
                400
            );

        }

        $job->delete();

    }
}