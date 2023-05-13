<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <ul  class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <router-link class="nav-item nav-link" :to="{ name: 'home' }"
            >Home</router-link
          >
        </li>
        <li class="nav-item">
          <router-link class="nav-item nav-link" :to="{ name: 'desks' }"
            >Доски</router-link
          >
        </li>
      </ul>
    </div>
  </nav>
  <div>
    <router-view></router-view>
  </div>
</template>

<script>
export default {};
</script>