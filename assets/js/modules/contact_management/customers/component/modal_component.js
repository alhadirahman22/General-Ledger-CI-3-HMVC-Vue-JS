let modal_component = `
      <div class="modal fade" v-bind:id="ref_id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <div class="pull-left">
                <h5 class="modal-title" id="exampleModalLabel">{{title}}</h5>
              </div>
              <div class="pull-right">
                <button
                  type="button"
                  class="close"
                  @click="$emit('close')"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <slot name="body"></slot>
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
`;