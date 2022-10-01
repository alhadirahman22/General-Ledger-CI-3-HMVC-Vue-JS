<template>
  <div>
    <div class="page-header">
      <h1>
        {{ title }}
      </h1>
    </div>
    <div class="row">
      <div class="col-xs-12" id="header_widget_box">
        <div class="widget-box">
          <div class="widget-body">
            <div class="widget-main">
              <slot name="body"></slot>
              <hr />
              <div class="row">
                <div class="col-xs-12">
                  <slot name="footerbutton"></slot>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["title"],
  data: function () {
    return {};
  },
};
</script>
