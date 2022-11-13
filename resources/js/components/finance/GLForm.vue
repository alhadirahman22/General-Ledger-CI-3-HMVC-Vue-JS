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
                v-model="form.fin_gl_code"
              />
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label> Data Refer</label>
              <multiselect
                id="ajax"
                label="text"
                track-by="code"
                placeholder="Type to  search code"
                :options="buktiOptions"
                :multiple="false"
                :searchable="true"
                :loading="isLoadingBukti"
                @search-change="asyncFind"
                @tag="addTag"
                :taggable="true"
                :disabled="status == '1' || status == '0'"
                v-model="form.fin_gl_no_bukti"
              >
              </multiselect>
              <div v-html="openLinkCode"></div>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label> Trans Date</label>
              <input
                type="date"
                class="form-control"
                :disabled="status == 1"
                v-model="form.fin_gl_date"
              />
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group">
              <label> Jurnal Voucher</label>
              <Select2
                :options="jurnalOptions"
                placeholder="Pilih Jurnal"
                :disabled="status == 1"
                v-model="form.fin_jurnal_voucher_id"
              />
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
                    <button
                      class="btn btn-sm btn-secondary"
                      @click="addCoa"
                      :disabled="status == 1"
                    >
                      Add Coa
                    </button>
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
                    <tbody>
                      <tr v-for="(item, index) in form.detail" :key="index">
                        <td>
                          <Select2
                            :options="coaOptions"
                            placeholder="Pilih Coa"
                            v-model="form.detail[index]['fin_coa_id']"
                            name="fin_coa_id"
                            :disabled="status == 1"
                            @select="onSelectCoa($event, index)"
                          />
                          <div style="display: flex">
                            <span style="color: red">{{
                              form.detail[index]["ref"]["name_coa_show"]
                            }}</span>
                          </div>
                        </td>
                        <td>
                          <input
                            type="text"
                            class="form-control"
                            v-model="form.detail[index]['fin_gl_referensi']"
                            placeholder="Input Referensi"
                            :disabled="status == 1"
                          />
                        </td>
                        <td>
                          <input
                            type="number"
                            class="form-control"
                            v-model="form.detail[index]['debit']"
                            placeholder="Input Debit"
                            v-on:keyup="onValidate($event, index, 'credit')"
                            :disabled="
                              form.detail[index]['ref']['disabled'] ==
                                'debit' || status == 1
                            "
                          />
                          <span style="color: red; font-weight: bold">{{
                            debitShow(index)
                          }}</span>
                        </td>
                        <td>
                          <input
                            type="number"
                            class="form-control"
                            v-model="form.detail[index]['credit']"
                            placeholder="Input Credit"
                            v-on:keyup="onValidate($event, index, 'debit')"
                            :disabled="
                              form.detail[index]['ref']['disabled'] ==
                                'credit' || status == 1
                            "
                          />
                          <span style="color: green; font-weight: bold">{{
                            creditShow(index)
                          }}</span>
                        </td>
                        <td>
                          <button
                            type="button"
                            class="btn btn-sm btn-danger"
                            @click="deleteRow(index)"
                            v-if="status != '1'"
                            :disabled="status == 1"
                          >
                            Delete
                          </button>
                        </td>
                      </tr>
                    </tbody>
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
                    <tbody>
                      <tr>
                        <td rowspan="2">Sub Total</td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td>
                          <span style="color: red">{{ totalDebit }}</span>
                        </td>
                        <td>
                          <span style="color: green">{{ totalCredit }}</span>
                        </td>
                        <td>
                          <span style="color: blue">{{
                            selishDebitCredit
                          }}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="pull-left">
          <a
            class="btn btn-purple"
            :href="moduledata.module_url"
            v-html="iconbtn.cancel_w_icon"
          >
          </a>
        </div>
        <div class="pull-right">
          <button
            class="btn btn-primary"
            @click="onDraft"
            v-if="status == null || status == '0'"
          >
            Draft
          </button>

          <button
            class="btn btn-success"
            v-if="status == '0'"
            @click="confirmData"
          >
            Submit
          </button>
          <button class="btn btn-info" v-if="status == '1'" @click="infoData">
            Info
          </button>
        </div>
      </div>
      <modal-info
        @close="closeModal"
        :title="'Detail data'"
        :ref_id="form.fin_gl_id"
        v-if="showModalInfo"
      >
        <div slot="footer" class="modal-footer">
          <button
            type="button"
            class="btn btn-success btn-modal-submit"
            @click="submit"
            :disabled="procModal == 1"
            v-show="modalStatus == '1'"
          >
            Submit
          </button>
          <button
            type="button"
            class="btn btn-default"
            data-dismiss="modal"
            @click="closeModal"
          >
            Close
          </button>
        </div>
        <div slot="body" class="modal-body">
          <div class="row">
            <div class="col-sm-3">
              <b>Code : {{ form.fin_gl_code }}</b>
            </div>
            <div class="col-sm-3">
              <b>No bukti : {{ form.fin_gl_no_bukti.code }}</b>
            </div>
            <div class="col-sm-3">
              <b>Trans Date : {{ moment(form.fin_gl_date) }}</b>
            </div>
            <div class="col-sm-3">
              <b>Jurnal Voucher : {{ ref.jurnal_voucher_name_show }}</b>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>Kode Perkiraan</th>
                    <th>Referensi</th>
                    <th>Debit</th>
                    <th>Credit</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in form.detail" :key="index">
                    <td>
                      {{ item.ref.name_coa_show }}
                    </td>
                    <td>
                      {{ item.fin_gl_referensi }}
                    </td>
                    <td>
                      {{ format_money_other(item.debit) }}
                    </td>
                    <td>
                      {{ format_money_other(item.credit) }}
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total</td>
                    <td>
                      {{
                        totalDebit == totalCredit ? "Balance" : "Not Balance"
                      }}
                    </td>
                    <td>
                      <b>{{ totalDebit }}</b>
                    </td>
                    <td>
                      <b>{{ totalCredit }}</b>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </modal-info>
    </div>
  </widget-form>
