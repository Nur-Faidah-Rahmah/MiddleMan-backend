<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Escrow;
use App\Models\Transaction;
use App\Models\Submission;
use App\Enums\JobStatus;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\DB;

class EscrowService extends BaseService
{
    /**
     * Membuat data escrow setelah requester melakukan pembayaran.
     */
    public function fund(Job $job, int $requesterId): Escrow
    {
        if ($job->requester_id !== $requesterId) {
            $this->fail(
                'Anda bukan pemilik quest.',
                403
            );
        }

        if ($job->status !== JobStatus::Draft->value) {

            $this->fail(
                'Quest tidak dapat dibayar.',
                400
            );

        }

        return DB::transaction(function () use ($job, $requesterId) {

            $escrow = Escrow::create([
                'job_id'       => $job->id,
                'requester_id' => $requesterId,
                'amount'       => $job->budget,
                'status'       => 'funded',
                'funded_at'    => now(),
            ]);

            $job->update([
                'status'    => JobStatus::Open->value,
                'opened_at' => now(),
            ]);

            Transaction::create([
                'user_id'        => $requesterId,
                'job_id'         => $job->id,
                'escrow_id'      => $escrow->id,
                'amount'         => $job->budget,
                'type'           => 'deposit',
                'status'         => 'success',
                'description'    => 'Deposit dana ke escrow.',
                'transaction_at' => now(),
            ]);

            return $escrow->fresh([
                'requester.profile',
                'worker.profile',
                'job.category'
            ]);
        });
    }

    /**
     * Release dana setelah requester menyetujui hasil pekerjaan.
     */
    public function release(Job $job, int $requesterId): Escrow
    {
        $escrow = Escrow::where('job_id', $job->id)->firstOrFail();

        if ($job->requester_id !== $requesterId) {

            $this->fail(
                'Anda bukan pemilik quest.',
                403
            );

        }

        if ($job->status !== JobStatus::Submitted->value) {

            $this->fail(
                'Submission belum tersedia untuk diproses.',
                400
            );

        }

        $submission = Submission::where('job_id', $job->id)->exists();

        if (! $submission) {

            $this->fail(
                'Submission belum tersedia.',
                400
            );

        }

        if ($escrow->status !== 'funded') {
            $this->fail(
                'Escrow sudah diproses.',
                400
            );
        }

        DB::transaction(function () use ($escrow, $job) {

            $escrow->update([
                'worker_id'   => $job->selected_worker_id,
                'status'      => 'released',
                'released_at' => now(),
            ]);

            Transaction::create([
                'user_id'        => $job->selected_worker_id,
                'job_id'         => $job->id,
                'escrow_id'      => $escrow->id,
                'amount'         => $escrow->amount,
                'type'           => 'release',
                'status'         => 'success',
                'description'    => 'Pelepasan dana escrow ke worker.',
                'transaction_at' => now(),
            ]);

            $job->update([

                'status' => JobStatus::Completed->value,

                'completed_at' => now(),

            ]);

        });

        return $escrow->fresh([
            'requester.profile',
            'worker.profile',
            'job.category'
        ]);
    }

    /**
     * Refund dana ke requester.
     */
    public function refund(
        Job $job,
        int $requesterId
    ): Escrow {

        $escrow = Escrow::where(
            'job_id',
            $job->id
        )->firstOrFail();

        if ($job->requester_id !== $requesterId) {

            $this->fail(
                'Anda bukan pemilik quest.',
                403
            );

        }

        if ($escrow->status !== 'funded') {

            $this->fail(
                'Escrow tidak dapat direfund.',
                400
            );

        }

        DB::transaction(function () use (
            $escrow,
            $job
        ) {

            $escrow->update([

                'status'=>'refunded',

                'refunded_at'=>now()

            ]);

            Transaction::create([

                'user_id'=>$job->requester_id,

                'job_id'=>$job->id,

                'escrow_id'=>$escrow->id,

                'amount'=>$escrow->amount,

                'type'=>'refund',

                'status'=>'success',

                'description'=>'Pengembalian dana escrow.',

                'transaction_at'=>now()

            ]);

        });

        return $escrow->fresh([
            'requester.profile',
            'worker.profile',
            'job.category'
        ]);
    }
}