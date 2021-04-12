<template>
  <popup-modal ref="popup">
    <div class="modal-header">
      <h5 class="modal-title">{{ title }}</h5>
      <button type="button" class="close" aria-label="Close" @click="_cancel">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <p>{{ message }}</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline-secondary" @click="_cancel">
        {{ cancelButton }}
      </button>
      <button :class="`btn ${okButtonColor}`" @click="_confirm">
        {{ okButton }}
      </button>
    </div>
  </popup-modal>
</template>
<script>
// Taken from: https://stackabuse.com/how-to-create-a-confirmation-dialogue-in-vue-js/
// This component has been bootstrapfied
import PopupModal from "./PopupModal";

export default {
  name: "ConfirmDialogue",

  components: { PopupModal },

  data() {
    return {
      // Parameters that change depending on the type of dialogue
      title: undefined,
      message: undefined, // Main text content
      okButton: undefined, // Text for confirm button; leave it empty because we don't know what we're using it for
      okButtonColor: "btn-primary",
      cancelButton: this.$t("message.cancel"), // text for cancel button

      // Private variables
      resolvePromise: undefined,
      rejectPromise: undefined,
    };
  },

  methods: {
    show(opts = {}) {
      this.title = opts.title;
      this.message = opts.message;
      this.okButton = opts.okButton;
      if (opts.cancelButton) {
        this.cancelButton = opts.cancelButton;
      }
      if (opts.okButtonColor) {
        this.okButtonColor = opts.okButtonColor;
      }
      // Once we set our config, we tell the popup modal to open
      this.$refs.popup.open();
      // Return promise so the caller can get results
      return new Promise((resolve, reject) => {
        this.resolvePromise = resolve;
        this.rejectPromise = reject;
      });
    },

    _confirm() {
      this.$refs.popup.close();
      this.resolvePromise(true);
    },

    _cancel() {
      this.$refs.popup.close();
      this.resolvePromise(false);
      // Or you can throw an error
      // this.rejectPromise(new Error('User cancelled the dialogue'))
    },
  },
};
</script>