</template>

<script>
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import Multiselect from "vue-multiselect";
import moment from "moment";
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
      form: {
        fin_gl_id: null,
        fin_jurnal_voucher_id: null,
        fin_gl_prefix: "GL-",
        fin_gl_code: null,
        fin_gl_date: null,
        fin_gl_code_inc: null,
        fin_gl_no_bukti: null,
        detail: [],
      },
      detailDeleted: [],
      coaOptions: [],
      status: null,
      ref: {
        jurnal_voucher_name_show: null,
        totalCredit: 0,
        totalDebit: 0,
        selisih: 0,
      },
      openLinkCode: null,
      showModalInfo: false,
      procModal: 0,
      modalStatus: "1", // 1  = submit , -1 just view
    };
  },
  methods: {
    moment: function (v) {
      return moment(v).format("DD MMMM YYYY");
    },
    closeModal() {
      this.showModalInfo = false;
    },
    onSelectCoa({ id, text }, index) {
      this.form.detail[index]["ref"]["name_coa_show"] = text;
    },
    // toggleUnBukti({ id, text }) {},

    onValidate(e, index, type) {
      const v = e.target.value;
      if (v == "") {
        const typeItself = type == "credit" ? "debit" : "credit";
        this.form.detail[index][typeItself] = 0;
      }
      if (v > 0) {
        this.form.detail[index].ref.disabled = type;
      } else {
        this.form.detail[index].ref.disabled = false;
      }
    },
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
    async coaOption() {
      const res = await axios.get(base_url + "main/optionModels", {
        params: {
          id: "fin_coa_id",
          text: "fin_coa_code-fin_coa_name-type",
          eloquent: "Modules\\finance\\models\\Coa_model_eloquent",
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
        this.coaOptions = myOptions;
      }
    },
    addCoa() {
      const temp = {
        fin_gl_detail_id: null,
        fin_gl_id: this.form.fin_gl_id,
        fin_coa_id: null,
        fin_gl_referensi: null,
        debit: 0,
        credit: 0,
        desc: null,
        ref: {
          name_coa_show: null,
          coa_type: null,
          code: null,
          disabled: false,
        },
      };

      this.form.detail.push(temp);
    },
    deleteRow(index) {
      const d = this.detailDeleted.filter(
        (x) =>
          parseInt(x.fin_gl_detail_id) ===
          parseInt(this.form.detail[index]["fin_gl_detail_id"])
      );
      if (!d.length) {
        this.detailDeleted.push(this.form.detail[index]);
      }

      this.form.detail = this.form.detail.filter((x, i) => i !== index);
    },
    format_money_other(bilangan) {
      bilangan = parseFloat(bilangan);
      return bilangan.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    },
    async submit() {
      if (this.ref.totalCredit > 0 || this.ref.totalDebit > 0) {
        if (parseFloat(this.ref.selisih) == 0) {
          const url = this.moduledata.module_url + "submit";
          let form = this.form;
          form["debit_total"] = this.ref.totalDebit;
          form["credit_total"] = this.ref.totalCredit;
          form["selisih_total"] = this.ref.selisih;
          const PostData = {
            form: this.form,
            detailDeleted: this.detailDeleted,
          };
          const token = jwt_encode(PostData, jwtKey);
          if (confirm("Are you sure ?")) {
            try {
              this.procModal = 1;
              const json = await App_template.AjaxSubmitFormPromises(
                url,
                token
              );
              if (json.status == "error") {
                this.$swal({
                  title: "Alert",
                  html: json.message,
                  type: "info",
                  confirmButtonText: "OK",
                }).then(function () {});
              } else {
                App_template.response_form_token(json);
              }
            } catch (err) {
              console.log(err);
            } finally {
              setTimeout(() => {
                this.procModal = 0;
              }, 1000);
            }
          }
        } else {
          this.$swal({
            title: "Alert",
            html: "Please check balance",
            type: "info",
            confirmButtonText: "OK",
          }).then(function () {});
        }
      } else {
        this.$swal({
          title: "Alert",
          html: "Debit & Credit is required",
          type: "info",
          confirmButtonText: "OK",
        }).then(function () {});
      }
    },
    async onDraft() {
      if (this.ref.totalCredit > 0 || this.ref.totalDebit > 0) {
        if (parseFloat(this.ref.selisih) == 0) {
          // if (true) {
          const url = this.moduledata.module_url + "draft";
          let form = this.form;
          form["debit_total"] = this.ref.totalDebit;
          form["credit_total"] = this.ref.totalCredit;
          form["selisih_total"] = this.ref.selisih;
          const PostData = {
            form: this.form,
            detailDeleted: this.detailDeleted,
          };
          const token = jwt_encode(PostData, jwtKey);
          if (confirm("Are you sure ?")) {
            App_template.loadingStart();
            try {
              const json = await App_template.AjaxSubmitFormPromises(
                url,
                token
              );
              if (json.status == "error") {
                this.$swal({
                  title: "Alert",
                  html: json.message,
                  type: "info",
                  confirmButtonText: "OK",
                }).then(function () {});
              } else {
                App_template.response_form_token(json);
              }
            } catch (err) {
              console.log(err);
            } finally {
              await App_template.timeout(1000);
              App_template.loadingEnd(0);
            }
          }
        } else {
          this.$swal({
            title: "Alert",
            html: "Please check balance",
            type: "info",
            confirmButtonText: "OK",
          }).then(function () {});
        }
      } else {
        this.$swal({
          title: "Alert",
          html: "Debit & Credit is required",
          type: "info",
          confirmButtonText: "OK",
        }).then(function () {});
      }
    },
    async load_data() {
      if (this.datas.fin_gl_id !== undefined) {
        this.status = this.datas.status;
        try {
          App_template.loadingStart();
          const url =
            this.moduledata["module_url"] + "load_data/" + this.datas.fin_gl_id;
          const json = await App_template.AjaxSubmitFormPromises(url);
          this.form = json.form;
          const newTag = json.form.fin_gl_no_bukti;

          const tag = {
            text: newTag,
            code: newTag,
          };
          this.buktiOptions.push(tag);
          this.form.fin_gl_no_bukti = tag;

          this.status = json.form.status;
          this.ref = json.ref;

          this.openLinkCode = json.openLinkCode;
        } catch (err) {
          console.log(err);
        } finally {
          await App_template.timeout(1000);
          App_template.loadingEnd(0);
        }
      }
    },
    confirmData() {
      this.showModalInfo = true;
    },
    infoData() {
      this.modalStatus = "-1";
      this.showModalInfo = true;
    },
    async onDelete() {},
  },
  async mounted() {
    await this.jurnalOption();
    await this.coaOption();
    this.mountedSet = true;

    await this.load_data();
  },
  computed: {
    debitShow() {
      return (index) =>
        this.format_money_other(this.form.detail[index]["debit"]);
    },
    creditShow() {
      return (index) =>
        this.format_money_other(this.form.detail[index]["credit"]);
    },
    totalDebit() {
      var msgTotal = this.form.detail.reduce(function (prev, cur) {
        return parseFloat(prev) + parseFloat(cur.debit);
      }, 0);
      msgTotal = parseFloat(msgTotal);
      this.ref.totalDebit = msgTotal;
      return this.format_money_other(msgTotal);
    },
    totalCredit() {
      var msgTotal = this.form.detail.reduce(function (prev, cur) {
        return parseFloat(prev) + parseFloat(cur.credit);
      }, 0);
      msgTotal = parseFloat(msgTotal);
      this.ref.totalCredit = msgTotal;
      return this.format_money_other(msgTotal);
    },
    selishDebitCredit() {
      var msgTotal1 = this.form.detail.reduce(function (prev, cur) {
        return parseFloat(prev) + parseFloat(cur.debit);
      }, 0);
      msgTotal1 = parseFloat(msgTotal1);

      var msgTotal2 = this.form.detail.reduce(function (prev, cur) {
        return parseFloat(prev) + parseFloat(cur.credit);
      }, 0);
      msgTotal2 = parseFloat(msgTotal2);
      var msgTotal = msgTotal1 - msgTotal2;
      this.ref.selisih = msgTotal;

      return this.format_money_other(msgTotal);
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
