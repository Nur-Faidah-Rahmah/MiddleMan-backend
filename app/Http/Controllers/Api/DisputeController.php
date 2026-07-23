<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\User;
use App\Models\Escrow;
use App\Models\Transaction;
use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Job\JobResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisputeController extends Controller
{
    /**
     * File a dispute for a job in submitted state.
     * POST /api/v1/disputes/jobs/{job}
     * Body: { "reason": "..." }
     * Accessible by: requester or worker assigned to the job.
     */
    public function file(Request $request, Job $job): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $userId = auth()->id();

        // Only the requester or the selected worker can file a dispute
        if ($job->requester_id !== $userId && $job->selected_worker_id !== $userId) {
            return $this->errorResponse('Anda tidak berhak mengajukan dispute untuk quest ini.', 403);
        }

        if ($job->status !== JobStatus::Submitted->value) {
            return $this->errorResponse('Dispute hanya dapat diajukan pada quest yang sudah disubmit.', 400);
        }

        $job->update([
            'status'      => JobStatus::Disputed->value,
            'dispute_reason' => $request->reason,
            'disputed_by' => $userId,
            'disputed_at' => now(),
        ]);

        return $this->successResponse(
            new JobResource($job->load('requester.profile', 'selectedWorker.profile', 'category')),
            'Dispute berhasil diajukan. Admin akan meninjau kasus ini.'
        );
    }

    /**
     * Resolve a dispute (Admin only).
     * POST /api/v1/disputes/jobs/{job}/resolve
     * Body: { "decision": "worker_won" | "requester_won", "notes": "..." }
     */
    public function resolve(Request $request, Job $job): JsonResponse
    {
        $request->validate([
            'decision' => 'required|in:worker_won,requester_won',
            'notes'    => 'nullable|string|max:2000',
        ]);

        if ($job->status !== JobStatus::Disputed->value) {
            return $this->errorResponse('Quest ini tidak sedang dalam status dispute.', 400);
        }

        $escrow = Escrow::where('job_id', $job->id)->firstOrFail();

        if ($escrow->status !== 'funded') {
            return $this->errorResponse('Escrow sudah diproses sebelumnya.', 400);
        }

        DB::transaction(function () use ($request, $escrow, $job) {

            if ($request->decision === 'worker_won') {

                // Release funds to worker
                $escrow->update([
                    'worker_id'   => $job->selected_worker_id,
                    'status'      => 'released',
                    'released_at' => now(),
                ]);

                $worker = User::findOrFail($job->selected_worker_id);
                if ($worker->profile) {
                    $worker->profile->increment('wallet_balance', $escrow->amount);
                }

                Transaction::create([
                    'user_id'        => $job->selected_worker_id,
                    'job_id'         => $job->id,
                    'escrow_id'      => $escrow->id,
                    'amount'         => $escrow->amount,
                    'type'           => 'release',
                    'status'         => 'success',
                    'description'    => 'Pelepasan dana escrow (Keputusan Dispute: Worker Menang) Quest #' . $job->id . '.',
                    'transaction_at' => now(),
                ]);

                $job->update([
                    'status'       => JobStatus::Completed->value,
                    'completed_at' => now(),
                    'dispute_notes' => $request->notes,
                ]);

            } else {

                // Refund funds to requester
                $escrow->update([
                    'status'      => 'refunded',
                    'refunded_at' => now(),
                ]);

                $requester = User::findOrFail($job->requester_id);
                if ($requester->profile) {
                    $requester->profile->increment('wallet_balance', $escrow->amount);
                }

                Transaction::create([
                    'user_id'        => $job->requester_id,
                    'job_id'         => $job->id,
                    'escrow_id'      => $escrow->id,
                    'amount'         => $escrow->amount,
                    'type'           => 'refund',
                    'status'         => 'success',
                    'description'    => 'Pengembalian dana escrow (Keputusan Dispute: Requester Menang) Quest #' . $job->id . '.',
                    'transaction_at' => now(),
                ]);

                $job->update([
                    'status'        => JobStatus::Cancelled->value,
                    'dispute_notes' => $request->notes,
                ]);
            }
        });

        return $this->successResponse(
            new JobResource($job->fresh()->load('requester.profile', 'selectedWorker.profile', 'category')),
            'Dispute berhasil diselesaikan.'
        );
    }
}
