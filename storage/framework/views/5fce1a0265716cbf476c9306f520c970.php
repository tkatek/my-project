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
     <?php $__env->slot('header', null, []); ?> Packages <?php $__env->endSlot(); ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <p style="margin:0;font-size:14px;color:#6b7280;"><?php echo e($packages->count()); ?> packages total</p>
        <a href="<?php echo e(route('admin.packages.create')); ?>" style="padding:10px 20px;background:#f97316;color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;">+ Add Package</a>
    </div>

    <div class="packages-table-container">
        <table class="packages-table">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Category</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="actions-col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <p style="margin:0;font-weight:500;color:#111827;"><?php echo e($package->title); ?></p>
                    </td>
                    <td><span class="category-badge <?php echo e($package->category); ?>"><?php echo e(ucfirst($package->category)); ?></span></td>
                    <td style="color:#4b5563;"><?php echo e($package->duration); ?></td>
                    <td style="font-weight:600;color:#111827;">MAD <?php echo e(number_format($package->price)); ?></td>
                    <td>
                        <span class="status-badge <?php echo e($package->status); ?>"><?php echo e(ucfirst($package->status)); ?></span>
                    </td>
                    <td class="actions-col">
                        <div class="action-btns">
                            <a href="<?php echo e(route('admin.packages.edit', $package)); ?>" class="btn-edit">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.packages.destroy', $package)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this package?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:24px;">
        <?php echo e($packages->links()); ?>

    </div>

    <style>
        .packages-table-container { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06); overflow:hidden; }
        .packages-table { width:100%; border-collapse:collapse; }
        .packages-table th { padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; border-bottom:1px solid #e5e7eb; }
        .packages-table td { padding:16px; font-size:14px; border-bottom:1px solid #f3f4f6; vertical-align:top; }
        .packages-table tr:hover td { background:#f9fafb; }
        .category-badge { padding:4px 12px; font-size:12px; font-weight:500; border-radius:9999px; display:inline-block; }
        .category-badge.adventure { background:#fef3c7; color:#92400e; }
        .category-badge.food { background:#dbeafe; color:#1e40af; }
        .category-badge.relax { background:#f3e8ff; color:#6b21a8; }
        .category-badge.private { background:#fce7f3; color:#9d174d; }
        .status-badge { padding:4px 12px; font-size:12px; font-weight:600; border-radius:9999px; display:inline-block; }
        .status-badge.active { background:#dcfce7; color:#166534; }
        .status-badge.draft { background:#fef3c7; color:#92400e; }
        .action-btns { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
        .btn-edit { padding:6px 12px; font-size:12px; font-weight:500; color:#2563eb; background:#eff6ff; border-radius:6px; text-decoration:none; }
        .btn-delete { padding:6px 12px; font-size:12px; font-weight:500; color:#dc2626; background:#fef2f2; border:none; border-radius:6px; cursor:pointer; }

        @media (max-width:768px) {
            .packages-table-container { background:transparent; box-shadow:none; }
            .packages-table thead { display:none; }
            .packages-table tbody { display:flex; flex-direction:column; gap:12px; }
            .packages-table tr { display:flex; flex-direction:column; background:#fff; border-radius:12px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06); border-bottom:none; }
            .packages-table td { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f3f4f6; }
            .packages-table td::before { content:attr(data-label); font-weight:600; color:#6b7280; font-size:12px; text-transform:uppercase; }
            .actions-col { flex-direction:column !important; align-items:stretch !important; }
            .action-btns { flex-direction:column; }
            .action-btns a, .action-btns button { width:100%; text-align:center; }
        }
        @media (max-width:480px) {
            .packages-table td { flex-direction:column; align-items:flex-start; gap:4px; }
            .packages-table td::before { margin-bottom:4px; }
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
<?php /**PATH C:\Users\HP\my-project\resources\views/admin/packages/index.blade.php ENDPATH**/ ?>