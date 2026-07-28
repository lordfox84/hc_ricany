<?php $__env->startSection('title', 'Odběry | Admin HC Říčany'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-panel" style="display:block;">
  <div class="admin-nav">
    <div class="admin-nav-left">
      <div class="admin-logo">HC ŘÍČANY</div>
      <div class="admin-badge">Administrace</div>
    </div>
    <div style="display:flex;align-items:center;gap:16px;">
      <span style="font-size:0.8rem;color:var(--gray-light);"><span style="color:var(--teal);">●</span> <?php echo e(auth()->user()->name); ?></span>
      <a href="<?php echo e(route('home')); ?>" class="admin-close-btn">Zpět na web</a>
      <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="admin-close-btn" style="background:none;border:1px solid #555;">Odhlásit</button>
      </form>
    </div>
  </div>

  <div class="admin-layout">
    <?php echo $__env->make('partials._admin_sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="admin-content">
      <div class="admin-panel-section active">
        <div class="admin-page-title">Odběry</div>
        <div class="admin-page-subtitle">
          Přihlášení k odběru novinek a marketingové komunikaci.
          Slouží jako základ pro cílení e-mailů v CZ i EN mutaci.
        </div>

        <?php if(session('success')): ?>
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <?php
          $totalCount       = $subscribers->count();
          $activeCount      = $subscribers->filter(fn($s) => $s->is_active)->count();
          $unsubCount       = $totalCount - $activeCount;
          $campCount        = $subscribers->where('source', 'camp')->count();
          $newsletterCount  = $subscribers->where('source', 'newsletter')->count();
          $csCount          = $subscribers->filter(fn($s) => $s->is_active && ($s->locale ?? 'cs') === 'cs')->count();
          $enCount          = $subscribers->filter(fn($s) => $s->is_active && ($s->locale ?? 'cs') === 'en')->count();
        ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;margin-bottom:24px;">
          <?php $__currentLoopData = [
            ['Celkem', $totalCount, '#2abfbf'],
            ['Aktivních', $activeCount, '#4ade80'],
            ['Odhlášených', $unsubCount, '#f87171'],
            ['Z newsletteru', $newsletterCount, '#818cf8'],
            ['Z kempů', $campCount, '#facc15'],
            ['CZ aktivní', $csCount, '#7dd3fc'],
            ['EN aktivní', $enCount, '#c084fc'],
          ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="background:var(--gray);border:1px solid rgba(255,255,255,0.07);border-left:3px solid <?php echo e($color); ?>;border-radius:4px;padding:14px 16px;">
              <div style="font-size:1.6rem;font-weight:700;color:<?php echo e($color); ?>;font-family:var(--font-display);"><?php echo e($count); ?></div>
              <div style="font-size:0.75rem;color:var(--gray-light);margin-top:2px;"><?php echo e($label); ?></div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
          <a href="<?php echo e(route('admin.subscribers.export')); ?>" class="btn btn-outline" style="font-size:0.8rem;">
            ⬇ Export aktivních (CSV)
          </a>
          <span style="font-size:0.8rem;color:var(--gray-light);">Filtrovat:</span>
          <?php $__currentLoopData = [
            ['all', 'Všechny'],
            ['active', 'Aktivní'],
            ['unsubscribed', 'Odhlášeni'],
            ['newsletter', 'Newsletter'],
            ['camp', 'Kempy'],
          ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$val, $lbl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.subscribers.index', ['filter' => $val])); ?>"
               class="btn btn-outline"
               style="font-size:0.75rem;padding:6px 14px;<?php echo e($filter === $val ? 'border-color:var(--teal);color:var(--teal);' : ''); ?>">
              <?php echo e($lbl); ?>

            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="admin-table-wrap">
          <div class="admin-table-header">
            <div class="admin-table-title">Kontakty (<?php echo e($subscribers->count()); ?>)</div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Jméno</th>
                <th>Email</th>
                <th style="text-align:center;">Jazyk</th>
                <th style="text-align:center;">Zdroj</th>
                <th style="text-align:center;">Stav</th>
                <th>Souhlas</th>
                <th>Odhlášen</th>
                <th>Přihlášen</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $subscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td style="white-space:nowrap;">
                    <strong><?php echo e($s->full_name); ?></strong>
                  </td>
                  <td>
                    <a href="mailto:<?php echo e($s->email); ?>" style="color:var(--teal);"><?php echo e($s->email); ?></a>
                  </td>
                  <td style="text-align:center;">
                    <span style="font-size:0.72rem;font-weight:700;padding:2px 7px;border-radius:3px;
                      background:<?php echo e(($s->locale ?? 'cs') === 'en' ? '#1a1a3a' : '#1a2a3a'); ?>;
                      color:<?php echo e(($s->locale ?? 'cs') === 'en' ? '#818cf8' : '#7dd3fc'); ?>;">
                      <?php echo e(strtoupper($s->locale ?? 'cs')); ?>

                    </span>
                  </td>
                  <td style="text-align:center;">
                    <span style="font-size:0.72rem;padding:2px 8px;border-radius:3px;
                      background:<?php echo e($s->source === 'camp' ? '#2a1a0a' : '#1a1a2a'); ?>;
                      color:<?php echo e($s->source === 'camp' ? '#facc15' : '#818cf8'); ?>;">
                      <?php echo e($s->source === 'camp' ? '🏕 kemp' : '📬 newsletter'); ?>

                    </span>
                  </td>
                  <td style="text-align:center;">
                    <?php if($s->is_active): ?>
                      <span style="color:#4ade80;font-size:0.78rem;font-weight:600;">✓ aktivní</span>
                    <?php else: ?>
                      <span style="color:#f87171;font-size:0.78rem;">✗ odhlášen</span>
                    <?php endif; ?>
                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">
                    <?php echo e($s->consented_at?->format('d.m.Y') ?? '—'); ?>

                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">
                    <?php echo e($s->unsubscribed_at?->format('d.m.Y') ?? '—'); ?>

                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">
                    <?php echo e($s->created_at->format('d.m.Y')); ?>

                  </td>
                  <td style="white-space:nowrap;">
                    
                    <form method="POST" action="<?php echo e(route('admin.subscribers.toggle', $s)); ?>" style="display:inline;">
                      <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                      <button type="submit" class="btn btn-outline"
                              style="font-size:0.72rem;padding:4px 10px;<?php echo e($s->is_active ? 'border-color:#f87171;color:#f87171;' : 'border-color:#4ade80;color:#4ade80;'); ?>"
                              title="<?php echo e($s->is_active ? 'Odhlásit z marketingu' : 'Znovu přihlásit'); ?>">
                        <?php echo e($s->is_active ? 'Odhlásit' : 'Přihlásit'); ?>

                      </button>
                    </form>
                    
                    <form method="POST" action="<?php echo e(route('admin.subscribers.destroy', $s)); ?>" style="display:inline;"
                          onsubmit="return confirm('Vymazat kontakt <?php echo e(addslashes($s->email)); ?> z databáze? Tato akce je nevratná (GDPR výmaz).')">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <button type="submit" class="btn"
                              style="font-size:0.72rem;padding:4px 10px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">
                        Vymazat
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="9" style="color:var(--gray-light);text-align:center;padding:40px;">
                    Žádné odběry.
                    <?php if($filter !== 'all'): ?>
                      <a href="<?php echo e(route('admin.subscribers.index')); ?>" style="color:var(--teal);">Zobrazit vše</a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        
        <div style="margin-top:20px;padding:14px 18px;background:rgba(42,191,191,0.05);border:1px solid rgba(42,191,191,0.15);border-radius:6px;font-size:0.78rem;color:var(--gray-light);">
          <strong style="color:var(--teal);">ℹ GDPR:</strong>
          „Odhlásit" deaktivuje marketing bez smazání záznamu (audit trail).
          „Vymazat" trvale odstraní kontakt z databáze — použijte při žádosti o výmaz osobních údajů dle čl. 17 GDPR.
        </div>
      </div>
    </main>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.hc', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\MSI\Documents\GitRepos\_personal_\hc_ricany\resources\views/admin/subscribers/index.blade.php ENDPATH**/ ?>