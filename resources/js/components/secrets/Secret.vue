<template>
  <div class="card">
    <div class="card-header">
      <p class="card-text"><strong>Secret created at: </strong>{{ created_at | humanDate }}</p>
    </div>
    <div class="card-body">
      <p class="card-text"><strong>Content:</strong></p>
      <p class="card-text">
        {{ secret }}
      </p>
    </div>
    <div class="card-footer text-muted">
      Last edited {{ updated_at | humanDate}}
    </div>
  </div>
</template>
<script>
export default {
  props: {
    created_at: null,
    updated_at: null,
    secret: null,
  },
};
</script>