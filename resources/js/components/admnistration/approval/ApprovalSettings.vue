<template>
  <widget-form :title="title">
    <div slot="body">
      <div class="form-group row">
        <label class="col-form-label col-md-3"> Pilih Approval Name</label>
        <div class="col-md-4">
          <Select2
            v-model="approval_rule_id"
            :options="approvalOptions"
            @change="onChangeApproval_department($event)"
            @select="onSelectjenisApproval_department($event)"
            placeholder="Pilih Jenis Approval"
            :disabled="disableInput"
          />
        </div>
      </div>
      <div class="form-group row" v-show="approval_rule_id !== null">
        <label for="jenis_mutasi_id" class="col-form-label col-md-3">
          Pilih Department</label
        >
        <div class="col-md-6">
          <multiselect
            v-model="department_id"
            :options="departmentOptions"
            :multiple="true"
            label="text"
            @select="onSelectDepartment($event)"
            :track-by="'id'"
            :disabled="disableInput"
            @remove="toggleUnDept($event)"
          >
          </multiselect>
        </div>
      </div>
      <div class="row" v-show="department_id.length">
        <div
          v-for="(item, index) in department_id"
          :key="index"
          :style="[index > 1 ? { 'margin-top': '25px' } : '']"
          :class="{
            'col-md-5': true,
            well: true,
            'col-md-offset-2': index % 2 != 0 ? true : false,
          }"
        >
          <div class="pull-left">
            <h5 style="color: red">
              {{ getNameDepartment(item.id) }}
            </h5>
          </div>
          <div class="pull-right">
            <button
              class="btn btn-sm btn-primary"
              @click="addJabatan(item.id)"
              :disabled="disableInput"
            >
              <i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add
              Jabatan
            </button>
          </div>

          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Jabatan</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item2, index2) in departmentJabatan[index].jabatan"
                :key="index2"
              >
                <td>{{ index2 + 1 }}</td>
                <td>
                  <Select2
                    v-model="
                      departmentJabatan[index].jabatan[index2].jabatan_id
                    "
                    :options="getJabatanOptions(item.id)"
                    placeholder="Pilih Jabatan"
                    @select="onSelectJabatan($event, index, index2)"
                    :disabled="disableInput"
                  />

                  <span style="color: blue">{{
                    departmentJabatan[index].jabatan[index2].employeeName
                  }}</span>
                </td>
                <td>
                  <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    @click="deleteJabatan(index, index2)"
                    :disabled="disableInput"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row" style="margin-top: 10px">
            <div class="col-xs-12">
              <label for="">Type Approval</label>
              <Select2
                v-model="departmentJabatan[index].typeApproval"
                :options="typeApproval"
                placeholder="Pilih Type Approval"
                :disabled="disableInput"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div slot="footerbutton">
      <div class="pull-left">
        <a
          :href="moduledata.module_url"
          class="btn btn-purple"
          v-html="iconbtn.cancel_w_icon"
        >
        </a>
      </div>
      <div class="pull-right">
        <button
          type="button"
          class="btn btn-success"
          v-html="iconbtn.save_w_icon"
          :disabled="loadingBtn || disableInput"
          v-show="!disableInput"
          @click="submit"
        ></button>
      </div>
    </div>
  </widget-form>
</template>

