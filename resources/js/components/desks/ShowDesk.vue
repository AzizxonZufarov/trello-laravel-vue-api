<template>
  <div class="container">
    <h2>{{ name }}</h2>
    <div class="form-group">
      <input
        type="text"
        @blur="saveName"
        v-model="name"
        class="form-control"
        :class="{
          'is-invalid': v$.name.$invalid,
        }"
      />
      <div class="invalid-feedback" v-if="!v$.name.required">Обяз поле</div>
      <div class="invalid-feedback" v-if="!v$.name.maxLength">
        Макс кол-во: {{ v$.name.$params.maxLength.max }}
      </div>
    </div>

    <form @submit.prevent="addNewDeskList">
      <div class="form-group">
        <input
          type="text"
          v-model="desk_list_name"
          class="form-control"
          :class="{
            'is-invalid': v$.desk_list_name.$error,
          }"
        />
        <div class="invalid-feedback" v-if="!v$.desk_list_name.required">
          Обяз поле
        </div>
        <div class="invalid-feedback" v-if="!v$.desk_list_name.maxLength">
          Макс кол-во: {{ v$.desk_list_name.$params.maxLength.max }}
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Добавить</button>
    </form>

    <div class="row">
      <div class="col-lg-4" v-for="desk_list in desk_lists" :key="desk_list.id">
        <div class="card mt-3">
          <div class="card-body">
            <form
              @submit.prevent="updateDeskList(desk_list.id, desk_list.name)"
              v-if="desk_list_input_id == desk_list.id"
            >
              <input type="text" v-model="desk_list.name" />
              <button
                type="button"
                @click="desk_list_input_id = null"
                class="btn-close"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </form>
            <h4
              v-else
              class="card-title"
              @click="desk_list_input_id = desk_list.id"
              style="cursor: pointer"
            >
              {{ desk_list.name
              }}<i
                class="fas fa-pencil-alt"
                style="font-size: 15px; position: absolute; right: 10px"
              ></i>
            </h4>

            <button
              type="button"
              @click="deleteDeskList(desk_list.id)"
              class="btn btn-danger"
            >
              Удалить
            </button>

            <div
              class="card mt-3 bg-light"
              v-for="card in desk_list.cards"
              :key="card.id"
            >
              <div class="card-body">
                <h4
                  class="card-title d-flex justify-content-between align-items-center mb-3"
                  style="cursor: pointer"
                >
                  {{ card.name }}
                </h4>
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="deleteCard(card.id)"
                >
                  Удалить
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  @click="getCard(card.id)"
                >
                  Открыть
                </button>
              </div>
            </div>

            <form
              class="d-flex justify-content-between align-items-center mt-3"
              @submit.prevent="addNewCard(desk_list.id)"
            >
              <input
                type="text"
                v-model="card_names[desk_list.id]"
                class="form-control"
              />
              <!--:class="{
                  'is-invalid': v$.card_names.$each[desk_list.id].$error,
                }" 
                <div
                class="invalid-feedback"
                v-if="!v$.card_names.$each[desk_list.id].required"
              >
                Обяз поле
              </div>
              <div
                class="invalid-feedback"
                v-if="!v$.card_names.$each[desk_list.id].maxLength"
              >
                Макс кол-во:
                {{ v$.card_names.$each[desk_list.id].$params.maxLength.max }}
              </div> -->
            </form>

            <div
              class="modal fade"
              id="exampleModal"
              tabindex="-1"
              aria-labelledby="exampleModalLabel"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <form
                      v-if="show_card_name_input"
                      class="d-flex justify-content-between align-items-center"
                      @submit.prevent="updateCardName"
                    >
                      <input
                        type="text"
                        v-model="current_card.name"
                        class="form-control"
                      />
                      <button
                        type="button"
                        class="btn-close"
                        aria-label="Close"
                        @click="show_card_name_input = false"
                      >
                        <span aria-hidden="true"></span>
                      </button>
                    </form>

                    <h1
                      style="cursor: pointer"
                      class="modal-title fs-5"
                      id="exampleModalLabel"
                      v-if="!show_card_name_input"
                      @click="show_card_name_input = true"
                    >
                      {{ current_card.name }}
                      <i
                        class="fas fa-pencil-alt"
                        style="font-size: 15px; right: 10px"
                      ></i>
                    </h1>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <div
                      class="form-check"
                      v-for="(task, index) in current_card.tasks"
                      :key="task.id"
                    >
                      <input
                        class="form-check-input"
                        type="checkbox"
                        @change="
                          updateTask(
                            current_card.tasks[index])
                        "
                        :checked="isTaskDone(current_card.tasks[index].is_done)"
                      />
                      <form
                        @submit.prevent="updateTask(current_card.tasks[index])"
                        v-if="task_input_name_id == task.id"
                      >
                        <input
                          type="text"
                          v-model="current_card.tasks[index].name"
                          class="form-control"
                        />
                      </form>
                      <label
                        v-else
                        :for="'inlineCheckbox' + index"
                        class="form-check-label"
                        >{{ task.name }}</label
                      >
                      <span
                        @click="task_input_name_id = task.id"
                        v-if="task_input_name_id != task.id"
                        ><i
                          class="fas fa-pencil-alt"
                          style="
                            font-size: 15px;
                            right: 10px;
                            cursor: pointer;
                            margin-left: 10px;
                          "
                        ></i
                      ></span>
                      <button
                        type="button"
                        class="btn-close"
                        aria-label="Close"
                        @click="deleteTask(task.id)"
                        style="margin-left: 10px"
                      >
                        <span aria-hidden="true"></span>
                      </button>
                    </div>

                    <form @submit.prevent="addNewTask" class="mt-5">
                      <div class="form-group">
                        <input
                          type="text"
                          v-model="new_task_name"
                          class="form-control"
                        />
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="alert alert-danger" role="alert" v-if="errored">
      A simple danger alert—check it out!
    </div>
    <div class="spinner-border" role="status" v-if="loading">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength } from "vuelidate/lib/validators";
