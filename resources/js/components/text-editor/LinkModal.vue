<template>
  <base-modal ref="base">
    <div class="modal-header">
      <h5 class="modal-title">URL</h5>
      <button
        type="button"
        class="close"
        aria-label="Close"
        @click="$_LinkModal_cancel()"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label for="url">URL</label>
        <input
          type="text"
          name="url"
          class="form-control"
          v-model="url"
          @keypress.enter="$_LinkModal_confirm()"
        />
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" @click="$_LinkModal_confirm()">
        {{ $t("message.confirm") }}
      </button>
      <button
        class="btn btn-outline-secondary"
        @click.prevent="$_LinkModal_cancel()"
      >
        {{ $t("message.cancel") }}
      </button>
    </div>
  </base-modal>
</template>
<script>
import BaseModal from "../modals/BaseModal.vue";
export default {
  components: {
    BaseModal,
  },
  data() {
    return {
      url: null,
    };
  },
  methods: {
    show(opts = {}) {
      this.url = null;
      if (opts.url) {
        this.url = opts.url;
      }
      this.$refs.base.open();
    },
    $_LinkModal_cancel() {
      this.$refs.base.close();
    },
    $_LinkModal_confirm() {
      let payload = {
        url: this.url,
      };
      if (this.url === null) {
        payload.url = "";
      }
      this.$emit("confirmed", payload);
      this.$refs.base.close();
    },
  },
};
</script>