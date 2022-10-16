<template>
  <widget-form :title="title" v-show="mountedSet">
    <div slot="body">
      <div class="desktop-view">
        <div class="row">
          <div class="col-xs-3">
            <div class="form-group">
              <label> Code</label>
              <input
                type="text"
                class="form-control"
                disabled
                placeholder="Auto by system"
              />
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label> No Bukti</label>
              <!-- <multiselect
                :options="buktiOptions"
                :multiple="false"
                label="text"
                @select="onSelectBukti($event)"
                :track-by="'code'"
                @remove="toggleUnBukti($event)"
                @tag="addTag"
                placeholder="Search or add no Bukti"
              >
              </multiselect> -->
              <multiselect
                v-model="fin_gl_no_bukti"
                id="ajax"
                label="text"
                track-by="code"
                placeholder="Type to search"
                :options="buktiOptions"
                :multiple="false"
                :searchable="true"
                :loading="isLoadingBukti"
                @search-change="asyncFind"
                @tag="addTag"
                :taggable="true"
              >
              </multiselect>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label> Trans Date</label>
              <input type="date" class="form-control" />
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label> Jurnal Voucher</label>
              <Select2 :options="jurnalOptions" placeholder="Pilih Jurnal" />
            </div>
          </div>
        </div>
        <hr class="hr-bold" />
        <div class="row">
          <div class="col-xs-12">
            <div class="well">
              <div class="row">
                <div class="col-xs-12">
                  <div style="padding: 15px">
                    <button class="btn btn-sm btn-secondary">Add Coa</button>
                  </div>
                  <table class="table tblmax-height">
                    <thead>
                      <tr>
                        <th>Kode Perkiraan</th>
                        <th>Referensi</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="hr-bold" />
      </div>
      <div class="not-mobile-view">
        <h3 style="color: red">This screen just for desktop only</h3>
      </div>
    </div>
    <div slot="footerbutton">
      <div class="desktop-view">
        <div class="row footer-content-desh">
          <div class="col-xs-12">
            <div class="well">
              <div class="row">
                <div class="col-xs-12">
                  <table class="table">
                    <thead>
                      <tr>
                        <td colspan="3"></td>
                        <td><span style="color: red">Debit</span></td>
                        <td><span style="color: green">Credit</span></td>
                        <td><span style="color: blue">Selisih</span></td>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="pull-left">
          <a class="btn btn-purple" v-html="iconbtn.cancel_w_icon"> </a>
        </div>
        <div class="pull-right">
          <button type="button" class="btn btn-primary">Draft</button>
        </div>
      </div>
    </div>
  </widget-form>
</template>

<script>
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import Multiselect from "vue-multiselect";
Vue.component("multiselect", Multiselect);
Vue.use(VueSweetalert2);
export default {
  props: ["title", "datas", "moduledata", "iconbtn"],
  data: function () {
    return {
      jurnalOptions: [],
      buktiOptions: [],
      mountedSet: false,
      isLoadingBukti: false,
      fin_gl_no_bukti: [],
    };
  },
  methods: {
    // onSelectBukti({ id, text }) {},
    // toggleUnBukti({ id, text }) {},
    limitText(count) {
      return `and ${count} Bukti`;
    },
    clearAll() {
      this.fin_gl_no_bukti = [];
    },
    async asyncFind(query) {
      this.isLoading = true;
      if (query.length >= 3 && query.substring(0, 1) == "#") {
        const res = await axios.get(base_url + "main/searchAllCode", {
          params: {
            query: query,
          },
        });
        if (res.data.success) {
          this.buktiOptions = res.data.data;
        }
      }
    },
    addTag(newTag) {
      this.clearAll();
      const tag = {
        text: newTag,
        code: newTag,
      };
      this.buktiOptions.push(tag);
      this.fin_gl_no_bukti.push(tag);
    },
    async jurnalOption() {
      const res = await axios.get(base_url + "main/optionModels", {
        params: {
          id: "fin_jurnal_voucher_id",
          text: "fin_jurnal_voucher_code-fin_jurnal_voucher_name",
          eloquent: "Modules\\finance\\models\\Jurnal_voucher_model_eloquent",
        },
      });
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].id,
            text: dataRest[index].text,
          };
          myOptions.push(temp);
        }
        this.jurnalOptions = myOptions;
      }
    },
    async noBukti() {},
  },
  async created() {
    await this.jurnalOption();
    this.mountedSet = true;
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
