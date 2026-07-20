<?php

namespace App\Services;

use App\Models\Job;
use Exception;

class JobService
{
    public function createJob(array $data, int $customerId): Job
    {
        $data['customer_id'] = $customerId;
        $data['status'] = 'pending_verification';
        
        return Job::create($data);
    }

    public function verifyJob(Job $job): Job
    {
        if ($job->status !== 'pending_verification') {
            throw new Exception('Hanya tugas berstatus pending yang bisa diverifikasi.', 400);
        }

        $job->update(['status' => 'approved']);
        return $job;
    }

    public function takeJob(Job $job, int $workerId): Job
    {
        if ($job->status !== 'approved') {
            throw new Exception('Gagal! Tugas ini belum/tidak tersedia untuk diambil.', 400);
        }

        $job->update([
            'status' => 'taken',
            'worker_id' => $workerId
        ]);
        
        return $job;
    }

    public function completeJob(Job $job, int $workerId): Job
    {
        if ($job->status !== 'taken') {
            throw new Exception('Gagal! Hanya tugas yang sedang dalam pengerjaan yang bisa diselesaikan.', 400);
        }

        if ($job->worker_id !== $workerId) {
            throw new Exception('Akses ditolak! Anda bukan pekerja yang ditugaskan untuk tugas ini.', 403);
        }

        $job->update(['status' => 'completed']);
        return $job;
    }

    public function getCustomerJobHistory(int $customerId)
    {
        return Job::with(['category', 'worker'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAvailableJobsForWorker()
    {
        return Job::with(['category', 'customer'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPendingJobsForAdmin()
    {
        return Job::with(['category', 'customer'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}