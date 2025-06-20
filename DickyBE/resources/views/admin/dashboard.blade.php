@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Top Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-indigo-100 p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-medium mb-2 text-indigo-800">Total Questions</h3>
                    <p class="text-3xl font-bold text-indigo-900">{{ $totalQuestions }}</p>
                </div>
                <div class="text-indigo-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-green-100 p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-medium mb-2 text-green-800">Total Tests</h3>
                    <p class="text-3xl font-bold text-green-900" id="totalTests">{{ $totalTests }}</p>
                    <p class="text-sm text-green-600 mt-1">Completed assessments</p>
                </div>
                <div class="text-green-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-medium mb-2 text-blue-800">Total Users</h3>
                    <p class="text-3xl font-bold text-blue-900">{{ $totalUsers }}</p>
                </div>
                <div class="text-blue-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-purple-100 p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-medium mb-2 text-purple-800">Most Common Style</h3>
                    <p class="text-2xl font-bold text-purple-900" id="dominantStyle">
                        @if($visualCount >= $auditoryCount && $visualCount >= $kinestheticCount)
                            Visual ({{ $visualCount }})
                        @elseif($auditoryCount >= $kinestheticCount)
                            Auditory ({{ $auditoryCount }})
                        @else
                            Kinesthetic ({{ $kinestheticCount }})
                        @endif
                    </p>
                </div>
                <div class="text-purple-500">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Learning Style Distribution</h3>
            <div class="relative" style="height: 400px;">
                <canvas id="learningStylePieChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                <div class="bg-blue-50 p-3 rounded">
                    <p class="text-sm text-gray-600">Visual</p>
                    <p class="text-2xl font-bold text-blue-600" id="visualCount">{{ $visualCount }}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded">
                    <p class="text-sm text-gray-600">Auditory</p>
                    <p class="text-2xl font-bold text-orange-600" id="auditoryCount">{{ $auditoryCount }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded">
                    <p class="text-sm text-gray-600">Kinesthetic</p>
                    <p class="text-2xl font-bold text-green-600" id="kinestheticCount">{{ $kinestheticCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Learning Style Trends (Last 30 Days)</h3>
            <div class="relative" style="height: 400px;">
                <canvas id="learningStyleLineChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Recent Results --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.questions.create') }}"
                   class="flex items-center justify-between w-full bg-indigo-600 text-white px-4 py-3 rounded hover:bg-indigo-700 transition duration-200">
                    <span>Add New Question</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.questions.index') }}"
                   class="flex items-center justify-between w-full bg-gray-600 text-white px-4 py-3 rounded hover:bg-gray-700 transition duration-200">
                    <span>Manage Questions</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                </a>
                <button onclick="refreshDashboard()"
                        class="flex items-center justify-between w-full bg-green-600 text-white px-4 py-3 rounded hover:bg-green-700 transition duration-200">
                    <span>Refresh Data</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Test Results</h3>
            <div class="space-y-3" id="recentResults">
                @if($recentResults->count() > 0)
                    @foreach($recentResults as $result)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">{{ $result['style_name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $result['created_at'] }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">
                                V: {{ $result['visual_score'] }} |
                                A: {{ $result['auditory_score'] }} |
                                K: {{ $result['kinesthetic_score'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">No test results yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Weekly Statistics Table --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Weekly Statistics</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Week</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditory</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kinesthetic</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="weeklyStatsTableBody">
                    @foreach($weeklyStats as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Week {{ $stat->week }}, {{ $stat->year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">{{ $stat->visual }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-semibold">{{ $stat->auditory }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">{{ $stat->kinestetik }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $stat->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let pieChart; 
    let lineChart;

    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts(
            {{ $visualCount }},
            {{ $auditoryCount }},
            {{ $kinestheticCount }},
            @json($chartData) 
        );
    });

    function initializeCharts(visualCount, auditoryCount, kinestheticCount, chartData) {
        if (pieChart) pieChart.destroy();
        if (lineChart) lineChart.destroy();

        const pieCtx = document.getElementById('learningStylePieChart').getContext('2d');
        pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Visual', 'Auditory', 'Kinesthetic'],
                datasets: [{
                    data: [visualCount, auditoryCount, kinestheticCount],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',    
                        'rgba(249, 115, 22, 0.8)',     
                        'rgba(34, 197, 94, 0.8)'       
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(34, 197, 94, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        const lineCtx = document.getElementById('learningStyleLineChart').getContext('2d');
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal); 
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });

        const visualData = chartData.map(item => item.visual || 0);
        const auditoryData = chartData.map(item => item.auditory || 0);
        const kinestheticData = chartData.map(item => item.kinestetik || 0); 

        lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Visual',
                        data: visualData,
                        borderColor: 'rgb(59, 130, 246)', 
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Auditory',
                        data: auditoryData,
                        borderColor: 'rgb(249, 115, 22)', 
                        backgroundColor: 'rgba(249, 115, 22, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Kinesthetic',
                        data: kinestheticData,
                        borderColor: 'rgb(34, 197, 94)', 
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Number of Tests'
                        },
                        beginAtZero: true,
                        ticks: {
                            precision: 0 
                        }
                    }
                }
            }
        });
    }

    async function refreshDashboard() {
        try {
            const response = await fetch("{{ route('admin.dashboard.data') }}"); 
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Refreshed Data:', data);

            document.getElementById('totalTests').textContent = data.totalTests;
            document.getElementById('visualCount').textContent = data.visualCount;
            document.getElementById('auditoryCount').textContent = data.auditoryCount;
            document.getElementById('kinestheticCount').textContent = data.kinestheticCount;

            let dominantStyleText;
            if (data.visualCount >= data.auditoryCount && data.visualCount >= data.kinestheticCount) {
                dominantStyleText = `Visual (${data.visualCount})`;
            } else if (data.auditoryCount >= data.kinestheticCount) {
                dominantStyleText = `Auditory (${data.auditoryCount})`;
            } else {
                dominantStyleText = `Kinesthetic (${data.kinestheticCount})`;
            }
            document.getElementById('dominantStyle').textContent = dominantStyleText;

            initializeCharts(data.visualCount, data.auditoryCount, data.kinestheticCount, data.chartData);

            const recentResultsContainer = document.getElementById('recentResults');
            recentResultsContainer.innerHTML = ''; 
            if (data.recentResults.length > 0) {
                data.recentResults.forEach(result => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded';
                    div.innerHTML = `
                        <div>
                            <p class="font-medium">${result.style_name}</p>
                            <p class="text-sm text-gray-600">${result.created_at}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">
                                V: ${result.visual_score} |
                                A: ${result.auditory_score} |
                                K: ${result.kinesthetic_score}
                            </div>
                        </div>
                    `;
                    recentResultsContainer.appendChild(div);
                });
            } else {
                recentResultsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">No test results yet</p>';
            }

            const weeklyStatsTableBody = document.getElementById('weeklyStatsTableBody');
            weeklyStatsTableBody.innerHTML = ''; 
            data.weeklyStats.forEach(stat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Week ${stat.week}, ${stat.year}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">${stat.visual}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-semibold">${stat.auditory}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">${stat.kinestetik}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">${stat.total}</td>
                `;
                weeklyStatsTableBody.appendChild(row);
            });


        } catch (error) {
            console.error('Error refreshing dashboard data:', error);
            alert('Failed to refresh dashboard data. Please try again.');
        }
    }
</script>
@endpush