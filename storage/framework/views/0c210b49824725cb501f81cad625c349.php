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
     <?php $__env->slot('header', null, []); ?> Messages <?php $__env->endSlot(); ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <p style="margin:0;color:#6b7280;font-size:14px;"><?php echo e($messages->total()); ?> message<?php echo e($messages->total() === 1 ? '' : 's'); ?> from the contact form.</p>
    </div>

    <?php if(session('success')): ?>
        <div style="background:#d1fae5;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($messages->isEmpty()): ?>
        <div style="background:#fff;border-radius:12px;padding:48px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <p style="margin:0;color:#9ca3af;font-size:15px;">No messages yet. Inquiries from the landing page contact form will appear here.</p>
        </div>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="background:#fff;border-radius:12px;padding:20px 24px;box-shadow:0 1px 3px rgba(0,0,0,0.06);<?php echo e($message->isUnread() ? 'border-left:4px solid #f97316;' : ''); ?>">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
                        <div style="min-width:220px;">
                            <p style="margin:0 0 4px;font-size:16px;font-weight:<?php echo e($message->isUnread() ? '700' : '600'); ?>;color:#111827;">
                                <?php echo e($message->name); ?>

                                <?php if($message->isUnread()): ?>
                                    <span style="background:#fff3e6;color:#c2410c;font-size:11px;font-weight:700;padding:2px 8px;border-radius:9999px;margin-left:6px;">NEW</span>
                                <?php endif; ?>
                            </p>
                            <p style="margin:0;font-size:13px;color:#6b7280;">
                                <a href="mailto:<?php echo e($message->email); ?>" style="color:#2563eb;text-decoration:none;"><?php echo e($message->email); ?></a>
                                <?php if($message->phone): ?>
                                    &nbsp;·&nbsp; <a href="tel:<?php echo e($message->phone); ?>" style="color:#2563eb;text-decoration:none;"><?php echo e($message->phone); ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div style="text-align:right;">
                            <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;"><?php echo e($message->created_at->format('d M Y, H:i')); ?></p>
                            <span style="display:inline-block;padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;background:#f3f4f6;color:#374151;text-transform:capitalize;"><?php echo e($message->subject); ?></span>
                        </div>
                    </div>

                    <p style="margin:16px 0 0;font-size:14px;line-height:1.6;color:#4b5563;background:#f9fafb;padding:16px;border-radius:8px;white-space:pre-wrap;"><?php echo e($message->message); ?></p>

                    <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;">
                        <a href="mailto:<?php echo e($message->email); ?>?subject=Re: your inquiry" style="padding:8px 16px;background:#f97316;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Reply by email</a>
                        <?php if($message->isUnread()): ?>
                            <form method="POST" action="<?php echo e(route('admin.messages.read', $message)); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" style="padding:8px 16px;background:#f3f4f6;color:#374151;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">Mark as read</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('admin.messages.destroy', $message)); ?>" onsubmit="return confirm('Delete this message?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="padding:8px 16px;background:#fee2e2;color:#b91c1c;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div style="margin-top:24px;"><?php echo e($messages->links()); ?></div>
    <?php endif; ?>
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
<?php /**PATH C:\Users\HP\my-project\resources\views/admin/messages/index.blade.php ENDPATH**/ ?>