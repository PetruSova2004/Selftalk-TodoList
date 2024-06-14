<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
          <li class="nav-item">
            <router-link :to="{ name: 'Home'}" class="nav-link">
              <p>
                Tasks
                <i class="right fas fa-angle-left"></i>
              </p>
            </router-link>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'TaskCreate'}" class="nav-link">
                  <p>Add</p>
                </router-link>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'App-Slider'
};
</script>
