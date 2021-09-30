<template>
  <form @submit.prevent="$emit('search')">
    <div class="input-group">
      <input
        type="text"
        class="form-control"
        name="search"
        :placeholder="$t('message.search')"
        :value="value"
        @input="$emit('input', $event.target.value)"
      />
      <div class="input-group-append">
        <button class="btn btn-primary" @click.prevent="$emit('search')">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>
</template>
<script>
export default {
  props: {
    value: {
      type: String,
      required: false,
      default: () => (null)
    }
  }
}
</script>