<template>
  <widget-form :title="title">
    <div slot="body"></div>
    <div slot="footerbutton"></div>
  </widget-form>
</template>

<script>
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

Vue.use(VueSweetalert2);
export default {
  props: ["title", "datas", "moduledata", "iconbtn"],
};
</script>
