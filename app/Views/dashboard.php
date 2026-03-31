<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .blue-icon { background: #eff6ff; color: #3b82f6; }
    .green-icon { background: #f0fdf4; color: #22c55e; }
    .orange-icon { background: #fff7ed; color: #f97316; }
    .red-icon { background: #fef2f2; color: #ef4444; }

    .chart-container {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
        align-items: stretch;
    }
    .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        min-height: 380px;
    }
    .canvas-wrapper {
        flex: 1;
        position: relative;
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .canvas-wrapper.distribution {
        max-width: 320px;
        margin: 0 auto;
        width: 100%;
    }
    .chart-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .recent-activity {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .log-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .log-item:last-child { border-bottom: none; }
    .log-icon { 
        width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .log-text { font-size: 0.875rem; }
    .log-time { font-size: 0.75rem; color: #9ca3af; margin-left: auto; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #1e293b;">System Command Center</h1>
        <p style="color: #64748b;">Overview of student lifecycle, system health, and secure operations.</p>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue-icon"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Students</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;"><?= $total_students ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Pending Approval</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;"><?= $pending_apps ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Approved Files</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;"><?= $approved_apps ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red-icon"><i class="fas fa-file-signature"></i></div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Certificates</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;"><?= $total_certs ?></div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-container">
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-chart-area" style="color: #3b82f6;"></i> Registration Velocity</div>
            <div class="canvas-wrapper">
                <canvas id="registrationTrendChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-chart-pie" style="color: #a855f7;"></i> Student Distribution</div>
            <div class="canvas-wrapper distribution">
                <canvas id="departmentStatsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="recent-activity">
        <div class="chart-title"><i class="fas fa-shield-alt" style="color: #ef4444;"></i> Security Audit Trail (Recent)</div>
        <div style="margin-top: 1rem;">
            <?php foreach($recent_logs as $log): ?>
                <div class="log-item">
                    <?php 
                        $iconClass = 'fa-info-circle';
                        $bgClass = '#3b82f6';
                        if(strpos($log['action'], 'delete') !== false) { $iconClass = 'fa-trash-alt'; $bgClass = '#ef4444'; }
                        if(strpos($log['action'], 'create') !== false) { $iconClass = 'fa-plus-circle'; $bgClass = '#22c55e'; }
                        if(strpos($log['action'], 'update') !== false) { $iconClass = 'fa-sync'; $bgClass = '#f59e0b'; }
                    ?>
                    <div class="log-icon" style="background: <?= $bgClass ?>15; color: <?= $bgClass ?>;">
                        <i class="fas <?= $iconClass ?>"></i>
                    </div>
                    <div class="log-text">
                        <strong style="color: #1e293b;"><?= esc($log['action']) ?></strong>
                        <span style="color: #64748b;">on target ID:</span> 
                        <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><?= esc($log['target']) ?></span>
                        <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 2px;">Details: <?= esc($log['details']) ?></div>
                    </div>
                    <div class="log-time"><?= date('M d, H:i', strtotime($log['created_at'])) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // 📈 Registration Trend Chart
    const trendCtx = document.getElementById('registrationTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($trend_labels) ?>,
            datasets: [{
                label: 'New Registrations',
                data: <?= json_encode($trend_values) ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        stepSize: 1,
                        callback: function(value) { if (value % 1 === 0) { return value; } }
                    } 
                } 
            }
        }
    });

    // 🍕 Department Dist Chart
    const deptCtx = document.getElementById('departmentStatsChart').getContext('2d');
    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($dept_data, 'department')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($dept_data, 'count')) ?>,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        boxWidth: 12, 
                        font: { size: 11, family: 'Inter' },
                        padding: 15
                    } 
                } 
            },
            cutout: '65%'
        }
    });
</script>
<?= $this->endSection() ?>