<script>
import Select2 from "v-select2-component";
import Select2MultipleControl from "v-select2-multiple-component";
import Multiselect from "vue-multiselect";
Vue.component("Select2", Select2);
Vue.component("Select2MultipleControl", Select2MultipleControl);
Vue.component("multiselect", Multiselect);
export default {
  props: ["title", "data_approval", "moduledata", "iconbtn"],
  data: function () {
    return {
      approval_rule_id: null,
      approvalOptions: [],
      disableInput: false,
      departmentOptions: [],
      action: "add",
      department_id: [],
      jabatanOptions: [],
      departmentJabatan: [],
      loadingBtn: false,
      typeApproval: [
        {
          id: "1",
          text: "Series",
        },
        {
          id: "2",
          text: "Paralel",
        },
      ],
    };
  },
  created() {
    if (this.data_approval != null) {
      this.action = "view";
    }
    this.loadApproval();

    if (this.action != "add") {
      this.showData();
      this.disableAllInput();
    }
  },
  mounted() {},
  computed: {},
  updated() {},
  methods: {
    disableAllInput() {
      this.disableInput = true;
    },
    showData() {},
    onChangeApproval_department(val) {},
    onSelectjenisApproval_department() {
      this.loadDataDepartment();
    },
    async loadDataDepartment() {
      const res = await axios(base_url + "administration/api_department/fetch");
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].department_id,
            text: dataRest[index].name,
            jabatan: [],
          };
          myOptions.push(temp);
        }
        this.departmentOptions = myOptions;
      }
    },
    async loadApproval() {
      const res = await axios(
        base_url + "administration/api_approval/onceUsed"
      );
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].approval_rule_id,
            text: dataRest[index].name,
          };
          myOptions.push(temp);
        }
        this.approvalOptions = myOptions;
      }
    },
    toggleUnDept({ id, text }) {
      this.departmentJabatan = this.departmentJabatan.filter(
        (x) => x.department_id != id
      );
    },
    async onSelectDepartment({ id, text }) {
      const temp = {
        department_id: id,
        jabatan: [],
        typeApproval: null,
      };
      const filter = this.departmentJabatan.filter(
        (t) => t.department_id == id
      );
      if (filter.length) {
        const checkExist = this.department_id.filter((t) => t.id == id);
        if (!checkExist.length) {
          this.departmentJabatan = this.departmentJabatan.filter(
            (t) => t.department_id != id
          );
        } else {
          const index = this.departmentJabatan.findIndex(
            (t) => t.department_id == id
          );
          this.departmentJabatan[index].jabatan.push({
            jabatan_id: null,
          });
        }
      } else {
        this.departmentJabatan.push(temp);
      }
      await this.loadJabatan(id);
    },
    async loadJabatan(department_id) {
      const res = await axios(
        base_url +
          "administration/api_jabatan/getJabatanByDepartment/" +
          department_id
      );

      if (res.data.success) {
        const data = res.data.data;
        const options = this.jabatanOptions.filter(
          (x) => x.department_id == department_id
        );

        if (options.length) {
          this.jabatanOptions = this.jabatanOptions.filter(
            (x) => x.department_id != department_id
          );
        }

        for (let index = 0; index < data.length; index++) {
          this.jabatanOptions.push({
            department_id: department_id,
            id: data[index].jabatan_id,
            text: data[index].name,
            employeeName: data[index].employee[0].name,
          });
        }
      }
    },
    submit: async function () {
      if (confirm("Are you sure ?")) {
        const d = this.department_id;
        let x = [];

        for (let index = 0; index < d.length; index++) {
          x.push(d[index].id);
        }

        const postData = {
          jenis_mutasi_department: {
            jenis_mutasi_id: this.jenis_mutasi_id,
            department_id: x,
          },
          jenis_mutasi_department_approval: this.departmentJabatan,
        };

        App_template.loadingStart();
        try {
          const token = jwt_encode(postData, jwtKey);
          const json = await App_template.AjaxSubmitFormPromises(
            this.moduledata.module_url + "save",
            token
          );
          App_template.response_form_token(json);
        } catch (error) {
          console.log(error);
        } finally {
          await App_template.timeout(1000);
          App_template.loadingEnd(0);
        }
      }
    },
    getNameDepartment(department_id) {
      const departmentArr = this.departmentOptions;
      const name = departmentArr.find((t) => t.id == department_id);
      return name.text;
    },
    getJabatanOptions(department_id) {
      const options = this.jabatanOptions.filter(
        (x) => x.department_id == department_id
      );
      return options;
    },
    addJabatan(department_id) {
      const index = this.departmentJabatan.findIndex(
        (t) => t.department_id == department_id
      );

      this.departmentJabatan[index].jabatan.push({
        jabatan_id: null,
      });
    },
    deleteJabatan(index, index2) {
      let jabatan = this.departmentJabatan[index].jabatan;
      jabatan = jabatan.filter((x, i) => i !== index2);
      this.departmentJabatan[index].jabatan = jabatan;
    },
    onSelectJabatan(ev, index, index2) {
      this.departmentJabatan[index].jabatan[index2].employeeName =
        ev.employeeName;
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
