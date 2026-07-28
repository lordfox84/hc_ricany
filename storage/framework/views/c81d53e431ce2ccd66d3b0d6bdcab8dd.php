<aside class="admin-sidebar">
  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-content')): ?>
  <div class="admin-sidebar-section">
    <div class="admin-sidebar-heading">Obsah</div>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.articles.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.articles.index')); ?>"><span class="icon">📝</span> Články &amp; novinky</a>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.articles.create') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.articles.create')); ?>"><span class="icon">✏️</span> Nový článek</a>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.categories.index')); ?>"><span class="icon">🏷️</span> Tagy</a>
  </div>
  <?php endif; ?>

  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-club')): ?>
  <div class="admin-sidebar-section" style="margin-top:24px;">
    <div class="admin-sidebar-heading">Klub</div>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.teams.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.teams.index')); ?>"><span class="icon">🏒</span> Týmy</a>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.players.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.players.index')); ?>"><span class="icon">👤</span> Hráči</a>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.camps.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.camps.index')); ?>"><span class="icon">🏕️</span> Kempy</a>
  </div>
  <?php endif; ?>

  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users')): ?>
  <div class="admin-sidebar-section" style="margin-top:24px;">
    <div class="admin-sidebar-heading">Uživatelé &amp; marketing</div>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.users.index')); ?>"><span class="icon">🔑</span> Uživatelé</a>
    <a class="admin-nav-item <?php echo e(request()->routeIs('admin.subscribers.*') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.subscribers.index')); ?>"><span class="icon">📬</span> Odběry</a>
  </div>
  <?php endif; ?>
</aside>
<?php /**PATH C:\Users\MSI\Documents\GitRepos\_personal_\hc_ricany\resources\views/partials/_admin_sidebar.blade.php ENDPATH**/ ?>