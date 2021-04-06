<template>
  <div class="card text-center card-body">
    <h3 class="card-title"><strong>{{ $t("modules.users.name") }}</strong> {{ payload.name }}</h3>
    <hr />
    <p class="card-text"><strong>{{ $t("message.email") }}</strong> {{ payload.email }}</p>
    <hr />
    <div class="" v-if="payload.role">
      <p class="card-text">
        <strong>{{ $t("modules.users.role") }}</strong>
        <span>
          {{ payload.role.name }}
        </span>
      </p>
      <hr />
    </div>

    <p class="card-text text-muted">{{ $t("message.member_since") }} {{ payload.created_at | fromNow }}</p>
  </div>
</template>
<script>
export default {
  props: {
    payload: Object
  },
};
</script>