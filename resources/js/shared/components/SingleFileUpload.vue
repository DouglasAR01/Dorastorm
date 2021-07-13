<template>
  <div>
    <div class="custom-file" v-if="isInitial || isFailed">
      <validation-error :errors="uploadErrors" name="file" #default="{ e }">
        <input
          type="file"
          name="file"
          id="file"
          class="custom-file-input"
          @change="upload"
          :class="[{ 'is-invalid': e }]"
        />
        <label for="file" class="custom-file-label">{{
          $t("message.file_upload")
        }}</label>
      </validation-error>
    </div>
    <div v-if="isSaving || isSuccess">
      <div class="progress">
        <div
          class="progress-bar progress-bar-striped"
          :class="[
            { 'progress-bar-animated bg-info': isSaving },
            { 'bg-success': isSuccess },
          ]"
          role="progressbar"
          :style="`width:${uploadPercentage}%`"
          :aria-valuenow="uploadPercentage"
          aria-valuemin="0"
          aria-valuemax="100"
        ></div>
      </div>
      <span class="text-success" v-if="isSuccess">
        {{ $t("message.file_uploaded") }}
      </span>
    </div>
  </div>
</template>
<script>
import ValidationError from "./ValidationError";
//Idea taken and adapted from https://www.digitalocean.com/community/tutorials/how-to-handle-file-uploads-in-vue-2
const STATUS_INITIAL = 0,
  STATUS_SAVING = 1,
  STATUS_SUCCESS = 2,
  STATUS_FAILED = 3;
export default {
  components: {
    ValidationError,
  },
  props: {
    endpoint: {
      type: String,
      required: true,
    },
    disk: {
      type: String,
      default: function () {
        return "local";
      },
    },
  },
  data() {
    return {
      uploadErrors: null,
      uploadPercentage: 0,
      currentStatus: STATUS_INITIAL,
    };
  },
  computed: {
    isInitial() {
      return this.currentStatus === STATUS_INITIAL;
    },
    isSaving() {
      return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
      return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
      return this.currentStatus === STATUS_FAILED;
    },
  },
  methods: {
    async upload(e) {
      this.reset();
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.currentStatus = STATUS_SAVING;
      var form = new FormData();
      form.append("file", files[0]);
      form.append("disk", this.disk);
      try {
        const path = (
          await axios.post(this.endpoint, form, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
            onUploadProgress: function (progressEvent) {
              this.uploadPercentage = parseInt(
                Math.round((progressEvent.loaded / progressEvent.total) * 100)
              );
            }.bind(this),
          })
        ).data.path;
        this.currentStatus = STATUS_SUCCESS;
        this.$emit("input", path);
      } catch (error) {
        this.currentStatus = STATUS_FAILED;
        this.uploadErrors = error.response.data.errors;
      }
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
      this.uploadPercentage = 0;
      this.uploadErrors = null;
    },
  },
};
</script>