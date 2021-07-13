<template>
  <animation-loader
    :width="400"
    :height="142"
    :speed="2"
  >
    <rect x="0" y="0" rx="5" ry="5" width="40%" height="20" />
    <rect x="0" y="42" rx="5" ry="5" width="100%" height="200" />
    <rect x="0" y="265" rx="5" ry="5" width="100%" height="10" />
    <rect x="0" y="285" rx="5" ry="5" width="100%" height="10" />
    <rect x="0" y="305" rx="5" ry="5" width="100%" height="10" />
    <rect x="0" y="335" rx="5" ry="5" width="65%" height="10" />
    <rect x="75%" y="335" rx="5" ry="5" width="10%" height="10" />
    <rect x="0" y="355" rx="5" ry="5" width="65%" height="10" />
    <rect x="75%" y="355" rx="5" ry="5" width="30%" height="10" />
    <rect x="0" y="375" rx="5" ry="5" width="65%" height="10" />
    <rect x="75%" y="375" rx="5" ry="5" width="30%" height="10" />
    <rect x="0" y="395" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="395" rx="5" ry="5" width="30%" height="8" />
    <rect x="0" y="415" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="415" rx="5" ry="5" width="30%" height="8" />
    <rect x="0" y="445" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="445" rx="5" ry="5" width="30%" height="8" />
    <rect x="0" y="465" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="465" rx="5" ry="5" width="30%" height="8" />
    <rect x="0" y="485" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="485" rx="5" ry="5" width="30%" height="8" />
    <rect x="0" y="505" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="505" rx="5" ry="5" width="30%" height="8" />
    <rect x="0" y="525" rx="5" ry="5" width="65%" height="8" />
    <rect x="75%" y="525" rx="5" ry="5" width="30%" height="8" />
    <rect x="75%" y="550" rx="5" ry="5" width="10%" height="10" />
  </animation-loader>
</template>
<script>
import AnimationLoader from "./AnimationLoader";

export default {
  components: { AnimationLoader },
};
</script>