export default {
  props: ["deskId"],
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      name: "",
      desk_list_name: "",
      errored: false,
      loading: true,
      desk_lists: true,
      desk_list_input_id: null,
      card_names: [],
      current_card: [],
      show_card_name_input: false,
      task_input_name_id: null,
      new_task_name: "",
    };
  },
  methods: {
    updateTask(task) {
      task.is_done = task.is_done == 1 ? 0 : 1
      axios
        .post("/api/tasks/" + task.id, {
          _method: "PATCH",
          name: task.name,
          is_done: task.is_done,
          card_id: task.card_id,
        })
        .then((response) => {
          this.task_input_name_id = null;
        })
        .catch((error) => {
          console.log(error.response);
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteTask(id) {
      axios
        .post("/api/tasks/" + id, {
          _method: "DELETE",
        })
        .then((response) => {
          this.v$.$reset();
          this.getCard(this.current_card.id);
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    addNewTask() {
      axios
        .post("/api/tasks/", {
          name: this.new_task_name,
          card_id: this.current_card.id,
        })
        .then((response) => {
          this.new_task_name = "";
          this.v$.$reset();
          this.getCard(this.current_card.id);
        })
        .catch((error) => {
          console.log(error.response);

          this.errored = true;
        });
    },
    updateCardName() {
      axios
        .post("/api/cards/" + this.current_card.id, {
          _method: "PATCH",
          name: this.current_card.name,
          desk_list_id: this.current_card.desk_list_id,
        })
        .then((response) => {
          this.show_card_name_input = false;
          this.getDeskLists();
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    getCard(id) {
      axios
        .get("/api/cards/" + id)
        .then((response) => {
          this.current_card = response.data.data;
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteCard(id) {
      axios
        .post("/api/cards/" + id, {
          _method: "DELETE",
        })
        .then((response) => {
          this.getDeskLists();
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    addNewCard(desk_list_id) {
      /* this.v$.card_names.$each[desk_list_id].$touch();
      if (this.v$.card_names.$each[desk_list_id].$anyError) {
        return;
      }  */
      axios
        .post("/api/cards/", {
          name: this.card_names[desk_list_id],
          desk_list_id,
        })
        .then((response) => {
          //this.card_names[desk_list_id] = '';
          //this.v$.$reset();
          this.getDeskLists();
        })
        .catch((error) => {
          console.log(error.response);

          this.errored = true;
        });
    },
    updateDeskList(id, name) {
      axios
        .post("/api/desk-lists/" + id, {
          _method: "PUT",
          name,
        })
        .then((response) => {
          this.desk_list_input_id = null;
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    saveName() {
      this.v$.name.$touch();
      if (this.v$.name.$anyError) {
        return;
      }
      axios
        .post("/api/desks/" + this.deskId, {
          _method: "PUT",
          name: this.name,
        })
        .then((response) => {})
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    getDeskLists() {
      axios
        .get("/api/desk-lists/", {
          params: {
            desk_id: this.deskId,
          },
        })
        .then((response) => {
          this.desk_lists = response.data.data;
          this.desk_lists.forEach((el) => {
            this.card_names[el.id] = "";
          });
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    addNewDeskList() {
      this.v$.$touch();
      if (this.v$.$anyError) {
        return;
      }
      axios
        .post("/api/desk-lists/", {
          name: this.desk_list_name,
          desk_id: this.deskId,
        })
        .then((response) => {
          this.v$.$reset();
          this.desk_list_name = "";
          this.desk_lists = [];
          this.getDeskLists();
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteDeskList(id) {
      axios
        .post("/api/desk-lists/" + id, {
          _method: "DELETE",
        })
        .then((response) => {
          this.desk_lists = [];
          this.getDeskLists();
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  /* created() {
    this.updateTask = this.updateTask.bind(this)
  }, */
  computed: {
    isTaskDone() {
      return (value) => {
        return value == 1
      }
    }
  },
  mounted() {
    axios
      .get("/api/desks/" + this.deskId)
      .then((response) => {
        this.name = response.data.data.name;
      })
      .catch((error) => {
        console.log(error.response);
        this.errored = true;
      })
      .finally(() => {
        this.loading = false;
      });
    this.getDeskLists();
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(5),
    },
    desk_list_name: {
      required,
      maxLength: maxLength(5),
    },
    card_names: {
      $each: {
        required,
        maxLength: maxLength(5),
      },
    },
  },
};
</script>

<style lang="scss" scoped>
</style>