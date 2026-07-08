<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Job\StoreJobRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Job;


class JobController extends Controller
{
    // ==========================================
    // 1. FUNGSI KHUSUS CUSTOMER
    // ==========================================

    public function store(StoreJobRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Inject ID customer yang sedang login & set status awal ke 'pending'
        $data['customer_id'] = auth()->id();
        $data['status'] = 'pending_verification';

        $job = Job::create($data);

        return $this->successResponse($job, 'Tugas berhasil dibuat dan menunggu persetujuan Admin.', 201);
    }

    public function customerJobs(): JsonResponse
    {
        // Mengambil semua tugas yang dibuat oleh customer yang sedang login
        $jobs = Job::with(['category', 'worker'])
            ->where('customer_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($jobs, 'Daftar riwayat tugas Anda berhasil diambil.', 200);
    }

    // ==========================================
    // 2. FUNGSI KHUSUS WORKER
    // ==========================================

    public function availableJobs(): JsonResponse
    {
        // Hanya menampilkan tugas yang sudah disetujui admin (status: approved)
        $jobs = Job::with(['category', 'customer'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($jobs, 'Daftar bursa kerja tersedia berhasil diambil.', 200);
    }

    public function takeJob(Job $job): JsonResponse
    {
        if ($job->status !== 'approved') {
            return $this->errorResponse('Gagal! Tugas ini belum/tidak tersedia untuk diambil.', 400);
        }

        $job->update([
            'status' => 'on_progress',
            'worker_id' => auth()->id() // Catat siapa worker yang mengambilnya
        ]);

        return $this->successResponse($job, 'Tugas berhasil diambil. Selamat bekerja!', 200);
    }

    public function completeJob(Job $job): JsonResponse
    {
        // 1. Pastikan tugas tersebut memang sedang dikerjakan (statusnya 'taken')
        if ($job->status !== 'on_progress') {
            return $this->errorResponse('Gagal! Hanya tugas yang sedang dalam pengerjaan yang bisa diselesaikan.', 400);
        }

        // 2. Keamanan Tambahan: Pastikan Worker yang klik 'selesai' adalah Worker yang benar-benar mengambil tugas itu
        if ($job->worker_id !== auth()->id()) {
            return $this->errorResponse('Akses ditolak! Anda bukan pekerja yang ditugaskan untuk tugas ini.', 403);
        }

        // 3. Ubah status menjadi completed
        $job->update(['status' => 'completed']);

        return $this->successResponse($job, 'Selamat! Tugas telah dinyatakan selesai. Pekerjaan bagus!', 200);
    }

    // ==========================================
    // 3. FUNGSI KHUSUS ADMIN
    // ==========================================

    public function pendingJobs(): JsonResponse
    {
        // Admin melihat daftar antrean tugas yang baru dibuat customer
        $jobs = Job::with(['category', 'customer'])
            ->where('status', 'pending_verification')
            ->orderBy('created_at', 'asc') // Yang paling lama mengantre di atas
            ->get();

        return $this->successResponse($jobs, 'Daftar antrean verifikasi tugas berhasil diambil.', 200);
    }

    public function verifyJob(Job $job): JsonResponse
    {
        if ($job->status !== 'pending_verification') {
            return $this->errorResponse('Hanya tugas berstatus pending yang bisa diverifikasi.', 400);
        }

        $job->update(['status' => 'approved']);

        return $this->successResponse($job, 'Tugas berhasil disetujui dan dilempar ke bursa kerja.', 200);
    }
}