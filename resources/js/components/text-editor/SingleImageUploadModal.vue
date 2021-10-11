<template>
  <base-modal ref="base">
    <div class="modal-header">
      <h5 class="modal-title">{{$t("message.upload_image")}}</h5>
      <button type="button" class="close" aria-label="Close" @click="$_SingleImageUploadModal_cancel">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <single-file-upload
        @input="$_SingleImageUploadModal_confirm"
        endpoint="/api/upload/image"
        disk="public"
      ></single-file-upload>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline-secondary" @click.prevent="$_SingleImageUploadModal_cancel">
        {{$t("message.cancel")}}
      </button>
    </div>
  </base-modal>
</template>
<script>
import BaseModal from "../modals/BaseModal.vue";
import SingleFileUpload from "../SingleFileUpload.vue";
export default {
  components: {
    BaseModal,
    SingleFileUpload,
  },
  data() {
    return {
      url: null,
      command: null,
    };
  },
  computed: {
    imageURL() {
      if (this.url) {
        return "/storage/" + this.url;
      }
      return null;
    },
    disabled() {
      if (this.url){
        return false;
      }
      return true;
    }
  },
  methods: {
    show(command) {
      this.command = command;
      this.$refs.base.open();
    },
    reset() {
      this.url = null;
      this.command = null;
    },
    $_SingleImageUploadModal_confirm(data) {
      this.url = data;
      const payload = {
        command: this.command,
        data: {
          src: this.imageURL,
          alt: "image",
        },
      };
      this.$emit("confirmed", payload);
      this.$refs.base.close();
      this.reset();
    },
    $_SingleImageUploadModal_cancel() {
      // Should delete the image if uploaded.
      this.reset();
      this.$refs.base.close();
    },
  },
};
</script>