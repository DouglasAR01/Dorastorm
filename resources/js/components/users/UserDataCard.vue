<template>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <div class="card">
        <div class="row no-gutters">
          <div class="col-sm-4 px-4 p-sm-2 d-flex justify-content-center">
            <img
              :src="require('../../../assets/default-user.svg')"
              alt=""
              class="profile-img"
            />
          </div>
          <div class="col-sm-8">
            <router-link
              :to="{ name: 'users-update', params: { locale: $route.params.locale, userId: user.id } }"
              v-if="allowEdit"
            >
              <span class="user-data-cog">
                <i class="fas fa-cog"></i>
              </span>
            </router-link>
            <div class="card-body">
              <h3 class="card-title">
                {{ user.name }}
              </h3>
              <p class="card-text">
                {{ user.email }}
              </p>
              <p class="card-text">
                <strong>{{ $t("modules.users.role") }}</strong>
                <span>
                  {{ user.role.name }}
                </span>
              </p>
              <p class="card-text text-muted">
                {{ $t("message.member_since") }}
                {{ user.created_at | fromNow }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-2"></div>
  </div>
</template>
<script>
export default {
  props: {
    user: {
      type: Object,
      required: true,
    },
    allowEdit: {
      type: Boolean,
      required: false,
      default: () => true,
    },
  },
};
</script>
<style scoped>
.profile-img {
  border-radius: calc(0.25rem - 1px);
  width: 100%;
  filter: invert(77%) sepia(9%) saturate(112%) hue-rotate(122deg) brightness(96%) contrast(90%);
}
.user-data-cog {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
}
@media only screen and (max-width: 575px) {
  .profile-img {
    width: 50%;
    padding: 0;
  }
}
@media only screen and (max-width: 400px) {
  .profile-img {
    width: 100%;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
}
</style>