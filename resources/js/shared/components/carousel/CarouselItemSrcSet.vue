<template>
  <div class="carousel-item">
    <div class="img-gradient">
      <picture>
        <source :srcset="item.sources[0]" media="(min-width: 1400px)" />
        <source :srcset="item.sources[1]" media="(min-width: 769px)" />
        <source :srcset="item.sources[2]" media="(min-width: 577px)" />
        <img
          :srcset="item.sources[3]"
          alt="responsive image"
          class="d-block img-fluid"
        />
      </picture>
    </div>

    <div class="carousel-caption" v-if="item.caption">
      <div>
        <h2>{{ item.caption.title }}</h2>
        <p>{{ item.caption.content }}</p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
      validator(item) {
        if (item.sources.length === 4) {
          return true;
        }
        return false;
      },
    },
  },
};
</script>
<style scoped>
.img-gradient{
  position:relative;
  display:inline-block;
}


/* 
#002f4b,#dc4225 
Convert HEX to RGBA - http://hex2rgba.devoth.com/
*/
.img-gradient:after {
  content:'';
  position:absolute;
  left:0; top:0;
  width:100%; height:100%;
  display:inline-block;
  background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(29, 29, 27, 0.5) 100%); /* FF3.6+ */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(29, 29, 27, 0.5)), color-stop(100%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(29, 29, 27, 0.5) 100%); /* Chrome10+,Safari5.1+ */
  background: -o-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(29, 29, 27, 0.5) 100%); /* Opera 11.10+ */
  background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(29, 29, 27, 0.5) 100%); /* IE10+ */
  background: linear-gradient(to bottom, rgba(255,255,255,0) 0%,rgba(29, 29, 27, 0.5) 100%); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#002f4b', endColorstr='#00000000',GradientType=0 ); /* IE6-9 */
}
</style>