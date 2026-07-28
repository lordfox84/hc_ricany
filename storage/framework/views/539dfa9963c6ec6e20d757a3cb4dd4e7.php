<?php $__env->startSection('title', 'Uživatelé | Admin HC Říčany'); ?>

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
        <div class="admin-page-title">Uživatelé</div>
        <div class="admin-page-subtitle">Správa přístupu do administrace a přidělených rolí.</div>

        <?php if(session('success')): ?>
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:14px 20px;margin-bottom:20px;color:#f88;"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="admin-table-wrap">
          <div class="admin-table-header">
            <div class="admin-table-title">Uživatelé (<?php echo e($users->count()); ?>)</div>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary" style="font-size:0.78rem;padding:10px 20px;">+ Nový uživatel</a>
          </div>
          <table>
            <thead>
              <tr>
                <th>Jméno</th>
                <th>E-mail</th>
                <th>Role</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td><?php echo e($u->name); ?></td>
                  <td style="color:var(--gray-light);font-size:0.85rem;"><?php echo e($u->email); ?></td>
                  <td>
                    <?php $__empty_2 = true; $__currentLoopData = $u->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                      <span title="<?php echo e($role->description); ?>" style="font-size:0.72rem;padding:2px 8px;border-radius:20px;margin-right:4px;display:inline-block;margin-bottom:2px;cursor:help;
                        <?php echo e($role->key === 'admin' ? 'background:#2abfbf22;color:var(--teal);border:1px solid #2abfbf55;' : 'background:rgba(255,255,255,0.06);color:var(--gray-light);border:1px solid rgba(255,255,255,0.1);'); ?>">
                        <?php echo e($role->name); ?>

                      </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                      <span style="color:var(--gray-light);font-size:0.8rem;">— bez role —</span>
                    <?php endif; ?>
                  </td>
                  <td style="white-space:nowrap;">
                    <a href="<?php echo e(route('admin.users.edit', $u)); ?>" class="btn btn-outline" style="font-size:0.75rem;padding:6px 12px;">Upravit</a>
                    <?php if (! ($u->hasRole(\App\Models\Role::ADMIN))): ?>
                      <button type="button" class="btn btn-outline" style="font-size:0.75rem;padding:6px 12px;"
                              onclick="openResetPassword(<?php echo e($u->id); ?>, '<?php echo e(addslashes($u->name)); ?>')">Resetovat heslo</button>
                    <?php endif; ?>
                    <?php if(!$u->hasRole(\App\Models\Role::ADMIN) && $u->id !== auth()->id()): ?>
                      <form method="POST" action="<?php echo e(route('admin.users.destroy', $u)); ?>" style="display:inline;"
                            onsubmit="return confirm('Smazat uživatele <?php echo e(addslashes($u->name)); ?>?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn" style="font-size:0.75rem;padding:6px 12px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">Smazat</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" style="color:var(--gray-light);">Žádní uživatelé.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>


<div id="reset-pw-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:200;align-items:center;justify-content:center;">
  <div class="admin-form-card" style="width:100%;max-width:420px;margin:0 16px;">
    <div class="admin-form-section-title">Resetovat heslo — <span id="reset-pw-name"></span></div>
    <small style="color:var(--gray-light);display:block;margin-bottom:14px;">
      Zadejte nové heslo, nebo nechte vygenerovat náhodné. Nové heslo si poznamenejte a předejte uživateli — e-mailem se automaticky neodesílá.
    </small>

    <form id="reset-pw-form" method="POST" action="">
      <?php echo csrf_field(); ?>

      <div class="form-group">
        <label class="form-label">Nové heslo</label>
        <div class="password-field">
          <input type="text" name="password" id="reset-pw-input" class="form-control" required minlength="8" placeholder="min. 8 znaků">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Nové heslo znovu</label>
        <input type="text" name="password_confirmation" id="reset-pw-confirm" class="form-control" required minlength="8">
      </div>

      <button type="button" class="btn btn-outline" style="width:100%;margin-bottom:10px;" onclick="generateRandomPassword()">
        🎲 Vygenerovat náhodné heslo
      </button>

      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary" style="flex:1;">Uložit nové heslo</button>
        <button type="button" class="btn btn-outline" onclick="closeResetPassword()">Zrušit</button>
      </div>
    </form>
  </div>
</div>

<script>
function openResetPassword(userId, userName) {
  document.getElementById('reset-pw-name').textContent = userName;
  document.getElementById('reset-pw-form').action = '/admin/users/' + userId + '/reset-password';
  document.getElementById('reset-pw-input').value = '';
  document.getElementById('reset-pw-confirm').value = '';
  document.getElementById('reset-pw-modal').style.display = 'flex';
}
function closeResetPassword() {
  document.getElementById('reset-pw-modal').style.display = 'none';
}
document.getElementById('reset-pw-modal').addEventListener('click', function (e) {
  if (e.target === this) closeResetPassword();
});
function generateRandomPassword() {
  var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
  var pw = '';
  for (var i = 0; i < 12; i++) pw += chars.charAt(Math.floor(Math.random() * chars.length));
  document.getElementById('reset-pw-input').value = pw;
  document.getElementById('reset-pw-confirm').value = pw;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.hc', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\MSI\Documents\GitRepos\_personal_\hc_ricany\resources\views/admin/users/index.blade.php ENDPATH**/ ?>