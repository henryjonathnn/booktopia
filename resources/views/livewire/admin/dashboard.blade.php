<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-20">
        <!-- Total Users Card -->
        <div class="bg-[#1a1625] rounded-xl shadow-md border border-purple-500/10 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Total User</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $totalUsers }}</h3>
                </div>
                <div class="p-3 bg-purple-500/10 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm flex items-center">
                <span class="text-green-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    8.4%
                </span>
                <span class="text-gray-400 ml-2">Sejak bulan lalu</span>
            </div>
        </div>

        <!-- Total Books Card -->
        <div class="bg-[#1a1625] rounded-xl shadow-md border border-purple-500/10 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Total Buku</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $totalBooks }}</h3>
                </div>
                <div class="p-3 bg-indigo-500/10 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm flex items-center">
                <span class="text-green-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    12.1%
                </span>
                <span class="text-gray-400 ml-2">Sejak bulan lalu</span>
            </div>
        </div>

        <!-- Total Loans Card -->
        <div class="bg-[#1a1625] rounded-xl shadow-md border border-purple-500/10 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Peminjaman</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $totalLoans }}</h3>
                </div>
                <div class="p-3 bg-blue-500/10 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm flex items-center">
                <span class="text-green-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    5.2%
                </span>
                <span class="text-gray-400 ml-2">Sejak bulan lalu</span>
            </div>
        </div>

        <!-- Active Loans Card -->
        <div class="bg-[#1a1625] rounded-xl shadow-md border border-purple-500/10 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Dipinjam</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $activeLoans }}</h3>
                </div>
                <div class="p-3 bg-green-500/10 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm flex items-center">
                <span class="text-red-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    2.1%
                </span>
                <span class="text-gray-400 ml-2">Sejak bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-[#1a1625] rounded-xl shadow-md border border-purple-500/10 p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Monthly Activity</h2>
        <div class="h-80" x-data="{
            monthlyData: @js($monthlyStats),
            init() {
                const ctx = document.getElementById('loansChart').getContext('2d');
                
                if (typeof Chart === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                    script.onload = () => this.initChart(ctx);
                    document.head.appendChild(script);
                } else {
                    this.initChart(ctx);
                }
            },
            initChart(ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.monthlyData.map(item => item.month),
                        datasets: [
                            {
                                label: 'Peminjaman',
                                data: this.monthlyData.map(item => item.loans),
                                backgroundColor: 'rgba(129, 140, 248, 0.5)',
                                borderColor: 'rgb(129, 140, 248)',
                                borderWidth: 1,
                                borderRadius: 6
                            },
                            {
                                label: 'Pengembalian',
                                data: this.monthlyData.map(item => item.returns),
                                backgroundColor: 'rgba(168, 85, 247, 0.5)',
                                borderColor: 'rgb(168, 85, 247)',
                                borderWidth: 1,
                                borderRadius: 6
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
                                    color: 'rgb(209, 213, 219)'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(75, 85, 99, 0.1)'
                                },
                                ticks: {
                                    color: 'rgb(156, 163, 175)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: 'rgb(156, 163, 175)'
                                }
                            }
                        }
                    }
                });
            }
        }">
            <canvas id="loansChart"></canvas>
        </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="bg-[#1a1625] rounded-xl shadow-md border border-purple-500/10 overflow-hidden">
        <div class="p-6 border-b border-purple-500/10">
            <h2 class="text-xl font-semibold">Recent Activities</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#2a2435]">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-500/10">
                    @forelse($recentActivities as $activity)
                        <tr class="hover:bg-purple-500/5">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-500/10 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-purple-400">{{ $activity['user']['initials'] }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium">{{ $activity['user']['name'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $activity['user']['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div>{{ $activity['book']['title'] }}</div>
                                <div class="text-xs text-gray-400">{{ $activity['book']['author'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="text-{{ $activity['status_color'] }}-400">{{ $activity['action'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $activity['date'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $activity['status_color'] }}-500/10 text-{{ $activity['status_color'] }}-400">
                                    {{ $activity['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                Belum ada aktivitas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>