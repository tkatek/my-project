<?php if (isset($component)) { $__componentOriginal1f7b3c69a858611a4ccc5f2ea9729c12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f7b3c69a858611a4ccc5f2ea9729c12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> Tableau de Bord <?php $__env->endSlot(); ?>

    <div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
        <a href="<?php echo e(route('dashboard.report')); ?>" style="padding:10px 20px;background:#f97316;color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-download"></i>
            Download Monthly Report
        </a>
    </div>

    <div class="stat-grid">
        <?php
        $stats = [
            ['label' => 'Total Orders', 'value' => $totalOrders, 'icon' => 'fa-solid fa-clipboard-list', 'bg' => '#e0f2fe', 'color' => '#0284c7'],
            ['label' => 'Pending', 'value' => $pendingOrders, 'icon' => 'fa-solid fa-clock', 'bg' => '#fef3c7', 'color' => '#d97706'],
            ['label' => 'Confirmed', 'value' => $confirmedOrders, 'icon' => 'fa-solid fa-circle-check', 'bg' => '#dcfce7', 'color' => '#16a34a'],
            ['label' => 'Completed', 'value' => $completedOrders, 'icon' => 'fa-solid fa-check', 'bg' => '#d1fae5', 'color' => '#059669'],
            ['label' => 'Revenue', 'value' => 'MAD ' . number_format($totalRevenue), 'icon' => 'fa-solid fa-money-bill-wave', 'bg' => '#ffedd5', 'color' => '#ea580c'],
        ];
        ?>
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stat-card">
            <div class="stat-icon" style="background:<?php echo e($stat['bg']); ?>;">
                <i class="<?php echo e($stat['icon']); ?>" style="color:<?php echo e($stat['color']); ?>;font-size:20px;"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label"><?php echo e($stat['label']); ?></p>
                <p class="stat-value"><?php echo e($stat['value']); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3 class="chart-title">Monthly Revenue (MAD)</h3>
            <?php if($monthlyRevenue->count() > 0): ?>
            <div class="bar-chart">
                <?php $__currentLoopData = $monthlyRevenue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bar-col">
                    <div class="bar" style="height:<?php echo e(max(5, ($mr->revenue / max(1, $monthlyRevenue->max('revenue'))) * 100)); ?>%;"></div>
                    <span class="bar-label"><?php echo e($mr->month); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="empty-state">No revenue data yet</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <h3 class="chart-title">Revenue by Package</h3>
            <?php if($revenueByPackage->count() > 0): ?>
            <div class="progress-list">
                <?php $__currentLoopData = $revenueByPackage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="progress-item">
                    <div class="progress-header">
                        <span><?php echo e($rp->package_name ?? 'Unknown'); ?></span>
                        <span>MAD <?php echo e(number_format($rp->revenue)); ?></span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:<?php echo e(max(5, ($rp->revenue / max(1, $revenueByPackage->max('revenue'))) * 100)); ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="empty-state">No package data yet</div>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <h3 class="chart-title">Best Sellers</h3>
            <?php if($bestSellers->count() > 0): ?>
            <div class="seller-list">
                <?php $__currentLoopData = $bestSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="seller-item">
                    <div class="seller-rank" style="background:<?php echo e(['#f97316','#3b82f6','#34d399','#a78bfa','#f472b6'][$index % 5]); ?>;"><?php echo e($index + 1); ?></div>
                    <div class="seller-info">
                        <p class="seller-name"><?php echo e($bs->package_name ?? 'Unknown'); ?></p>
                        <p class="seller-count"><?php echo e($bs->total_bookings); ?> bookings</p>
                    </div>
                    <span class="seller-revenue">MAD <?php echo e(number_format($bs->total_revenue)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="empty-state">No sales data yet</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-card">
        <h3 class="chart-title">Recent Orders</h3>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Visit Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($booking->customer_name); ?></td>
                        <td><?php echo e($booking->package_name ?? 'N/A'); ?></td>
                        <td><?php echo e($booking->visit_date ? date('d/m/Y', strtotime($booking->visit_date)) : 'N/A'); ?></td>
                        <td><span class="status-badge <?php echo e($booking->status); ?>"><?php echo e(ucfirst($booking->status)); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="empty-cell">No orders yet. Orders from the landing page will appear here.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .stat-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:24px; }
        .stat-card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06); display:flex; align-items:center; gap:16px; }
        .stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .stat-info { min-width:0; }
        .stat-label { margin:0; font-size:13px; color:#6b7280; }
        .stat-value { margin:4px 0 0; font-size:24px; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        .chart-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-bottom:24px; }
        .chart-card { background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); }
        .chart-title { margin:0 0 20px; font-size:16px; font-weight:600; color:#111827; }

        .bar-chart { display:flex; align-items:flex-end; gap:6px; height:160px; padding-top:20px; }
        .bar-col { flex:1; display:flex; flex-direction:column; align-items:center; height:100%; justify-content:flex-end; }
        .bar { width:70%; background:#f97316; border-radius:4px 4px 0 0; min-height:4px; }
        .bar-label { font-size:10px; color:#9ca3af; margin-top:6px; }

        .progress-list { display:flex; flex-direction:column; gap:12px; }
        .progress-header { display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px; }
        .progress-bar { width:100%; background:#f3f4f6; border-radius:9999px; height:8px; }
        .progress-fill { background:#3b82f6; height:8px; border-radius:9999px; }

        .seller-list { display:flex; flex-direction:column; gap:12px; }
        .seller-item { display:flex; align-items:center; gap:12px; }
        .seller-rank { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; font-weight:700; flex-shrink:0; }
        .seller-info { flex:1; min-width:0; }
        .seller-name { margin:0; font-size:14px; font-weight:500; color:#111827; }
        .seller-count { margin:0; font-size:12px; color:#9ca3af; }
        .seller-revenue { font-size:12px; font-weight:600; color:#16a34a; white-space:nowrap; }

        .table-card { background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th { padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; border-bottom:2px solid #f3f4f6; }
        .data-table td { padding:14px 16px; font-size:14px; color:#111827; border-bottom:1px solid #f3f4f6; }
        .data-table tr:hover td { background:#f9fafb; }
        .status-badge { padding:4px 12px; font-size:12px; font-weight:600; border-radius:9999px; display:inline-block; }
        .status-badge.pending { background:#fef3c7; color:#92400e; }
        .status-badge.confirmed { background:#dbeafe; color:#1e40af; }
        .status-badge.completed { background:#d1fae5; color:#065f46; }
        .empty-cell { text-align:center; color:#9ca3af; padding:48px; }
        .empty-state { display:flex; align-items:center; justify-content:center; height:160px; color:#9ca3af; font-size:14px; }

        @media (max-width:1200px) {
            .stat-grid { grid-template-columns:repeat(3,1fr); }
            .chart-grid { grid-template-columns:1fr 1fr; }
        }
        @media (max-width:768px) {
            main { padding:16px !important; }
            .stat-grid { grid-template-columns:repeat(2,1fr); gap:12px; }
            .stat-card { padding:16px; }
            .stat-value { font-size:20px; }
            .chart-grid { grid-template-columns:1fr; gap:16px; }
            .chart-card { padding:16px; }
            .bar-chart { height:120px; }
            .data-table th, .data-table td { padding:10px 12px; font-size:12px; }
        }
        @media (max-width:480px) {
            .stat-grid { grid-template-columns:1fr 1fr; gap:8px; }
            .stat-card { padding:12px; gap:8px; }
            .stat-icon { width:32px; height:32px; }
            .stat-label { font-size:11px; }
            .stat-value { font-size:18px; }
            .data-table th:nth-child(3), .data-table td:nth-child(3) { display:none; }
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f7b3c69a858611a4ccc5f2ea9729c12)): ?>
<?php $attributes = $__attributesOriginal1f7b3c69a858611a4ccc5f2ea9729c12; ?>
<?php unset($__attributesOriginal1f7b3c69a858611a4ccc5f2ea9729c12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f7b3c69a858611a4ccc5f2ea9729c12)): ?>
<?php $component = $__componentOriginal1f7b3c69a858611a4ccc5f2ea9729c12; ?>
<?php unset($__componentOriginal1f7b3c69a858611a4ccc5f2ea9729c12); ?>
<?php endif; ?>
<?php /**PATH C:\Users\HP\my-project\resources\views/dashboard.blade.php ENDPATH**/ ?>