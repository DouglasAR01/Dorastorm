<template>
  <div class="card text-center card-body">
    <h3 class="card-title"><strong>{{ $t("modules.users.name") }}</strong> {{ name }}</h3>
    <hr />
    <p class="card-text"><strong>{{ $t("message.email") }}</strong> {{ email }}</p>
    <hr />
    <div class="" v-if="role">
      <p class="card-text">
        <strong>{{ $t("modules.users.role") }}</strong>
        <span>
          {{ role.name }}
        </span>
      </p>
      <hr />
    </div>

    <p class="card-text text-muted">{{ $t("messages.member_since") }}{{ created_at | fromNow }}</p>
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