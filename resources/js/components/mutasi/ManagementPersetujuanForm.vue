<template>
  <div>
    <widget-form :title="title">
      <div slot="body">
        <div class="form-group row">
          <label for="jenis_mutasi_id" class="col-form-label col-md-3">
            Pilih Jenis Mutasi</label
          >
          <div class="col-md-4">
            <Select2
              v-model="jenis_mutasi_id"
              :options="jeniMutasiOptions"
              @change="onChangejenis_mutasi_department($event)"
              @select="onSelectjenis_mutasi_department($event)"
              placeholder="Pilih Jenis Mutasi"
              :disabled="disableInput"
            />
          </div>
        </div>
        <div class="form-group row" v-show="jenis_mutasi_id !== null">
          <label for="jenis_mutasi_id" class="col-form-label col-md-3">
            Pilih Department</label
          >
          <div class="col-md-4">
            <select2-multiple-control
              v-model="department_id"
              :options="departmentOptions"
              @change="onChangeDepartment($event)"
              @select="onSelectDepartment($event)"
              :disabled="disableInput"
            />
          </div>
        </div>
        <div class="row" v-show="department_id.length">
          <div
            class="col-md-6"
            v-for="(item, index) in department_id"
            :key="index"
            :style="[index > 1 ? { 'margin-top': '25px' } : '']"
          >
            <div>
              <div class="pull-left">
                <h5 style="color: red">
                  {{ getNameDepartment(item) }}
                </h5>
              </div>
              <div class="pull-right">
                <button
                  class="btn btn-sm btn-primary"
                  @click="addJabatan(item)"
                  :disabled="disableInput"
                >
                  <i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add
                  Jabatan
                </button>
              </div>
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
                      :options="getJabatanOptions(item)"
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
  </div>
</template>
<script>
import Select2 from "v-select2-component";
import Select2MultipleControl from "v-select2-multiple-component";
Vue.component("Select2", Select2);
Vue.component("Select2MultipleControl", Select2MultipleControl);
export default {
  props: ["title", "datajenismutasi", "moduledata", "iconbtn"],
  data: function () {
    return {
      jenis_mutasi_id: null,
      jeniMutasiOptions: [], // or [{id: key, text: value}, {id: key, text: value}]
      departmentOptions: [], // or [{id: key, text: value}, {id: key, text: value}]
      department_id: [],
      action: "add",
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
      disableInput: false,
    };
  },
  created() {
    if (this.datajenismutasi != null) {
      this.action = "view";
    }
    this.loadJeniMutasi();

    if (this.action != "add") {
      this.showData();
      this.disableAllInput();
    }
  },
  mounted() {},
  computed: {},
  methods: {
    disableAllInput() {
      this.disableInput = true;
    },
    async showData() {
      this.jenis_mutasi_id = this.datajenismutasi.jenis_mutasi_id;
      const museum_id = this.datajenismutasi.museum_id;

      await this.loadDataDepartment(museum_id);

      const tagging_department = this.datajenismutasi.tagging_department;
      for (let index = 0; index < tagging_department.length; index++) {
        this.department_id.push(
          String(tagging_department[index].department_id)
        );
        // await this.loadJabatan(tagging_department[index].department_id);
        await this.onSelectDepartment({
          id: tagging_department[index].department_id,
          text: null,
        });
      }

      for (let index = 0; index < this.departmentJabatan.length; index++) {
        const department_id = this.departmentJabatan[index].department_id;
        const find = tagging_department.find(
          (x) => parseInt(x.department_id) == parseInt(department_id)
        );
        let jabatan = find.pivot.jabatan;

        for (let i = 0; i < jabatan.length; i++) {
          const jabatan_id = jabatan[i].jabatan_id;

          const findJabatan = this.jabatanOptions.find(
            (x) => parseInt(x.id) == parseInt(jabatan_id)
          );
          jabatan[i].employeeName = findJabatan.employeeName;
        }
        this.departmentJabatan[index].jabatan = jabatan;
        this.departmentJabatan[index].typeApproval = find.pivot.type_approval;
      }
    },
    submit: async function () {
      if (confirm("Are you sure ?")) {
        const postData = {
          jenis_mutasi_department: {
            jenis_mutasi_id: this.jenis_mutasi_id,
            department_id: this.department_id,
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
          console.log(err);
        } finally {
          await App_template.timeout(1000);
          App_template.loadingEnd(0);
        }
      }
    },
    onChangejenis_mutasi_department(val) {},
    onSelectJabatan(ev, index, index2) {
      this.departmentJabatan[index].jabatan[index2].employeeName =
        ev.employeeName;
    },
    onSelectjenis_mutasi_department({ id, text, museum_id }) {
      // const dataOptions = { id, text, museum_id };
      this.loadDataDepartment(museum_id);
    },
    onChangeDepartment(val) {
      // console.log(val);
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
        const checkExist = this.department_id.filter((t) => t == id);
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

    async loadJeniMutasi() {
      const res = await axios(base_url + "mutasi/api_jenis_mutasi");
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].jenis_mutasi_id,
            text: dataRest[index].museum.name + " - " + dataRest[index].name,
            museum_id: dataRest[index].museum_id,
          };
          myOptions.push(temp);
        }
        this.jeniMutasiOptions = myOptions;
      }
    },
    async loadDataDepartment(museum_id) {
      const newPost = {
        museum_id: museum_id,
      };

      const token = jwt_encode(newPost, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        base_url + "administration/api_department/fetch",
        token
      );
      if (json.success) {
        let dataRest = json.data;
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
    getNameDepartment(department_id) {
      const departmentArr = this.departmentOptions;
      const name = departmentArr.find((t) => t.id == department_id);
      return name.text;
    },
    async loadJabatan(department_id) {
      const json = await App_template.AjaxSubmitFormPromises(
        base_url +
          "administration/api_jabatan/getJabatanByDepartment/" +
          department_id
      );
      if (json.success) {
        const data = json.data;
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
  },
};
</script>
