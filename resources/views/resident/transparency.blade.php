@extends('layouts.app')

@section('title', 'Transparency')
@section('page-title', 'Transparency')

@section('content')

<!-- Tab Navigation -->
<div class="bg-white border-b border-gray-200 -mx-8 -mt-8 mb-0">
    <div class="max-w-7xl mx-auto px-8 pt-4 pb-0">
        <div class="flex space-x-2 overflow-x-auto pb-0">
            <button onclick="switchTab('budget')" id="tab-budget" class="tab-button active px-6 py-3 rounded-t-xl font-medium transition whitespace-nowrap">
                Budget
            </button>
            <button onclick="switchTab('projects')" id="tab-projects" class="tab-button px-6 py-3 rounded-t-xl font-medium transition whitespace-nowrap">
                Projects
            </button>
            <button onclick="switchTab('announcements')" id="tab-announcements" class="tab-button px-6 py-3 rounded-t-xl font-medium transition whitespace-nowrap">
                Announcements
            </button>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto bg-gray-50">

    <!-- BUDGET TAB CONTENT -->
    <div id="content-budget" class="tab-content py-6">

        <!-- Budget Overview Card -->
        @if($budget)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4">
            <h2 class="text-base font-bold text-gray-800 mb-4">Budget Overview ({{ $budget['year'] ?? date('Y') }})</h2>

            @php
                $total = $budget['totalBudget'] ?? 0;
                $spent = $budget['totalSpent'] ?? 0;
                $remaining = $budget['remainingBudget'] ?? 0;
                $spentPercent = $total > 0 ? ($spent / $total) * 100 : 0;
                $remainingPercent = $total > 0 ? ($remaining / $total) * 100 : 0;
            @endphp

            <!-- Total Budget -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Total Budget</span>
                    <span class="text-sm font-semibold text-gray-800">₱{{ number_format($total, 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gray-800 h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            <!-- Spent -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Spent</span>
                    <span class="text-sm font-semibold text-blue-600">₱{{ number_format($spent, 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $spentPercent }}%"></div>
                </div>
            </div>

            <!-- Remaining -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Remaining</span>
                    <span class="text-sm font-semibold text-green-600">₱{{ number_format($remaining, 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $remainingPercent }}%"></div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4 text-center">
            <p class="text-gray-400 text-sm">No budget data available</p>
        </div>
        @endif

        <!-- Financial Reports Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4">
            <h2 class="text-base font-bold text-gray-800 mb-2">Financial Reports</h2>
            <p class="text-xs text-gray-500 mb-4">Download detailed financial reports and transparency documents</p>

            @if(count($reports) > 0)
            <div class="space-y-3">
                @foreach($reports as $report)
                <a href="{{ $report['fileUrl'] }}" target="_blank" class="flex items-center justify-between p-4 border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                    <span class="text-sm text-gray-800">{{ $report['title'] }}</span>
                    <i class="fas fa-download text-gray-500 text-sm"></i>
                </a>
                @endforeach
            </div>
            @else
            <div class="py-6 text-center">
                <p class="text-gray-400 text-sm">No reports available</p>
            </div>
            @endif
        </div>

        <!-- Budget Breakdown Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-bold text-gray-800 mb-4">Budget Breakdown</h2>

            @if(count($projects) > 0)
                @foreach($projects as $project)
                <div class="pb-3 mb-3 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xs font-medium text-gray-800 mb-1">{{ $project['name'] }}</h3>
                            <p class="text-xs text-gray-500">
                                {{ $project['status'] === 'completed' ? 'Completed' : $project['progress'] . '% complete' }}
                            </p>
                        </div>
                        <span class="text-xs font-medium text-gray-800">₱{{ number_format($project['totalBudget'], 0) }}</span>
                    </div>
                </div>
                @endforeach
            @else
            <div class="py-6 text-center">
                <p class="text-gray-400 text-sm">No projects available</p>
            </div>
            @endif
        </div>
    </div>

    <!-- PROJECTS TAB CONTENT -->
    <div id="content-projects" class="tab-content hidden py-6">
        @if(count($projects) > 0)
            @foreach($projects as $project)
            @php
                $isCompleted = $project['status'] === 'completed';
                $statusColor = $isCompleted ? 'green' : 'blue';
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-base font-bold text-gray-800 flex-1 pr-3">{{ $project['name'] }}</h3>
                    <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 rounded-xl text-xs font-semibold whitespace-nowrap">
                        {{ $isCompleted ? 'Completed' : 'Ongoing' }}
                    </span>
                </div>

                <p class="text-xs text-gray-600 mb-4 line-clamp-2">{{ $project['description'] ?? 'No description available' }}</p>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">Progress</span>
                        <span class="text-xs font-semibold text-{{ $statusColor }}-600">{{ $project['progress'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-{{ $statusColor }}-600 h-2 rounded-full" style="width: {{ $project['progress'] }}%"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-xs text-gray-600">Budget</span>
                    <span class="text-xs font-semibold text-gray-800">₱{{ number_format($project['totalBudget'], 0) }}</span>
                </div>
            </div>
            @endforeach
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 font-medium">No projects available</p>
            <p class="text-gray-400 text-sm mt-2">Projects will appear here once added</p>
        </div>
        @endif
    </div>

    <!-- ANNOUNCEMENTS TAB CONTENT -->
    <div id="content-announcements" class="tab-content hidden py-6">
        @if(count($announcements) > 0)
            @foreach($announcements as $announcement)
            @php
                // Determine category from title/content
                $title = strtolower($announcement['title']);
                $content = strtolower($announcement['content']);

                if (str_contains($title, 'emergency') || str_contains($content, 'emergency')) {
                    $category = 'emergency';
                    $badgeColor = 'bg-red-100 text-red-700 border-red-200';
                } elseif (str_contains($title, 'alert') || str_contains($title, 'warning') || str_contains($content, 'alert') || str_contains($content, 'warning')) {
                    $category = 'alert';
                    $badgeColor = 'bg-orange-100 text-orange-700 border-orange-200';
                } else {
                    $category = 'event';
                    $badgeColor = 'bg-blue-100 text-blue-700 border-blue-200';
                }

                // Format date
                $dateStr = 'N/A';
                $dateValue = $announcement['publishedAt'] ?? $announcement['createdAt'];
                if ($dateValue) {
                    try {
                        $dateStr = date('M d, Y', strtotime($dateValue));
                    } catch (\Exception $e) {
                        $dateStr = 'N/A';
                    }
                }

                $hasImage = !empty($announcement['featuredImageUrl']);
                $hasPdfs = !empty($announcement['pdfAttachments']);
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-base font-bold text-gray-800 flex-1 pr-3">{{ $announcement['title'] }}</h3>
                    <span class="px-2 py-1 {{ $badgeColor }} border rounded-xl text-xs font-medium whitespace-nowrap">
                        {{ $category }}
                    </span>
                </div>

                @if($hasImage)
                <div class="mb-4 -mx-6">
                    <img src="{{ $announcement['featuredImageUrl'] }}" alt="{{ $announcement['title'] }}" class="w-full h-auto object-cover" onerror="this.style.display='none'">
                </div>
                @endif

                <p class="text-xs text-gray-800 mb-3 leading-relaxed">{{ $announcement['content'] }}</p>

                @if($hasPdfs)
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($announcement['pdfAttachments'] as $pdf)
                    <a href="{{ $pdf['downloadUrl'] }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-file-pdf text-red-700 text-xs mr-2"></i>
                        <span class="text-xs text-red-700 font-medium">{{ Str::limit($pdf['fileName'], 20) }}</span>
                    </a>
                    @endforeach
                </div>
                @endif

                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">{{ $dateStr }}</span>
                    @if($hasImage || $hasPdfs)
                    <div class="flex items-center space-x-2 text-gray-400">
                        @if($hasImage)
                        <i class="fas fa-image text-xs"></i>
                        @endif
                        @if($hasPdfs)
                        <div class="flex items-center">
                            <i class="fas fa-paperclip text-xs"></i>
                            <span class="text-xs ml-1">{{ count($announcement['pdfAttachments']) }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 font-medium">No announcements available</p>
            <p class="text-gray-400 text-sm mt-2">Published announcements will appear here</p>
        </div>
        @endif
    </div>

</div>

<style>
    .tab-button {
        background-color: white;
        color: #6B7280;
        border: 1px solid #E5E7EB;
    }

    .tab-button.active {
        background-color: #1585e1;
        color: white;
        border-color: #1585e1;
    }

    .tab-button:hover:not(.active) {
        background-color: #F3F4F6;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });

        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');

        // Add active class to selected button
        document.getElementById('tab-' + tabName).classList.add('active');

        // Update URL hash without scrolling
        history.replaceState(null, null, '#' + tabName);
    }

    // Handle hash on page load
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.substring(1); // Remove the #
        if (hash && ['budget', 'projects', 'announcements'].includes(hash)) {
            switchTab(hash);
        }
    });
</script>
@endsection
