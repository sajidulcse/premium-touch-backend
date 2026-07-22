<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\ConsultationRequest;
use App\Models\EstimatorLead;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get aggregated statistics for the admin dashboard.
     */
    public function index()
    {
        try {
            // General resource counts
            $totalBlogs = Blog::count();
            $totalProjects = Project::count();
            $totalPortfolios = Portfolio::count();
            $totalServices = Service::count();
            $totalTeam = TeamMember::count();

            // Consultations counts
            $totalConsultations = ConsultationRequest::count();
            $pendingConsultations = ConsultationRequest::where('status', 'New')->count();

            // Comment moderation counts
            $totalComments = Comment::whereNull('parent_id')->count();
            $pendingComments = Comment::where('is_approved', false)->count();
            $unrepliedComments = Comment::whereNull('parent_id')
                ->where(function ($q) {
                    $q->where('is_admin_reply', false)->orWhereNull('is_admin_reply');
                })
                ->whereDoesntHave('replies', function ($q) {
                    $q->where('is_admin_reply', true);
                })->count();

            // Estimator leads statistics
            $totalLeads = EstimatorLead::count();
            $avgEstimate = EstimatorLead::avg('total_estimate') ?: 0;
            $totalEstimatedValue = EstimatorLead::sum('total_estimate') ?: 0;

            // Fetch recent consultation requests (last 5)
            $recentConsultations = ConsultationRequest::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Fetch recent estimator leads (last 5)
            $recentLeads = EstimatorLead::with('package')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Detect database driver to construct safe monthly formatting
            $driver = DB::connection()->getDriverName();
            if ($driver === 'sqlite') {
                $monthFormat = "strftime('%Y-%m', created_at)";
            } else {
                $monthFormat = "DATE_FORMAT(created_at, '%Y-%m')";
            }

            // Monthly leads aggregation (last 6 months)
            $monthlyLeads = EstimatorLead::select(
                DB::raw("$monthFormat as month"),
                DB::raw('count(*) as count'),
                DB::raw('sum(total_estimate) as value')
            )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
                ->reverse()
                ->values();

            // Monthly consultations aggregation (last 6 months)
            $monthlyConsultations = ConsultationRequest::select(
                DB::raw("$monthFormat as month"),
                DB::raw('count(*) as count')
            )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
                ->reverse()
                ->values();

            // Estimator leads distribution by Package
            $packageBreakdown = EstimatorLead::select('packages.name', DB::raw('count(estimator_leads.id) as count'))
                ->join('packages', 'packages.id', '=', 'estimator_leads.package_id')
                ->groupBy('packages.name')
                ->get();

            // Estimator leads distribution by Flat Status
            $flatStatusBreakdown = EstimatorLead::select('flat_status', DB::raw('count(*) as count'))
                ->groupBy('flat_status')
                ->get();

            // Consultation requests distribution by Status
            $consultationsStatusBreakdown = ConsultationRequest::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            return response()->json([
                'success' => true,
                'counts' => [
                    'blogs' => $totalBlogs,
                    'projects' => $totalProjects,
                    'portfolios' => $totalPortfolios,
                    'services' => $totalServices,
                    'team' => $totalTeam,
                    'consultations' => $totalConsultations,
                    'pending_consultations' => $pendingConsultations,
                    'comments' => $totalComments,
                    'pending_comments' => $pendingComments,
                    'unreplied_comments' => $unrepliedComments,
                    'leads' => $totalLeads,
                ],
                'leads_stats' => [
                    'avg_estimate' => round($avgEstimate, 2),
                    'total_value' => round($totalEstimatedValue, 2),
                ],
                'recent_consultations' => $recentConsultations,
                'recent_leads' => $recentLeads,
                'monthly_leads' => $monthlyLeads,
                'monthly_consultations' => $monthlyConsultations,
                'package_breakdown' => $packageBreakdown,
                'flat_status_breakdown' => $flatStatusBreakdown,
                'consultations_status_breakdown' => $consultationsStatusBreakdown,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
