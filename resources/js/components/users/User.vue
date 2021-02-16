<template>
  <div class="card text-center card-body">
    <h3 class="card-title"><strong>User:</strong> {{ name }}</h3>
    <hr />
    <p class="card-text"><strong>Email:</strong> {{ email }}</p>
    <hr />
    <div class="" v-if="role">
      <p class="card-text">
        <strong>Role:</strong>
        <span>
          {{ role.name }}
        </span>
      </p>
      <hr />
    </div>

    <p class="card-text text-muted">Member since: {{ created_at | fromNow }}</p>
    <p>Esta es una prueba</p>
  </div>
</template>
<script>
export default {
  props: {
    name: null,
    email: null,
    created_at: null,
    role: null,
  },
};
</script>