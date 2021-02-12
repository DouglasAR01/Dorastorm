<template>
  <div class="card text-center card-body">
    <h3 class="card-title"><strong>User:</strong> {{ name }}</h3>
    <hr />
    <p class="card-text"><strong>Email:</strong> {{ email }}</p>
    <hr />
    <div class="" v-if="roles.length > 0">
      <p class="card-text">
        <strong>Roles:</strong>
        <span v-for="role in roles" :key="role.id" class="d-block">
          {{ role.name }}
        </span>
      </p>
      <hr />
    </div>

    <p class="card-text text-muted">Member since: {{ created_at | fromNow }}</p>
  </div>
</template>
<script>
export default {
  props: {
    name: null,
    email: null,
    created_at: null,
    roles: null,
  },
};
</script>